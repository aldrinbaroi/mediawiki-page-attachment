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

namespace PageAttachment\AuditLog;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class AuditLogData
{
	private $attachedToPageId;
	private $attachmentFileName;
	private $userId;
	private $activityTime;
	private $activityType;
	private $activityDetail;

	function __construct($attachedToPageId, $attachmentFileName, $activityType,
	$activityTime = null, $userId = null)
	{
		global $wgUser;

		$this->attachedToPageId = $attachedToPageId;
		$this->attachmentFileName = $attachmentFileName;
		$this->userId = ($userId == null) ?  $wgUser->getId() : $userId;
		$this->activityTime = ($activityTime == null) ? time() : $activityTime;
		$this->activityType = $activityType;
	}

	function getAttachedToPageId()
	{
		return $this->attachedToPageId;
	}

	function getAttachmentFileName()
	{
		return $this->attachmentFileName;
	}

	function getUserId()
	{
		return $this->userId;
	}

	function getActivityTime()
	{
		return $this->activityTime;
	}

	function getActivityType()
	{
		return $this->activityType;
	}

}

## :: END ::
