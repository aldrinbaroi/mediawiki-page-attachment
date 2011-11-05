<?php
/**
 *
 * Copyright (C) 2011 Aldrin Edison Baroi
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the
 *     Free Software Foundation, Inc.,
 *     51 Franklin Street, Fifth Floor
 *     Boston, MA 02110-1301, USA.
 *     http://www.gnu.org/copyleft/gpl.html
 *
 */

namespace PageAttachment\Security;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

# The following constants are used for session attributes
define('PA_REQUEST_VALIDATION_TOKEN', 'REQUEST_VALIDATION_TOKEN');
define('PA_DOWNLOAD_REQUEST_VALID',   'PA_DOWNLOAD_REQUEST_VALID');

# The following constants are used for permissions
define('PA_VIEW',               'view');
define('PA_VIEW_AUDIT_LOG',     'viewAuditLog');
define('PA_VIEW_HISTORY_LOG',   'viewHistory');
define('PA_UPLOAD_AND_ATTACH',  'uploadAndAttach');
define('PA_BROWSE_SEARCH',      'browseSearch');
define('PA_REMOVE',             'remove');
define('PA_REMOVE_PERMANENTLY', 'removePermanently');
define('PA_DOWNLOAD',           'download');
define('PA_LOGIN_REQUIRED',     'loginRequired');
define('PA_ALLOWED',            'allowed');
define('PA_GROUP',              'group');
define('PA_GROUP_ALL',          '*');
define('PA_USER',               'user');

# Permanent file removal
define('PA_PERMANENTLY',         'permanently');
define('PA_IGNORE_IF_EMBEDDED',  'ignoreIfEmbedded');
define('PA_IGNORE_IF_ATTACHED',  'ignoreIfAttached');

class SecurityManager
{
	private $mediaWikiSecurityManager;

	function __construct()
	{
		$factory = new MediaWiki\MediaWikiSecurityManagerFactory();
		$this->mediaWikiSecurityManager = $factory->createMediaWikiSecurityManager();
	}

	function isPageInAllowedNameSpaces($pageId, $pageNS)
	{
		global $wgPageAttachment_allowedNameSpaces;

		if ($pageId < 1)
		{
			return false;
		}
		if ($pageId > 0 )
		{
			for ($i = 0; $i < count($wgPageAttachment_allowedNameSpaces); $i++)
			{
				if ($pageNS == $wgPageAttachment_allowedNameSpaces[$i])
				{
					return true;
				}
			}
			return false;
		}
	}

