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
 * NOTE: *** RECOMMENDATION: DO NOT MODIFY THIS FILE ***
 *       Put your site specific settings in the "SiteSpecificConfigurations.php" file
 *       
 */

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
} 

# ---------------------------------------------------------------------------
# Show user's real name
# ---------------------------------------------------------------------------
#
# By default, user's real name is displayed if available.
# 
$wgPageAttachment_showUserRealName = true;

# ---------------------------------------------------------------------------
# Allowed namespaces
# ---------------------------------------------------------------------------
# 
# You can specify which MediaWiki namespace pages can have attachments.
#
# By default, attachments are allowed to be attached to pages contained in the
# main namespace only.      
#
$wgPageAttachment_allowedNameSpaces[] = NS_MAIN; 

# ---------------------------------------------------------------------------
# Allowed categories
# ---------------------------------------------------------------------------
#
# You can specify which MediaWiki categories can have attachments.
#
# By default, no categories are configured to have attachments.
#
#$wgPageAttachment_allowedCategories[] = '';

# ---------------------------------------------------------------------------
# Excluded Pages
# ---------------------------------------------------------------------------
#
# You can specify which pages should not have attachments, even though the 
# page may have attachments through defined namespaces or categories.  This
# cannot be overriden with dynamic inclusion token __ALLOW_ATTACHMENTS__ to
# allow attachments.
#
# 1) For pages in main namespace, just specify the page name.  For example,
#    you don't want any attachment on the home/main wiki page:
#      $wgPageAttachment_excludedPages[] = 'Main Page';
# 2) For pages in namespaces other than main, include namespace as a prefix
#    separated by a colon.  For examle: suppose you have configured to have
#    attachments on talk pages, however you don't want any attachment on the
#    home/main wiki talk page: 
#      $wgPageAttachment_excludedPages[] = 'Talk:Main Page';
#
# By default, no pages are configured to be excluded.
#
#$wgPageAttachment_excludedPages[] = '';

# ---------------------------------------------------------------------------
# Dynamic Inclusion
# ---------------------------------------------------------------------------
#
# The magic word "__ATTACHMENTS__" can be used within a Wiki page content 
# to allow attachments on a page when that page is not configured to have 
# attachments through either namespace or, category settings.
#
# By default, this option is turned off.
#
$wgPageAttachment_allowAttachmentsUsingMagicWord = false;

#
# If you prefer a different magic word other than "__ATTACHMENTS__" then set 
# the following to the keyword of you choice.
#
$wgPageAttachment_magicWordToAllowAttachments = '__ATTACHMENTS__';

# ---------------------------------------------------------------------------
# Dynamic Exclusion
# ---------------------------------------------------------------------------
#
# The magic word "__NOATTACHMENTS__" can be used within a Wiki page content
# to disallow attachments on a page when that page is configured to have
# attachments through either namespace or, category settings.
#
# By default, this option is turned off.
#
$wgPageAttachment_disllowAttachmentsUsingMagicWord = false;

#
# If you prefer a different magic word other than "__NOATTACHMENTS__" then set
# the following to the keyword of you choice.
#
$wgPageAttachment_magicWordToDisallowAttachments = '__NOATTACHMENTS__';

# ---------------------------------------------------------------------------
# Date Format
# ---------------------------------------------------------------------------
# You can specify language specific date formatting option.
# Example: $wgPageAttachment_dateFormat['langCode'] = 'dateformat'
#
# For date format string patterns, consult PHP DateTime class documentaion.
#
# Date formatting is based on MediaWiki settings. The following specific date
# format is for English language only and more readable than 24HR format used
# by MediaWiki. However, you can disable this and use MediaWiki formatting by 
# unsetting $wgPageAttachment_dateFormat variable.
#
$wgPageAttachment_dateFormat['en'] = 'M d, Y h:i a';

# ---------------------------------------------------------------------------
# CSS File
# ---------------------------------------------------------------------------
# You can either specify skin specific CSS file or, override the default or,
# both. For skin spcific settings, specify skin name, instead of 'default'.
#
$wgPageAttachment_cssFileCommon['default'] = 'common.css';
$wgPageAttachment_cssFileLTR['default']    = 'ltr-lang.css';
$wgPageAttachment_cssFileRTL['default']    = 'rtl-lang.css';

