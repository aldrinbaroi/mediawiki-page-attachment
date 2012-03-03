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

namespace PageAttachment\User;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class UserManager
{
	private $cacheManager;
	private $rtc;
	private $name;
	private $isValidUser;

	function __construct()
	{
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
		$this->rtc = new \PageAttachment\Configuration\RuntimeConfiguration();
	}

	function getUser($userId)
	{
		$name = null;
		$realName = null;
		$userPageLink = null;
		$emailAddress = null;
		$emailAddressValid = false;
		$timeCorrection = null;
		$user = $this->cacheManager->retrieveUser($userId);
		if (!isset($user))
		{
			$mediaWikiUser = \User::newFromId($userId);
			$name = $mediaWikiUser->getName();
			if ($this->rtc->isShowUserRealName())
			{
				$realName = $mediaWikiUser->getRealName();
			}
			if (!isset($realName) || (isset($realName) && strlen(trim($realName)) == 0))
			{
				$realName = $name;
			}
			$emailAddress = $mediaWikiUser->getEmail();
			$emailAddressValid = ($mediaWikiUser->isEmailConfirmed()) ? true : false;
			$userPageLink = \PageAttachment\UI\Command::getViewUserPageCommandLink($name, $realName);
			$timeCorrection = $mediaWikiUser->getOption( 'timecorrection' );
			$languageCode = $mediaWikiUser->getOption('language');
			$dateFormat = $mediaWikiUser->getOption('date');
			$user = new User($userId, $name, $realName, $userPageLink, $emailAddress, $emailAddressValid, $timeCorrection, $languageCode, $dateFormat);
			$this->cacheManager->storeUser($user);
		}
		return $user;
	}

}

## ::END::