	// Check if upload is enabled in MediaWiki configuration
	private function isUploadEnabled()
	{
		if ($this->mediaWikiSecurityManager->isUploadEnabled())
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	// Check if upload is allowed in MediaWiki configuration
	private function isUploadAllowed()
	{
		if ($this->mediaWikiSecurityManager->isUploadAllowed() == true)
		{
			if ($this->mediaWikiSecurityManager->isUserBlocked() ||
			$this->mediaWikiSecurityManager->isWikiInReadonlyMode())
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}

	function isLoggedIn()
	{
		global $wgUser;

		if ($wgUser->isLoggedIn())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private function isLoginRequired($action)
	{
		global $wgPageAttachment_permissions;

		if (isset($wgPageAttachment_permissions[$action][PA_LOGIN_REQUIRED]))
		{
			$loginRequired = $wgPageAttachment_permissions[$action][PA_LOGIN_REQUIRED];
		}
		else
		{
			$loginRequired = true;
		}
		return ($loginRequired == true) ? true : false;
	}

	private function __isAllowed($action)
	{
		global $wgUser;
		global $wgPageAttachment_permissions;

		$actionAllowed = false;
		if ($this->isLoginRequired($action))
		{
			if ($this->isLoggedIn())
			{
				// Check if all groups are allowed to perform the specific action
				if (isset($wgPageAttachment_permissions[$action][PA_GROUP][PA_GROUP_ALL]))
				{
					$actionAllowed = $wgPageAttachment_permissions[$action][PA_GROUP][PA_GROUP_ALL];
				}
				// Check if user's one of the effective groups are allowed to perform
				// the specific action
				if (!$actionAllowed)
				{
					$effectiveGroups = $wgUser->getEffectiveGroups();
					foreach($effectiveGroups as $group)
					{
						if (isset($wgPageAttachment_permissions[$action][PA_GROUP][$group]))
						{
							$actionAllowed = $wgPageAttachment_permissions[$action][PA_GROUP][$group];
							if ($actionAllowed == true)
							{
								break;
							}
						}
					}
				}
				// Check if this user is allowed to perform the specific action
				if (!$actionAllowed)
				{
					$userId = $wgUser->getName();
					if (isset($wgPageAttachment_permissions[$action][PA_USER][$userId]))
					{
						$actionAllowed = $wgPageAttachment_permissions[$action][PA_USER][$userId];
					}
				}
			}
		}
		else
		{
			if (isset($wgPageAttachment_permissions[$action][PA_ALLOWED]))
			{
				$actionAllowed = $wgPageAttachment_permissions[$action][PA_ALLOWED];
			}
		}
		return ($actionAllowed == true ) ? true : false;
	}

	private function isAllowed($action)
	{
		global $wgUser;
		global $wgPageAttachment_permissions;

		$actionAllowed = false;
		if ($this->isUploadEnabled() && $this->isUploadAllowed())
		{
			$actionAllowed = $this->__isAllowed($action);
		}
		return ($actionAllowed == true ) ? true : false;
	}

	function isViewAttachmentsAllowed()
	{
		return $this->__isAllowed(PA_VIEW);
	}

	function isViewAttachmentsRequireLogin()
	{
		return $this->isLoginRequired(PA_VIEW);
	}

	function isAttachmentAddUpdateRequireLogin()
	{
		return $this->isLoginRequired(PA_UPLOAD_AND_ATTACH);
	}

	function isAttachmentUploadAndAttachAllowed()
	{
		global $wgEnableUploads;

		if ($wgEnableUploads == true)
		{
			if ($this->isViewAttachmentsAllowed()
			&& !$this->mediaWikiSecurityManager->isWikiInReadonlyMode()
			&& !$this->mediaWikiSecurityManager->isUserBlocked())
			{
				return $this->isAllowed(PA_UPLOAD_AND_ATTACH);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	function isBrowseSearchAttachRequireLogin()
	{
		return $this->isLoginRequired(PA_BROWSE_SEARCH);
	}

	function isBrowseSearchAttachAllowed()
	{
		if ($this->isViewAttachmentsAllowed()
		&& !$this->mediaWikiSecurityManager->isWikiInReadonlyMode()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			return $this->isAllowed(PA_BROWSE_SEARCH);
		}
		else
		{
			return false;
		}
	}

	function isAttachmentRemovalRequireLogin()
	{
		return $this->isLoginRequired(PA_REMOVE);
	}

	function isRemoveAttachmentPermanentlyEnabled()
	{
		global $wgPageAttachment_removeAttachments;

		if (isset($wgPageAttachment_removeAttachments[PA_PERMANENTLY]))
		{
			return  ($wgPageAttachment_removeAttachments[PA_PERMANENTLY]) ? true : false;
		}
		else
		{
			return false;
		}
	}

	function isRemoveAttachmentPermanentlyEvenIfEmbedded()
	{
		global $wgPageAttachment_removeAttachments;

		if (isset($wgPageAttachment_removeAttachments[PA_IGNORE_IF_EMBEDDED]))
		{
			return  ($wgPageAttachment_removeAttachments[PA_IGNORE_IF_EMBEDDED] == true) ? true : false;
		}
		else
		{
			return false;
		}
	}
	
	function isRemoveAttachmentPermanentlyEvenIfAttached()
	{
		global $wgPageAttachment_removeAttachments;
	
		if (isset($wgPageAttachment_removeAttachments[PA_IGNORE_IF_ATTACHED]))
		{
			return  ($wgPageAttachment_removeAttachments[PA_IGNORE_IF_ATTACHED] == true) ? true : false;
		}
		else
		{
			return false;
		}
	}
	
	function isUserAllowedToDeleteFile()
	{
		return $this->mediaWikiSecurityManager->isUserAllowedToDeleteFile();
	}

	function isAttachmentRemovalAllowed()
	{
		if ($this->isViewAttachmentsAllowed()
		&& !$this->mediaWikiSecurityManager->isWikiInReadonlyMode()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			if ($this->isAllowed(PA_REMOVE))
			{
				if ($this->isRemoveAttachmentPermanentlyEnabled())
				{
					if ($this->isUserAllowedToDeleteFile())
					{
						return  true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			return false;
		}
	}

	function isAttachmentDownloadRequireLogin()
	{
		return $this->isLoginRequired(PA_DOWNLOAD);
	}

	function isAttachmentDownloadAllowed()
	{
		if ($this->isViewAttachmentsAllowed()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			return $this->isAllowed(PA_DOWNLOAD);
		}
		else
		{
			return false;
		}
	}

	function isAuditLogViewRequireLogin()
	{
		return $this->isLoginRequired(PA_VIEW_AUDIT_LOG);
	}

	function isAuditLogViewAllowed()
	{
		global $wgPageAttachment_enableAuditLog;

		if (isset($wgPageAttachment_enableAuditLog)
		&& $wgPageAttachment_enableAuditLog == true
		&& $this->isViewAttachmentsAllowed()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			return $this->isAllowed(PA_VIEW_AUDIT_LOG);
		}
		else
		{
			return false;
		}
	}

	function isHistoryViewRequireLogin()
	{
		return $this->isLoginRequired(PA_VIEW_HISTORY_LOG);
	}

	function isHistoryViewAllowed()
	{
		if ($this->isViewAttachmentsAllowed()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			return $this->isAllowed(PA_VIEW_HISTORY_LOG);
		}
		else
		{
			return false;
		}
	}

	function newRequestValidationToken()
	{
		$requestValidationToken = hash('sha256', dechex(mt_rand()));
		$_SESSION[PA_REQUEST_VALIDATION_TOKEN] = $requestValidationToken;
		return $requestValidationToken;
	}

	function getCurrentRequestValidationToken()
	{
		$requestValidationToken = $_SESSION[PA_REQUEST_VALIDATION_TOKEN];
		return $requestValidationToken;
	}

	function isRequestValidationTokenValid()
	{
		global $wgRequest;

		if (isset($_SESSION[PA_REQUEST_VALIDATION_TOKEN]))
		{
			return $wgRequest->getVal('rvt','') == $_SESSION[PA_REQUEST_VALIDATION_TOKEN];
		}
		else
		{
			return false;
		}
	}

	function isRequestValidationTokenValid2($rvt)
	{

		if (isset($_SESSION[PA_REQUEST_VALIDATION_TOKEN]))
		{
			return $rvt == $_SESSION[PA_REQUEST_VALIDATION_TOKEN];
		}
		else
		{
			return false;
		}
	}

	function setDownloadRequestValid($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_SESSION[PA_DOWNLOAD_REQUEST_VALID] = $trueFalse;
		}
		else
		{
			$_SESSION[PA_DOWNLOAD_REQUEST_VALID] = false;
		}
	}

	function isDownloadRequestValid()
	{
		if (isset($_SESSION[PA_DOWNLOAD_REQUEST_VALID]))
		{
			return $_SESSION[PA_DOWNLOAD_REQUEST_VALID] == true ? true : false;
		}
		else
		{
			return false;
		}
	}

}

## ::END::