# ---------------------------------------------------------------------------
# Image Files
# ---------------------------------------------------------------------------
# You can either specify skin specific image file or, override the default or,
# both. For skin spcific settings, specify skin name, instead of 'default'.
#
$wgPageAttachment_imgSpacer['default']             = 'transparent-16x16.png';
$wgPageAttachment_imgBrowseSearchAttach['default'] = 'tango-folder-saved-search-16x16.png';
$wgPageAttachment_imgUploadAndAttach['default']    = 'tango-mail-attachment-16x16.png';
$wgPageAttachment_imgAttachFile['default']         = 'tango-mail-attachment-16x16.png';
$wgPageAttachment_imgRemoveAttachment['default']   = 'tango-edit-cut-16x16.png';
$wgPageAttachment_imgViewAuditLog['default']       = 'tango-edit-find-16x16.png';
$wgPageAttachment_imgViewHistory['default']        = 'tango-system-file-manager-16x16.png';
$wgPageAttachment_imgViewMore['default']           = 'tango-go-jump-12x12.png';

# ---------------------------------------------------------------------------
# Attachment list display section
# ---------------------------------------------------------------------------
#
# :: Columns to Display ::
#
# Currently the following columns are available for display & by default all
# are displayed. You can cusotmize the display columns do the follwing:
#   1. To remove a column
#        1.1 Unset $wgPageAttachment_colToDisplay[] 
#               Examle: unset($wgPageAttachment_colToDisplay);
#        1.2 Set the columns to be displayed
#        1.3 Set the column widths $wgPageAttachment_colWidth[] so that the 
#            sum of the column withds add up to 100
#        1.4 Ensure that title row's column span total match total number of
#            columns going to be displayed
#   2. To change the order of the columns
#        The columns are displayed in the sequence added to $wgPageAttachment_colToDisplay.
#        So, to change the display order, specify the column names in the sequence
#        you would like them to be displayed.
#
$wgPageAttachment_colToDisplay[] = 'Name';
$wgPageAttachment_colToDisplay[] = 'Description';
$wgPageAttachment_colToDisplay[] = 'Size';
$wgPageAttachment_colToDisplay[] = 'DateUploaded';
$wgPageAttachment_colToDisplay[] = 'UploadedBy';
$wgPageAttachment_colToDisplay[] = 'Buttons';

#
# :: Colum Widths ::
#
# You can either specify skin & language specific column widths or, override 
# the default or, both. For skin spcific settings, specify skin name, instead
# of 'default'.  Similarly, for language specific settings, specify language
# code instead of 'default'.
#
#   $wgPageAttachment_colWidth['Skin Name']['Language Code']['Column Name']
#
# -- Header & Attachment List Rows --
#
$wgPageAttachment_colWidth['default']['default']['Name'        ] = 34;
$wgPageAttachment_colWidth['default']['default']['Description' ] = 23;
$wgPageAttachment_colWidth['default']['default']['Size'        ] = 10; 
$wgPageAttachment_colWidth['default']['default']['DateUploaded'] = 14;
$wgPageAttachment_colWidth['default']['default']['UploadedBy'  ] = 14;
$wgPageAttachment_colWidth['default']['default']['Buttons'     ] = 7;  

#
# :: Title Row Column Spans ::
#
# The title row has three columns.  So, to match total number of columns being
# displayed, "span" must be used.  If you change the number of column being
# displayed, change the column span values so that the total match the total
# number of columns to be displayed.
#
$wgPageAttachment_titleRowColSpan['default']['default']['Title'          ] = 3;
$wgPageAttachment_titleRowColSpan['default']['default']['DisplayTimeZone'] = 2;
$wgPageAttachment_titleRowColSpan['default']['default']['Buttons'        ] = 1;

#
# :: Attachment Name Column
#
# To keep the display clean, the following is setting is used to detemine the
# number of characters to display.  If the attachment name is longer than the
# maximum length specified, the name is trimed and elipses are used indicate that
# the ....
#
$wgPageAttachment_attachmentNameMaxLength = 55;

