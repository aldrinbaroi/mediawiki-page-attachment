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

class CategoryManagerHelper
{


	function __construct()
	{
	}

	function isCategoriesCountChanged(&$linksUpdate)
	{
		$categoriesCountChanged = false;
		$existing = $this->getExistingCategories($linksUpdate);
		$categoryDeletes = $this->getCategoryDeletions($linksUpdate, $existing );
		$categoryInserts = array_diff_assoc( $linksUpdate->mCategories, $existing );
		if (count($categoryInserts) > 0 || count($categoryDeletes) > 0)
		{
			$categoriesCountChanged = true;
		}
		return $categoriesCountChanged;
	}

	// ********************************************************************************
	//
	// The following methods are copied from "LinksUpdate" class from "LinksUpdate.php"
	// file and updated to pass in $linksUpdate object; since these methods are now 
	// private in MediaWiki 1.19
	//
	//       1) getExistingCategories()
	//       2) getCategoryDeletions()
	//
	// NOTE: The following variables are not still private in "LinksUpdate" class.
	//       1) $mDb
	//       2) $mId
	//       3) $mOptions
	//       4) $mCategories
	//
	//       If any of the above variable's access permission is changed to private
	//       then need to rewrite these methods again.
	//
	// ********************************************************************************

	/**
	 * Get an array of existing categories, with the name in the key and sort key in the value.
	 *
	 * @return array
	 */
	private function getExistingCategories(&$linksUpdate)
	{
		$res = $linksUpdate->mDb->select( 'categorylinks', array( 'cl_to', 'cl_sortkey_prefix' ),
		array( 'cl_from' => $linksUpdate->mId ), __METHOD__, $linksUpdate->mOptions );
		$arr = array();
		foreach ( $res as $row ) {
			$arr[$row->cl_to] = $row->cl_sortkey_prefix;
		}
		return $arr;
	}

	/**
	 * Given an array of existing categories, returns those categories which are not in $this
	 * and thus should be deleted.
	 * @param $existing array
	 * @return array
	 */
	private function getCategoryDeletions(&$linksUpdate, $existing )
	{
		return array_diff_assoc( $existing, $linksUpdate->mCategories );
	}


}

## :: END ::
