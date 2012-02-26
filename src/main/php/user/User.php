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

class User
{
	private $id;
	private $name;
	private $realName;
	private $isValidUser;
	private $userPageLink;
	private $emailAddress;
	private $emailAddressValid;

	function __construct($id, $name, $realName, $isValidUser, $userPageLink, $emailAddress, $emailAddressValid)
	{
		$this->id = $id;
		$this->name = $name;
		$this->realName = $realName;
		$this->isValidUser = $isValidUser;
		$this->userPageLink = $userPageLink;
		$this->emailAddress = $emailAddress;
		$this->emailAddressValid = (is_bool($emailAddressValid) && $emailAddressValid == true) ? true : false;
	}

	function getId()
	{
		return $this->id;
	}

	function getName()
	{
		return $this->name;
	}

	function getRealName()
	{
		return $this->realName;
	}

	function isValidUser()
	{
		return $this->isValidUser;
	}

	function getUserPageLink()
	{
		return $this->userPageLink;
	}
	
	function getEmailAddress()
	{
		return $this->emailAddress;
	}
	
	function isEmailAddressValid()
	{
		return $this->emailAddressValid;
	}

}

## ::END::
