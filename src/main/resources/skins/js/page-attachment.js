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
 * @param x
 * @param y
 * @returns
 */
function pageAttachment_Position(x, y)
{
	this.x = x;
	this.y = y;
	
	this.getX = function()
	{
		return this.x;
	}
	
	this.getY = function()
	{
		return this.y;
	}
}
/**
 * 
 * @param element
 * @returns
 */
function pageAttachment_getPosition(element)
{
	var e = element;
    var left = e.offsetLeft;
    while ((e = e.offsetParent) != null) 
    {
        left += e.offsetLeft;
    }
    var e = element;
    var top = e.offsetTop;
    while ((e = e.offsetParent) != null) 
    {
        top += e.offsetTop;
    }
    this.x = left;
    this.y = top + element.offsetHeight;
    this.w = element.offsetWidth;
    this.h = element.offsetHeight;
    return new pageAttachment_Position(x, y);
}
/**
 * 
 * @param fontFamily
 * @param fontSize
 * @returns
 */
function pageAttachment_FontInfo(fontFamily, fontSize)
{
	this.fontFamily = fontFamily;
	this.fontSize = fontSize;
	
	this.getFontFamily = function()
	{
		return this.fontFamily;
	}
	
	this.getFontSize = function()
	{
		return this.fontSize;
	}
}
/**
 * 
 * @returns {pageAttachment_FontInfo}
 */
function pageAttachment_getBodyContentFontInfo()
{
	var fontFamily = '';
	var fontSize = '';
	var bodyContent = document.getElementById("bodyContent");
	if (typeof document.defaultView != 'undefined' && typeof document.defaultView['getComputedStyle'] != 'undefined') 
	{
		var computedStyle = document.defaultView.getComputedStyle(bodyContent, null);
		fontFamily = computedStyle.getPropertyValue("font-family");
		fontSize = computedStyle.getPropertyValue("font-size");
	} 
	else 
	{
		computedStyle = bodyContent.currentStyle;
		fontFamily = computedStyle["fontFamily"];
		fontSize = computedStyle["fontSize"];
	}
	return new pageAttachment_FontInfo(fontFamily, fontSize);
}
/**
 * 
 * @param element
 * @param popupWidth
 * @param popupHeight
 * @param text
 */
function pageAttachment_showPopup(element, popupWidth, popupHeight, text, rtlLang)
{
	var pos = pageAttachment_getPosition(element);
	var popup = document.createElement("div");
	var id = document.createAttribute("id");
    var style = document.createAttribute("style");
	var txt   = document.createTextNode(new String(text));
	popup.setAttributeNode(id);
	popup.setAttributeNode(style);
	popup.appendChild(txt);
	popup.id = "PageAttachment_Popup";
	var fi = pageAttachment_getBodyContentFontInfo();
	popup.style.cssText = "top:" + pos.getY() + "px;left:" + pos.getX() + "px;width:" + popupWidth + 
	                      ";font-family:" + fi.getFontFamily() + ";font-size:" + fi.getFontSize();
	if (rtlLang == true)
	{
		popup.style.cssText += ";text-align:right;";
	}
	else
	{
		popup.style.cssText += ";text-align:left;";
	}
	document.body.appendChild(popup);
}
/**
 * 
 * @param element
 */
function pageAttachment_removePopup(element)
{
	var popup = document.getElementById("PageAttachment_Popup");
	var parentNode = popup.parentNode;
	parentNode.removeChild(popup);
}
/**
 * 
 * @param html
 */
function pageAttachment_renderAttachmentSection(html)
{
	var e = document.getElementById('PageAttachment');
	e.innerHTML = html;
}
/**
 * 
 * @param functionName
 */
function pageAttachment_makeAjaxCall(functionName)
{
	var args = Array.prototype.slice.call(arguments, 0);
	args.shift();
	$.get(
	        mw.util.wikiScript(), 
	        {
	                action: 'ajax',
	                rs:     functionName,
	                rsargs: args 
	        },
	        pageAttachment_renderAttachmentSection
	);
}
/**
 * 
 */
function pageAttachment_addAttachmentListBox()
{
	var attachmentListBox = document.getElementById("PageAttachment");
	if (attachmentListBox == null)
	{
		attachmentListBox = document.createElement("div");
		var id = document.createAttribute("id");
		attachmentListBox.setAttributeNode(id);
		attachmentListBox.id = "PageAttachment";
		var pageAttachmentContainer = document.getElementById("PageAttachmentContainer");
		pageAttachmentContainer.appendChild(attachmentListBox);
	}
}
/**
 * 
 */
function pageAttachment_loadPageAttachments()
{
	if (typeof pageAttachment_isLoadPageAttachments == "function")
	{	
		if (pageAttachment_isLoadPageAttachments())
		{
			pageAttachment_addAttachmentListBox();
			var pageTitle = pageAttachment_getAttachToPageTitle();
			var div = document.getElementById("PageAttachment");
			if (pageAttachment_isForceReload())
			{
				randomToken = pageAttachment_randomToken();
				pageAttachment_makeAjaxCall("PageAttachment\\Ajax\\getPageAttachments", pageTitle, randomToken);
			}
			else
			{
				pageAttachment_makeAjaxCall("PageAttachment\\Ajax\\getPageAttachments", pageTitle);
			}
		}
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
		pageAttachment_makeAjaxCall("PageAttachment\\Ajax\\removePageAttachment", pageTitle, attachmentName, rvt);
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
		pageAttachment_makeAjaxCall("PageAttachment\\Ajax\\removePageAttachmentPermanently", pageTitle, attachmentName, rvt);
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
