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
