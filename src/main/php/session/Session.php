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

class Session
{
	private $security;
	private $pageFactory;
	
	const MESSAGE                        = 'MESSAGE';
	const MESSAGE_DOWNLOAD_ERROR         = 'MESSAGE_DOWNLOAD_ERROR';
	const PAGE_INFO                      = 'PAGE_INFO';
	const ATTACH_TO_PAGE                 = 'ATTACH_TO_PAGE';
	const ATTACH_TO_PAGE_HASH            = 'ATTACH_TO_PAGE_HASH';
	const ATTACH_TO_PAGE_VALIDATION_SALT = 'ATTACH_TO_PAGE_VALIDATION_SALT';
	const UPLOAD_ATTACH_TO_PAGE          = 'UPLOAD_ATTACH_TO_PAGE';
	const FORCE_RELOAD                   = 'FORCE_RELOAD';
	const UPLOAD_AND_ATTACH_INITIATED    = 'UPLOAD_AND_ATTACH_INITIATED';
	const VIEW_PAGE_SPECIAL              = 'VIEW_PAGE_SPECIAL';
	const LOGIN_LOGOUT_TIME              = 'LOGIN_LOGOUT_TIME';
	const DELETED_FILE_INFO              = 'DELETED_FILE_INFO';
	const REINITIALIZE_CATEGORY_LIST     = 'REINITIALIZE_CATEGORY_LIST';
	
	function __construct(\PageAttachment\Security\SecurityManager $security, \PageAttachment\Session\PageFactory $pageFactory)
	{
		$this->security = $security;
		$this->pageFactory = $pageFactory;
	}

	function startSessionIfNotStarted()
	{
		\wfSuppressWarnings();
		if(!isset($_SESSION))
		{
			session_start();
		}
		\wfRestoreWarnings();
	}

	function setCurrentPage($page)
	{
		if (isset($page) && ($page instanceof \PageAttachment\Session\Page))
		{
			$_SESSION[self::PAGE_INFO] = serialize($page);
		}
		else
		{
			unset($_SESSION[self::PAGE_INFO]);
		}
	}

	function getCurrentPage()
	{
		if (isset($_SESSION[self::PAGE_INFO]))
		{
			return unserialize($_SESSION[self::PAGE_INFO]);
		}
		else
		{
			return ($this->pageFactory->createPage());
		}
	}

	function setViewPageSpecial($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_SESSION[self::VIEW_PAGE_SPECIAL] = $trueFalse;
		}
		else
		{
			$_SESSION[self::VIEW_PAGE_SPECIAL] = false;
		}
	}

	function isViewPageSpecial()
	{
		if (isset($_SESSION[self::VIEW_PAGE_SPECIAL]))
		{
			return $_SESSION[self::VIEW_PAGE_SPECIAL] == true ? true : false;
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
			$_SESSION[self::ATTACH_TO_PAGE] = serialize($attachToPage);
		}
		else
		{
			unset($_SESSION[self::ATTACH_TO_PAGE]);
		}
	}

	function getAttachToPage()
	{
		if (isset($_SESSION[self::ATTACH_TO_PAGE]))
		{

			$attachToPage = unserialize( $_SESSION[self::ATTACH_TO_PAGE]);
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
			$_SESSION[self::FORCE_RELOAD] = $trueFalse;
		}
		else
		{
			$_SESSION[self::FORCE_RELOAD] = false;
		}
	}

	function isForceReload()
	{
		if (isset($_SESSION[self::FORCE_RELOAD]))
		{
			return $_SESSION[self::FORCE_RELOAD] == true ? true : false;
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
			$_SESSION[self::UPLOAD_AND_ATTACH_INITIATED] = $trueFalse;
		}
		else
		{
			$_SESSION[self::UPLOAD_AND_ATTACH_INITIATED] = false;
		}
	}


	function isUploadAndAttachFileInitiated()
	{
		if (isset($_SESSION[self::UPLOAD_AND_ATTACH_INITIATED]))
		{
			return $_SESSION[self::UPLOAD_AND_ATTACH_INITIATED] == true ? true : false;
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
			$_SESSION[self::MESSAGE] = $message;
		}
	}

	function getStatusMessage()
	{
		if (isset($_SESSION[self::MESSAGE]))
		{
			$message = $_SESSION[self::MESSAGE];
			unset($_SESSION[self::MESSAGE]);
		}
		else
		{
			$message = null;
		}
		return $message;
	}

	function setLoginLogoutTime()
	{
		$_SESSION[self::LOGIN_LOGOUT_TIME] = time();
	}

	function getLoginLogoutTime()
	{
		if (isset($_SESSION[self::LOGIN_LOGOUT_TIME]))
		{
			return $_SESSION[self::LOGIN_LOGOUT_TIME];
		}
		else
		{
			$this->setLoginLogoutTime();
			return $_SESSION[self::LOGIN_LOGOUT_TIME];
		}
	}

	function storeDeletedFileInfo($page)
	{
		if (isset($page) && $page instanceof \PageAttachment\Session\Page)
		{
			$_SESSION[self::DELETED_FILE_INFO] = serialize($page);
		}
	}

	function retrieveDeletedFileInfo()
	{
		if (isset($_SESSION[self::DELETED_FILE_INFO] ))
		{
			$page = unserialize($_SESSION[self::DELETED_FILE_INFO]);
			unset ($_SESSION[self::DELETED_FILE_INFO]);
			return $page;
		}
		else
		{
			return null;
		}
	}

	function setReinitializeCategoryList()
	{
		$_SESSION[self::REINITIALIZE_CATEGORY_LIST] = true;
	}

	function isReinitializeCategoryList()
	{
		if (isset($_SESSION[self::REINITIALIZE_CATEGORY_LIST]) &&
		is_bool($_SESSION[self::REINITIALIZE_CATEGORY_LIST]) &&
		$_SESSION[self::REINITIALIZE_CATEGORY_LIST] == true)
		{
			unset ($_SESSION[self::REINITIALIZE_CATEGORY_LIST]);
			return true;
		}
		else
		{
			return false;
		}
	}

}

## ::END::
