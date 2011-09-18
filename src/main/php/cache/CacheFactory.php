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

class CacheFactory
{
	private $cache;

	function __construct() 
	{
		$this->cache = null;
	}

	function createCache()
	{
		if (!isset($this->cache))
		{
			global $wgPageAttachment_useInternalCache;
			global $wgPageAttachment_internalCacheType;

			if (isset($wgPageAttachment_useInternalCache) && ($wgPageAttachment_useInternalCache == true))
			{
				if (isset($wgPageAttachment_internalCacheType))
				{
					if ($wgPageAttachment_internalCacheType == 'SQLite3')
					{
						$this->cache = new Provider\SQLiteCache();
					}
					else if ($wgPageAttachment_internalCacheType == 'Database')
					{
						$this->cache = new Provider\DatabaseCache();
					}
					else
					{
						print '<br/>*** ERROR ***************************************************************<br/>';
						print 'PageAttachment internal cache is enabled.<br/>';
						print 'However, invalid PageAttachment internal cache type specified: ' . $wgPageAttachment_internalCacheType . '<br/>';
						print '*************************************************************************<br/>';
					}
				}
				else
				{
					print '<br/>*** ERROR ***************************************************************<br/>';
					print 'PageAttachment internal cache is enabled.<br/>';
					print 'However, PageAttachment internal cache type is not specified.<br/>';
					print '*************************************************************************<br/>';

				}
			}
			else
			{
				$mwCacheObj =  \wfGetMainCache();
				$this->cache = new Provider\MWCacheObjWrapper($mwCacheObj);
			}
		}
		return $this->cache;
	}
}

## :: END ::