#
# :: Description Column ::
#
# To keep the display clean, the following setting is used to detemine the
# number of characters to display.  If the description is longer than the
# maximum length specified, the description is trimed and a popup is used 
# to display the whole description.
# 
$wgPageAttachment_descriptionMaxLength = 35;

#
# :: Description Popup ::
#
# Use the following to change the width & height of the description popup.
#
$wgPageAttachment_descriptionPopupWidth  = '50em';
$wgPageAttachment_descriptionPopupHeight = '7em';

# ---------------------------------------------------------------------------
# Status Message Format
# ---------------------------------------------------------------------------
# You can either specify skin specific message format or, override the default
# or, both. For skin spcific settings, specify skin name, instead of 'default'.
#
# STATUS_MESSAGE will be replaced with actual status message
#
$wgPageAttachment_statusMessageFormat['default'] = '&nbsp;&#187; STATUS_MESSAGE &#171;&nbsp;';

# ---------------------------------------------------------------------------
# Audit Log
# ---------------------------------------------------------------------------
#
# Audit log captures all attachments related activities.
#
# By default, audit logging is turned off.  
#
# To turn audit logging on, do the following:
#    1. First, set $wgPageAttachment_enableAuditLog = true, in the 
#       "SiteSpecificConfigurations.php" file
#    2. Second, run MediaWiki "update.php" maintenance program to create
#       audit log tables
#
$wgPageAttachment_enableAuditLog = false;

# ---------------------------------------------------------------------------
# Permissions - General
# ---------------------------------------------------------------------------
#
# First, MediaWiki upload permissions are checked.
# Second, the following permissions & the overrides are checked.
# View must be permitted to allow all other actions.
#
# The default permission settings:
#
#    1. Login is not required to view attachments
#    2. Login is required for all other actions
#    3. Logged in administrators can perform all the actions
#    4. Logged in users can perform all the actions   
# 
$wgPageAttachment_permissions['view'           ]['loginRequired'] = false;
$wgPageAttachment_permissions['uploadAndAttach']['loginRequired'] = true;
$wgPageAttachment_permissions['browseSearch'   ]['loginRequired'] = true;
$wgPageAttachment_permissions['remove'         ]['loginRequired'] = true;
$wgPageAttachment_permissions['download'       ]['loginRequired'] = true;
$wgPageAttachment_permissions['viewAuditlog'   ]['loginRequired'] = true;
$wgPageAttachment_permissions['viewHistory'    ]['loginRequired'] = true;
#
# :: When login is not required ::
#
$wgPageAttachment_permissions['view'           ]['allowed'] = true;
$wgPageAttachment_permissions['uploadAndAttach']['allowed'] = true;
$wgPageAttachment_permissions['browseSearch'   ]['allowed'] = true;
$wgPageAttachment_permissions['remove'         ]['allowed'] = true;
$wgPageAttachment_permissions['download'       ]['allowed'] = true;
$wgPageAttachment_permissions['viewAuditLog'   ]['allowed'] = true;
$wgPageAttachment_permissions['viewHistory'    ]['allowed'] = true;
#
# :: When login is required - Group Permission ::
#
$wgPageAttachment_permissions['view'           ]['group']['sysop'] = true;
$wgPageAttachment_permissions['uploadAndAttach']['group']['sysop'] = true;
$wgPageAttachment_permissions['browseSearch'   ]['group']['sysop'] = true;
$wgPageAttachment_permissions['remove'         ]['group']['sysop'] = true;
$wgPageAttachment_permissions['download'       ]['group']['sysop'] = true; 
$wgPageAttachment_permissions['viewAuditLog'   ]['group']['sysop'] = true;
$wgPageAttachment_permissions['viewHistory'    ]['group']['sysop'] = true;
$wgPageAttachment_permissions['view'           ]['group']['user' ] = true;
$wgPageAttachment_permissions['uploadAndAttach']['group']['user' ] = true;
$wgPageAttachment_permissions['browseSearch'   ]['group']['user' ] = true;
$wgPageAttachment_permissions['remove'         ]['group']['user' ] = true;
$wgPageAttachment_permissions['download'       ]['group']['user' ] = true;
$wgPageAttachment_permissions['viewAuditLog'   ]['group']['user' ] = true;
$wgPageAttachment_permissions['viewHistory'    ]['group']['user' ] = true;
$wgPageAttachment_permissions['view'           ]['group']['*'    ] = true; 
$wgPageAttachment_permissions['uploadAndAttach']['group']['*'    ] = false;
$wgPageAttachment_permissions['browseSearch'   ]['group']['*'    ] = false;
$wgPageAttachment_permissions['remove'         ]['group']['*'    ] = false;
$wgPageAttachment_permissions['download'       ]['group']['*'    ] = false;
$wgPageAttachment_permissions['viewAuditLog'   ]['group']['*'    ] = false;
$wgPageAttachment_permissions['viewHistory'    ]['group']['*'    ] = false;
#
# :: When login is required - User Specific Permission ::
#
# Use the following format to add user specific permissions.
#
# $wgPageAttachment_permissions['remove'      ]['user']['johndoe' ] = true;

