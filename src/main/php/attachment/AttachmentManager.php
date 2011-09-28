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

namespace PageAttachment\Attachment;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

define('PA_UPLOADED', 'PA_UPLOADED');
define('PA_EXISTING', 'PA_EXISTING');

class AttachmentManager
{
	private $security;
	private $session;
	private $auditLogManager;
	private $cacheManager;


	function __construct($security, $session, $auditLogManager)
	{
		$this->security = $security;
		$this->session = $session;
		$this->auditLogManager = $auditLogManager;
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
	}

	function getAttachmentIds($attachedToPageId)
	{
		if ($this->session->isForceReload() == true)
		{
			$ids = $this->getAttachmentIdsFromDB($attachedToPageId);
			$this->cacheManager->storeAttachmentList($attachedToPageId, $ids);
			return $ids;
		}
		else
		{
			$ids = $this->cacheManager->retrieveAtachmentList($attachedToPageId);
			if (isset($ids) && count($ids) > 0)
			{
				return $ids;
			}
			else
			{
				$ids = $this->getAttachmentIdsFromDB($attachedToPageId);
				$this->cacheManager->storeAttachmentList($attachedToPageId, $ids);
				return $ids;
			}
		}
	}

	private function getAttachmentIdsFromDB($attachedToPageId)
	{
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('page_attachment_data', 'attachment_page_id', 'attached_to_page_id = ' . $attachedToPageId);
		if ($rs == false)
		{
			return false;
		}
		else
		{
			$ids = array();
			$i = 0;
			foreach($rs as $row)
			{
				$attachmentPageId = $row->attachment_page_id;
				$rs2 = $dbr->select('page','*', 'page_id = ' . $attachmentPageId);
				if ($found = $rs2->fetchRow($rs2))
				{
					$ids[$i++] = $attachmentPageId;
				}
				else
				{
					// Attachment document doesn't exist or deleted, so
					// remove it from attachment table & from cache
					$dbw = \wfGetDB( DB_MASTER );
					$dbw->delete('page_attachment_data', array('attachment_page_id' => $attachmentPageId));
					$this->cacheManager->removeAttachmentData($attachmentPageId);
					$this->cacheManager->removeAttachmentList($attachedToPageId);
					$this->session->setForceReload(true);
				}
			}
			return $ids;
		}
	}

	function attachUploadedFile( &$image )
	{
		if ($this->session->isUploadAndAttachFileInitiated() == true)
		{
			$attachToPage = $this->session->getAttachToPage();
			if (isset($attachToPage))
			{
				$attachToPageId = $attachToPage->getId();
				$attachToPageNS = $attachToPage->getNameSpace();
				$attachToPageTitle = $attachToPage->getPageTitle();
				$attachmentId   = $image->getTitle()->getArticleID();
				$attachmentName = $image->getTitle()->getText();
				$this->addUpdateAttachment($attachToPageId, $attachToPageNS, $attachmentName, $attachmentId, PA_UPLOADED);
			}
			else
			{
				$this->session->setStatusMessage('UnableToDetermineAttachToPage');
				$this->session->setForceReload(true);
			}
		}
		return true;
	}

	function attachExistingFile(&$title, &$article, &$output, &$user, $request, $mediaWiki)
	{
		global $wgRequest;

		$action = $wgRequest->getVal('action');
		if ($action != 'AttachFile')
		{
			return true;
		}
		$abort = false;
		if (!$this->security->isBrowseSearchAttachAllowed())
		{
			$this->session->setStatusMessage('BrowseSearchAttachIsNotPermitted');
			$abort = true;
		}
		elseif ($this->security->isBrowseSearchAttachRequireLogin() && !$this->security->isLoggedIn())
		{
			$this->session->setStatusMessage('YouMustBeLoggedInToBrowseSearchAttach');
			$abort = true;
		}
		elseif (!$this->security->isRequestValidationTokenValid())
		{
			$this->session->setStatusMessage('UnableToAuthenticateYourRequest');
			$abort = true;
		}
		if ($abort == false)
		{
			$page = $this->session->getAttachToPage();
			$attachToPage = $page->getRedirectURL();
			$attachToPageTitle = \Title::newFromText($attachToPage);
			$attachToPageId = $attachToPageTitle->getArticleID();
			$attachToPageNS = $attachToPageTitle->getNamespace();
			$attachmentFileName = $wgRequest->getVal('fileName','');
			$attachmentTitle = \Title::newFromText($attachmentFileName, NS_FILE);
			$attachmentId = $attachmentTitle->getArticleID();
			$attachmentName = $attachmentTitle->getText();
			$this->addUpdateAttachment($attachToPageId, $attachToPageNS, $attachmentName, $attachmentId, PA_EXISTING);
		}
		$action = 'view';
		$wgRequest->setVal('action', $action);
		$mediaWiki->setVal('action', $action);
		return true;
	}

