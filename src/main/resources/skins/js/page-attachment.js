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
 * @param attachmentName
 * @param rvt
 * @param confirmRemoveMessage
 */
function pageAttachment_removePageAttachmentPermanently(attachmentName, rvt, confirmRemoveMessage)
{
	if (confirm(confirmRemoveMessage))
	{
		var pageTitle = pageAttachment_getAttachToPageTitle();
		var div = document.getElementById("PageAttachment");
		sajax_do_call("\\PageAttachment\\Ajax\\removePageAttachmentPermanently",
			[
					pageTitle, attachmentName, rvt
			], div);
	}
}
/**
 * 
 * @param message
 */
function pageAttachment_unableToFulfillRemoveAttachmentPermanentlyRequest(message)
{
	alert(message);
}
/**
 * 
 * @param evt
 */
function pageAttachment_onLoad(evt)
{
	pageAttachment_loadPageAttachments();
}

/**
 * 
 * @param evt
 */
function pageAttachment_onPageShow(evt)
{
	var event = window.event ? window.event : evt;
	if (event.persisted)
	{
		pageAttachment_loadPageAttachments();
	}
}

/**
 * 
 */
function pageAttachment_registerOnLoad()
{
	if (window.addEventListener)
	{
		window.addEventListener("load", pageAttachment_onLoad, false);
	}
	else if (window.attachEvent)
	{
		// For IE < version 9.0
		window.attachEvent("onload", pageAttachment_onLoad, false);
	}
	else
	{
		// For older browsers, IE6 & the likes
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
}


/**
 * 
 */
function pageAttachment_registerOnPageShow()
{
	if (window.addEventListener)
	{
		window.addEventListener("pageshow", pageAttachment_onPageShow, false);
	}
	else 
	{
		// pageshow event not supported
	}
}

pageAttachment_registerOnLoad(); 
pageAttachment_registerOnPageShow();

// ::End::
