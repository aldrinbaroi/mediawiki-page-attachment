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
	
	const MagicWord_NOATTACHMENTS    = 'PageAttachment_MagicWord_NOATTACHMENTS';
	const MagicWord_ALLOWATTACHMENTS = 'PageAttachment_MagicWord_ALLOWATTACHMENTS';

	/**
	 *
	 * Register the MagicWord IDs
	 *
	 * @param array $magicWordVariableIDs
	 */
	function onMagicWordwgVariableIDs( &$magicWordVariableIDs )
	{
		$magicWordVariableIDs[] = self::MagicWord_NOATTACHMENTS;
		$magicWordVariableIDs[] = self::MagicWord_ALLOWATTACHMENTS;
		return true;
	}

	/**
	 *
	 * Register the MagicWords
	 *
	 * @param array $magicWords
	 * @param string $langCode
	 */
	function onLanguageGetMagic(&$magicWords, $langCode)
	{
		global $wgPageAttachment_magicWordToDisallowAttachments;
		global $wgPageAttachment_magicWordToAllowAttachments;
		
		$magicWords[self::MagicWord_NOATTACHMENTS]    = array( 0, $wgPageAttachment_magicWordToDisallowAttachments );
		$magicWords[self::MagicWord_ALLOWATTACHMENTS] = array( 0, $wgPageAttachment_magicWordToAllowAttachments );
		return true;
	}

	/**
	 *
	 * Process the MagicWords
	 *
	 * @param Parser $parser
	 * @param string $text
	 * @param StripState $stripState
	 * @return boolean
	 */
	function onParserBeforeInternalParse(&$parser, &$text, &$stripState)
	{
		$config = \PageAttachment\Configuration\StaticConfiguration::getInstance();

		$pageAttachmentDefinedMagicWord = false;
		$allowAttachments = false;
		$magicWord1 = \MagicWord::get( self::MagicWord_NOATTACHMENTS );
		if ( $magicWord1->matchAndRemove( $text ) )
		{
			$pageAttachmentDefinedMagicWord = true;
			$allowAttachments = false;
		}
		$magicWord2 = \MagicWord::get( self::MagicWord_ALLOWATTACHMENTS );
		if ( $magicWord2->matchAndRemove( $text ) )
		{
			$pageAttachmentDefinedMagicWord = true;
			$allowAttachments = true;
		}
		if ($pageAttachmentDefinedMagicWord == true)
		{
			if ($allowAttachments == true)
			{
				if ($config->isAllowAttachmentsUsingMagicWord())
				{
					$parser->mOutput->addHeadItem('<script> pageAttachment__ALLOW_ATTACHMENTS__ = true; </script>');
				}
			}
			else
			{
				if ($config->isDisallowAttachmentsUsingMagicWord())
				{
					$parser->mOutput->addHeadItem('<script> pageAttachment__ALLOW_ATTACHMENTS__ = false; </script>');
				}
			}
		}
		return true;
	}

}

## :: END ::
