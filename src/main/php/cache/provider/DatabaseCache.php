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

namespace PageAttachment\Cache\Provider;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class DatabaseCache implements \PageAttachment\Cache\ICache
{
	private $sqlite;

	function __construct()
	{
	}

	function store($id, $obj)
	{
		$data = serialize($obj);
		$this->remove($id);
		$dbw = \wfGetDB( DB_MASTER );
		$dbw->insert('page_attachment_cache', array('id' => $id, 'data' => $data));
	}

	function retrieve($id)
	{
		$dbr = \wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow('page_attachment_cache', 'data', 'id = \'' . $id . '\'');
		if ($row)
		{
			if ($row->data)
			{
				$obj = unserialize($row->data);
			}
			else
			{
				$obj = null;
			}
		}
		else
		{
			$obj = null;
		}
		return $obj;
	}

	function remove($id)
	{
		$dbw = \wfGetDB( DB_MASTER );
		$dbw->delete('page_attachment_cache', array('id' => $id));
	}
}

## :: END ::
