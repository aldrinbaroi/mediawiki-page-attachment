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

namespace PageAttachment\Request;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class RequestHelper
{
	const PREVIEW_MODE      = 'PREVIEW MODE';
	const EDIT_MODE         = 'EDIT MODE';
	const VIEW_HISTORY_MODE = 'VIEW HISTORY MODE';
	const VIEW_CHANGES_MODE = 'VIEW CHANGES MODE';

	function __construct()
	{

	}

	function setPageMode($request, $editpage = null)
	{
		\wfDebugLog("PageAttachment", "HERE START 88888888888888888888888888888888888888888888888888888888888");
		\wfDebugLog("PageAttachment", "HERE editpage = " . (isset($editpage) ? 'TRUE' : 'FALSE'));
		if (isset($editpage) && is_bool($editpage->preview) && ($editpage->preview == true))
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 1");
			$this->setPreviewMode(true);
		}
		else
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 2");
			$this->setPreviewMode(false);
		}

		//
		global $wgRequest;

		$action = $wgRequest->getVal('action');
		if ($action == 'edit')
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 3");
			$this->setEditMode(true);
		}
		else
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 4");
			$this->setEditMode(false);
		}


		if ($action == 'history')
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 3.1");
			$this->setViewHistoryMode(true);
		}
		else
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 4.1");
			$this->setViewHistoryMode(false);
		}


		if (isset($editpage) && is_bool($editpage->diff) && ($editpage->diff == true))
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 5");
			$this->setViewChangesMode(true);
		}
		else
		{
			\wfDebugLog("PageAttachment", "setPageMode HERE 6");
			$this->setViewChangesMode(false);
		}
		\wfDebugLog("PageAttachment", "HERE END 88888888888888888888888888888888888888888888888888888888888");
	}

	private function setPreviewMode($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_REQUEST[self::PREVIEW_MODE] = $trueFalse;
		}
		else
		{
			$_REQUEST[self::PREVIEW_MODE] = false;
		}
	}

	function isPreviewMode()
	{
		if (isset($_REQUEST[self::PREVIEW_MODE]))
		{
			return $_REQUEST[self::PREVIEW_MODE];
		}
		else
		{
			return false;
		}
	}

	private function setEditMode($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			\wfDebugLog("PageAttachment", ">>>>>>>>>>>>>>>>>>>> SET EDIT MODE : " . ($trueFalse ? 'TRUE' : 'FALSE'));
			$_REQUEST[self::EDIT_MODE] = $trueFalse;
		}
		else
		{
			\wfDebugLog("PageAttachment", ">>>>>>>>>>>>>>>>>>>> SET EDIT MODE : false");
			$_REQUEST[self::EDIT_MODE] = false;
		}
	}

	function isEditMode()
	{
		if (isset($_REQUEST[self::EDIT_MODE]))
		{
			\wfDebugLog("PageAttachment", ">>>>>>>>>>>>>>>>>>>> GET EDIT MODE : " . ($_REQUEST[self::EDIT_MODE] ? 'TRUE' : 'FALSE'));
			return $_REQUEST[self::EDIT_MODE];
		}
		else
		{
			return false;
		}
	}

	private function setViewChangesMode($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_REQUEST[self::VIEW_CHANGES_MODE] = $trueFalse;
		}
		else
		{
			$_REQUEST[self::VIEW_CHANGES_MODE] = false;
		}
	}

	function isViewChangesMode()
	{
		if (isset($_REQUEST[self::VIEW_CHANGES_MODE]))
		{
			return $_REQUEST[self::VIEW_CHANGES_MODE];
		}
		else
		{
			return false;
		}
	}

	private function setViewHistoryMode($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_REQUEST[self::VIEW_HISTORY_MODE] = $trueFalse;
		}
		else
		{
			$_REQUEST[self::VIEW_HISTORY_MODE] = false;
		}
	}

	function isViewHistoryMode()
	{
		if (isset($_REQUEST[self::VIEW_HISTORY_MODE]))
		{
			return $_REQUEST[self::VIEW_HISTORY_MODE];
		}
		else
		{
			return false;
		}
	}

	function isSpecialPage(&$title)
	{
		if (isset($title) && $title instanceof \Title)
		{
			if ($title->getNamespace() == NS_SPECIAL)
			{
				return true;
			}
		}
		return false;
	}

	function isPageAttachmentSpecialPage(&$title, $viewPageTitle)
	{
		if ($this->isSpecialPage($title))
		{
			$nameParts = explode( ':', $viewPageTitle);
			if (count($nameParts) == 2)
			{
				$specialPageName = $nameParts[1];
				if ($specialPageName == 'PageAttachmentUpload'
				|| $specialPageName == 'PageAttachmentListFiles'
				|| $specialPageName == 'PageAttachmentAuditLogViewer')
				{
					return true;
				}
			}
		}
		return false;
	}

}

## ::END::
