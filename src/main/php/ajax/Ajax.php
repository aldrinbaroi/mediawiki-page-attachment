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
 * @file
 * @ingroup Extensions
 * 
 */

namespace PageAttachment\Ajax;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

function setAjaxCacheDuration(&$ajaxResponse)
{
	global $wgPageAttachment_ajaxCacheDuration;

	if (isset($wgPageAttachment_ajaxCacheDuration) && $wgPageAttachment_ajaxCacheDuration > 0)
	{
		$ajaxResponse->setCacheDuration($wgPageAttachment_ajaxCacheDuration);
	}
	else
	{
		$ajaxResponse->setCacheDuration(false);
	}
}

function getPageAttachments($pageTitle)
{
	$requestHandler = new \PageAttachment\RequestHandler();
	$ajaxResponse = new \AjaxResponse($requestHandler->getAttachments($pageTitle));
	setAjaxCacheDuration($ajaxResponse);
	return $ajaxResponse;
}

function removePageAttachment($pageTitle, $attachmentName, $rvt)
{
	$requestHandler = new \PageAttachment\RequestHandler();
	$ajaxResponse = new \AjaxResponse($requestHandler->removeAttachment($pageTitle, $attachmentName, $rvt));
	setAjaxCacheDuration($ajaxResponse);
	return $ajaxResponse;
}

function removePageAttachmentPermanently($pageTitle, $attachmentName, $rvt)
{
	$requestHandler = new \PageAttachment\RequestHandler();
	$ajaxResponse = new \AjaxResponse($requestHandler->removeAttachmentPermanently($pageTitle, $attachmentName, $rvt));
	setAjaxCacheDuration($ajaxResponse);
	return $ajaxResponse;	
}

## ::END::