# ---------------------------------------------------------------------------
# Permissions - Protected Pages
# ---------------------------------------------------------------------------
# 
# Permission settings for protected pages work exactly the same way as the 
# general permissions settings.  The only difference is that general permissions
# settings are checked first before check is performed for permissions for 
# protected pages.
#
# The default settings for protected pages:
#
#    1. Login is not required to view attachments
#    2. Login is required for all other actions
#    3. Logged in administrators can perform all the actions
#    4. Logged in users can only view & download attachments   
#
$wgPageAttachment_permissions['viewOnProtectedPages'           ]['loginRequired'] = false;
$wgPageAttachment_permissions['uploadAndAttachOnProtectedPages']['loginRequired'] = true;
$wgPageAttachment_permissions['browseSearchOnProtectedPages'   ]['loginRequired'] = true;
$wgPageAttachment_permissions['removeOnProtectedPages'         ]['loginRequired'] = true;
$wgPageAttachment_permissions['downloadOnProtectedPages'       ]['loginRequired'] = true;
$wgPageAttachment_permissions['viewAuditlogOnProtectedPages'   ]['loginRequired'] = true;
$wgPageAttachment_permissions['viewHistoryOnProtectedPages'    ]['loginRequired'] = true;
#
# :: When login is not required ::
#
$wgPageAttachment_permissions['viewOnProtectedPages'           ]['allowed'] = true;
$wgPageAttachment_permissions['uploadAndAttachOnProtectedPages']['allowed'] = true;
$wgPageAttachment_permissions['browseSearchOnProtectedPages'   ]['allowed'] = true;
$wgPageAttachment_permissions['removeOnProtectedPages'         ]['allowed'] = true;
$wgPageAttachment_permissions['downloadOnProtectedPages'       ]['allowed'] = true;
$wgPageAttachment_permissions['viewAuditLogOnProtectedPages'   ]['allowed'] = true;
$wgPageAttachment_permissions['viewHistoryOnProtectedPages'    ]['allowed'] = true;
#
# :: When login is required - Group Permission ::
#
$wgPageAttachment_permissions['viewOnProtectedPages'           ]['group']['sysop'] = true;
$wgPageAttachment_permissions['uploadAndAttachOnProtectedPages']['group']['sysop'] = true;
$wgPageAttachment_permissions['browseSearchOnProtectedPages'   ]['group']['sysop'] = true;
$wgPageAttachment_permissions['removeOnProtectedPages'         ]['group']['sysop'] = true;
$wgPageAttachment_permissions['downloadOnProtectedPages'       ]['group']['sysop'] = true;
$wgPageAttachment_permissions['viewAuditLogOnProtectedPages'   ]['group']['sysop'] = true;
$wgPageAttachment_permissions['viewHistoryOnProtectedPages'    ]['group']['sysop'] = true;

$wgPageAttachment_permissions['viewOnProtectedPages'           ]['group']['user' ] = true;
$wgPageAttachment_permissions['uploadAndAttachOnProtectedPages']['group']['user' ] = false;
$wgPageAttachment_permissions['browseSearchOnProtectedPages'   ]['group']['user' ] = false;
$wgPageAttachment_permissions['removeOnProtectedPages'         ]['group']['user' ] = false;
$wgPageAttachment_permissions['downloadOnProtectedPages'       ]['group']['user' ] = true;
$wgPageAttachment_permissions['viewAuditLogOnProtectedPages'   ]['group']['user' ] = false;
$wgPageAttachment_permissions['viewHistoryOnProtectedPages'    ]['group']['user' ] = false;

