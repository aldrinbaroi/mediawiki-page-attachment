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


namespace PageAttachment\Localization;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class LocalizationHelper
{
	private $user;
	private $languageCode;
	private $mwLanguageObj;
	private $timezone;
	private $timezoneName;

	function __construct(\PageAttachment\User\User $user)
	{
		$this->user = $user;
		$this->languageCode = $this->user->getLanguageCode();
		$this->mwLanguageObj = \Language::factory($this->languageCode);
	}

	/**
	 *
	 *
	 * @param timestamp $timestamp
	 */
	function formatDate($timestamp)
	{
		$ts = \wfTimestamp( TS_MW, $timestamp );
		$timecorrection = $this->user->getTimeCorrection();
		if (isset($timecorrection))
		{
			$ts = $this->mwLanguageObj->userAdjust( $ts, $timecorrection );
		}
		$dateFormat = $this->user->getDateFormat();
		$datePreferenceMigrationMap = $this->mwLanguageObj->getDatePreferenceMigrationMap();
		if ( isset( $datePreferenceMigrationMap[$dateFormat] ) )
		{
			$dateFormat = $datePreferenceMigrationMap[$dateFormat];
		}
		$df = $this->getDateFormat();
		if (!isset($df))
		{
			$df = $this->mwLanguageObj->getDateFormatString( 'both', $this->mwLanguageObj->dateFormat( $dateFormat ) );
		}
		$formattedDate =  $this->mwLanguageObj->sprintfDate( $df, $ts );
		return $formattedDate;
	}

	private function getDateFormat()
	{
		global $wgPageAttachment_dateFormat;

		if (isset($wgPageAttachment_dateFormat[$this->languageCode]))
		{
			return $wgPageAttachment_dateFormat[$this->languageCode];
		}
		else
		{
			return null;
		}
	}

	function getMessage($messageCode)
	{
		$messageParameters = func_get_args();
		array_shift($messageParameters);
		$message = \wfMsgGetKey($messageCode, true, $this->languageCode);
		$message = \wfMsgReplaceArgs( $message, $messageParameters );
		return $message;
	}

	function getTimeZone()
	{
		if (!isset($this->timezone))
		{
			global $wgLocalTimezone;

			$tzUser = '';
			$tzOffset = $this->user->getTimeCorrection();
			if (isset($tzOffset))
			{
				$tzInfo = explode( '|', $tzOffset);
					
				if (count($tzInfo) == 3)
				{
					$tzUser = $tzInfo[2];
				}
			}
			if ($tzUser == '')
			{
				if ($wgLocalTimezone == null)
				{
					$tzCode = 'UTC';
				}
				else
				{
					$tzCode = $wgLocalTimezone;
				}
			}
			else
			{
				$tzCode = $tzUser;
			}
			$this->timezone = new \DateTimeZone($tzCode);
		}
		return $this->timezone;
	}

	// FIXME Hmmm... how do I get the timezone city name in user language?
	function getTimeZoneName()
	{
		if (!isset($this->timezoneName))
		{
			$tzUser = $this->getTimeZone();
			$tzName = $tzUser->getName();
			if ($tzName == 'UTC')
			{
				$this->timezoneName = \wfMsgGetKey('utc', true, $this->languageCode );
			}
			else
			{
				$tzCityName = ' ';
				$tzRegionName = ' ';
				$tzInfo = explode('/', $tzName);
				$tzRegion = $tzInfo[0];
				$tzCity = $tzInfo[1];
				$tzRegionMsgCode = 'timezoneregion-' . strtolower($tzRegion);
				$tzRegionName = str_replace('_', ' ', \wfMsgGetKey($tzRegionMsgCode, true, $this->languageCode));
				$tzCityName = str_replace('_', ' ', $tzCity);
				if ($this->mwLanguageObj->isRTL())
				{
					$this->timezoneName = $tzCityName . '\\' . $tzRegionName;
				}
				else
				{
					$this->timezoneName = $tzRegionName . '/' . $tzCityName;
				}
			}
		}
		return $this->timezoneName;
	}

}

## :: END ::
