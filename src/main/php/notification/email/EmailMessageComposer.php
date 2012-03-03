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

namespace PageAttachment\Notification\Email;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class EmailMessageComposer implements \PageAttachment\Notification\MessageComposer
{
	private $userManager;
	private $dateUtil;

	function __construct()
	{
		$this->userManager = new \PageAttachment\User\UserManager();
		$this->dateUtil = new \PageAttachment\Utility\DateUtil();
	}

	function composeSubject(\PageAttachment\WatchedItem\WatchedItem $wathchedItem, \PageAttachment\Localization\LocalizationHelper $localizationHelper)
	{
		return \wfMsg('AttachmentChangeNotification');
	}

	function composeMessage(\PageAttachment\WatchedItem\WatchedItem $watchedItem, $attachmentName, \PageAttachment\Localization\LocalizationHelper $localizationHelper)
	{
		global $wgPageAttachment_messageFormat;
		global $wgPageAttachment_messageTemplates;

		if (isset($wgPageAttachment_messageFormat))
		{
			if (isset($wgPageAttachment_messageTemplates[$wgPageAttachment_messageFormat]))
			{
				$keyValuePairs = $this->getKeyValuePairs($watchedItem, $attachmentName, $localizationHelper);
				$message = strtr($wgPageAttachment_messageTemplates[$wgPageAttachment_messageFormat], $keyValuePairs);
				return $message;
			}
			else
			{
				throw new \MWException('Template $wgPageAttachment_messageTemplates[' . $wgPageAttachment_messageFormats . '] is not set/loaded!');
			}
		}
		else
		{
			throw new \MWException('$wgPageAttachment_messageFormat is not set!');
		}
	}

	protected function getKeyValuePairs($watchedItem, $attachmentName, \PageAttachment\Localization\LocalizationHelper $localizationHelper)
	{
		global $wgSitename;
		global $wgContLang;
		
		$user = $this->userManager->getUser($watchedItem->getModifiedByUserId());
		$header = $localizationHelper->getMessage('AttachmentChangeNotification');
		$attachedToPageNameLabel = str_pad($localizationHelper->getMessage('attached_to_page_id'), 30);
		$attachmentNameLabel = str_pad($localizationHelper->getMessage('attachment_file_name'), 30);
		$activityTypeLabel = str_pad($localizationHelper->getMessage('activity_type'), 30);
		$activityTimeLabel = str_pad($localizationHelper->getMessage('activity_time'), 30);
		$modifiedByUserLabel = str_pad($localizationHelper->getMessage('user_id'), 30);
		$attachedToPageName = $watchedItem->getPageTitle();
		//$attachmentName = $attachmentName;
		$activityType =  $localizationHelper->getMessage($watchedItem->getModificationType());
		$activityTime = $localizationHelper->formatDate($watchedItem->getModificationTime()) . ' ' . $localizationHelper->getTimeZoneName();		
		$modifiedByUser = $user->getRealName();
		$keyValuePairs = array();
		$keyValuePairs['HEADER'] = $header;
		$keyValuePairs['SITENAME'] = $wgSitename;
		$keyValuePairs['ATTACHED_TO_PAGE_NAME_LABEL'] = $attachedToPageNameLabel;
		$keyValuePairs['ATTACHMENT_NAME_LABEL'] = $attachmentNameLabel;
		$keyValuePairs['ACTIVITY_TYPE_LABEL'] = $activityTypeLabel;
		$keyValuePairs['ACTIVITY_TIME_LABEL'] = $activityTimeLabel;
		$keyValuePairs['MODIFIED_BY_USER_LABEL'] = $modifiedByUserLabel;
		$keyValuePairs['ATTACHED_TO_PAGE_NAME'] = $attachedToPageName;
		$keyValuePairs['ATTACHMENT_NAME'] = $attachmentName;
		$keyValuePairs['ACTIVITY_TYPE'] = $activityType;
		$keyValuePairs['ACTIVITY_TIME'] = $activityTime;
		$keyValuePairs['MODIFIED_BY_USER'] = $modifiedByUser;
		return $keyValuePairs;
	}

}

## :: END ::