$wgPageAttachment_permissions['viewOnProtectedPages'           ]['group']['*'    ] = true;
$wgPageAttachment_permissions['uploadAndAttachOnProtectedPages']['group']['*'    ] = false;
$wgPageAttachment_permissions['browseSearchOnProtectedPages'   ]['group']['*'    ] = false;
$wgPageAttachment_permissions['removeOnProtectedPages'         ]['group']['*'    ] = false;
$wgPageAttachment_permissions['downloadOnProtectedPages'       ]['group']['*'    ] = false;
$wgPageAttachment_permissions['viewAuditLogOnProtectedPages'   ]['group']['*'    ] = false;
$wgPageAttachment_permissions['viewHistoryOnProtectedPages'    ]['group']['*'    ] = false;
#
# :: When login is required - User Specific Permission ::
#
# Use the following format to add user specific permissions.
#
# $wgPageAttachment_permissions['viewOnProtectedPages'           ]['user' ]['johndoe' ] = true;

# ---------------------------------------------------------------------------
# Attachment Removal - Permanently
# ---------------------------------------------------------------------------
#
# Attachment files are not permanently deleted from MediaWiki's file repository
# when attachments are removed from a page for the following reasons:
#    1. By default, MediaWiki only allow user's with admin rights to delete a file
#    2. The file maybe attached to other pages
#    3. The file maybe embedded in a Wiki page
#
# To allow permanent file deletion of a file, do the following:
#    1. Allow user to delete file through MediaWiki permission settings
#       See: http://www.mediawiki.org/wiki/Manual:User_rights
#    2. Set $wgPageAttachment_removeAttachments['permanently'] = true to
#       allow file deletion.  Please, note that if the file is attached to
#       other pages or, embedded in a page through file or media link, then
#       file removal request will not honored.
#    3. Set $wgPageAttachment_removeAttachments['ignoreIfAttached'] to true
#       to allow removal of a file even it is attached to another page.
#    4. Set $wgPageAttachment_removeAttachments['ignoreIfEmbedded'] to true
#       to allow removal of a file even it is embedded in a page through a
#       file or media link.
#
$wgPageAttachment_removeAttachments['permanently']      = false;
$wgPageAttachment_removeAttachments['ignoreIfEmbedded'] = false;
$wgPageAttachment_removeAttachments['ignoreIfAttached'] = false;

# ---------------------------------------------------------------------------
# Server Cache - Page Attachment List & Other Data 
# ---------------------------------------------------------------------------
#
# PageAttachment extension uses cache to store page attachment list and other
# data. 
#
# By default MediaWiki cache is used.  Consult MediaWiki cache setup 
# documentation to choose and setup cache appropriate for your installation.  
#
# ** RECOMMENDATION: Whenever possible setup & use MediaWiki's cache.
#
# For small installation, you can choose not to use any cache.  In this case, 
# no configuration changes for MediaWiki cache is necessary.
#
# For medium to large installation, it is recommended to use some form of
# caching.  Specifically, use one of the caching options provided by MediaWiki.
#
# For [[single server]] installation, you can choose to use Page Attachment
# extension's internal cache implementations, either SQLite3 or Database 
# (MediaWiki). To use the internal cache implementation, override the following
# in the SiteSpecificConfigurations.php file. For SQLite3 cache implementation,
# set the cache directory.  Make sure the web server has read & write permissions
# to that directory. For Database (MediaWiki), the tables would be created as
# part of the database setup.
#
# ** Note ** : List caching will sometime display incosistent data to users 
#              other than the user performing the add/update/remove action.
#
# $wgPageAttachment_internalCacheType = 'SQLite3' or 'Database'
# $wgPageAttachment_sqlite3CacheDirectory = 'provide absolute path'
#
$wgPageAttachment_useInternalCache = false;
$wgPageAttachment_internalCacheType = '';
$wgPageAttachment_sqlite3CacheDirectory = '';

