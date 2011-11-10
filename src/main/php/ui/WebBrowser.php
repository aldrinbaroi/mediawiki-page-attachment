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

class WebBrowser
{
	private $security;
	private $requestHelper;
	private $session;
	private $dateHelper;
	private $attachmentManager;
	private $resource;
	private $runtimeConfig;
	private $fileManager;


	function __construct($security, $requestHelper, $session, $attachmentManager, $resource)
	{
		global $wgUser;

		$this->security = $security;
		$this->requestHelper = $requestHelper;
		$this->session = $session;
		$this->dateHelper = new \PageAttachment\Utility\DateUtil();
		$this->attachmentManager = $attachmentManager;
		$this->resource = $resource;
		$this->runtimeConfig = new \PageAttachment\Config\RuntimeConfig();
		$this->fileManager = new \PageAttachment\File\FileManager($this->security);
	}

	function setRedirectPage( $form )
	{
		if ($this->session->isUploadAndAttachFileInitiated())
		{
			$attachToPage = $this->session->getAttachToPage();
			$form->mLocalFile = new \PageAttachment\Utility\PsuedoArticle($attachToPage);
		}
		return true;
	}

	function setupAttachmentListSection(&$data)
	{
		global $wgUser;

		if (!$this->session->isViewPageSpecial())
		{
			$page = $this->session->getCurrentPage();
			$pageId = $page->getId();
			$pageNS = $page->getNameSpace();
			$pageURL = $page->getRedirectURL();
			$pageInAllowedNameSpaces = $this->security->isPageInAllowedNameSpaces($pageId, $pageNS);
			if (($pageInAllowedNameSpaces == false)
			|| ($pageInAllowedNameSpaces == true
			&& ($this->requestHelper->isEditMode() == true || $this->requestHelper->isPreviewMode() || $this->requestHelper->isViewChangesMode())))
			{
				$data = '';
			}
			else
			{
				$pageTitle = $page->getPageTitle();
				$attachmentDiv = \HTML::element('br') . \HTML::element('div', array('id' => 'PageAttachment'));
				$script = \HTML::inlineScript('  function pageAttachment_getAttachToPageTitle() { return "' . $pageTitle . '"; } ');
				if ($this->session->isForceReload())
				{
					$script .= \HTML::inlineScript(' function pageAttachment_isForceReload() { return true; } ');
				}
				else
				{
					$script .= \HTML::inlineScript(' function pageAttachment_isForceReload() { return false; } ');
				}
				$data = $attachmentDiv . $script;
			}
		}
		return true;
	}

	function addResources(&$out, &$sk)
	{
		$this->resource->addCSSFiles($out, $sk);
		$this->resource->addJSFiles($out, $sk);
	}

	function renderAttachmentList($requestPageTitle)
	{
		global $wgParser;
		global $wgUser;
		global $wgLang;
		global $wgContLang;
		global $wgScriptPath;

		$page = $this->session->getCurrentPage();
		$pageId = $page->getId();
		$pageNS = $page->getNameSpace();
		$pageURL = $page->getURL();
		$pageTitle = $page->getPageTitle();
		if (!$this->security->isPageInAllowedNameSpaces($pageId, $pageNS))
		{
			$data = '';
			return $data;
		}
		$rvt = $this->security->newRequestValidationToken();
		$command = new Command($this->session, $this->resource, $rvt);
		$titleRowColumns = $this->getTitleRowColumns($command);
		$headerRowColumns = $this->getHeaderRowColumns();
		$attachmentRows = $this->getAttachmentRows($pageId, $command);
		$uiComposer = new UIComposer($this->security, $this->session, $this->runtimeConfig);
		$data = $uiComposer->composeAttachmentListTable($titleRowColumns, $headerRowColumns, $attachmentRows);
		$this->session->setForceReload(false);
		return $data;
	}

	function registerOnLoadHook($skin, &$text)
	{
		$text =\HTML::inlineScript(' pageAttachment_registerOnLoad(); pageAttachment_registerOnPageShow(); ') . $text;
		return true;
	}

	/*
	 * Do not cache this, otherwise, request validation token would be invalid
	*/
	private function getTitleRowColumns($command)
	{
		$tzName = $this->dateHelper->getUserTimeZoneInUserLang();
		$rtlLang = $this->runtimeConfig->isRTL();
		$titleRowColumns = array();
		$titleRowColumns['Title'] = ':: ' . \wfMsg('Attachments') . ' ::';
		if ($rtlLang == true)
		{
			$titleRowColumns['DisplayTimeZone'] = $tzName .  ' :' .  \wfMsg('DisplayTimeZone');
		}
		else
		{
			$titleRowColumns['DisplayTimeZone'] = \wfMsg('DisplayTimeZone') . ': ' . $tzName;
		}
		$titleRowColumns['Buttons'] = '';
		if ($this->security->isAuditLogViewAllowed())
		{
			$titleRowColumns['Buttons'] .= $command->getViewAuditLogAllCommandLink();
		}
		if ($this->security->isBrowseSearchAttachAllowed())
		{
			$titleRowColumns['Buttons'] .= $command->getBrowseSearchAttachCommandLink();
		}
		if ($this->security->isAttachmentUploadAndAttachAllowed())
		{
			$titleRowColumns['Buttons'] .= $command->getUploadAndAttachCommandLink();
		}
		if ($titleRowColumns['Buttons'] == '')
		{
			$titleRowColumns['Buttons'] = HTML::buildImageLink(null, $this->resource->getSpacerImageURL());
		}
		return $titleRowColumns;
	}

