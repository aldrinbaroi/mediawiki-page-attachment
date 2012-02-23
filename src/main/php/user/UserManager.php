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
	private $name;
	private $isValidUser;

	function __construct()
	{
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
	}

	function getUser($userId)
	{
		$user = $this->cacheManager->retrieveUser($userId);
		if (!isset($user))
		{	
			$name = '';
			$realName = '';
			$isValid = false;
			$userPageLink = '';
			$dbr = \wfGetDB( DB_SLAVE );
			$rs = $dbr->select('user', array('user_name', 'user_real_name', 'user_email', 'user_email_authenticated'), array( 'user_id' => $userId));
			if ($rs == false)
			{
				$name = 'Invalid User';
				$realName = $name;
				$isValidUser = false;
				$userPageLink = '';
			}
			else
			{
				foreach($rs as $row)
				{
					$name = $row->user_name;
					$realName = $row->user_real_name;
					$emailAddress = $row->user_email;
					$emailAddressValid = ($row->user_email_authenticated != null) ? true : false;
					$isValid = true;
					if (!isset($realName) || (isset($realName) && strlen(trim($realName)) == 0))
					{
						$realName = $name;
					}
				}
				$rtc = new \PageAttachment\Configuration\RuntimeConfiguration();
				if (!$rtc->isShowUserRealName())
				{
					$realName = $name;
				}
				$userPageLink = \PageAttachment\UI\Command::getViewUserPageCommandLink($name, $realName);
			}
			$user = new User($userId, $name, $realName, $isValid, $userPageLink, $emailAddress, $emailAddressValid);
			$this->cacheManager->storeUser($user);
		}
		return $user;
	}

}

## ::END::
