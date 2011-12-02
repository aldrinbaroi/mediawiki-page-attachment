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

class CategoryManager
{
	private $session;
	private $cacheManager;
	private $defaultCategory;

	function __construct($session)
	{
		global $wgPageAttachment_attachmentCategory;

		$this->session = $session;
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
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
		$categoryList = $this->cacheManager->retrieveCategoryList();
		if (!isset($categoryList))
		{
			$categoryList = $this->initializeCategoryList();
		}
		return $categoryList;
	}

	function initializeCategoryList()
	{
		global $wgPageAttachment_attachmentCategory;

		$categoryList = array();
		if (isset($wgPageAttachment_attachmentCategory))
		{
			if (isset($wgPageAttachment_attachmentCategory['allowedCategories']))
			{
				$allowedCategories = $wgPageAttachment_attachmentCategory['allowedCategories'];
				switch ($allowedCategories)
				{
					case 'PredefinedCategoriesOnly':
						$categoryList = $this->getPredefinedCategories();
						break;
					case 'MediaWikiCategoriesOnly':
						$categoryList = $this->getMediaWikiCategories();
						break;
					case 'BothPredefinedAndMediaWikiCategories':
						$categoryList = $this->getBothPredefinedAndMediaWikiCategories();
						break;
					default:
						// Not set, defaulting to 'MediaWikiCategoriesOnly'
						$categoryList = $this->getMediaWikiCategories();
					break;
				}
			}
			else
			{
				// Not set, defaulting to 'MediaWikiCategoriesOnly'
				$categoryList = $this->getMediaWikiCategories();
			}
			$modifiedCategoryList = $this->modifyCategoryNamesForPresentation($categoryList);
			if (count($this->cacheManager->retrieveCategoryList()))
			{
				$this->cacheManager->removeCategoryList();
			}
			$this->cacheManager->storeCategoryList($modifiedCategoryList);
			$categoryList = $modifiedCategoryList;
		}
		return $categoryList;
	}

	function setReinitializeCategoryList(&$linksUpdate)
	{
		$existing = $linksUpdate->getExistingCategories();
		$categoryDeletes = $linksUpdate->getCategoryDeletions( $existing );
		$categoryInserts = array_diff_assoc( $linksUpdate->mCategories, $existing );
		if (count($categoryInserts) > 0 || count($categoryDeletes) > 0)
		{
			$this->session->setReinitializeCategoryList();
		}
	}

	function reinitializeCategoryList()
	{
		if ($this->session->isReinitializeCategoryList())
		{
			$this->cacheManager->removeCategoryList();
			$this->initializeCategoryList();
		}
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
		$rs = $dbr->select('category', 'cat_title');
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

	private function modifyCategoryNamesForPresentation($categoryList)
	{
		$i = 0;
		$modifiedCategoryList = array();
		if (count($categoryList) > 0)
		{
			foreach($categoryList as $category)
			{
				$title = \Title::makeTitleSafe( NS_CATEGORY, $category );
				$modifiedCategoryList[$i++] = $title->getText();
			}
		}
		return $modifiedCategoryList;
	}
}

## :: END ::