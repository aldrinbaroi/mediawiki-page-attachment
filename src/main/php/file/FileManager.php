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

namespace PageAttachment\File;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class FileManager
{
	private $securityManager;
	private $cacheManager;


	function __construct($securityManager)
	{
		$this->securityManager = $securityManager;
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
	}

	function getFile($fileName)
	{
		if ($fileName instanceof \Title)
		{
			$title = $fileName;
		}
		else
		{
			$title = \Title::newFromText($fileName, NS_FILE);
		}
		$userFileName = $title->getText();
		$mwFileName = $title->getPartialURL();
		$file = null;
		$dbr = \wfGetDB( DB_SLAVE );
		$res = $dbr->select(array( 'imagelinks', 'page' ), array( 'page_namespace', 'page_title', 'page_id' ),
		array( 'il_to' => $mwFileName, 'il_from = page_id' ));
		$embeddedInPagesCount = $dbr->numRows( $res );
		if ( $embeddedInPagesCount == 0 )
		{
			$file = new File($userFileName);
		}
		else
		{
			$count = 0;
			$embeddedInPagesNames = array();
			foreach ( $res as $pageData )
			{
				if (++$count > 3)
				{
					$embeddedInPagesNames[] = '...';
					break;
				}
				$pageName = $this->cacheManager->retrieveArticleName($pageData->page_id);
				if (isset($pageName))
				{
					$embeddedInPagesNames[] = $pageName;
				}
				else
				{
					$title = \Title::makeTitle( $pageData->page_namespace, $pageData->page_title );
					$pageName =  $title->getText();
					$embeddedInPagesNames[] = $pageName;
					$this->cacheManager->storeArticleName($pageData->page_id, $pageName);
				}
			}
			$file = new File($userFileName, $embeddedInPagesNames);
		}
		return $file;
	}

	function removeFilePermanently($fileName)
	{
		$deleteSuccess = false;
		try
		{
			$title = \Title::newFromText($fileName, NS_FILE);
			$file = \wfFindFile( $title, array( 'ignoreRedirect' => true ) );
			$oldimage = null; // Leave it to null to delete all
			$reason = \wfMsg('attachmentRemovedPermanently');
			$suppress = false; // Do not hide revision log
			$status = \FileDeleteForm::doDelete( $title, $file, $oldimage, $reason, $suppress );
			$deleteSuccess =  ($status->isGood()) ? true : false;
		}
		catch(Exception $e)
		{
			$deleteSuccess = false;
		}
		return $deleteSuccess;
	}
}

## :: END ::
