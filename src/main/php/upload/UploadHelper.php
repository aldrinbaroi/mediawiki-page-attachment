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

namespace PageAttachment\Upload;

use PageAttachment\Category\Category;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class UploadHelper
{
	private $categoryManager;

	function __construct($categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}

	function isSetAttachmentCategoryOnUploadEnabled()
	{
		global $wgPageAttachment_attachmentCategory;

		if (isset($wgPageAttachment_attachmentCategory['setOnUpload']))
		{
			return ($wgPageAttachment_attachmentCategory['setOnUpload'] == true) ? true : false;
		}
		else
		{
			return false;
		}
	}

	// TODO Remove hardcoded HTML tags using MediaWiki's \HTML class
	function addCategoryChooserToUploadForm($uploadFormObj)
	{
		$categoryChooserHTML = '<tr>'.
		'<td class="mw-label">' . 
		'  <label for="attachmentCategory">'. \wfMsg('selectCategory') . ':</label>' . 
		'</td>' . 
		'<td class="mw-input">' . 
		'  <select id="attachmentCategory" name="attachmentCategory">'; 
		if (!$this->categoryManager->isMustSetCategory())
		{
			$categoryChooserHTML .= '<option value=""></option>';
		}
		foreach($this->categoryManager->getCategoryList() as $category)
		{
			if ($this->categoryManager->isDefaultCategorySet() && $this->categoryManager->isDefaultCategory($category))
			{
				$categoryChooserHTML .= '<option value="'. $category .'" selected="selected">' . $category . '</option>';
			}
			else
			{
				$categoryChooserHTML .= '<option value="'. $category .'">' . $category . '</option>';
			}
		}
		$categoryChooserHTML .= '  </select>' .
		'</td>' . 
		'</tr>';
		$uploadFormTextAfterSummary = $uploadFormObj->uploadFormTextAfterSummary;
		$uploadFormObj->uploadFormTextAfterSummary = $categoryChooserHTML . $uploadFormTextAfterSummary;
	}

	function setAttachmentCategory($uploadFormObj)
	{
		global $wgRequest;

		$attachmentCategory = $wgRequest->getText( 'attachmentCategory' );
		if ( isset($attachmentCategory) && (strlen($attachmentCategory) > 0))
		{
			$uploadFormObj->mComment .= " \n \n" . '[[Category:' . $attachmentCategory . ']]';
		}
	}
}

## :: END ::