	private function getHeaderRowColumns()
	{
		global $wgPageAttachment_colToDisplay;

		$headerRowColumns = array();
		for($i = 0; $i < count($wgPageAttachment_colToDisplay); $i++)
		{
			if ($wgPageAttachment_colToDisplay[$i] == 'Buttons')
			{
				$headerRowColumns[$wgPageAttachment_colToDisplay[$i]] = '';
			}
			else
			{
				$headerRowColumns[$wgPageAttachment_colToDisplay[$i]] = \wfMsg($wgPageAttachment_colToDisplay[$i]);
			}
		}
		return $headerRowColumns;
	}

	/*
	 * Do not cache this, otherwise, request validation token would be invalid
	*/
	private function getAttachmentRows($pageId, $command)
	{
		global $wgUser;
		global $wgPageAttachment_colToDisplay;

		$pageAttachmentDataFactory = new \PageAttachment\Attachment\AttachmentDataFactory($this->security);
		$sk = $wgUser->getSkin();
		$aIds = $this->attachmentManager->getAttachmentIds($pageId);
		$aCount = 0;
		$attachmentRows = array();
		if ($this->security->isViewAttachmentsAllowed())
		{
			foreach($aIds as $aId)
			{
				$attachmentData = $pageAttachmentDataFactory->newAttachmentData($aId);
				$fileName = $attachmentData->getTitle()->getText();
				for($i = 0; $i < count($wgPageAttachment_colToDisplay); $i++)
				{
					switch ($wgPageAttachment_colToDisplay[$i])
					{
						case 'Name':
							$attachmentRows[$aCount]['Name'] = $this->getNameColumn($command, $fileName);
							break;
						case 'Size':
							$attachmentRows[$aCount]['Size'] = $sk->formatSize($attachmentData->getSize());
							break;
						case 'Description':
							$attachmentRows[$aCount]['Description'] = $attachmentData->getDescription();
							break;
						case 'DateUploaded':
							$attachmentRows[$aCount]['DateUploaded'] = $this->dateHelper->formatDate($attachmentData->getDateUploaded());
							break;
						case 'UploadedBy':
							$attachmentRows[$aCount]['UploadedBy'] = $attachmentData->getUploadedBy();
							break;
						case 'Buttons':
							$attachmentRows[$aCount]['Buttons'] = $this->getButtonsColumn($command, $fileName, $attachmentData);
							break;
					}
				}
				$aCount++;
			}
		}
		return $attachmentRows;
	}

	private function getNameColumn($command, $fileName)
	{
		$nameColum = '';
		if ($this->security->isAttachmentDownloadAllowed() == true)
		{
			$nameColum = $command->getDownloadCommandLink($fileName);
		}
		else
		{
			if ($this->security->isAttachmentDownloadRequireLogin() && !$this->security->isLoggedIn())
			{
				$nameColum = HTML::buildLabel('PleaseLoginToActivateDownloadLink', $fileName);
			}
			else
			{
				$nameColum = HTML::buildLabel('AttachmentDownloadIsNotPermitted', $fileName);
			}
		}
		return $nameColum;
	}


	private function getButtonsColumn($command, $fileName, $attachmentData)
	{
		$buttonsColumn = '';
		if ($this->security->isAuditLogViewAllowed())
		{
			$buttonsColumn .= $command->getViewAuditLogCommandLink($fileName);
		}
		if ($this->security->isHistoryViewAllowed())
		{
			$buttonsColumn .= $command->getViewHistoryCommandLink($fileName);
		}
		if ($this->security->isAttachmentRemovalAllowed())
		{
			if ($this->security->isRemoveAttachmentPermanentlyEnabled())
			{
				$file = $this->fileManager->getFile($attachmentData->getTitle());
				$removeAttachmentPermanentlyEvenIfAttached = $this->security->isRemoveAttachmentPermanentlyEvenIfAttached();
				$removeAttachmentPermanentlyEvenIfEmbedded = $this->security->isRemoveAttachmentPermanentlyEvenIfEmbedded();
				$buttonsColumn .=
				$command->getRemoveAttachmentPermanentlyCommandLink($attachmentData, $removeAttachmentPermanentlyEvenIfAttached, $file, $removeAttachmentPermanentlyEvenIfEmbedded);
			}
			else
			{
				$buttonsColumn .= $command->getRemoveAttachmentCommandLink($fileName);
			}
		}
		if ($buttonsColumn == '')
		{
			$buttonsColumn = HTML::buildImageLink(null, $this->resource->getSpacerImageURL());
		}
		return $buttonsColumn;
	}

}

## ::END::
