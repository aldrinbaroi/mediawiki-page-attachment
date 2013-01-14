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
	private $staticConfig;


	function __construct($security, $requestHelper, $session, $attachmentManager, $resource)
	{
		global $wgUser;

		$this->security = $security;
		$this->requestHelper = $requestHelper;
		$this->session = $session;
		$this->dateHelper = new \PageAttachment\Utility\DateUtil();
		$this->attachmentManager = $attachmentManager;
		$this->resource = $resource;
		$this->runtimeConfig = new \PageAttachment\Configuration\RuntimeConfiguration();
		$this->fileManager = new \PageAttachment\File\FileManager($this->security);
		$this->staticConfig = \PageAttachment\Configuration\StaticConfiguration::getInstance();
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
		$_data = '';
		$attachmentContainerDiv = \HTML::element('br') . \HTML::element('div', array('id' => 'PageAttachmentContainer'));
		$page = $this->session->getCurrentPage();
		if ($this->isSetupAttachmentListSection($page))
		{
			$pageTitle = $page->getPageTitle();
			if ($this->staticConfig->isDisallowAttachmentsUsingMagicWord())
			{
				$script = \HTML::inlineScript('  function pageAttachment_isLoadPageAttachments() { return ((typeof pageAttachment__ALLOW_ATTACHMENTS__ == "boolean") ? pageAttachment__ALLOW_ATTACHMENTS__ : true); } ');
			}
			else
			{
				$script = \HTML::inlineScript('  function pageAttachment_isLoadPageAttachments() { return true; } ');
			}
			$script .= \HTML::inlineScript('  function pageAttachment_getAttachToPageTitle() { return "' . $pageTitle . '"; } ');
			if ($this->session->isForceReload())
			{
				$script .= \HTML::inlineScript(' function pageAttachment_isForceReload() { return true; } ');
			}
			else
			{
				$script .= \HTML::inlineScript(' function pageAttachment_isForceReload() { return false; } ');
			}
			$_data = $script;
		}
		else
		{
			$allowAttachmentsUsingMagicWord = $this->staticConfig->isAllowAttachmentsUsingMagicWord();
			if ($allowAttachmentsUsingMagicWord && $this->isSetupAttachmentListSection($page, $allowAttachmentsUsingMagicWord))
			{
				$pageTitle = $page->getPageTitle();
				$script = \HTML::inlineScript('  function pageAttachment_isLoadPageAttachments() { return ((typeof pageAttachment__ALLOW_ATTACHMENTS__ == "boolean") ? pageAttachment__ALLOW_ATTACHMENTS__ : false); } ');
				$script .= \HTML::inlineScript('  function pageAttachment_getAttachToPageTitle() { return "' . $pageTitle . '"; } ');
				if ($this->session->isForceReload())
				{
					$script .= \HTML::inlineScript(' function pageAttachment_isForceReload() { return true; } ');
				}
				else
				{
					$script .= \HTML::inlineScript(' function pageAttachment_isForceReload() { return false; } ');
				}
			}
			else
			{
				$script = \HTML::inlineScript('  function pageAttachment_isLoadPageAttachments() { return false; } ');
			}
			$_data = $script;
		}
		$data = $attachmentContainerDiv . $_data;
		return true;
	}

	private function isSetupAttachmentListSection($page, $allowAttachmentsUsingMagicWord = false)
	{
		$setup = false;
		if (!$this->session->isViewPageSpecial())
		{
			$protectedPage = $page->isProtected();
			if ($this->security->isAttachmentAllowed($page) || ($allowAttachmentsUsingMagicWord && !$this->security->isPageExcluded($page)))
			{
				if (!($this->requestHelper->isEditMode()
				|| $this->requestHelper->isPreviewMode()
				|| $this->requestHelper->isViewHistoryMode()
				|| $this->requestHelper->isViewChangesMode()
				|| $this->requestHelper->isUpdatePageProtectionSettingsMode()))
				{
					if ($this->security->isViewAttachmentsAllowed($protectedPage))
					{
						$setup = true;
					}
					else
					{
						$setup = false;
					}
				}
			}
		}
		return $setup;
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
		$protectedPage = $page->isProtected();
		if (!$this->security->isAttachmentAllowed($page))
		{
			if ($this->staticConfig->isAllowAttachmentsUsingMagicWord())
			{
				// Proceed normally
			}
			else
			{
				$data = '';
				return $data;
			}
		}
		$rvt = $this->security->newRequestValidationToken();
		$command = new Command($this->session, $this->resource, $rvt);
		$titleRowColumns = $this->getTitleRowColumns($protectedPage, $command);
		$headerRowColumns = $this->getHeaderRowColumns();
		$attachmentRows = $this->getAttachmentRows($protectedPage, $pageId, $command);
		$uiComposer = new UIComposer($this->security, $this->session, $this->runtimeConfig, $this->resource);
		$data = $uiComposer->composeAttachmentListTable($protectedPage, $titleRowColumns, $headerRowColumns, $attachmentRows);
		$this->session->setForceReload(false);
		return $data;
	}

	function registerOnLoadHook($skin, &$text)
	{
		$text = \HTML::inlineScript(' pageAttachment_registerOnLoad(); pageAttachment_registerOnPageShow(); ') . $text;
		return true;
	}

	/*
	 * Do not cache this, otherwise, request validation token would be invalid
	*/
	private function getTitleRowColumns($protectedPage, $command)
	{
		$tzName = $this->dateHelper->getUserTimeZoneInUserLang();
		$rtlLang = $this->runtimeConfig->isRightToLeftLanguage();
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
		if ($this->security->isAuditLogViewAllowed($protectedPage))
		{
			$titleRowColumns['Buttons'] .= $command->getViewAuditLogAllCommandLink();
		}
		if ($this->security->isBrowseSearchAttachAllowed($protectedPage))
		{
			$titleRowColumns['Buttons'] .= $command->getBrowseSearchAttachCommandLink();
		}
		if ($this->security->isAttachmentUploadAndAttachAllowed($protectedPage))
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
	private function getAttachmentRows($protectedPage, $pageId, $command)
	{
		$attachmentRows = array();
		if ($this->security->isViewAttachmentsAllowed($protectedPage))
		{
			global $wgPageAttachment_colToDisplay;

			$pageAttachmentDataFactory = new \PageAttachment\Attachment\AttachmentDataFactory($this->security);
			$sk = $this->runtimeConfig->getSkin();
			$aIds = $this->attachmentManager->getAttachmentIds($pageId);
			$aCount = 0;
			foreach($aIds as $aId)
			{
				$attachmentData = $pageAttachmentDataFactory->newAttachmentData($aId);
				$fileName = $attachmentData->getTitle()->getText();
				for($i = 0; $i < count($wgPageAttachment_colToDisplay); $i++)
				{
					switch ($wgPageAttachment_colToDisplay[$i])
					{
						case 'Name':
							$attachmentRows[$aCount]['Name'] = $this->getNameColumn($protectedPage, $command, $fileName);
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
							$attachmentRows[$aCount]['Buttons'] = $this->getButtonsColumn($protectedPage, $command, $fileName, $attachmentData);
							break;
					}
				}
				$aCount++;
			}
		}
		return $attachmentRows;
	}

	private function getNameColumn($protectedPage, $command, $fileName)
	{
		global $wgPageAttachment_attachmentNameMaxLength;

		$nameColum = '';
		$fileNameLabel = $fileName;
		if (strlen($fileName) > $wgPageAttachment_attachmentNameMaxLength)
		{
			$rtlLang = $this->runtimeConfig->isRightToLeftLanguage();
			if ($rtlLang == true)
			{
				// FIXME Need to figure out how to detect file name content language to properly trim it
				$fileNameLabel = substr($fileName, 0, ($wgPageAttachment_attachmentNameMaxLength - 4)) . ' ...';
			}
			else
			{
				$fileNameLabel = substr($fileName, 0, ($wgPageAttachment_attachmentNameMaxLength - 4)) . ' ...';
			}
		}
		if ($this->security->isAttachmentDownloadAllowed($protectedPage) == true)
		{
			$nameColum = $command->getDownloadCommandLink($fileName, $fileNameLabel);
		}
		else
		{
			if ($this->security->isAttachmentDownloadRequireLogin($protectedPage) && !$this->security->isLoggedIn())
			{
				$nameColum = HTML::buildLabel('PleaseLoginToActivateDownloadLink', $fileNameLabel);
			}
			else
			{
				$nameColum = HTML::buildLabel('AttachmentDownloadIsNotPermitted', $fileNameLabel);
			}
		}
		return $nameColum;
	}

	private function getDescriptionColumn($description)
	{

	}


	private function getButtonsColumn($protectedPage, $command, $fileName, $attachmentData)
	{
		$buttonsColumn = '';
		if ($this->security->isAuditLogViewAllowed($protectedPage))
		{
			$buttonsColumn .= $command->getViewAuditLogCommandLink($fileName);
		}
		if ($this->security->isHistoryViewAllowed($protectedPage))
		{
			$buttonsColumn .= $command->getViewHistoryCommandLink($fileName);
		}
		if ($this->security->isAttachmentRemovalAllowed($protectedPage))
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
