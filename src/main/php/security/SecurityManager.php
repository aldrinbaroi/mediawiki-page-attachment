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

class SecurityManager
{
	private $mediaWikiSecurityManager;

	const REQUEST_VALIDATION_TOKEN = 'REQUEST_VALIDATION_TOKEN';
	const DOWNLOAD_REQUEST_VALID   = 'DOWNLOAD_REQUEST_VALID';
	//
	// The following constants are used for permissions
	const VIEW               = 'view';
	const VIEW_AUDIT_LOG     = 'viewAuditLog';
	const VIEW_HISTORY_LOG   = 'viewHistory';
	const UPLOAD_AND_ATTACH  = 'uploadAndAttach';
	const BROWSE_SEARCH      = 'browseSearch';
	const REMOVE             = 'remove';
	const REMOVE_PERMANENTLY = 'removePermanently';
	const DOWNLOAD           = 'download';
	const LOGIN_REQUIRED     = 'loginRequired';
	const ALLOWED            = 'allowed';
	const GROUP              = 'group';
	const GROUP_ALL          = '*';
	const USER               = 'user';

	// Permanent file removal
	const PERMANENTLY        = 'permanently';
	const IGNORE_IF_EMBEDDED = 'ignoreIfEmbedded';
	const IGNORE_IF_ATTACHED = 'ignoreIfAttached';

	// The following constants are used for permissions
	const VIEW_ON_PROTECTED_PAGES               = 'viewOnProtectedPages';
	const VIEW_AUDIT_LOG_ON_PROTECTED_PAGES     = 'viewAuditLogOnProtectedPages';
	const VIEW_HISTORY_LOG_ON_PROTECTED_PAGES   = 'viewHistoryOnProtectedPages';
	const UPLOAD_AND_ATTACH_ON_PROTECTED_PAGES  = 'uploadAndAttachOnProtectedPages';
	const BROWSE_SEARCH_ON_PROTECTED_PAGES      = 'browseSearchOnProtectedPages';
	const REMOVE_ON_PROTECTED_PAGES             = 'removeOnProtectedPages';
	const REMOVE_PERMANENTLY_ON_PROTECTED_PAGES = 'removePermanentlyOnProtectedPages';
	const DOWNLOAD_ON_PROTECTED_PAGES           = 'downloadOnProtectedPages';
	const LOGIN_REQUIRED_ON_PROTECTED_PAGES     = 'loginRequiredOnProtectedPages';


	function __construct()
	{
		$factory = new MediaWiki\MediaWikiSecurityManagerFactory();
		$this->mediaWikiSecurityManager = $factory->createMediaWikiSecurityManager();
	}

	function isPageInAllowedNameSpaces($pageId, $pageNS)
	{
		global $wgPageAttachment_allowedNameSpaces;

		$inAllowedNameSpaces = false;
		if (isset($wgPageAttachment_allowedNameSpaces) && is_array($wgPageAttachment_allowedNameSpaces))
		{
			if ($pageId > 0 )
			{
				for ($i = 0; $i < count($wgPageAttachment_allowedNameSpaces); $i++)
				{
					if ($pageNS == $wgPageAttachment_allowedNameSpaces[$i])
					{
						$inAllowedNameSpaces = true;
						break;
					}
				}
			}
		}
		return $inAllowedNameSpaces;
	}

	function isPageInAllowedCategories($pageCategories)
	{
		global $wgPageAttachment_allowedCategories;

		$pageInAllowedCategories = false;
		if (isset($wgPageAttachment_allowedCategories) && is_array($wgPageAttachment_allowedCategories))
		{
			if (is_array($pageCategories) && (count($pageCategories) > 0))
			{
				for($i = 0; $i < count($pageCategories); $i++)
				{
					if (in_array($pageCategories[$i], $wgPageAttachment_allowedCategories))
					{
						$pageInAllowedCategories = true;
						break;
					}
				}
			}
		}
		return $pageInAllowedCategories;
	}

	function isPageExcluded(\PageAttachment\Session\Page $page)
	{
		global $wgPageAttachment_excludedPages;

		if (isset($wgPageAttachment_excludedPages) && is_array($wgPageAttachment_excludedPages))
		{
			if ($page->getNameSpacePrefix() == '')
			{
				$pageTitle = $page->getPageTitle();
			}
			else
			{
				$pageTitle = $page->getNameSpacePrefix() . ':' . $page->getPageTitle();
			}
			if (in_array($pageTitle, $wgPageAttachment_excludedPages))
			{
				return true;
			}
		}
		return false;
	}

