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

namespace PageAttachment\WatchedItem;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class WatchedItem
{
	private $pageId;
	private $pageTitle;
	private $modifiedByUserId;
	private $modificationType;
	private $modificationTime;
	private $watched;
	private $watchers;

	function __construct($pageId, $pageTitle, $modifiedByUserId, $modificationType, $modificationTime, $watched, $watchers)
	{
		$this->pageId = $pageId;
		$this->pageTitle = $pageTitle;
		$this->modifiedByUserId = $modifiedByUserId;
		$this->modificationType = $modificationType;
		$this->modificationTime = $modificationTime;
		$this->watched = $watched;
		$this->watchers = $watchers;
	}

	function getPageId()
	{
		return $this->pageId;
	}

	function getPageTitle()
	{
		return $this->pageTitle;
	}

	function getModifiedByUserId()
	{
		return $this->modifiedByUserId;
	}

	function getModificationType()
	{
		return $this->modificationType;
	}

	function getModificationTime()
	{
		return $this->modificationTime;
	}

	function isWatched()
	{
		return $this->watched;
	}

	function getWatchers()
	{
		return $this->watchers;
	}

}

## :: END ::