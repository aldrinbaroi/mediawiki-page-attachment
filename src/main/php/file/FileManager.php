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


	function __construct($securityManager)
	{
		$this->securityManager = $securityManager;
	}


	function getFile($fileName)
	{
		$file = null;
		$dbr = \wfGetDB( DB_SLAVE );
		$res = $dbr->select(array( 'imagelinks', 'page' ), array( 'page_namespace', 'page_title' ),
		array( 'il_to' => $fileName, 'il_from = page_id' ));
		$embeddedInPagesCount = $dbr->numRows( $res );
		if ( $embeddedInPagesCount == 0 )
		{
			$file = new File($fileName);
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
				$title = \Title::makeTitle( $pageData->page_namespace, $pageData->page_title );
				$embeddedInPagesNames[] =  $title->getText();
			}
			$file = new File($fileName, $embeddedInPagesNames);
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
