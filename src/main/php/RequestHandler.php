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
 * @category   MediaWiki Extension
 * @package    PageAttachment
 * @author     Aldrin Edison Baroi <aldrin.baroi@gmail.com>
 * @copyright  Copyright (C) 2011 Aldrin Edison Baroi
 * @license    http://www.gnu.org/copyleft/gpl.html   GPL License 3.0 or later
 * @version    SVN: $Id$
 * @since      File available since Release 1.0
 *
 */

namespace PageAttachment;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class RequestHandler
{
	private $pageURL = '';
	private $pageId = -1;
	private $pageNS = -1;
	private $page;
	private $cacheManager;
	private $requestHelper;
	private $security;
	private $session;
	private $attachmentManager;
	private $webBrowser;
	private $resource;
	private $auditLogManager;
	private $downloadManager;
	private $uploadHelper;
	private $categoryManager;
	private $pageFactory;

	function __construct()
	{
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
		$this->pageFactory = new \PageAttachment\Session\PageFactory($this->cacheManager);
		$this->requestHelper = new \PageAttachment\Request\RequestHelper();
		$this->security = new \PageAttachment\Security\SecurityManager();
		$this->session = new \PageAttachment\Session\Session($this->security, $this->pageFactory);
		$this->auditLogManager = new \PageAttachment\AuditLog\AuditLogManager();
		$this->attachmentManager = new \PageAttachment\Attachment\AttachmentManager($this->security, $this->session, $this->auditLogManager);
		$this->resource = new \PageAttachment\UI\Resource($this->security, $this->session);
		$this->webBrowser = new \PageAttachment\UI\WebBrowser($this->security, $this->requestHelper, $this->session, $this->attachmentManager, $this->resource);
		$this->downloadManager = new \PageAttachment\Download\DownloadManager($this->security, $this->session, $this->attachmentManager);
		$this->categoryManager = new \PageAttachment\Category\CategoryManager($this->session);
		$this->uploadHelper = new \PageAttachment\Upload\UploadHelper($this->categoryManager);
	}

	function onSetupDatabase()
	{
			
		$databaseHelper = new \PageAttachment\Setup\DatabaseSetup();
		$databaseHelper->setupDatabase();
		return true;
	}

	// 
	// NOTE: As of MediaWiki 1.18.0, $article is NULL
	//
	function onBeforeInitialize(&$title, $article, &$output, &$user, $request, $mediaWiki)
	{
		global $wgRequest;

		$this->session->startSessionIfNotStarted();
		$this->requestHelper->setPageMode($request);
		$action = $wgRequest->getVal('action');
		$currentViewPage = $this->pageFactory->createPage($title);
		$viewPageId = $currentViewPage->getId();
		$viewPageNS = $currentViewPage->getNameSpace();
		$viewPageTitle = $currentViewPage->getPrefixedURL();

		// Set attach to page
		if ($this->requestHelper->isPageAttachmentSpecialPage($title, $viewPageTitle))
		{
			$previousPage = $this->session->getCurrentPage();
			$this->session->setAttachToPage($previousPage);
		}
		// For Browse/Search & attach attachment function
		if ($action == 'AttachFile')
		{
			$this->attachmentManager->attachExistingFile($title, $article, $output, $user, $request, $mediaWiki);
		}
		if ($viewPageId > 0)
		{
			$this->session->setCurrentPage($currentViewPage);
			$this->session->setViewPageSpecial(false);
		}
		else
		{
			if ($this->requestHelper->isSpecialPage($title))
			{
				$this->session->setViewPageSpecial(true);
			}
			else
			{
				$this->session->setCurrentPage(null);
			}
		}
		return true;
	}

	function onEditPageImportFormData($editpage, $request)
	{
		$this->requestHelper->setPageMode($request, $editpage);
		return true;
	}

	function onArticleSaveComplete(&$article, &$user, $text, $summary, $minoredit, $watchthis, $sectionanchor, &$flags, $revision, &$status, $baseRevId)
	{
		$title = $article->getTitle();
		if ($title->getNamespace() == NS_FILE)
		{
			$this->attachmentManager->cleardAttachmentData($article->getID());
		}
		$this->cacheManager->removePage($article->getID());
		$this->session->setForceReload(true);
		return true;
	}

	function onBeforePageDisplay(&$out, &$sk)
	{
		$this->webBrowser->addResources($out, $sk);
		return true;
	}