	private function addUpdateAttachment($attachToPageId, $attachToPageNS, $attachmentName, $attachmentId, $uploadedOrExisting)
	{
		if (!$this->security->isPageInAllowedNameSpaces($attachToPageId, $attachToPageNS))
		{
			return;
		}
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('page_attachment_data', '*','attached_to_page_id = ' . $attachToPageId . ' and attachment_page_id = ' . $attachmentId );
		if ($row = $dbr->fetchRow($rs))
		{
			// Attachment link exists, no need to re-attach.
			$this->session->setStatusMessage('AttachmentUpdated', $attachmentName);
			if ($this->auditLogManager->isAuditLogEnabled())
			{
				if ($uploadedOrExisting == PA_UPLOADED)
				{
					$activityType = \PageAttachment\AuditLog\ActivityType::UPLOADED_AND_REATTACHED;
				}
				else
				{
					$activityType = \PageAttachment\AuditLog\ActivityType::REATTACHED_EXISTING;
				}
				$activityDetail = '';
				$this->auditLogManager->createLog($attachToPageId, $attachmentName, $activityType, $activityDetail);
			}
		}
		else
		{
			try
			{
				$dbw = \wfGetDB( DB_MASTER );
				$dbw->insert('page_attachment_data', array(0 => array('attached_to_page_id' => $attachToPageId, 'attachment_page_id' => $attachmentId)));
				$this->session->setStatusMessage('AttachmentAdded', $attachmentName);
				if ($this->auditLogManager->isAuditLogEnabled())
				{
					if ($uploadedOrExisting == PA_UPLOADED)
					{
						$activityType = \PageAttachment\AuditLog\ActivityType::UPLOADED_AND_ATTACHED;
					}
					else
					{
						$activityType = \PageAttachment\AuditLog\ActivityType::ATTACHED_EXISTING;
					}
					$activityDetail = '';
					$this->auditLogManager->createLog($attachToPageId, $attachmentName, $activityType, $activityDetail);
				}
			}
			catch(Exception $e)
			{
				\wfErrorLog('PageAttachment', 'Failed to create attachment link');
				\wfErrorLog('PageAttachment', 'DB Error: ' . $e.getMessage());
				$this->session->setStatusMessage('FailedToAddAttachment', $attachmentName);
			}
		}
		# Clear the cache entry so that cache data can be updated
		$this->cacheManager->removeAttachmentData($attachmentId);
		$this->cacheManager->removeAttachmentList($attachToPageId);
		$this->session->setForceReload(true);
	}

	function removeAttachment($attachmentName, $rvt)
	{
		global $wgRequest;

		$abort = false;
		$page = $this->session->getCurrentPage();
		$attachedToPage = $page->getRedirectURL();
		if (!$this->security->isRequestValidationTokenValid2($rvt))
		{
			$this->session->setStatusMessage('UnableToAuthenticateYourRequest');
			$abort = true;
		}
		elseif (!$this->security->isAttachmentRemovalAllowed())
		{
			if ($this->security->isAttachmentRemovalRequireLogin() &&
			!$this->security->isLoggedIn())
			{
				$this->session->setStatusMessage('YouMustBeLoggedInToRemoveAttachments');
			}
			else
			{
				$this->session->setStatusMessage('AttachmentRemovalIsNotPermitted');
			}
			$abort = true;
		}
		if ($abort == false)
		{
			$title = \Title::newFromText($attachedToPage);
			$attachedToPageId = $title->getArticleID();
			$attachedToPageNS = $title->getNamespace();
			$title2 = \Title::newFromText($attachmentName, NS_FILE);
			$attachmentId = $title2->getArticleID();
			$attachmentNS = $title2->getNamespace();
			if ($attachedToPageId == 0 || $attachmentId == 0)
			{
				$this->session->setStatusMessage('FailedToValidateAttachmentRemovalRequest');
				$abort = true;
			}
		}
		if ($abort == false)
		{
			$fileName = $title2->getText();
			$dbr = \wfGetDB( DB_SLAVE );
			$rs = $dbr->select('page_attachment_data', '*','attached_to_page_id = ' . $attachedToPageId . ' and attachment_page_id = ' . $attachmentId );
			if ($row = $dbr->fetchRow($rs))
			{
				try
				{
					$dbw = \wfGetDB( DB_MASTER );
					$dbw->delete('page_attachment_data', array('attached_to_page_id' => $attachedToPageId, 'attachment_page_id' => $attachmentId));
					$this->session->setStatusMessage('AttachmentRemoved', $fileName);
					if ($this->auditLogManager->isAuditLogEnabled())
					{
						$activityType = \PageAttachment\AuditLog\ActivityType::REMOVED;
						$activityDetail = '';
						$this->auditLogManager->createLog($attachedToPageId, $attachmentName, $activityType, $activityDetail);
					}
				}
				catch(Exception $e)
				{
					\wfDebugLog('PageAttachment','Failed to remove attachment link');
					\wfDebugLog('PageAttachment','DB Error: ' . $e->getMessage());
					$this->session->setStatusMessage('FailedToRemoveAttachment');
				}
			}
			else
			{
				// Remove request, potentially, was initiated from a cached page
				// Should the message indicate that the attachment was already removed?
				$this->session->setStatusMessage('AttachmentRemoved', $fileName);
			}
			$this->cacheManager->removeAttachmentData($attachmentId);
			$this->cacheManager->removeAttachmentList($attachedToPageId);
		}
		$this->session->setForceReload(true);
	}