	function isAttachmentAllowed(\PageAttachment\Session\Page $page)
	{
		$attachmentAllowed = false;
		$pageId = $page->getId();
		$pageNS = $page->getNameSpace();
		$pageCategories = $page->getCategories();
		$pageInAllowedNameSpaces = $this->isPageInAllowedNameSpaces($pageId, $pageNS);
		$pageInAllowedCategories = $this->isPageInAllowedCategories($pageCategories);
		if ($pageInAllowedNameSpaces || $pageInAllowedCategories)
		{
			if ($this->isPageExcluded($page))
			{
				$attachmentAllowed =  false;
			}
			else
			{
				$attachmentAllowed =  true;
			}
		}
		return $attachmentAllowed;
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

		if (isset($wgPageAttachment_permissions[$action][self::LOGIN_REQUIRED]))
		{
			$loginRequired = $wgPageAttachment_permissions[$action][self::LOGIN_REQUIRED];
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
				if (isset($wgPageAttachment_permissions[$action][self::GROUP][self::GROUP_ALL]))
				{
					$actionAllowed = $wgPageAttachment_permissions[$action][self::GROUP][self::GROUP_ALL];
				}
				// Check if user's one of the effective groups are allowed to perform
				// the specific action
				if (!$actionAllowed)
				{
					$effectiveGroups = $wgUser->getEffectiveGroups();
					foreach($effectiveGroups as $group)
					{
						if (isset($wgPageAttachment_permissions[$action][self::GROUP][$group]))
						{
							$actionAllowed = $wgPageAttachment_permissions[$action][self::GROUP][$group];
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
					if (isset($wgPageAttachment_permissions[$action][self::USER][$userId]))
					{
						$actionAllowed = $wgPageAttachment_permissions[$action][self::USER][$userId];
					}
				}
			}
		}
		else
		{
			if (isset($wgPageAttachment_permissions[$action][self::ALLOWED]))
			{
				$actionAllowed = $wgPageAttachment_permissions[$action][self::ALLOWED];
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

	function isViewAttachmentsAllowed($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->__isAllowed(self::VIEW))
			{
				return $this->__isAllowed(self::VIEW_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->__isAllowed(self::VIEW);
		}
	}

	function isViewAttachmentsRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->isLoginRequired(self::VIEW))
			{
				return $this->isLoginRequired(self::VIEW_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::VIEW);
		}
	}

	function isAttachmentAddUpdateRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->isLoginRequired(self::UPLOAD_AND_ATTACH))
			{
				return $this->isLoginRequired(self::UPLOAD_AND_ATTACH_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::UPLOAD_AND_ATTACH);
		}
	}

