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

namespace PageAttachment\Attachment;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class AttachmentDataFactory
{
	private $cache;
	private $runtimeConfig;
	private $securityManager;
	private $articleNameCache;
	private $cacheManager;

	function __construct($securityManager)
	{
		$this->securityManager = $securityManager;
		$this->cache = new \PageAttachment\Cache\AttachmentDataCache();
		$this->runtimeConfig = new \PageAttachment\Configuration\RuntimeConfiguration();
		$this->articleNameCache = new \PageAttachment\Cache\ArticleNameCache();
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
	}

	function newAttachmentData($id)
	{
		$obj = $this->cacheManager->retrieveAtachmentData($id);
		if ($obj instanceof \PageAttachment\Attachment\AttachmentData)
		{
			$pageAttachmentData = $obj;
		}
		else
		{
			$title = \Title::newFromID($id);
			$article = new \Article($title, NS_FILE);
			$file = \wfFindFile($title);
			$size  = $file->getSize();
			$description = $this->replaceHtmlTags( $file->getDescriptionText() );
			$dateUploaded = $article->getTimestamp();
			$uploadedBy = null;
			if ($this->runtimeConfig->isShowUserRealName())
			{
				$uploadedBy = \User::whoIsReal($article->getUser());
			}
			if ($uploadedBy == null)
			{
				$uploadedBy = \User::whoIs($article->getUser());
			}
			$attachedToPages = null;
			if ($this->securityManager->isRemoveAttachmentPermanentlyEnabled())
			{
				$attachedToPages = $this->getAttachedToPages($id);
			}
			$pageAttachmentData = new AttachmentData($id, $title, $size, $description, $dateUploaded, $uploadedBy, $attachedToPages);
			$this->cacheManager->storeAttachmentData($pageAttachmentData);
		}
		return $pageAttachmentData;
	}

	private function getAttachedToPages($attachmentPageId)
	{
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('page_attachment_data', 'attached_to_page_id', 'attachment_page_id = ' . $attachmentPageId);
		if ($rs == false)
		{
			return null;
		}
		else
		{
			$attachedToPages = array();
			$i = 0;
			foreach($rs as $row)
			{
				$attachedToPageId = $row->attached_to_page_id;
				$pageName = $this->cacheManager->retrieveArticleName($attachedToPageId);
				if (isset($pageName))
				{
					$attachedToPages[$i++] = $pageName;
				}
				else
				{
					$rs2 = $dbr->select('page','page_title', 'page_id = ' . $attachedToPageId);
					if ($found = $rs2->fetchRow($rs2))
					foreach($rs2 as $row2)
					{
						$title = \Title::newFromText($row2->page_title);
						$pageName = $title->getText();
						$attachedToPages[$i++] = $pageName;
						$this->cacheManager->storeArticleName($attachedToPageId, $pageName);
					}
				}
			}
			return $attachedToPages;
		}
	}

	private function replaceHtmlTags($htmlText)
	{
		if (is_string($htmlText))
		{
			$text = preg_replace('/\t|\n|\r/', '',  preg_replace('/<\/*p\/*>/', '', $htmlText));
		}
		else
		{
			$text =  '';
		}

		return $text;
	}

}

## :: END ::