# ---------------------------------------------------------------------------
# Browser Cache - Page Attachment List
# ---------------------------------------------------------------------------
#
# Ajax is used to load the page attachment list.  By default Ajax data caching
# in user's web browser is disabled. 
#
# ** NOTE **: Caching the list will sometime display inconsistent data based 
#             on when an attachment was added/update/removed and after user's
#             login/logout.
#
# duration value = 0  :: Disable Browser caching of Ajax data
# duration value > 0  :: Number of seconds to cache Ajax data in the user's browser
#
# Default duration: 0 seconds
#
$wgPageAttachment_ajaxCacheDuration = 0;

# ---------------------------------------------------------------------------
# Attachment Category
# ---------------------------------------------------------------------------
# 
# Category of the attachment file can be set during upload.
#
# By default setting attachment category during upload is not enabled.
#
# To enable, set 
#
#    $wgPageAttachment_attachmentCategory['setOnUpload'] = true;
#
# To allow a user not set a cateogry, set
#
#    $wgPageAttachment_attachmentCategory['mustSet'] = false;
#
# To force a user to set a category, set
#
#    $wgPageAttachment_attachmentCategory['mustSet'] = true;
#
# To set a category to be the default category, set
#
#    $wgPageAttachment_attachmentCategory['defaultCategory'] = 'You Default Category Name';
#
# You can restrict the category selection options to:
# 
#    1. Predefined categories
#         -- Restrict user to only predefined categories
#         -- Set the following to enable this:
#
#              $wgPageAttachment_attachmentCategory['allowedCategories'] = 'PredefinedCategoriesOnly';
#
#         -- In addition, use the following to define the predefined categories:
#
#              $wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'Your Predefined Category 1';
#              $wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'Your Predefined Category 2';
#              $wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'Your Predefined Category 3';
#
#    2. Categories that exists in MediaWiki
#         -- All the categories that exists in MediaWiki would be
#            available to the user
#         -- Not recommended if your Wiki has a large number of categories,
#            since the category selection dropdown would contain all the 
#            categories and it would be hard for user to select a particular category.
#         -- set the following to enable this:
#
#              $wgPageAttachment_attachmentCategory['allowedCategories'] = 'MediaWikiCategoriesOnly';
#
#    3. Both to predefined categories & categories that exists in MediaWiki
#         -- Combination of #1 & #2
#         -- Not recommended for the same reason as #2
#         -- set the following to enable this:
#
#              $wgPageAttachment_attachmentCategory['allowedCategories'] = 'BothPredefinedAndMediaWikiCategories';
# 
#    4. If you do not specify which category selection restriction to use, the restriction
#       would default to 'MediaWikiCategoriesOnly'
#
$wgPageAttachment_attachmentCategory['setOnUpload']              = false;
# $wgPageAttachment_attachmentCategory['mustSet']                = false;
# $wgPageAttachment_attachmentCategory['defaultCategory']        = ''; 
# $wgPageAttachment_attachmentCategory['allowedCategories']      = '';
# $wgPageAttachment_attachmentCategory['predefinedCategories'][] = '';

# ---------------------------------------------------------------------------
# Notification
# ---------------------------------------------------------------------------
#
# On watched pages, notifications can be sent out on attachment related changes.
#
# By default, notification is not enabled.
#
# To enable notifcation, do the following:
#
#    1. Setup MediaWiki to use an email server.  Consult MediaWiki's email setup 
#       documentation.
#    2. Set the following to true:
#
#           $wgPageAttachment_enableNotification = true;
#
#    3. If your Wiki has large user base then is it recommended to use MediaWiki's
#       job queue to send the notificaitons.  To use the job queue, do the following:
# 
#           1. Setup MediaWiki's job queue.  Consult MediaWiki's job queue 
#              documentation for this.
#           2. Set the following to true:
#
#                  $wgPageAttachment_useJobQueueForNotification = true;
#
#    4. Notification messages are formatted using templates.  Two templates
#       are included.  These templates are under the template folder.
#
#       To use the plain text template, set the following:
#
#           $wgPageAttachment_messageFormat = 'plaintext';
#
#       To use the HTML template, set the following:
#
#           $wgPageAttachment_messageFormat = 'html';
#
$wgPageAttachment_enableNotification = false;
# $wgPageAttachment_useJobQueueForNotification = false;
# $wgPageAttachment_messageFormat = 'plaintext';


## ::END ::
