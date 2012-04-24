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

class PageFactory
{
	private $cacheManager;

	function __construct(\PageAttachment\Cache\CacheManager $cacheManager)
	{
		$this->cacheManager = $cacheManager;
	}

	function createPage($title = NULL)
	{
		$page = null;
		if (isset($title))
		{
			global $wgUser;

			if ($title instanceof \Title)
			{
				$_title = $title;
			}
			else
			{
				$_title = \Title::newFromText($title);
			}
			$id = $_title->getArticleID();
			$_page = $this->cacheManager->retrievePage($id);
			if ($_page instanceof Page)
			{
				$page = $_page;
			}
			else
			{
				$nameSpace = $_title->getNamespace();
				$url = $_title->getPartialURL();
				$prefixedUrl = $_title->getPrefixedDBkey();
				$nameSpacePrefix = $this->getNameSpacePrefix($prefixedUrl);
				$fullUrl = $_title->getFullURL();
				$pageTitle  = trim($_title->getText());
				$protected = (count($title->getUserPermissionsErrors('edit', $wgUser)) > 0) ? true : false;
				$categories = $this->getCategories($id);
				$page = new Page($id, $nameSpace, $nameSpacePrefix, $url, $prefixedUrl, $fullUrl, $pageTitle, $protected, $categories);
				$this->cacheManager->storePage($page);
			}
		}
		else
		{
			$id = -1;
			$nameSpace = -1;
			$nameSpacePrefix = '';
			$url = '';
			$prefixedUrl = '';
			$fullUrl = '';
			$pageTitle = '';
			$protected = false;
			$categories = array();
			$page = new Page($id, $nameSpace, $nameSpacePrefix, $url, $prefixedUrl, $fullUrl, $pageTitle, $protected, $categories);
		}
		return $page;
	}

	private function getCategories($pageId)
	{
		$categories = array();
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select( 'categorylinks', '*', array('cl_from' => $pageId) , __METHOD__, array());
		if ( $dbr->numRows( $rs ) > 0 )
		{
			foreach ( $rs as $row )
			{
				$categories[] = $row->cl_to;
			}
		}
		return $categories;
	}

	private function getNameSpacePrefix($prefixedUrl)
	{
		$tokens = explode(':', $prefixedUrl);
		if (count($tokens) == 2)
		{
			$nameSpacePrefix = $tokens[0];
		}
		else
		{
			$nameSpacePrefix = '';
		}
		return $nameSpacePrefix;
	}

}

## ::END::