	function onSkinAfterContent(&$data)
	{
		$this->webBrowser->setupAttachmentListSection($data);
		return true;
	}

	function onSkinAfterBottomScripts($skin, &$text)
	{
		//$this->webBrowser->registerOnLoadHook($skin, $text);
		return true;
	}

	function onUploadFormInitial($uploadFormObj)
	{
		if ($this->uploadHelper->isSetAttachmentCategoryOnUploadEnabled())
		{
			$this->uploadHelper->addCategoryChooserToUploadForm($uploadFormObj);
		}
		return true;
	}

	function onUploadFormBeforeProcessing($uploadFormObj)
	{
		if ($this->uploadHelper->isSetAttachmentCategoryOnUploadEnabled())
		{
			$this->uploadHelper->setAttachmentCategory($uploadFormObj);
		}
		return true;
	}

	function onUploadComplete(&$image)
	{
		$this->attachmentManager->attachUploadedFile($image);
		return true;
	}

	function onSpecialUploadComplete($form)
	{
		$this->webBrowser->setRedirectPage($form);
		$this->session->setUploadAndAttachFileInitiated(false);
		$this->session->setAttachToPage(null);
		$this->security->setDownloadRequestValid(false);
		return true;
	}

	/**
	 * Fulfils Ajax request to remove a page attachment
	 *
	 */
	function removeAttachment($pageTitle, $attachmentName, $rvt)
	{
		$this->attachmentManager->removeAttachment($attachmentName, $rvt);
		return $this->webBrowser->renderAttachmentList($pageTitle);
	}

	/**
	 * Fulfils Ajax request to remove a page attachment permanently
	 *
	 */
	function removeAttachmentPermanently($pageTitle, $attachmentName, $rvt)
	{
		$this->attachmentManager->removeAttachmentPermanently($attachmentName, $rvt);
		return $this->webBrowser->renderAttachmentList($pageTitle);
	}


	/**
	 * Fulfils Ajax request get attachment list for a page
	 *
	 */
	function getAttachments($pageTitle)
	{
		return $this->webBrowser->renderAttachmentList($pageTitle);
	}

	function onUserLoginComplete(&$user, &$inject_html)
	{
		$this->session->setLoginLogoutTime();
		$this->session->setForceReload(true);
		// Need this, since when auto redirected to the original page, browser doesn't execute
		// the javascript to load the page attachment list <-- why?. Need a way to fix this
		// without forcing display of successfull login page.
		$inject_html = '<br/>';
		return true;
	}

	function onUserLogoutComplete(&$user, &$inject_html, $old_name)
	{
		$this->session->setLoginLogoutTime();
		$this->session->setForceReload(true);
		return true;
	}

	function sendRequestedFile()
	{
		$this->downloadManager->sendRequestedFile();
	}

	function onArticleDelete(&$article, &$user, &$reason, &$error)
	{
		$title  = $article->getTitle();
		$page = $this->pageFactory->createPage($title);
		if ($page->getNameSpace() == NS_FILE)
		{
			$this->session->storeDeletedFileInfo($page);
		}
		return true;
	}


	function onFileDeleteComplete($file, $oldimage, $article, $user, $reason)
	{
		if (isset($article))
		{
			$page = $this->session->retrieveDeletedFileInfo();
			if (isset($page))
			{
				$this->attachmentManager->removeDeletedAttachment($page);
			}
			else
			{
				// Something went wrong!
				// Should or, Should not set PageAttachment status message; since, this funciton was triggered
				// as a result of MediaWiki's image deletion action ?
				\wfDebugLog("PageAttachment", "File [] was being deleted, however, article info not set in session!");
			}
		}
		return true;
	}

	function onFileUndeleteComplete($file, $fileVersions, $user, $reason)
	{
		$page = $this->pageFactory->createPage($file);
		$this->attachmentManager->restoreDeletedAttachment($page, $user);
		return true;
	}

	function onLinksUpdate(&$linksUpdate)
	{
		if ($this->uploadHelper->isSetAttachmentCategoryOnUploadEnabled())
		{
			$this->categoryManager->setReinitializeCategoryList($linksUpdate);
		}
		return true;
	}

	function onLinksUpdateComplete(&$linksUpdate)
	{
		if ($this->uploadHelper->isSetAttachmentCategoryOnUploadEnabled())
		{
			$this->categoryManager->reinitializeCategoryList();
		}
		return true;
	}
}

## :: END ::
