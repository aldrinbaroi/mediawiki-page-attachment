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

namespace PageAttachment\AuditLog;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class AuditLogViewer extends \SpecialPage
{

	function __construct()
	{
		parent::__construct('PageAttachmentAuditLogViewer','',false);
	}

	function execute($par)
	{
		global $wgOut;
		global $wgRequest;

		$cacheManager = new \PageAttachment\Cache\CacheManager();
		$pageFactory = new \PageAttachment\Session\PageFactory($cacheManager);
		$security = new \PageAttachment\Security\SecurityManager();
		$session = new \PageAttachment\Session\Session($security, $pageFactory);
		$attachToPage = $session->getAttachToPage();
		if (isset($attachToPage) && $attachToPage->getId() > 0)
		{
			$protectedPage = $attachToPage->isProtected();
			if (!$security->isAuditLogViewAllowed($protectedPage))
			{
				if ($security->isAuditLogViewRequireLogin($protectedPage) && !$security->isLoggedIn())
				{
					$session->setStatusMessage('YouMustBeLoggedInToViewAuditLog');
				}
				else
				{
					$session->setStatusMessage('AuditLogViewingIsNotPermitted');
				}
				$wgOut->redirect($attachToPage->getFullURL());
			}
			else
			{
				$attachmentName = $wgRequest->getVal("attachmentName", null);
				if (isset($attachmentName))
				{
					$attachmentName = base64_decode($attachmentName);
				}
				$this->setHeaders();
				$pager = new AuditLogPager($attachToPage, $attachmentName);
				$limit = $pager->getForm();
				$body = $pager->getBody();
				$nav = $pager->getNavigationBar();
				$wgOut->addHTML("$limit<br />\n$nav<br />\n$body<br />\n$nav");
			}
		}
		else
		{
			$session->setStatusMessage('UnableToDetermineAttachToPage');
			$title = \Title::newFromText(\wfMsgForContent('mainpage'));
			$wgOut->redirect($title->getFullURL());
		}
	}

}

## :: END ::
