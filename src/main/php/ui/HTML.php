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

namespace PageAttachment\UI;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class HTML
{

	private function __construct() {
	}

	private static function getMsg($msgArgs)
	{
		if (is_array($msgArgs))
		{
			$msgId = array_shift($msgArgs);
			$msg = \wfMsg($msgId, $msgArgs);
		}
		else
		{
			$msg = \wfMsg($msgArgs);
		}
		return $msg;
	}

	static function jsOpenURL($url)
	{
		return 'javascript:pageAttachment_openURL("' . $url . '")';
	}

	static function buildCommandLink($titleMsgArgs, $commandURL, $linkLabel)
	{
		return \HTML::rawElement('a', array('title' => self::getMsg($titleMsgArgs), 'href' => $commandURL), $linkLabel);
	}

	static function buildImageLink($titleMsgArgs, $imgURL)
	{
		if (isset($titleMsgArgs))
		{
			return \HTML::rawElement('img', array('title' => self::getMsg($titleMsgArgs), 'src' => $imgURL));
		}
		else
		{
			return \HTML::rawElement('img', array('src' => $imgURL));
		}
	}

	static function buildImageCommandLink($titleMsgArgs, $commandURL, $imageURL)
	{
		return self::buildCommandLink($titleMsgArgs, $commandURL, self::buildImageLink($titleMsgArgs, $imageURL));
	}

	static function buildCommandLinkJS($titleMsgArgs, $commandURL, $linkLabel)
	{
		return self::buildCommandLink($titleMsgArgs, self::jsOpenURL($commandURL), $linkLabel);
	}

	static function buildImageCommandLinkJS($titleMsgArgs, $commandURL, $imageURL)
	{
		return self::buildImageCommandLink($titleMsgArgs, self::jsOpenURL($commandURL), $imageURL);
	}

	static function buildRemoveAttachmentCommandLink($titleMsgArgs, $attachmentName, $imageURL, $rvt)
	{
		$confirmMessage = \wfMsg('PleaseConfirmRemoveAttachment', $attachmentName);
		$jsRemoveAttachment = 'javascript:pageAttachment_removePageAttachment("' . $attachmentName   . '", "' .  $rvt . '", "' . $confirmMessage . '");';
		return self::buildImageCommandLink($titleMsgArgs, $jsRemoveAttachment, $imageURL);
	}

	static function buildLabel($title, $labelText)
	{
		return \HTML::rawElement('span', array('title' => \wfMsg($title)), $labelText);
	}

}

## ::END::
