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

class NotificationManager
{
	private $notificationEnabled;
	private $useJobQueueForNotification;
	private $notificationTypes;
	private $messageComposers;
	private $messageTransporters;
	private $userManager;

	function __construct($notificationEnabled, $useJobQueueForNotification, $notificationTypes, $messageComposers, $messageTransporters, $userManager)
	{
		$this->notificationEnabled = $notificationEnabled;
		$this->useJobQueueForNotification = $useJobQueueForNotification;
		$this->notificationTypes = $notificationTypes;
		$this->messageComposers = $messageComposers;
		$this->messageTransporters = $messageTransporters;
		$this->userManager = $userManager;
	}

	function isNotificationEnabled()
	{
		return $this->notificationEnabled;
	}

	function sendNotification($pageId, $attachmentName, $modificationType, $modificationTime)
	{
		if ($this->notificationEnabled)
		{
			global $wgUser;

			$modifiedByUserId = $wgUser->getId();
			$watchedItem =  \PageAttachment\WatchedItem\WatchedItemFactory::createWatchedItem($pageId, $modifiedByUserId, $modificationType, $modificationTime) ;
			if ($watchedItem->isWatched())
			{
				if ($this->useJobQueueForNotification)
				{
					$this->queueNotificationJob($watchedItem, $attachmentName);
				}
				else
				{
					$this->sendNotficationNow($watchedItem, $attachmentName);
				}
			}
		}
	}

	function sendNotficationNow($watchedItem, $attachmentName)
	{
		if ($this->notificationEnabled)
		{
			foreach($this->notificationTypes as $nt)
			{
				$messageComposer = $this->messageComposers[$nt];
				$watchers = $watchedItem->getWatchers();
				foreach($watchers as $watcher)
				{
					$user = $this->userManager->getUser($watcher);
					$localizationHelper = new \PageAttachment\Localization\LocalizationHelper($user);
					$subject = $messageComposer->composeSubject($watchedItem, $localizationHelper);
					$message = $messageComposer->composeMessage($watchedItem, $attachmentName, $localizationHelper);
					$this->messageTransporters[$nt]->sendMessage($user, $subject, $message);
				}
			}
			unset($nt);
		}
	}

	function queueNotificationJob($watchedItem, $attachmentName)
	{
		if ($this->notificationEnabled)
		{
			$title = \Title::newFromID($watchedItem->getPageId());
			$params = array();
			$params['watchedItem'] = serialize($watchedItem);
			$params['attachmentName'] = $attachmentName;
			$notificationJob = new NotificationJob($title, $params);
			$notificationJob->insert();
		}
	}

}

## :: END ::
