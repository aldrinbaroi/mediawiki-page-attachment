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

class WatchedItemFactory
{
	private function __construct()
	{
	}

	public static function createWatchedItem($pageId, $modifiedByUserId, $modificationType, $modificationTime)
	{
		$title = \Title::newFromID($pageId);
		$pageTitle = $title->getText();
		$watchers = self::loadWatchers($title, $modifiedByUserId);
		$watched = count($watchers) > 0 ? true : false;
		return new WatchedItem($pageId, $pageTitle, $modifiedByUserId, $modificationType, $modificationTime, $watched, $watchers);
	}

	private static function loadWatchers($title, $modifiedByUserId)
	{
		$watchers = array();
		$dbr = \wfGetDB( DB_SLAVE );
		$res = $dbr->select(array( 'watchlist' ), array( 'wl_user' ),
		array(  'wl_title' => $title->getDBkey(), 'wl_namespace' => $title->getNamespace(), 'wl_user != ' . intval( $modifiedByUserId ))
		);
		foreach ( $res as $row )
		{
			$watchers[] = intval( $row->wl_user );
		}
		return $watchers;
	}
}

## ::END::
