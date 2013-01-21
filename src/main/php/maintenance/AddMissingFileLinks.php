<?php
/**
 *
 * Copyright (C) 2013 Aldrin Edison Baroi
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

namespace PageAttachment\Maintenance;

$mediaWikiBaseDir = dirname( __FILE__ ) . '/../../..';
$preIP = $mediaWikiBaseDir; 

require_once("$preIP/maintenance/Maintenance.php");

$maintClass = "\PageAttachment\Maintenance\AddMissingFileLinks";

class AddMissingFileLinks extends \Maintenance
{

	public function __construct()
	{
		parent::__construct();
		$this->mDescription = "Script to add missing file links for the attachments.";
	}

	public function execute()
	{
		$this->println('Adding missing file links for attachments.');
		try
		{
			$dbr = \wfGetDB( DB_SLAVE );
			$res = $dbr->select('page_attachment_data', '*');
			if ( !$res->numRows() )
			{
				$this->println('No attachments found.  No links added.');
				break;
			}
			else
			{
				foreach ( $res as $row )
				{
					$linkFromPageId = $row->attached_to_page_id;
					$linkToFileId = $row->attachment_page_id;
					$linkToFileTitle = \Title::newFromId($linkToFileId);
					$linkToFileDatabaseKey = $linkToFileTitle->getDBkey();
					if (!$this->isLinkExist($linkFromPageId, $linkToFileDatabaseKey))
					{
						$linkFromPageTitle = \Title::newFromId($linkFromPageId);
						$linkToFileName = $linkToFileTitle->getText();
						$this->println('Adding missing link from page [' . $linkFromPageTitle . '] to attachment file [' . $linkToFileName . ']');
						$linkToFileWikiPage = \WikiPage::factory($linkToFileTitle);
						$linkToFileWikiPage->doPurge();
						$this->addLink($linkFromPageId, $linkToFileDatabaseKey);
					}
				}
				$this->println('Finished adding file links.');
			}
		}
		catch(Exception $e)
		{
			$this->println('Failed to add file links.  Error: [' . $e->getMessage() . ']');
		}

	}

	private function isLinkExist($linkFromPageId, $linkToFileDatabaseKey)
	{
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('imagelinks', '*', 'il_from = ' . $linkFromPageId . ' and il_to = ' . $dbr->addQuotes($linkToFileDatabaseKey));
		if ($row = $dbr->fetchRow($rs))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private function addLink($linkFromPageId, $linkToFileDatabaseKey)
	{
		$dbw = \wfGetDB( DB_MASTER );
		$dbw->insert('imagelinks', array(0 =>array('il_from' => $linkFromPageId, 'il_to' => $linkToFileDatabaseKey)));
	}
	
	private function println($msg = '')
	{
		$this->output($msg . "\n");
	}
}

require_once( RUN_MAINTENANCE_IF_MAIN );

## :: END ::
