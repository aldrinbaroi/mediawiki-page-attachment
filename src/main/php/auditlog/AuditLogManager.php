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

class AuditLogManager
{
	private $auditLogEnabled;

	function __construct()
	{
		global $wgPageAttachment_enableAuditLog;

		if (isset($wgPageAttachment_enableAuditLog) && $wgPageAttachment_enableAuditLog == true)
		{
			$this->auditLogEnabled = $wgPageAttachment_enableAuditLog;
		}
		else
		{
			$this->auditLogEnabled = false;
		}
	}

	function isAuditLogEnabled()
	{
		return $this->auditLogEnabled;
	}

	function createLog($attachedToPageId, $attachmentFileName, $activityType, $activityTime = null, $userId = null)
	{
		if ($this->auditLogEnabled == true)
		{
			$dbw = \wfGetDB( DB_MASTER );
			$auditLogData = new AuditLogData($attachedToPageId, $attachmentFileName, $activityType, $activityTime);
			$data = array(
						'attached_to_page_id'  => $auditLogData->getAttachedToPageId(),
						'attachment_file_name' => $dbw->strencode($auditLogData->getAttachmentFileName()),
						'user_id'              => $auditLogData->getUserId(),
						'activity_time'        => $dbw->timestamp($auditLogData->getActivityTime()),
						'activity_type'        => $dbw->strencode($auditLogData->getActivityType())
			);
			$dbw->insert('page_attachment_audit_log',  $data);
		}
	}

}

## :: END ::
