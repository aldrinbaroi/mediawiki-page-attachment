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

namespace PageAttachment\Session;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

define('PA_MESSAGE',                        'PA_MESSAGE');
define('PA_MESSAGE_DOWNLOAD_ERROR',         'PA_MESSAGE_DOWNLOAD_ERROR');
define('PA_PAGE_INFO',                      'PA_PAGE_INFO');
define('PA_ATTACH_TO_PAGE',                 'PA_ATTACH_TO_PAGE');
define('PA_ATTACH_TO_PAGE_HASH',            'PA_ATTACH_TO_PAGE_HASH');
define('PA_ATTACH_TO_PAGE_VALIDATION_SALT', 'PA_ATTACH_TO_PAGE_VALIDATION_SALT');
define('PA_UPLOAD_ATTACH_TO_PAGE',          'PA_UPLOAD_ATTACH_TO_PAGE');
define('PA_FORCE_RELOAD',                   'PA_FORCE_RELOAD');
define('PA_UPLOAD_AND_ATTACH_INITIATED',    'PA_UPLOAD_AND_ATTACH_INITIATED');
define('PA_VIEW_PAGE_SPECIAL',              'PA_VIEW_PAGE_SPECIAL');
define('PA_LOGIN_LOGOUT_TIME',              'PA_LOGIN_LOGOUT_TIME');
define('PA_DELETED_FILE_INFO',              'PA_DELETED_FILE_INFO');


class Session
{
	private $security;

	function __construct($security)
	{
		$this->security = $security;
	}

	function setCurrentPage($page)
	{
		if (isset($page) && ($page instanceof \PageAttachment\Session\Page))
		{
			$_SESSION[PA_PAGE_INFO] = serialize($page);
		}
		else
		{
			unset($_SESSION[PA_PAGE_INFO]);
		}
	}

	function getCurrentPage()
	{
		if (isset($_SESSION[PA_PAGE_INFO]))
		{
			return unserialize($_SESSION[PA_PAGE_INFO]);
		}
		else
		{
			return (new \PageAttachment\Session\Page());
		}
	}

	function setViewPageSpecial($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_SESSION[PA_VIEW_PAGE_SPECIAL] = $trueFalse;
		}
		else
		{
			$_SESSION[PA_VIEW_PAGE_SPECIAL] = false;
		}
	}

	function isViewPageSpecial()
	{
		if (isset($_SESSION[PA_VIEW_PAGE_SPECIAL]))
		{
			return $_SESSION[PA_VIEW_PAGE_SPECIAL] == true ? true : false;
		}
		else
		{
			return false;
		}
	}

	function setAttachToPage($attachToPage)
	{
		if (isset($attachToPage) && ($attachToPage instanceof \PageAttachment\Session\Page))
		{
			$_SESSION[PA_ATTACH_TO_PAGE] = serialize($attachToPage);
		}
		else
		{
			unset($_SESSION[PA_ATTACH_TO_PAGE]);
		}
	}

	function getAttachToPage()
	{
		if (isset($_SESSION[PA_ATTACH_TO_PAGE]))
		{

			$attachToPage = unserialize( $_SESSION[PA_ATTACH_TO_PAGE]);
			if ($attachToPage instanceof \PageAttachment\Session\Page)
			{
				return $attachToPage;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}

	function setForceReload( $trueFalse )
	{
		if (is_bool($trueFalse))
		{
			$_SESSION[PA_FORCE_RELOAD] = $trueFalse;
		}
		else
		{
			$_SESSION[PA_FORCE_RELOAD] = false;
		}
	}

	function isForceReload()
	{
		if (isset($_SESSION[PA_FORCE_RELOAD]))
		{
			return $_SESSION[PA_FORCE_RELOAD] == true ? true : false;
		}
		else
		{
			return false;
		}
	}


	function setUploadAndAttachFileInitiated( $trueFalse )
	{
		if (is_bool($trueFalse))
		{
			$_SESSION[PA_UPLOAD_AND_ATTACH_INITIATED] = $trueFalse;
		}
		else
		{
			$_SESSION[PA_UPLOAD_AND_ATTACH_INITIATED] = false;
		}
	}


	function isUploadAndAttachFileInitiated()
	{
		if (isset($_SESSION[PA_UPLOAD_AND_ATTACH_INITIATED]))
		{
			return $_SESSION[PA_UPLOAD_AND_ATTACH_INITIATED] == true ? true : false;
		}
		else
		{
			return false;
		}
	}

	function setStatusMessage($messageKey, $messageArg = null)
	{
		if (isset($messageKey))
		{
			if (isset($messageArg))
			{
				$message = \wfMsgNoTrans($messageKey, $messageArg);
			}
			else
			{
				$message = \wfMsg($messageKey);
			}
			$_SESSION[PA_MESSAGE] = $message;
		}
	}

	function getStatusMessage()
	{
		if (isset($_SESSION[PA_MESSAGE]))
		{
			$message = $_SESSION[PA_MESSAGE];
			unset($_SESSION[PA_MESSAGE]);
		}
		else
		{
			$message = null;
		}
		return $message;
	}

	function setLoginLogoutTime()
	{
		$_SESSION[PA_LOGIN_LOGOUT_TIME] = time();
	}

	function getLoginLogoutTime()
	{
		if (isset($_SESSION[PA_LOGIN_LOGOUT_TIME]))
		{
			return $_SESSION[PA_LOGIN_LOGOUT_TIME];
		}
		else
		{
			$this->setLoginLogoutTime();
			return $_SESSION[PA_LOGIN_LOGOUT_TIME];
		}
	}

	function storeDeletedFileInfo($page)
	{
		if (isset($page) && $page instanceof \PageAttachment\Session\Page)
		{
			$_SESSION[PA_DELETED_FILE_INFO] = serialize($page);
		}
	}

	function retrieveDeletedFileInfo()
	{
		if (isset($_SESSION[PA_DELETED_FILE_INFO] ))
		{
			$page = unserialize($_SESSION[PA_DELETED_FILE_INFO]);
			unset ($_SESSION[PA_DELETED_FILE_INFO]);
			return $page;
		}
		else
		{
			return null;
		}
	}

}

## ::END::
