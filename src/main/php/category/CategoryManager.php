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

namespace PageAttachment\Category;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

// TODO use cache
// TODO update category cache when new category is added
// TODO update category cache when a category is deleted

class CategoryManager
{
	//private $securityManager;
	private $cacheManager;
	private $defaultCategory;

	function __construct() //$securityManager)
	{
		//$this->securityManager = $securityManager;
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
		$this->initialize();
	}

	private function initialize()
	{
		global $wgPageAttachment_attachmentCategory;

		if (isset($wgPageAttachment_attachmentCategory['defaultCategory'])
		&& strlen($wgPageAttachment_attachmentCategory['defaultCategory']) > 0)
		{
			$this->defaultCategory = $wgPageAttachment_attachmentCategory['defaultCategory'];
		}
		else
		{
			$this->defaultCategory =  null;
		}
	}

	function getDefaultCatogry()
	{
		return $this->defaultCategory;
	}

	function isDefaultCategorySet()
	{
		if ($this->defaultCategory == null)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	function isDefaultCategory($category)
	{
		if ($this->defaultCategory != null && $this->defaultCategory == $category)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function isMustSetCategory()
	{
		global $wgPageAttachment_attachmentCategory;
		
		if (isset($wgPageAttachment_attachmentCategory['mustSet']) && $wgPageAttachment_attachmentCategory['mustSet'] == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function getCategoryList()
	{
		global $wgPageAttachment_attachmentCategory;

		$categories = array();
		if (isset($wgPageAttachment_attachmentCategory))
		{
			if (isset($wgPageAttachment_attachmentCategory['allowedCategories']))
			{
				$allowedCategories = $wgPageAttachment_attachmentCategory['allowedCategories'];
				switch ($allowedCategories)
				{
					case 'PredefinedCategoriesOnly':
						$categories = $this->getPredefinedCategories();
						break;
					case 'MediaWikiCategoriesOnly':
						$categories = $this->getMediaWikiCategories();
						break;
					case 'BothPredefinedAndMediaWikiCategories':
						$categories = $this->getBothPredefinedAndMediaWikiCategories();
						break;
					default:
						// Not set, defaulting to 'MediaWikiCategoriesOnly'
						$categories = $this->getMediaWikiCategories();
					break;
				}
			}
			else
			{
				// Not set, defaulting to 'MediaWikiCategoriesOnly'
				$categories = $this->getMediaWikiCategories();
			}
		}
		return $categories;
	}

	private function getPredefinedCategories()
	{
		global $wgPageAttachment_attachmentCategory;

		$predefinedCategories = array();
		if (isset($wgPageAttachment_attachmentCategory['predefinedCategories']))
		{
			foreach($wgPageAttachment_attachmentCategory['predefinedCategories'] as $predefinedCategory)
			{
				$predefinedCategories[] = $predefinedCategory;
			}
		}
		return $predefinedCategories;
	}

	private function getMediaWikiCategories()
	{
		$mediaWikiCategories = array();
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('category', 'cat_title'); //, 'attachment_page_id = ' . $attachmentPageId);
		if ($rs == false)
		{
			//
		}
		else
		{
			$i = 0;
			foreach($rs as $row)
			{
				$mediaWikiCategories[$i++] = $row->cat_title;
			}
		}
		return $mediaWikiCategories;
	}

	private function getBothPredefinedAndMediaWikiCategories()
	{
		$predefinedCategories = $this->getPredefinedCategories();
		$mediaWikiCategories = $this->getMediaWikiCategories();
		if (count($predefinedCategories) > 0 && count($mediaWikiCategories) > 0)
		{
			return array_unique( array_merge($predefinedCategories, $mediaWikiCategories));
		}
		else if (count($predefinedCategories) > 0)
		{
			return $predefinedCategories;
		}
		else if (count($mediaWikiCategories) > 0)
		{
			return $mediaWikiCategories;
		}
		else
		{
			return array();
		}
	}
}

## :: END ::
