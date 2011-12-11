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

class Command
{
	private $rvt;
	private $urlPrefix;
	private $session;
	private $resource;

	function __construct($session, $resource, $rvt)
	{
		global $wgScriptPath;

		$this->rvt = $rvt;
		$this->session = $session;
		$this->resource = $resource;
		$this->urlPrefix = $wgScriptPath . '/index.php?title=';
	}


	function getBrowseSearchAttachCommandLink()
	{
		return HTML::buildImageCommandLinkJS('BrowseSearchAttach',  $this->getBrowseSearchAttachCommandURL(),  $this->resource->getBrowseSearchAttachImageURL());
	}

	function getUploadAndAttachCommandLink()
	{
		return HTML::buildImageCommandLinkJS('UploadAndAttach', $this->getUploadAndAttachtCommandURL(), $this->resource->getUploadAndAttachtImageURL());
	}

	function getViewAuditLogAllCommandLink()
	{
		return HTML::buildImageCommandLinkJS('ViewAuditLog', $this->getViewAuditLogAllCommandURL(), $this->resource->getViewAuditLogImageURL());
	}

	function getViewAuditLogCommandLink($fileName)
	{
		return HTML::buildImageCommandLinkJS('ViewAuditLog', $this->getViewAuditLogCommandURL($fileName), $this->resource->getViewAuditLogImageURL());
	}

	function getDownloadCommandLink($fileName, $fileNameLabel)
	{
		return HTML::buildCommandLink(array('DownloadFile', $fileName), $this->getDownloadCommandURL($fileName), $fileNameLabel);
	}


	function getViewHistoryCommandLink($fileName)
	{
		return HTML::buildImageCommandLink('ViewHistory', $this->getViewHistoryCommandURL($fileName), $this->resource->getViewHistoryImageURL());
	}

	function getRemoveAttachmentCommandLink($fileName)
	{
		return HTML::buildRemoveAttachmentCommandLink('RemoveAttachment', $fileName, $this->resource->getRemoveAttachmentImageURL(), $this->rvt);
	}

	function getRemoveAttachmentPermanentlyCommandLink($attachmentData, $removeAttachmentPermanentlyEvenIfAttached, $file, $removeAttachmentPermanentlyEvenIfEmbedded)
	{
		return HTML::buildRemoveAttachmentPermanentlyCommandLink('RemoveAttachmentPermanently', $this->resource->getRemoveAttachmentImageURL(),
		$this->rvt, $attachmentData, $removeAttachmentPermanentlyEvenIfAttached, $file, $removeAttachmentPermanentlyEvenIfEmbedded);
	}

	function getAttachFileCommandLink($fileName)
	{
		return HTML::buildImageCommandLink('AttachFile', $this->getAttachFileCommandURL($fileName), $this->resource->getAttachFileImageURL());
	}

	static function getViewUserPageCommandLink($userName, $userRealName)
	{
		return HTML::buildCommandLink(array('ViewUserPage', $userRealName), self::getViewUserPageCommandURL($userName), $userRealName);
	}

	/*
	 * PRIVATE FUNCTIONS
	*/

	private function getBrowseSearchAttachCommandURL()
	{
		return $this->urlPrefix . 'Special:PageAttachmentListFiles&rvt=' . $this->rvt;
	}

	private function getUploadAndAttachtCommandURL()
	{
		return $this->urlPrefix . 'Special:PageAttachmentUpload&rvt=' . $this->rvt;
	}

	private function getViewAuditLogAllCommandURL()
	{
		return $this->urlPrefix . 'Special:PageAttachmentAuditLogViewer&rvt=' . $this->rvt;
	}

	private function getViewAuditLogCommandURL($fileName)
	{
		$__fileName = base64_encode($fileName);
		return $this->urlPrefix . 'Special:PageAttachmentAuditLogViewer&rvt=' . $this->rvt . '&attachmentName=' . rawurlencode($__fileName);
	}

	private function getDownloadCommandURL($fileName)
	{
		global $wgScriptPath;

		return $wgScriptPath . '/extensions/PageAttachment/download/Download.php?rvt=' . $this->rvt	. '&downloadFileName=' . rawurlencode($fileName);
	}

	private function getViewHistoryCommandURL($fileName)
	{
		global $wgScriptPath;

		return $wgScriptPath . '/index.php/File:'  . rawurldecode($fileName);
	}

	private function getRemoveAttachmentCommandURL($fileName)
	{
		$page = $this->session->getCurrentPage();
		$pageURL = $page->getURL();
		return $this->urlPrefix . $pageURL . '&action=RemoveAttachment&rvt=' . $this->rvt . '&attachmentName=' . rawurlencode($fileName);
	}

	private function getAttachFileCommandURL($fileName)
	{
		$attachToPage = $this->session->getAttachToPage();
		return $this->urlPrefix . $attachToPage->getRedirectURL() . '&action=AttachFile&rvt=' . $this->rvt . '&fileName=' . rawurlencode($fileName);
	}

	private static function getViewUserPageCommandURL($userName)
	{
		global $wgScriptPath;

		return $wgScriptPath . '/index.php/User:'  . $userName;
	}

}

## ::END::
