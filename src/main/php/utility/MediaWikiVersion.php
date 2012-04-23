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


namespace PageAttachment\Utility;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class MediaWikiVersion
{

	private $version;
	private $majorVersion;
	private $minorVersion;
	private $patchNumber;

	function __construct()
	{
	}

	private function init()
	{
		global $wgVersion;

		$this->version = $wgVersion;
		$versionInfo = explode( '.', $wgVersion);
		$this->majorVersion = $versionInfo[0];
		$matches = array();
		if (preg_match("/(\d+)([[:alpha:]]+)/", $versionInfo[1], $matches) > 0)
		{
			$this->minorVersion = $matches[1];
			$this->patchNumber = $matches[2];
		}
		else
		{
			$this->minorVersion = $versionInfo[1];
			$this->patchNumber = $versionInfo[2];
		}
	}

	function getVersion()
	{
		if (!isset($this->version))
		{
			$this->init();
		}
		return $this->version;
	}

	function getMajorVersion()
	{
		if (!isset($this->majorVersion))
		{
			$this->init();
		}
		return $this->majorVersion;
	}

	function getMinorVersion()
	{
		if (!isset($this->minorVersion))
		{
			$this->init();
		}
		return $this->minorVersion;
	}

	function getPatchNumber()
	{
		if (!isset($this->patchNumber))
		{
			$this->init();
		}
		return $this->patchNumber;
	}

}

## :: END ::
