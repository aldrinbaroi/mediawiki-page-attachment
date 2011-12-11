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
	private $url;
	private $prefixedUrl;
	private $fullUrl;
	private $pageTitle;

	function __construct($title = NULL)
	{
		if (isset($title))
		{
			if ($title instanceof \Title)
			{
				$_title = $title;
			}
			else
			{
				$_title = \Title::newFromText($title);
			}
			$this->id = $_title->getArticleID();
			$this->nameSpace = $_title->getNamespace();
			$this->url = $_title->getPartialURL();
			$this->prefixedUrl = $_title->getPrefixedDBkey();
			$this->fullUrl = $_title->getFullURL();
			$this->pageTitle  = trim($_title->getText());
		}
		else
		{
			$this->id = -1;
			$this->nameSpace = -1;
			$this->url = '';
			$this->prefixedUrl = '';
			$this->pageTitle = '';
		}
	}

	function getId()
	{
		return $this->id;
	}

	function getNameSpace()
	{
		return $this->nameSpace;
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
}

## ::END::
