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

class MediaWikiSecurityManagerFactory
{
	private $mediaWikiSecurityManager;

	function __construct()
	{

	}

	function createMediaWikiSecurityManager()
	{
		if (!isset($this->mediaWikiSecurityManager))
		{
			$uploadPermissionChecker = null;
			$versionSupported = true;
			$mwv = new \PageAttachment\Utility\MediaWikiVersion();
			$majorVersion = $mwv->getMajorVersion();
			$minorVersion = $mwv->getMinorVersion();
			$patchNumber = $mwv->getPatchNumber();
			if ($majorVersion == 1)
			{
				switch($minorVersion)
				{
					case 16:
						$uploadPermissionChecker = new Upload\UploadPermissionChecker_MediaWiki_v1162();
						break;
					case 17:
						$uploadPermissionChecker = new Upload\UploadPermissionChecker_MediaWiki_v1170();
						break;
					default:
						$versionSupported = false;
				}
			}
			else
			{
				$versionSupported = false;
			}
			if ($versionSupported == true)
			{
				$this->mediaWikiSecurityManager = new MediaWikiSecurityManager($uploadPermissionChecker);
			}
			else
			{
				throw new \Exception("MediaWiki version [" + $mwv->getVersion() + "] is not supported!");
			}
		}
		return $this->mediaWikiSecurityManager;
	}

}

## :: END ::

