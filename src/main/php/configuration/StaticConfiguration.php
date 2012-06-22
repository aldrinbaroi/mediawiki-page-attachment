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

namespace PageAttachment\Configuration;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

// TODO Move all static configuration evaluation into this class

class StaticConfiguration
{
	private static $INSTANCE;
	private $allowAttachmentsUsingMagicWord;
	private $disallowAttachmentsUsingMagicWord;


	private function __construct()
	{
	}
	
	static function getInstance()
	{
		if (!isset(self::$INSTANCE))
		{
			self::$INSTANCE = new StaticConfiguration();
		}
		return self::$INSTANCE;
	}

	function isAllowAttachmentsUsingMagicWord()
	{
		if (!isset($allowAttachmentsUsingMagicWord))
		{
			global $wgPageAttachment_allowAttachmentsUsingMagicWord;

			if (isset($wgPageAttachment_allowAttachmentsUsingMagicWord) && is_bool($wgPageAttachment_allowAttachmentsUsingMagicWord))
			{
				$allowAttachmentsUsingMagicWord =  $wgPageAttachment_allowAttachmentsUsingMagicWord;
			}
			else
			{
				$allowAttachmentsUsingMagicWord =  false;
			}
		}
		return $allowAttachmentsUsingMagicWord;
	}
	
	function isDisallowAttachmentsUsingMagicWord()
	{
		if (!isset($disallowAttachmentsUsingMagicWord))
		{
			global $wgPageAttachment_disllowAttachmentsUsingMagicWord;
	
			if (isset($wgPageAttachment_disllowAttachmentsUsingMagicWord) && is_bool($wgPageAttachment_disllowAttachmentsUsingMagicWord))
			{
				$disallowAttachmentsUsingMagicWord =  $wgPageAttachment_disllowAttachmentsUsingMagicWord;
			}
			else
			{
				$disallowAttachmentsUsingMagicWord =  false;
			}
		}
		return $disallowAttachmentsUsingMagicWord;
	}
	
}

## ::END::
