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


namespace PageAttachment\Utility;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class DateUtil
{
	private $runtimeConfig;
	private $mediaWikiTimezone;
	private $userTimezone;
	private $userTimeZoneInUserLang;

	function __construct()
	{
		$this->runtimeConfig = new \PageAttachment\Configuration\RuntimeConfiguration();
		$this->mediaWikiTimezone = new \DateTimeZone('UTC');
	}

	function getMediaWikiTimeZone()
	{
		return $this->mediaWikiTimezone;
	}

	function formatDate($timestamp)
	{
		$tzMW = $this->getMediaWikiTimeZone();
		$tzUser = $this->getUserTimeZone();
		$dateFormat = $this->getDateFormat();
		if (isset($dateFormat))
		{
			$datetime = \DateTime::createFromFormat('YmdHis', $timestamp, $tzMW);
			$datetime->setTimeZone($tzUser);
			$formattedDate = $datetime->format($dateFormat);
		}
		else
		{
			global $wgLang;

			$formattedDate = $wgLang->timeanddate($timestamp, true);
		}
		return $formattedDate;
	}

	/**
	 *
	 * Note: currently only supports timestamp format returned by MediaWiki in tablepager class
	 * @param $sqlDatetime
	 */
	function formatSQLDate($sqlDatetime)
	{
		// Format returned by MediaWiki [2011-07-21 08:37:01]
		$timestamp = str_replace(array('-',':', ' '), '', $sqlDatetime);
		return $this->formatDate($timestamp);
	}


	function getUserTimeZone()
	{
		if (!isset($this->userTimezone))
		{
			global $wgUser;
			global $wgLocalTimezone;

			$tzOffset = $wgUser->getOption( 'timecorrection' );
			$tzInfo = explode( '|', $tzOffset);
			$tzUser = '';
			if (count($tzInfo) == 3)
			{
				$tzUser = $tzInfo[2];
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
			$this->userTimezone = new \DateTimeZone($tzCode);
		}
		return $this->userTimezone;
	}

	// FIXME Hmmm... how do I get the timezone city name in user language?
	function getUserTimeZoneInUserLang()
	{
		if (!isset($this->userTimeZoneInUserLang))
		{
			global $wgLang;

			$tzCityUserLang = ' ';
			$tzRegionUserLang = ' ';
			$tzUser = $this->getUserTimeZone();
			$tzName = $tzUser->getName();
			if ($tzName == 'UTC')
			{
				$tzNameUserLang = \wfMsg('utc');
			}
			else
			{
				$tzInfo = explode('/', $tzName);
				$tzRegion = $tzInfo[0];
				$tzCity = $tzInfo[1];
				$tzRegionMsgCode = 'timezoneregion-' . strtolower($tzRegion);
				$tzRegionUserLang = str_replace('_', ' ', \wfMsg($tzRegionMsgCode));
				$tzCityUserLang = str_replace('_', ' ', $tzCity);
				if ($this->runtimeConfig->isRightToLeftLanguage())
				{
					$tzNameUserLang = $tzCityUserLang . '\\' . $tzRegionUserLang;
				}
				else
				{
					$tzNameUserLang = $tzRegionUserLang . '/' . $tzCityUserLang;
				}
			}
			$this->userTimeZoneInUserLang = $tzNameUserLang;
		}
		return $this->userTimeZoneInUserLang;
	}

	private function getDateFormat()
	{
		global $wgLang;
		global $wgPageAttachment_dateFormat;

		$userLangCode = $this->runtimeConfig->getUserLanguageCode();
		if (isset($wgPageAttachment_dateFormat[$userLangCode]))
		{
			return $wgPageAttachment_dateFormat[$userLangCode];
		}
		else
		{
			return null;
		}
	}

}

## :: END ::
