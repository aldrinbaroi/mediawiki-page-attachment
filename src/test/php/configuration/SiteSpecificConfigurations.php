<?php

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
} 

/**
 * Use this config file for testing settings
 * 
 */

# 
# Show users real name
# 
#$wgPageAttachment_showUserRealName = false;

#
# Server Cache - Page Attachment List & Data
#
# - Use SQLite3 internal cache
#
#$wgPageAttachment_useInternalCache = true;
#$wgPageAttachment_internalCacheType = 'SQLite3';
#$wgPageAttachment_sqlite3CacheDirectory = '/Users/aldrin/Sites/mw/cache';
#
# - Use MySQL internal cache
#
#$wgPageAttachment_useInternalCache = true;
#$wgPageAttachment_internalCacheType = 'Database';
#
# $wgPageAttachment_permissions['view'        ]['loginRequired'] = true;
#$wgPageAttachment_permissions['remove'      ]['group']['sysop'] = false;
#$wgPageAttachment_permissions['remove'      ]['group']['user' ] = false;
#$wgPageAttachment_permissions['remove'      ]['user' ]['Admin'] = true;

#$wgPageAttachment_permissions['view'        ]['loginRequired'] = true;
#$wgPageAttachment_allowedNameSpaces[] = NS_FILE;

$wgPageAttachment_enableAuditLog = true;

$wgPageAttachment_removeAttachments['permanently']      = false;
$wgPageAttachment_removeAttachments['ignoreIfAttached'] = true;
$wgPageAttachment_removeAttachments['ignoreIfEmbedded'] = true;

#
# Attachment Category
#
$wgPageAttachment_attachmentCategory['setOnUpload']            = true;
$wgPageAttachment_attachmentCategory['mustSet']                = false;
$wgPageAttachment_attachmentCategory['defaultCategory']        = 'MyCategory 2';
#$wgPageAttachment_attachmentCategory['allowedCategories']      = 'PredefinedCategoriesOnly';
#$wgPageAttachment_attachmentCategory['allowedCategories']     = 'MediaWikiCategoriesOnly';
$wgPageAttachment_attachmentCategory['allowedCategories']     = 'BothPredefinedAndMediaWikiCategories';
$wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'MyCategory 1';
$wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'MyCategory 2';
$wgPageAttachment_attachmentCategory['predefinedCategories'][] = 'MyCategory 3';

#
# Notificaiton 
#
$wgPageAttachment_enableNotification = true;
$wgPageAttachment_useJobQueueForNotification = false;
$wgPageAttachment_messageFormat = 'plaintext';

# Allowed categories
//$wgPageAttachment_allowedCategories[] = 'Docs';

# Excluded Pages
//$wgPageAttachment_excludedPages[] = 'Main Page';
//$wgPageAttachment_excludedPages[] = 'Third Page';
//$wgPageAttachment_excludedPages[] = 'Talk:Third Page';


$wgPageAttachment_allowAttachmentsUsingMagicWord = true;
$wgPageAttachment_disllowAttachmentsUsingMagicWord = false;