	function isAttachmentUploadAndAttachAllowed($protectedPage)
	{
		global $wgEnableUploads;

		if ($wgEnableUploads == true)
		{
			if ($this->isViewAttachmentsAllowed($protectedPage)
			&& !$this->mediaWikiSecurityManager->isWikiInReadonlyMode()
			&& !$this->mediaWikiSecurityManager->isUserBlocked())
			{
				if ($protectedPage)
				{
					if ($this->isAllowed(self::UPLOAD_AND_ATTACH))
					{
						return $this->isAllowed(self::UPLOAD_AND_ATTACH_ON_PROTECTED_PAGES);
					}
					else
					{
						return false;
					}
				}
				else
				{
					return $this->isAllowed(self::UPLOAD_AND_ATTACH);
				}
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

	function isBrowseSearchAttachRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->isLoginRequired(self::BROWSE_SEARCH))
			{
				return $this->isLoginRequired(self::BROWSE_SEARCH_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::BROWSE_SEARCH);
		}
	}

	function isBrowseSearchAttachAllowed($protectedPage)
	{
		if ($this->isViewAttachmentsAllowed($protectedPage)
		&& !$this->mediaWikiSecurityManager->isWikiInReadonlyMode()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			if ($protectedPage)
			{
				if($this->isAllowed(self::BROWSE_SEARCH))
				{
					return $this->isAllowed(self::BROWSE_SEARCH_ON_PROTECTED_PAGES);
				}
				else
				{
					return false;
				}
			}
			else
			{
				return $this->isAllowed(self::BROWSE_SEARCH);
			}
		}
		else
		{
			return false;
		}
	}

	function isAttachmentRemovalRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->isLoginRequired(self::REMOVE))
			{
				return $this->isLoginRequired(self::REMOVE_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::REMOVE);
		}
	}

	function isRemoveAttachmentPermanentlyEnabled()
	{
		global $wgPageAttachment_removeAttachments;

		if (isset($wgPageAttachment_removeAttachments[self::PERMANENTLY]))
		{
			return  ($wgPageAttachment_removeAttachments[self::PERMANENTLY]) ? true : false;
		}
		else
		{
			return false;
		}
	}

	function isRemoveAttachmentPermanentlyEvenIfEmbedded()
	{
		global $wgPageAttachment_removeAttachments;

		if (isset($wgPageAttachment_removeAttachments[self::IGNORE_IF_EMBEDDED]))
		{
			return  ($wgPageAttachment_removeAttachments[self::IGNORE_IF_EMBEDDED] == true) ? true : false;
		}
		else
		{
			return false;
		}
	}

	function isRemoveAttachmentPermanentlyEvenIfAttached()
	{
		global $wgPageAttachment_removeAttachments;

		if (isset($wgPageAttachment_removeAttachments[self::IGNORE_IF_ATTACHED]))
		{
			return  ($wgPageAttachment_removeAttachments[self::IGNORE_IF_ATTACHED] == true) ? true : false;
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

	function isAttachmentRemovalAllowed($protectedPage)
	{
		if ($this->isViewAttachmentsAllowed($protectedPage)
		&& !$this->mediaWikiSecurityManager->isWikiInReadonlyMode()
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			if ($this->isAllowed(self::REMOVE))
			{
				if ($this->isRemoveAttachmentPermanentlyEnabled())
				{
					if ($this->isUserAllowedToDeleteFile())
					{
						if ($protectedPage)
						{
							return $this->isAllowed(self::REMOVE_ON_PROTECTED_PAGES);
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
				else
				{
					if ($protectedPage)
					{
						return $this->isAllowed(self::REMOVE_ON_PROTECTED_PAGES);
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
		else
		{
			return false;
		}
	}

	function isAttachmentDownloadRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if($this->isLoginRequired(self::DOWNLOAD))
			{
				return $this->isLoginRequired(self::DOWNLOAD_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::DOWNLOAD);
		}
	}

	function isAttachmentDownloadAllowed($protectedPage)
	{
		if ($this->isViewAttachmentsAllowed($protectedPage)
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			if ($protectedPage)
			{
				if ($this->isAllowed(self::DOWNLOAD))
				{
					return $this->isAllowed(self::DOWNLOAD_ON_PROTECTED_PAGES);
				}
				else
				{
					return false;
				}
			}
			else
			{
				return $this->isAllowed(self::DOWNLOAD);
			}
		}
		else
		{
			return false;
		}
	}

	function isAuditLogViewRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->isLoginRequired(self::VIEW_AUDIT_LOG))
			{
				return $this->isLoginRequired(self::VIEW_AUDIT_LOG_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::VIEW_AUDIT_LOG);
		}
	}

	function isAuditLogViewAllowed($protectedPage)
	{
		global $wgPageAttachment_enableAuditLog;

		if (isset($wgPageAttachment_enableAuditLog)
		&& $wgPageAttachment_enableAuditLog == true
		&& $this->isViewAttachmentsAllowed($protectedPage)
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			if ($protectedPage)
			{
				if ($this->isAllowed(self::VIEW_AUDIT_LOG))
				{
					return $this->isAllowed(self::VIEW_AUDIT_LOG_ON_PROTECTED_PAGES);
				}
				else
				{
					return false;
				}
			}
			else
			{
				return $this->isAllowed(self::VIEW_AUDIT_LOG);
			}
		}
		else
		{
			return false;
		}
	}

	function isHistoryViewRequireLogin($protectedPage)
	{
		if ($protectedPage)
		{
			if ($this->isLoginRequired(self::VIEW_HISTORY_LOG))
			{
				return $this->isLoginRequired(self::VIEW_HISTORY_LOG_ON_PROTECTED_PAGES);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $this->isLoginRequired(self::VIEW_HISTORY_LOG);
		}
	}

	function isHistoryViewAllowed($protectedPage)
	{
		if ($this->isViewAttachmentsAllowed($protectedPage)
		&& !$this->mediaWikiSecurityManager->isUserBlocked())
		{
			if ($protectedPage)
			{
				if ($this->isAllowed(self::VIEW_HISTORY_LOG))
				{
					return $this->isAllowed(self::VIEW_HISTORY_LOG_ON_PROTECTED_PAGES);
				}
				else
				{
					return false;
				}
			}
			else
			{
				return $this->isAllowed(self::VIEW_HISTORY_LOG);
			}
		}
		else
		{
			return false;
		}
	}

	function newRequestValidationToken()
	{
		$requestValidationToken = hash('sha256', dechex(mt_rand()));
		$_SESSION[self::REQUEST_VALIDATION_TOKEN] = $requestValidationToken;
		return $requestValidationToken;
	}

	function getCurrentRequestValidationToken()
	{
		$requestValidationToken = $_SESSION[self::REQUEST_VALIDATION_TOKEN];
		return $requestValidationToken;
	}

	function isRequestValidationTokenValid()
	{
		global $wgRequest;

		if (isset($_SESSION[self::REQUEST_VALIDATION_TOKEN]))
		{
			return $wgRequest->getVal('rvt','') == $_SESSION[self::REQUEST_VALIDATION_TOKEN];
		}
		else
		{
			return false;
		}
	}

	function isRequestValidationTokenValid2($rvt)
	{

		if (isset($_SESSION[self::REQUEST_VALIDATION_TOKEN]))
		{
			return $rvt == $_SESSION[self::REQUEST_VALIDATION_TOKEN];
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
			$_SESSION[self::DOWNLOAD_REQUEST_VALID] = $trueFalse;
		}
		else
		{
			$_SESSION[self::DOWNLOAD_REQUEST_VALID] = false;
		}
	}

	function isDownloadRequestValid()
	{
		if (isset($_SESSION[self::DOWNLOAD_REQUEST_VALID]))
		{
			return $_SESSION[self::DOWNLOAD_REQUEST_VALID] == true ? true : false;
		}
		else
		{
			return false;
		}
	}


}

## ::END::
