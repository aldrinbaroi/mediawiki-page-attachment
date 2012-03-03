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

class NotificationManagerFactory
{
	private static $NOTIFICATION_MANAGER;

	private function __construct()
	{
	}

	public static function getNotificationManager()
	{
		if (!(isset(self::$NOTIFICATION_MANAGER) && (self::$NOTIFICATION_MANAGER instanceof NotificationManager)))
		{
			global $wgEnableEmail;
			global $wgPageAttachment_enableNotification;
			global $wgPageAttachment_notificationMediums;
			global $wgPageAttachment_messageComposers;
			global $wgPageAttachment_messageTransporters;
			global $wgPageAttachment_useJobQueueForNotification;

			$notificationEnabled = false;
			$useJobQueueForNotification = false;
			$notificationMediums = array();
			$messageComposers = array();
			$messageTransporters = array();
			if ((isset($wgEnableEmail) && is_bool($wgEnableEmail)) &&
			(isset($wgPageAttachment_enableNotification) && is_bool($wgPageAttachment_enableNotification)))
			{
				$notificationEnabled = ($wgEnableEmail && $wgPageAttachment_enableNotification) ? true : false;
			}
			if ($notificationEnabled)
			{
				if (isset($wgPageAttachment_useJobQueueForNotification) && is_bool($wgPageAttachment_useJobQueueForNotification))
				{
					$useJobQueueForNotification = $wgPageAttachment_useJobQueueForNotification;
				}
				foreach ($wgPageAttachment_notificationMediums as $notificationMedium)
				{
					if (isset($wgPageAttachment_messageComposers[$notificationMedium]) && isset($wgPageAttachment_messageTransporters[$notificationMedium]))
					{
						$notificationMediums[] = $notificationMedium;
						$messageComposers[$notificationMedium] = new $wgPageAttachment_messageComposers[$notificationMedium];
						$messageTransporters[$notificationMedium] = new $wgPageAttachment_messageTransporters[$notificationMedium];
					}
				}
				unset($notificationMedium);
			}
			$userManager = new \PageAttachment\User\UserManager();
			self::$NOTIFICATION_MANAGER = new NotificationManager($notificationEnabled, $useJobQueueForNotification, $notificationMediums, $messageComposers, $messageTransporters, $userManager);
		}
		return self::$NOTIFICATION_MANAGER;
	}
}

## ::END::
