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

namespace PageAttachment\UI;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class Resource
{
	private $security;
	private $session;
	private $runtimeConfig;
	private $addResources;
	private $staticConfig;

	function __construct($security, $session)
	{
		$this->security = $security;
		$this->session = $session;
		$this->runtimeConfig = new \PageAttachment\Configuration\RuntimeConfiguration();
		$this->staticConfig = \PageAttachment\Configuration\StaticConfiguration::getInstance();
	}

	private function isAddResources()
	{
		if (!isset($this->addResources))
		{
			$page = $this->session->getCurrentPage();
			$isViewPageSpecial = $this->session->isViewPageSpecial();
			if ($this->security->isAttachmentAllowed($page) || ( $isViewPageSpecial == true))
			{
				$this->addResources =  true;
			}
			else
			{
				if ($this->staticConfig->isAllowAttachmentsUsingMagicWord())
				{
					$this->addResources =  true; 
				}
				else
				{
					$this->addResources =  false;
				}
			}
		}
		return $this->addResources;
	}

	function addCSSFiles(&$out, &$sk)
	{
		if ($this->isAddResources())
		{
			$urlCSSFiles[] = $this->getCssFileCommonURL();
			if ($this->runtimeConfig->isRightToLeftLanguage() == true)
			{
				$urlCSSFiles[] = $this->getCssFileRTL_URL();
			}
			else
			{
				$urlCSSFiles[] = $this->getCssFileLTR_URL();
			}
			foreach ($urlCSSFiles as $urlCSSFile)
			{
				$out->addExtensionStyle($urlCSSFile);
			}
		}
		return true;
	}

	function addJSFiles(&$out, &$sk)
	{
		if ($this->isAddResources())
		{
			$urlJSFiles[] = $this->getUrlPrefixForJS() . 'page-attachment.js';
			foreach ($urlJSFiles as $urlJSFile)
			{
				$out->addScriptFile($urlJSFile);
			}
		}
		return true;
	}

	function getUrlPrefixForJS()
	{
		return $this->runtimeConfig->getScriptPath() . '/extensions/PageAttachment/skins/js/';
	}

	function getUrlPrefixForCSS($skinName = 'default')
	{
		return $this->runtimeConfig->getScriptPath() . '/extensions/PageAttachment/skins/' . $skinName . '/css/';
	}

	function getUrlPrefixForImage($skinName = 'default')
	{
		return $this->runtimeConfig->getScriptPath() . '/extensions/PageAttachment/skins/' . $skinName . '/images/';
	}

	##
	## CSS file URLs
	##

	function getCssFileCommonURL()
	{
		global $wgPageAttachment_cssFileCommon;

		$skinName = $this->runtimeConfig->getSkinName();
		if(isset($wgPageAttachment_cssFileCommon[$skinName]))
		{
			return $this->getUrlPrefixForCSS($skinName) . $wgPageAttachment_cssFileCommon[$skinName];
		}
		else
		{
			return $this->getUrlPrefixForCSS() . $wgPageAttachment_cssFileCommon['default'];
		}
	}

	function getCssFileLTR_URL()
	{
		global $wgPageAttachment_cssFileLTR;

		$skinName = $this->runtimeConfig->getSkinName();
		if(isset($wgPageAttachment_cssFileLTR[$skinName]))
		{
			return $this->getUrlPrefixForCSS($skinName) . $wgPageAttachment_cssFileLTR[$skinName];
		}
		else
		{
			return $this->getUrlPrefixForCSS() . $wgPageAttachment_cssFileLTR['default'];
		}
	}

	function getCssFileRTL_URL()
	{
		global $wgPageAttachment_cssFileRTL;

		$skinName = $this->runtimeConfig->getSkinName();
		if(isset($wgPageAttachment_cssFileRTL[$skinName]))
		{
			return $this->getUrlPrefixForCSS($skinName) . $wgPageAttachment_cssFileRTL[$skinName];
		}
		else
		{
			return $this->getUrlPrefixForCSS() . $wgPageAttachment_cssFileRTL['default'];
		}
	}

	##
	## Image URLs
	##

	function getSpacerImageURL()
	{
		global $wgPageAttachment_imgSpacer;

		$skinName = $this->runtimeConfig->getSkinName();
		if (isset($wgPageAttachment_imgSpacer[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgSpacer[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgSpacer['default'] ;
		}
	}

	function getBrowseSearchAttachImageURL()
	{
		$skinName = $this->runtimeConfig->getSkinName();
		global $wgPageAttachment_imgBrowseSearchAttach;

		if (isset($wgPageAttachment_imgBrowseSearchAttach[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgBrowseSearchAttach[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgBrowseSearchAttach['default'] ;
		}
	}

	function getUploadAndAttachtImageURL()
	{
		global $wgPageAttachment_imgUploadAndAttach;

		$skinName = $this->runtimeConfig->getSkinName();
		if (isset($wgPageAttachment_imgUploadAndAttach[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgUploadAndAttach[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgUploadAndAttach['default'] ;
		}
	}

	function getAttachFileImageURL()
	{
		global $wgPageAttachment_imgAttachFile;

		$skinName = $this->runtimeConfig->getSkinName();
		if (isset($wgPageAttachment_imgAttachFile[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgAttachFile[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgAttachFile['default'] ;
		}
	}

	function getViewAuditLogImageURL()
	{
		global $wgPageAttachment_imgViewAuditLog;

		$skinName = $this->runtimeConfig->getSkinName();
		if (isset($wgPageAttachment_imgViewAuditLog[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgViewAuditLog[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgViewAuditLog['default'] ;
		}
	}

	function getViewHistoryImageURL()
	{
		global $wgPageAttachment_imgViewHistory;

		$skinName = $this->runtimeConfig->getSkinName();
		if (isset($wgPageAttachment_imgViewHistory[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgViewHistory[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgViewHistory['default'] ;
		}
	}


	function getRemoveAttachmentImageURL()
	{
		$skinName = $this->runtimeConfig->getSkinName();
		global $wgPageAttachment_imgRemoveAttachment;

		if (isset($wgPageAttachment_imgRemoveAttachment[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgRemoveAttachment[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgRemoveAttachment['default'] ;
		}
	}

	function getViewMoreImageURL()
	{
		$skinName = $this->runtimeConfig->getSkinName();
		global $wgPageAttachment_imgViewMore;

		if (isset($wgPageAttachment_imgViewMore[$skinName]))
		{
			return $this->getUrlPrefixForImage($skinName) . $wgPageAttachment_imgViewMore[$skinName] ;
		}
		else
		{
			return $this->getUrlPrefixForImage() . $wgPageAttachment_imgViewMore['default'] ;
		}
	}

}

## ::END::
