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

namespace PageAttachment\Attachment;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class AttachmentData
{
	var $id;
	var $title;
	var $size;
	var $description;
	var $dateUploaded;
	var $uploadedBy;
	var $attachedToMoreThanOnePage;
	var $attachedToPages;

	function __construct($id, $title, $size, $description, $dateUploaded, $uploadedBy, $attachedToPages)
	{
		$this->id = $id;
		$this->title = $title;
		$this->size = $size;
		$this->description = $description;
		$this->dateUploaded = $dateUploaded;
		$this->uploadedBy = $uploadedBy;
		$this->attachedToMoreThanOnePage = ($attachedToPages == NULL) ? false : (count($attachedToPages) > 1) ? true : false;
		$this->attachedToPages = $attachedToPages;
	}

	function getId()
	{
		return $this->id;
	}

	function getTitle()
	{
		return $this->title;
	}

	function getSize()
	{
		return $this->size;
	}
	
	function getDescription()
	{
		return $this->description;
	}

	function getDateUploaded()
	{
		return $this->dateUploaded;
	}

	function getUploadedBy()
	{
		return $this->uploadedBy;
	}

	function isAttachedToMoreThanOnePage()
	{
		return $this->attachedToMoreThanOnePage;
	}

	function getAttachedToPages()
	{
		return $this->attachedToPages;
	}

}

## :: END ::
