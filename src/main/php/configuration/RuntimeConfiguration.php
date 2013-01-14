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

namespace PageAttachment\Configuration;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class RuntimeConfiguration
{
	private $showUserRealName;
	private $skin;
	private $skinName;
	private $rightToLeftLang;
	private $scriptPath;
	private $userLanguageCode;
	private $rtlLang;


	function __construct()
	{
	}

	function isShowUserRealName()
	{
		if (!isset($this->showUserRealName))
		{
			global $wgPageAttachment_showUserRealName;

			if (isset($wgPageAttachment_showUserRealName) && is_bool($wgPageAttachment_showUserRealName))
			{
				$this->showUserRealName = $wgPageAttachment_showUserRealName;
			}
			else
			{
				$this->showUserRealName = false;
			}
		}
		return $this->showUserRealName;
	}
	
	function getSkin()
	{
		if (!isset($this->skin))
		{
			$this->skin =  \RequestContext::getMain()->getSkin();
			$this->skinName = $this->skin->getSkinName();
		}
		return $this->skin;
	}

	function getSkinName()
	{
		if (!isset($this->skinName))
		{
			$this->skinName = $this->getSkin()->getSkinName();
		}
		return $this->skinName;
	}

	function isRightToLeftLanguage()
	{
		if (!isset($this->rightToLeftLang))
		{
			global $wgLang;

			$this->rightToLeftLang = $wgLang->isRTL();
		}
		return $this->rightToLeftLang;
	}

	function getScriptPath()
	{
		if (!isset($this->scriptPath))
		{
			global $wgScriptPath;

			$this->scriptPath = $wgScriptPath;
		}
		return $this->scriptPath;
	}

	function getUserLanguageCode()
	{
		if (!isset($this->userLanguageCode))
		{
			global $wgLang;
			
			$this->userLanguageCode = $wgLang->getCode();
		}
		return $this->userLanguageCode;
	}

}

## ::END::
