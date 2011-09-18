/*
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
function pageAttachment_randomToken()
{
	var tokenChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ";
	var tokenLength = 23;
	var randomToken = '';
	for ( var i = 0; i < tokenLength; i++)
	{
		var index = Math.floor(Math.random() * tokenChars.length);
		randomToken += tokenChars.substring(index, index + 1);
	}
	return randomToken;
}
/**
 * @param url
 */
function pageAttachment_openURL(url)
{
	var modifiedURL = url + "&rnd=" + pageAttachment_randomToken();
	window.location.assign(modifiedURL);
}
/**
 * 
 */
function pageAttachment_loadPageAttachments()
{
	var pageTitle = pageAttachment_getAttachToPageTitle();
	var div = document.getElementById("PageAttachment");
	if (pageAttachment_isForceReload())
	{
		randomToken = pageAttachment_randomToken();
		sajax_do_call("\\PageAttachment\\Ajax\\getPageAttachments",
			[
					pageTitle, randomToken
			], div);
	}
	else
	{
		sajax_do_call("\\PageAttachment\\Ajax\\getPageAttachments",
			[
				pageTitle
			], div);
	}
}
/**
 * @param attachmentName
 * @param rvt
 * @param confirmRemoveMessage
 */
function pageAttachment_removePageAttachment(attachmentName, rvt,
		confirmRemoveMessage)
{
	if (confirm(confirmRemoveMessage))
	{
		var pageTitle = pageAttachment_getAttachToPageTitle();
		var div = document.getElementById("PageAttachment");
		sajax_do_call("\\PageAttachment\\Ajax\\removePageAttachment",
			[
					pageTitle, attachmentName, rvt
			], div);
	}
}
/**
 * 
 */
function pageAttachment_registerOnLoad()
{
	if (typeof window.onload == "function")
	{
		var existingOnLoad = window.onload;
		window.onload = function()
		{
			existingOnLoad();
			pageAttachment_loadPageAttachments();
		};
	}
	else
	{
		window.onload = pageAttachment_loadPageAttachments;
	}
}
/**
 * 
 */
function pageAttachment_registerOnPageShow()
{
	if (typeof window.onpageshow == "function")
	{
		var existingOnPageShow = window.onpageshow;
		window.onpageshow = function()
		{
			if (event.persisted)
			{
				existingOnPageShow();
				pageAttachment_loadPageAttachments();
			}
		};
	}
	else
	{
		window.onpageshow = function()
		{
			if (event.persisted)
			{
				pageAttachment_loadPageAttachments();
			}
		};
	}
}
// ::End::
