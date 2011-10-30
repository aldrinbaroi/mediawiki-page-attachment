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

namespace PageAttachment\Security\MediaWiki;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class MediaWikiSecurityManager
{
	private $uploadPermissionChecker;

	function __construct(\PageAttachment\Security\MediaWiki\Upload\IUploadPermissionChecker $uploadPermissionChecker)
	{
		$this->uploadPermissionChecker = $uploadPermissionChecker;
	}

	function isWikiInReadonlyMode()
	{
		if (\wfReadOnly() == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function isUserBlocked()
	{
		global $wgUser;

		if ($wgUser->isBlocked() == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function isUploadEnabled()
	{
		return $this->uploadPermissionChecker->isUploadEnabled();
	}

	function isUploadAllowed()
	{
		return $this->uploadPermissionChecker->isUploadAllowed();
	}

	function isUserAllowedToDeleteFile()
	{
		global $wgUser;

		$title = \Title::newFromText(\wfMsgForContent('mainpage'));
		$permission_errors = $title->getUserPermissionsErrors('delete', $wgUser);
		if (count($permission_errors)>0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

## :: END ::

