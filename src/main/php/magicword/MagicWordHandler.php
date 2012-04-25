<?php
/**
 *
 * Copyright (C) 2012 Aldrin Edison Baroi
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

namespace PageAttachment\MagicWord;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class MagicWordHandler
{
	function __construct()
	{

	}

	function onMagicWordwgVariableIDs( &$magicWordVariableIDs )
	{
		$magicWordVariableIDs[] = 'PageAttachment_MagicWord_NOATTACHMENT';
		$magicWordVariableIDs[] = 'PageAttachment_MagicWord_ALLOWATTACHMENT';
		return true;
	}

	function onLanguageGetMagic(&$magicWords, $langCode)
	{
		$magicWords['PageAttachment_MagicWord_NOATTACHMENT']    = array( 0, '__NO_ATTACHMENTS__' );
		$magicWords['PageAttachment_MagicWord_ALLOWATTACHMENT'] = array( 0, '__ALLOW_ATTACHMENTS__' );
		return true;
	}

	function onParserBeforeInternalParse(&$parser, &$text, &$stripState)
	{
		$pageAttachmentDefinedMagicWord = false;
		$allowAttachments = false;
		$mw1 = \MagicWord::get( 'PageAttachment_MagicWord_NOATTACHMENT' );
		if ( $mw1->matchAndRemove( $text ) )
		{
			$pageAttachmentDefinedMagicWord = true;
			$allowAttachments = false;
		}
		$mw2 = \MagicWord::get( 'PageAttachment_MagicWord_ALLOWATTACHMENT' );
		if ( $mw2->matchAndRemove( $text ) )
		{
			$pageAttachmentDefinedMagicWord = true;
			$allowAttachments = true;
		}
		if ($pageAttachmentDefinedMagicWord == true)
		{
			if ($allowAttachments == true)
			{
				$parser->mOutput->addHeadItem('<script> pageAttachment__ALLOW_ATTACHMENTS__ = true; </script>');
			}
			else
			{
				$parser->mOutput->addHeadItem('<script> pageAttachment__ALLOW_ATTACHMENTS__ = false; </script>');
			}
		}
		return true;
	}

}

## :: END ::
