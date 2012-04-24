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

class Page
{
	private $id;
	private $nameSpace;
	private $nameSpacePrefix;
	private $url;
	private $prefixedUrl;
	private $fullUrl;
	private $pageTitle;
	private $protected;
	private $categories;
	
	function __construct($id, $nameSpace, $nameSpacePrefix, $url, $prefixedUrl, $fullUrl, $pageTitle, $protected, $categories)
	{
		$this->id = $id;
		$this->nameSpace = $nameSpace;
		$this->nameSpacePrefix = $nameSpacePrefix;
		$this->url = $url;
		$this->prefixedUrl = $prefixedUrl;
		$this->pageTitle = $pageTitle;
		$this->protected = $protected;
		$this->categories = $categories;
	}

	function getId()
	{
		return $this->id;
	}

	function getNameSpace()
	{
		return $this->nameSpace;
	}
	
	function getNameSpacePrefix()
	{
		return $this->nameSpacePrefix;
	}

	function getURL()
	{
		return $this->url;
	}

	function getPrefixedURL()
	{
		return $this->prefixedUrl;
	}

	function getRedirectURL()
	{
		if ($this->nameSpace == NS_MAIN)
		{
			return $this->url;
		}
		else
		{
			return $this->prefixedUrl;
		}
	}

	function getFullURL()
	{
		return $this->fullUrl;
	}

	function getPageTitle()
	{
		return $this->pageTitle;
	}

	function isProtected()
	{
		return $this->protected;
	}
	
	function getCategories()
	{
		return $this->categories;
	}
}

## ::END::