	/**
	 * NOTE:  This function is triggered when someone with MediaWiki image delete
	 *        permission deletes an image, so no additional security check is done.
	 */
	function removeDeletedAttachment($page)
	{
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('page_attachment_data', 'attached_to_page_id', 'attachment_page_id = ' . $page->getId());
		if ($rs == false)
		{
			// Not attached to any page, so nothing to do
			return;
		}
		else
		{
			$attachedToPageIds = array();
			foreach($rs as $row)
			{
				$attachedToPageIds[] = $row->attached_to_page_id;
			}
			$attachmentName = $page->getPageTitle();
			$dbw = \wfGetDB( DB_MASTER );
			foreach($attachedToPageIds as $attachedToPageId)
			{
				$insertData = array('attachment_file_name' => $attachmentName, 'attached_to_page_id' => $attachedToPageId);
				$dbw->insert('page_attachment_delete_data', $insertData);
				$this->cacheManager->removeAttachmentList($attachedToPageId);
			}
			$dbw->delete('page_attachment_data', array('attachment_page_id' => $page->getId()));
			$this->session->setForceReload(true);
			if ($this->auditLogManager->isAuditLogEnabled())
			{
				$activityType = PageAttachment\AuditLog\ActivityType::REMOVED_DELETED;
				$activityDetail = '';
				$this->auditLogManager->createLog($attachToPageId, $attachmentName, $activityType, $activityDetail);
			}

		}
	}

	/**
	 * NOTE:  This function is triggered when someone, with MediaWiki image undelete
	 *        permission, undeletes an image, so no additional security check is done.
	 */
	function restoreDeletedAttachment($page, $user)
	{

		$attachmentId = $page->getId();
		$attachmentName = $page->getPageTitle();
		$dbr = \wfGetDB( DB_SLAVE );
		$rs = $dbr->select('page_attachment_delete_data', 'attached_to_page_id', 'attachment_file_name = \'' . $attachmentName . '\'');
		if ($rs == false)
		{
			// This file was not attached to any page, so nothing to do
			return;
		}
		else
		{
			$attachedToPageIds = array();
			foreach($rs as $row)
			{
				$attachedToPageIds[] = $row->attached_to_page_id;
			}
			$dbw = \wfGetDB( DB_MASTER );
			foreach($attachedToPageIds as $attachedToPageId)
			{
				$insertData =  array('attached_to_page_id' => $attachedToPageId, 'attachment_page_id' => $attachmentId);
				$dbw->insert('page_attachment_data', $insertData);
				$this->cacheManager->removeAttachmentList($attachedToPageId);
			}
			$deleteCriteria =  array('attachment_file_name' => $dbw->strencode($attachmentName));
			$dbw->delete('page_attachment_delete_data', $deleteCriteria);
			$this->session->setForceReload(true);
			if ($this->auditLogManager->isAuditLogEnabled())
			{
				$activityType = PageAttachment\AuditLog\ActivityType::REATTACHED_UNDELETED;
				$activityDetail = '';
				$this->auditLogManager->createLog($attachToPageId, $attachmentName, $activityType, $activityDetail);
			}
		}
	}

	function isAttachmentExist($attachmentName)
	{
		if (isset($attachmentName))
		{
			$title = \Title::newFromText($attachmentName, NS_FILE);
			$article = new \Article($title);
			$file  = \wfFindFile($title);
			if (is_bool($file))
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}

}

## ::END::
