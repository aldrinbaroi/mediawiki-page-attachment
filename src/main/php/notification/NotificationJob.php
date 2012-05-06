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

namespace PageAttachment\Notification;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class NotificationJob extends \Job
{
	function __construct($title, $params, $id = 0)
	{
		parent::__construct( 'pageAttachmentNotification', $title, $params, $id );
	}

	function run()
	{
		$runSucceeded = true;
		if (isset($this->params['watchedItem']) && isset($this->params['attachmentName']))
		{
			$watchedItem = unserialize($this->params['watchedItem']);
			$attachmentName = $this->params['attachmentName'];
			if ($watchedItem instanceof \PageAttachment\WatchedItem\WatchedItem)
			{
				try
				{
					$notificationManager = NotificationManagerFactory::getNotificationManager();
					$notificationManager->sendNotficationNow($watchedItem, $attachmentName);
				}
				catch(Exception $e)
				{
					$runSucceeded = false;
					$this->setLastError('Failed to send notification. Error: ' . $e->getMessage());
				}
			}
			else
			{
				$runSucceeded = false;
				$this->setLastError('Unable to deserialize watchedItem object.');
			}
		}
		else
		{
			$runSucceeded = false;
			$this->setLastError('The required parameters are not set: watchedItem & attachmentName.');
		}
		return $runSucceeded;
	}

}

## :: END ::
