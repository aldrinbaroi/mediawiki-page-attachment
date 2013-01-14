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

namespace PageAttachment\BrowseSearch;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class ImageListPager extends \ImageListPager
{
	private $security;
	private $session;
	private $currentFileName;
	private $msgAttachFile;
	private $urlImgAttachFile;
	private $urlAttachFilePrefix;
	private $rvt;
	private $resource;
	private $command;
	
	const ADD_ATTACHMENT = 'AddAttachment';

	function __construct(\IContextSource $context, $userName = null, $search = '', $including = false)
	{
		global $wgScriptPath;
		global $wgPageAttachment_imgAddUpdateAttachment;

		parent::__construct($context, $userName, $search, $including);
		$cacheManager = new \PageAttachment\Cache\CacheManager();
		$pageFactory = new \PageAttachment\Session\PageFactory($cacheManager);
		$this->security = new \PageAttachment\Security\SecurityManager();
		$this->session = new \PageAttachment\Session\Session($this->security, $pageFactory);
		$this->resource = new \PageAttachment\UI\Resource($this->security, $this->session);
		$this->rvt = $this->security->getCurrentRequestValidationToken();
		$this->command = new \PageAttachment\UI\Command($this->session, $this->resource, $this->rvt);
	}

	function getFieldNames()
	{
		$fieldNames = parent::getFieldNames();
		$fieldNames[self::ADD_ATTACHMENT] = 'Add Attachment';
		return $fieldNames;
	}

	function formatRow($row)
	{
		$this->currentFileName = $row->img_name;
		return parent::formatRow($row);
	}

	function formatValue($field, $value)
	{
		switch($field)
		{
			case self::ADD_ATTACHMENT:
				$attachFileCommandLink = $this->command->getAttachFileCommandLink($this->currentFileName);
				return $attachFileCommandLink; 
				break;

			default:
				return parent::formatValue($field, $value);
		}
	}

	function getQueryInfo()
	{
		$queryInfo = parent::getQueryInfo();
		$ndx = array_search(self::ADD_ATTACHMENT, $queryInfo['fields']);
		if ($ndx != false)
		{
			unset($queryInfo['fields'][$ndx]);
		}
		return $queryInfo;
	}
	
	function getTitle()
	{
		return \SpecialPage::getTitleFor( 'PageAttachmentListFiles' );
	}
	
}

## :: END ::
