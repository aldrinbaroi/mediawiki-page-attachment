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

namespace PageAttachment\Upload;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class Upload extends \SpecialUpload
{

	function __construct()
	{
		parent::__construct();
		$this->mName = 'PageAttachmentUpload';
		$this->mRestriction = '';
		$this->setListed(false);
	}

	public function execute( $par )
	{
		global $wgOut;

		$cacheManager = new \PageAttachment\Cache\CacheManager();
		$pageFactory = new \PageAttachment\Session\PageFactory($cacheManager);
		$security = new \PageAttachment\Security\SecurityManager();
		$session = new \PageAttachment\Session\Session($security, $pageFactory);
		$attachToPage = $session->getAttachToPage();
		if (isset($attachToPage) && $attachToPage->getId() > 0)
		{
			$abort = false;
			if ($security->isDownloadRequestValid())
			{
				// Subsequest Passes - Upload File 
				parent::execute($par);
			}
			else
			{
				// First Pass - Display Form
				$protectedPage = $attachToPage->isProtected();
				if ($security->isAttachmentAddUpdateRequireLogin($protectedPage) && !$security->isLoggedIn())
				{
					$session->setStatusMessage('YouMustBeLoggedInToAddUpdateAttachments');
					$abort = true;
				}
				elseif (!$security->isRequestValidationTokenValid())
				{
					$session->setStatusMessage('UnableToAuthenticateYourRequest');
					$abort = true;
				}

				if ($abort == true)
				{
					$title = \Title::newFromText($attachToPage->getPageTitle());
					$wgOut->redirect($title->getFullURL());
				}
				else
				{
					$security->setDownloadRequestValid(true);
					$session->setUploadAndAttachFileInitiated(true);
					parent::execute($par);
				}
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
