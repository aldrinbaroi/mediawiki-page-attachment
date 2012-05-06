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

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
} 

#
# The following are example overrides & additional settings.
#

#
# Allowed Namespaces 
#
# Addtional three (3) namespaces are allowed to have attachments
#
$wgPageAttachment_allowedNameSpaces[] = NS_TALK;
$wgPageAttachment_allowedNameSpaces[] = NS_USER;
$wgPageAttachment_allowedNameSpaces[] = NS_USER_TALK;

#
# CSS File
#
# Common CSS file is overriden
$wgPageAttachment_cssFileCommon['default'] = 'my-common.css';
# 'myskin' skin spcific CSS files
$wgPageAttachment_cssFileCommon['myskin'] = 'myskin-common.css';
$wgPageAttachment_cssFileLTR['myskin']    = 'myskin-ltr-lang.css';
$wgPageAttachment_cssFileRTL['myskin']    = 'myskin-rtl-lang.css';

#
# Image Files
# 
# 'myskin' skin spcific image files
$wgPageAttachment_imgAddUpdateAttachment['myskin'] = 'myskin-add-attachment-16x16.png';
$wgPageAttachment_imgBrowseSearchAttach['myskin']  = 'myskin-browse-search-16x16.png';
$wgPageAttachment_imgRemoveAttachment['myskin']    = 'myskin-remove-16x16.png';

#
# Column Widths : Attachment list display section
#
# 'vector' skin specific column widths
$wgPageAttachment_colWidth['myskin'][] = 50;
$wgPageAttachment_colWidth['myskin'][] = 12;
$wgPageAttachment_colWidth['myskin'][] = 17;
$wgPageAttachment_colWidth['myskin'][] = 17;
$wgPageAttachment_colWidth['myskin'][] = 4;

#
# Status Message Format
#
# Overriden message formant
$wgPageAttachment_statusMessageFormat['default'] = '-=< STATUS_MESSAGE >=-';
# 'myskin' skin specific message format
$wgPageAttachment_statusMessageFormat['myskin'] = '*[ STATUS_MESSAGE ]*';

#
# Permissions
# 
# Only 'admin' user is allowed to remove attachments 
$wgPageAttachment_permissions['remove'      ]['group']['sysop'] = false;
$wgPageAttachment_permissions['remove'      ]['group']['user' ] = false;
$wgPageAttachment_permissions['remove'      ]['user' ]['admin'] = true;

#
# Server Cache - Page Attachment List & Data
#
# - Use internal SQLite3 cache
#
$wgPageAttachment_useInternalCache = true;
$wgPageAttachment_internalCacheType = 'SQLite3';
$wgPageAttachment_sqlite3CacheDirectory = '/Users/aldrin/Sites/mw/cache';
#
# - Use internal MySQL cache
#
$wgPageAttachment_useInternalCache = true;
$wgPageAttachment_internalCacheType = 'MySQL';
#
#
# Browser Cache - Page Attachment List
#
# Set Ajax cache to 60 seconds
$wgPageAttachment_ajaxCacheDuration = 60;

#
# Attachment Category
#
$wgPageAttachment_attachmentCategory['setOnUpload']            = true;
$wgPageAttachment_attachmentCategory['mustSet']                = false;
$wgPageAttachment_attachmentCategory['defaultCategory']        = 'MyCategory 2';
$wgPageAttachment_attachmentCategory['allowedCategories']      = 'PredefinedCategoriesOnly';
#$wgPageAttachment_attachmentCategory['allowedCategories']     = 'MediaWikiCategoriesOnly';
#$wgPageAttachment_attachmentCategory['allowedCategories']     = 'BothPredefinedAndMediaWikiCategories';
$wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'MyCategory 1';
$wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'MyCategory 2';
$wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'MyCategory 3';

## ::END:: 




