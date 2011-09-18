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

define('PA_PREVIEW_MODE',      'PA_PREVIEW_MODE');
define('PA_EDIT_MODE',         'PA_EDIT_MODE');
define('PA_VIEW_CHANGES_MODE', 'PA_VIEW_CHANGES_MODE');

class RequestHelper
{
	function __construct()
	{

	}

	function setPageMode($editpage, $request)
	{
		if (is_bool($editpage->preview) && ($editpage->preview == true))
		{
			$this->setPreviewMode(true);
		}
		else
		{
			$this->setPreviewMode(false);
		}

		//
		global $wgRequest;

		$action = $wgRequest->getVal('action');
		if ($action == 'edit')
		{
			$this->setEditMode(true);
		}
		else
		{
			$this->setEditMode(false);
		}

		if (is_bool($editpage->diff) && ($editpage->diff == true))
		{
			$this->setViewChangesMode(true);
		}
		else
		{
			$this->setViewChangesMode(false);
		}
	}

	private function setPreviewMode($trueFalse)
	{
		if (is_bool($trueFalse))
		{
			$_REQUEST[PA_PREVIEW_MODE] = ($trueFalse == true) ? true : false;
		}
		else
		{
			$_REQUEST[PA_PREVIEW_MODE] = false;
		}
	}

	function isPreviewMode()
	{
		if (isset($_REQUEST[PA_PREVIEW_MODE]))
		{
			return $_REQUEST[PA_PREVIEW_MODE];
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
			$_REQUEST[PA_EDIT_MODE] = ($trueFalse == true) ? true : false;
		}
		else
		{
			$_REQUEST[PA_EDIT_MODE] = false;
		}
	}

	function isEditMode()
	{
		if (isset($_REQUEST[PA_EDIT_MODE]))
		{
			return $_REQUEST[PA_EDIT_MODE];
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
			$_REQUEST[PA_VIEW_CHANGES_MODE] = ($trueFalse == true) ? true : false;
		}
		else
		{
			$_REQUEST[PA_VIEW_CHANGES_MODE] = false;
		}
	}

	function isViewChangesMode()
	{
		if (isset($_REQUEST[PA_VIEW_CHANGES_MODE]))
		{
			return $_REQUEST[PA_VIEW_CHANGES_MODE];
		}
		else
		{
			return false;
		}
	}

}

## ::END::
