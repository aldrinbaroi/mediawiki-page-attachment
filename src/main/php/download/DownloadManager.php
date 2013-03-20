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

namespace PageAttachment\Download;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class DownloadManager
{
	private $security;
	private $session;
	private $attachmentManager;
	private $fileStreamerFactory;

	function __construct($security, $session, $attachmentManager)
	{
		$this->security = $security;
		$this->session = $session;
		$this->attachmentManager = $attachmentManager;
		$this->fileStreamerFactory = new FileStreamerFactory();
	}

	function sendRequestedFile()
	{
		global $wgRequest;
		global $wgScriptPath;
			
		$downloadFromPage = $this->session->getCurrentPage();
		$downloadFileName = $wgRequest->getVal('downloadFileName');
		$protectedPage = $downloadFromPage->isProtected();
		$attachmentDownloadAllowed = $this->security->isAttachmentDownloadAllowed($protectedPage);
		$requestValidationTokenValid = $this->security->isRequestValidationTokenValid();
		$validAttachment = $this->attachmentManager->isAttachmentExist($downloadFileName);
		if ($attachmentDownloadAllowed && $requestValidationTokenValid && isset($downloadFromPage) && $validAttachment)
		{
			$fs = $this->fileStreamerFactory->createFileStreamer();
			try
			{
				$fs->streamFile($downloadFileName);
			}
			catch(FileStreamerException $e)
			{
				$this->session->setStatusMessage('FailedSendTheRequestedFile_ContactSystemAdministrator');
				$redirectPage = $wgScriptPath . '/index.php/' . $downloadFromPage->getFullURL();
				header("Location: " . $redirectPage);
			}
		}
		else
		{
			if (!isset($downloadFromPage))
			{
				$this->session->setStatusMessage('UnableToDetermineDownloadFromPage');
			}
			else if (!isset($downloadFileName))
			{
				$this->session->setStatusMessage('UnableToDetermineDownloadFileName');
			}
			else if (!$validAttachment)
			{
				$this->session->setStatusMessage('RequestedDownloadFileDoesNotExist');
			}
			else if ($requestValidationTokenValid == false)
			{
				$this->session->setStatusMessage('UnableToAuthenticateYourRequest');
			}
			else if ($attachmentDownloadAllowed == false)
			{
				if ($this->security->isAttachmentDownloadRequireLogin() && !$this->security->isLoggedIn())
				{
					$this->session->setStatusMessage('YouMustBeLoggedInToDownloadAttachments');
				}
				else
				{
					$this->session->setStatusMessage('AttachmentDownloadIsNotPermitted');
				}
			}
			else
			{
				// This should not happen
				$this->session->setStatusMessage('UnknownDownloadError');
			}
			if (!isset($downloadFromPage))
			{
				$downloadFromPage = \wfMsgForContent('mainpage');
			}
			$redirectPage = $wgScriptPath . '/index.php/' . $downloadFromPage->getFullURL();
			header("Location: " . $redirectPage);
		}
	}

}

## ::END::
