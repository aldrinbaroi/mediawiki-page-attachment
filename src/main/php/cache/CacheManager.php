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

namespace PageAttachment\Cache;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class CacheManager
{
	private $attachmentDataCache;
	private $attachmentListCache;
	private $userCache;
	private $articleNameCache;
	private $fileCache;
	private $categoryListCache;
	private $pageCache;

	function __construct()
	{
		$this->attachmentDataCache = new AttachmentDataCache();
		$this->attachmentListCache = new AttachmentListCache();
		$this->userCache = new UserCache();
		$this->articleNameCache = new ArticleNameCache();
		$this->fileCache = new FileCache();
		$this->categoryListCache = new CategoryListCache();
		$this->pageCache = new PageCache();
	}

	function storeAttachmentData($attachmentData)
	{
		$this->attachmentDataCache->store($attachmentData->getId(), $attachmentData);
	}

	function retrieveAtachmentData($attachmentId)
	{
		return $this->attachmentDataCache->retrieve($attachmentId);
	}

	function removeAttachmentData($attachmentId)
	{
		$this->attachmentDataCache->remove($attachmentId);
	}

	function storeAttachmentList($attachedToPageId, $attachmentList)
	{
		$this->attachmentListCache->store($attachedToPageId, $attachmentList);
	}

	function retrieveAtachmentList($attachedToPageId)
	{
		return $this->attachmentListCache->retrieve($attachedToPageId);
	}

	function removeAttachmentList($attachedToPageId)
	{
		$this->attachmentListCache->remove($attachedToPageId);
	}

	function storeUser($user)
	{
		$this->userCache->store($user->getId(), $user);
	}

	function retrieveUser($userId)
	{
		return $this->userCache->retrieve($userId);
	}

	function removeUser($userId)
	{
		$this->userCache->removeUser($userId);
	}

	function storeArticleName($articleId, $articleName)
	{
		$this->articleNameCache->store($articleId, $articleName);
	}

	function retrieveArticleName($articleId)
	{
		return $this->articleNameCache->retrieve($articleId);
	}

	function removeArticleName($articleId)
	{
		$this->articleNameCache->remove($articleId);
	}

	function storeFile($file)
	{
		$this->fileCache->store($file);
	}

	function retrieveFile($fieName)
	{
		return $this->fileCache->retrieve($fileName);
	}

	function removeFile($fileName)
	{
		$this->fileCache->remove($fileName);
	}

	function storeCategoryList($categoryList)
	{
		$this->categoryListCache->store($categoryList);
	}

	function retrieveCategoryList()
	{
		return $this->categoryListCache->retrieve();
	}

	function removeCategoryList()
	{
		$this->categoryListCache->remove();
	}
	
	function storePage(\PageAttachment\session\Page $page)
	{
		$this->pageCache->store($page);
	}
	
	function retrievePage($pageId)
	{
		return $this->pageCache->retrieve($pageId);
	}
	
	function removePage($pageId)
	{
		$this->pageCache->remove($pageId);
	}

}

## :: END ::
