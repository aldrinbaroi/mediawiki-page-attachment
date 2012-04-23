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

class SQLiteCache implements \PageAttachment\Cache\ICache
{
	private $sqlite;

	function __construct()
	{
		global $wgPageAttachment_sqlite3CacheDirectory;

		if (isset($wgPageAttachment_sqlite3CacheDirectory))
		{
			$cacheDBFile = $wgPageAttachment_sqlite3CacheDirectory . '/pageattachment-cache.db';
			try
			{
				$this->sqlite = new SQLite3($cacheDBFile, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
				$this->sqlite->exec('create table if not exists  page_attachment_cache(id text primary key, data blob)');
			}
			catch(Exception $e)
			{
				print '<br/>*** ERROR ***************************************************************<br/>';
				print 'PageAttachment internal cache error<br/>';
				print 'Cache DB File: ' . $cacheDBFile . '<br/>';
				print $e;
				print '<br/>*************************************************************************<br/>';
			}
		}
		else
		{
			print '<br/>*** ERROR ***************************************************************<br/>';
			print 'PageAttachment internal SQLite3 cache is enabled.<br/>';
			print 'However cache directory is not set.<br/>';
			print '*************************************************************************<br/>';
		}
	}

	function store($id, $obj)
	{
		$data = serialize($obj);
		$this->remove($id);
		$stmt = $this->sqlite->prepare("insert into page_attachment_cache (id, data) values(:id, :data)");
		$stmt->bindValue(':id', $id, SQLITE3_TEXT);
		$stmt->bindValue(':data', $data, SQLITE3_BLOB);
		$result = $stmt->execute();
	}

	function retrieve($id)
	{
		$obj = null;
		$result = $this->sqlite->querySingle("select data from page_attachment_cache where id = '$id'");
		if ($result)
		{
			$obj = unserialize($result);
		}
		return $obj;
	}

	function remove($id)
	{
		$this->sqlite->exec("delete from page_attachment_cache where id = '$id'");
	}
}

## :: END ::
