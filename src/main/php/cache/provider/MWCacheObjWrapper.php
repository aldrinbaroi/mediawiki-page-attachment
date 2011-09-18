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

class MWCacheObjWrapper implements \PageAttachment\Cache\ICache
{
	private $mwCacheObj;

	function __construct($mwCacheObj)
	{
		$this->mwCacheObj = $mwCacheObj;
	}

	function store($id, $obj)
	{
		$data = serialize($obj);
		$this->mwCacheObj->set($id, $data);
	}

	function retrieve($id)
	{
		$obj = null;
		$data = $this->mwCacheObj->get($id);
		if ($data)
		{
			$obj = unserialize($data);
		}
		return $obj;
	}

	function remove($id)
	{
		$this->mwCacheObj->delete($id);
	}

}

## :: END ::
