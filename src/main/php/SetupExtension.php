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

$dir = dirname(__FILE__) . '/';
require_once($dir . 'Version.php');

$wgExtensionCredits['parserhook'][] = array(
     'path' => __FILE__,
     'name' => 'PageAttachment',
     'author' => 'Aldrin Edison Baroi',
     'url' => 'http://www.mediawiki.org/wiki/Extension:PageAttachment',
     'descriptionmsg' => 'PageAttachmentExtensionDescription',
     'version' => $wgPageAttachment_version
);

## Internationalization 
$wgExtensionMessagesFiles['PageAttachment_Messages'] = $dir . 'messages/SetupMessages.php';

## Load System Configurations
require_once($dir . 'configuration/DefaultSystemConfigurations.php');
$siteSpecificSystemConfigurationsFile = $dir . 'configuration/SiteSpecificSystemConfigurations.php';
if (file_exists($siteSpecificSystemConfigurationsFile))
{
	require_once($siteSpecificSystemConfigurationsFile);
}

## Load Configurations 
require_once($dir . 'configuration/DefaultConfigurations.php');
$siteSpecificConfigurationsFile = $dir . 'configuration/SiteSpecificConfigurations.php';
if (file_exists($siteSpecificConfigurationsFile))
{
	require_once($siteSpecificConfigurationsFile);
}
require_once($dir . 'ajax/Ajax.php');

## Load Notification Templates
require_once($dir . 'template/TemplateLoader.php');

## Autoload needed MediaWiki class that is not loaded automatically
$wgAutoloadClasses['ImageListPager']                                              = $IP . '/includes/specials/SpecialListfiles.php';

## Autoload PageAttachment classes
$wgAutoloadClasses['PageAttachment\\Configuration\\StaticConfiguration']          = $dir . 'configuration/StaticConfiguration.php';
$wgAutoloadClasses['PageAttachment\\Configuration\\RuntimeConfiguration']         = $dir . 'configuration/RuntimeConfiguration.php';
$wgAutoloadClasses['PageAttachment\\Setup\\DatabaseSetup']                        = $dir . 'setup/DatabaseSetup.php';
$wgAutoloadClasses['PageAttachment\\Utility\\MediaWikiVersion']                   = $dir . 'utility/MediaWikiVersion.php';
$wgAutoloadClasses['PageAttachment\\Utility\\DateUtil']                           = $dir . 'utility/DateUtil.php';
$wgAutoloadClasses['PageAttachment\\Utility\\PsuedoTitle']                        = $dir . 'utility/PsuedoTitle.php';
$wgAutoloadClasses['PageAttachment\\Utility\\PsuedoArticle']                      = $dir . 'utility/PsuedoArticle.php';
$wgAutoloadClasses['PageAttachment\\Utility\\StringUtil']                         = $dir . 'utility/StringUtil.php';
$wgAutoloadClasses['PageAttachment\\Cache\\ICache']                               = $dir . 'cache/ICache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\Provider\\MWCacheObjWrapper']          = $dir . 'cache/provider/MWCacheObjWrapper.php';
$wgAutoloadClasses['PageAttachment\\Cache\\Provider\\SQLiteCache']                = $dir . 'cache/provider/Sqlite3Cache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\Provider\\DatabaseCache']              = $dir . 'cache/provider/DatabaseCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\AttachmentDataCache']                  = $dir . 'cache/AttachmentDataCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\AttachmentListCache']                  = $dir . 'cache/AttachmentListCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\ArticleNameCache']                     = $dir . 'cache/ArticleNameCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\CategoryListCache']                    = $dir . 'cache/CategoryListCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\UserCache']                            = $dir . 'cache/UserCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\FileCache']                            = $dir . 'cache/FileCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\PageCache']                            = $dir . 'cache/PageCache.php';
$wgAutoloadClasses['PageAttachment\\Cache\\CacheFactory']                         = $dir . 'cache/CacheFactory.php';
$wgAutoloadClasses['PageAttachment\\Cache\\CacheManager']                         = $dir . 'cache/CacheManager.php';
$wgAutoloadClasses['PageAttachment\\Security\\MediaWiki\\Upload\\IUploadPermissionChecker']                 = $dir . 'security/mediawiki/upload/IUploadPermissionChecker.php';
$wgAutoloadClasses['PageAttachment\\Security\\MediaWiki\\Upload\\AbstractUploadPermissionChecker']          = $dir . 'security/mediawiki/upload/AbstractUploadPermissionChecker.php';
$wgAutoloadClasses['PageAttachment\\Security\\MediaWiki\\Upload\\UploadPermissionChecker_MediaWiki_v1162']  = $dir . 'security/mediawiki/upload/UploadPermissionChecker_MediaWiki_v1162.php';
$wgAutoloadClasses['PageAttachment\\Security\\MediaWiki\\Upload\\UploadPermissionChecker_MediaWiki_v1170']  = $dir . 'security/mediawiki/upload/UploadPermissionChecker_MediaWiki_v1170.php';
$wgAutoloadClasses['PageAttachment\\Security\\MediaWiki\\MediaWikiSecurityManager']                         = $dir . 'security/mediawiki/MediaWikiSecurityManager.php';
$wgAutoloadClasses['PageAttachment\\Security\\MediaWiki\\MediaWikiSecurityManagerFactory']                  = $dir . 'security/mediawiki/MediaWikiSecurityManagerFactory.php';
$wgAutoloadClasses['PageAttachment\\Security\\SecurityManager']                   = $dir . 'security/SecurityManager.php';
$wgAutoloadClasses['PageAttachment\\AuditLog\\ActivityType']                      = $dir . 'auditlog/ActivityType.php';
$wgAutoloadClasses['PageAttachment\\AuditLog\\AuditLogData']                      = $dir . 'auditlog/AuditLogData.php';
$wgAutoloadClasses['PageAttachment\\AuditLog\\AuditLogManager']                   = $dir . 'auditlog/AuditLogManager.php';
$wgAutoloadClasses['PageAttachment\\AuditLog\\AuditLogPager']                     = $dir . 'auditlog/AuditLogPager.php';
$wgAutoloadClasses['PageAttachment\\AuditLog\\AuditLogViewer']                    = $dir . 'auditlog/AuditLogViewer.php';
$wgAutoloadClasses['PageAttachment\\Request\\RequestHelper']                      = $dir . 'request/RequestHelper.php';
$wgAutoloadClasses['PageAttachment\\Request\\AttachFileAction']                   = $dir . 'request/AttachFileAction.php';
$wgAutoloadClasses['PageAttachment\\Session\\Session']                            = $dir . 'session/Session.php';
$wgAutoloadClasses['PageAttachment\\Session\\Page']                               = $dir . 'session/Page.php';
$wgAutoloadClasses['PageAttachment\\Session\\PageFactory']                        = $dir . 'session/PageFactory.php';
$wgAutoloadClasses['PageAttachment\\Attachment\\AttachmentData']                  = $dir . 'attachment/AttachmentData.php';
$wgAutoloadClasses['PageAttachment\\Attachment\\AttachmentDataFactory']           = $dir . 'attachment/AttachmentDataFactory.php';
$wgAutoloadClasses['PageAttachment\\Attachment\\AttachmentManager']               = $dir . 'attachment/AttachmentManager.php';
$wgAutoloadClasses['PageAttachment\\BrowseSearch\\ImageListPager']                = $dir . 'browse-search/ImageListPager.php';
$wgAutoloadClasses['PageAttachment\\BrowseSearch\\ListFiles']                     = $dir . 'browse-search/ListFiles.php';
$wgAutoloadClasses['PageAttachment\\Upload\\UploadHelper']                        = $dir . 'upload/UploadHelper.php';
$wgAutoloadClasses['PageAttachment\\Upload\\Upload']                              = $dir . 'upload/Upload.php';
$wgAutoloadClasses['PageAttachment\\Download\\FileStreamer']                      = $dir . 'download/FileStreamer.php';
$wgAutoloadClasses['PageAttachment\\Download\\FileStreamerException']             = $dir . 'download/FileStreamerException.php';
$wgAutoloadClasses['PageAttachment\\Download\\AbstractFileStreamer']              = $dir . 'download/AbstractFileStreamer.php';
$wgAutoloadClasses['PageAttachment\\Download\\BasicFileStreamer']                 = $dir . 'download/BasicFileStreamer.php';
$wgAutoloadClasses['PageAttachment\\Download\\FileStreamerWithBasicAndDigestAuth']= $dir . 'download/FileStreamerWithBasicAndDigestAuth.php';
$wgAutoloadClasses['PageAttachment\\Download\\FileStreamerFactory']               = $dir . 'download/FileStreamerFactory.php';
$wgAutoloadClasses['PageAttachment\\Download\\DownloadManager']                   = $dir . 'download/DownloadManager.php';
$wgAutoloadClasses['PageAttachment\\UI\\HTML']                                    = $dir . 'ui/HTML.php';
$wgAutoloadClasses['PageAttachment\\UI\\Command']                                 = $dir . 'ui/Command.php';
$wgAutoloadClasses['PageAttachment\\UI\\Resource']                                = $dir . 'ui/Resource.php';
$wgAutoloadClasses['PageAttachment\\UI\\UIComposer']                              = $dir . 'ui/UIComposer.php';
$wgAutoloadClasses['PageAttachment\\UI\\WebBrowser']                              = $dir . 'ui/WebBrowser.php';
$wgAutoloadClasses['PageAttachment\\User\\User']                                  = $dir . 'user/User.php';
$wgAutoloadClasses['PageAttachment\\User\\UserManager']                           = $dir . 'user/UserManager.php';
$wgAutoloadClasses['PageAttachment\\File\\File']                                  = $dir . 'file/File.php';
$wgAutoloadClasses['PageAttachment\\File\\FileManager']                           = $dir . 'file/FileManager.php';
$wgAutoloadClasses['PageAttachment\\Category\\CategoryManager']                   = $dir . 'category/CategoryManager.php';
$wgAutoloadClasses['PageAttachment\\Category\\CategoryManagerHelper']             = $dir . 'category/CategoryManagerHelper.php';
$wgAutoloadClasses['PageAttachment\\Localization\\LocalizationHelper']            = $dir . 'localization/LocalizationHelper.php';
$wgAutoloadClasses['PageAttachment\\WatchedItem\\WatchedItem']                    = $dir . 'watcheditem/WatchedItem.php';
$wgAutoloadClasses['PageAttachment\\WatchedItem\\WatchedItemFactory']             = $dir . 'watcheditem/WatchedItemFactory.php';
$wgAutoloadClasses['PageAttachment\\Notification\\MessageComposer']               = $dir . 'notification/MessageComposer.php';
$wgAutoloadClasses['PageAttachment\\Notification\\MessageTransporter']            = $dir . 'notification/MessageTransporter.php';
$wgAutoloadClasses['PageAttachment\\Notification\\NotificationManager']           = $dir . 'notification/NotificationManager.php';
$wgAutoloadClasses['PageAttachment\\Notification\\NotificationManagerFactory']    = $dir . 'notification/NotificationManagerFactory.php';
$wgAutoloadClasses['PageAttachment\\Notification\\NotificationJob']               = $dir . 'notification/NotificationJob.php';
$wgAutoloadClasses['PageAttachment\\Notification\\Email\\EmailMessageComposer']   = $dir . 'notification/email/EmailMessageComposer.php';
$wgAutoloadClasses['PageAttachment\\Notification\\Email\\EmailTransporter']       = $dir . 'notification/email/EmailTransporter.php';
$wgAutoloadClasses['PageAttachment\\RequestHandler']                              = $dir . 'RequestHandler.php';
$wgAutoloadClasses['PageAttachment\\MagicWord\\MagicWordHandler']                 = $dir . 'magicword/MagicWordHandler.php';

## Ajax Hooks
$wgAjaxExportList[]                               = 'PageAttachment\\Ajax\\getPageAttachments';
$wgAjaxExportList[]                               = 'PageAttachment\\Ajax\\removePageAttachment';
$wgAjaxExportList[]                               = 'PageAttachment\\Ajax\\removePageAttachmentPermanently';

## Special Pages (Unlisted)
$wgSpecialPages['PageAttachmentListFiles']        = 'PageAttachment\\BrowseSearch\\ListFiles';
$wgSpecialPages['PageAttachmentUpload']           = 'PageAttachment\\Upload\\Upload';
$wgSpecialPages['PageAttachmentAuditLogViewer']   = 'PageAttachment\\AuditLog\\AuditLogViewer';

## Extension Function Hook
$wgExtensionFunctions[] = 'pageAttachment_registerEventHandlers';

## Function to register the hooks & enable custom actions
function pageAttachment_registerEventHandlers()
{
	global $wgHooks;
	global $wgActions;

	$requestHandler = new PageAttachment\RequestHandler();

	// Hooks
	$wgHooks['LoadExtensionSchemaUpdates'][]          = array($requestHandler, 'onSetupDatabase');
	$wgHooks['BeforeInitialize'][]                    = array($requestHandler, 'onBeforeInitialize');
	$wgHooks['EditPage::importFormData'][]            = array($requestHandler, 'onEditPageImportFormData');
	$wgHooks['ArticleSaveComplete'][]                 = array($requestHandler, 'onArticleSaveComplete');
	$wgHooks['BeforePageDisplay'][]                   = array($requestHandler, 'onBeforePageDisplay');
	$wgHooks['SkinAfterContent'][]                    = array($requestHandler, 'onSkinAfterContent');
	$wgHooks['SkinAfterBottomScripts'][]              = array($requestHandler, 'onSkinAfterBottomScripts');
	$wgHooks['UploadForm:initial'][]                  = array($requestHandler, 'onUploadFormInitial');
	$wgHooks['UploadForm:BeforeProcessing'][]         = array($requestHandler, 'onUploadFormBeforeProcessing');
	$wgHooks['UploadComplete'][]                      = array($requestHandler, 'onUploadComplete');
	$wgHooks['SpecialUploadComplete'][]               = array($requestHandler, 'onSpecialUploadComplete');
	$wgHooks['UserLoginComplete'][]                   = array($requestHandler, 'onUserLoginComplete');
	$wgHooks['UserLogoutComplete'][]                  = array($requestHandler, 'onUserLogoutComplete');
	$wgHooks['ArticleDelete'][]                       = array($requestHandler, 'onArticleDelete');
	$wgHooks['FileDeleteComplete'][]                  = array($requestHandler, 'onFileDeleteComplete');
	$wgHooks['FileUndeleteComplete'][]                = array($requestHandler, 'onFileUndeleteComplete');
	$wgHooks['LinksUpdate'][]                         = array($requestHandler, 'onLinksUpdate');
	$wgHooks['LinksUpdateComplete'][]                 = array($requestHandler, 'onLinksUpdateComplete');
	
	$magicWordHandler = new PageAttachment\MagicWord\MagicWordHandler();
	
	$wgHooks['MagicWordwgVariableIDs'][]              = array($magicWordHandler, 'onMagicWordwgVariableIDs');
	$wgHooks['LanguageGetMagic'][]                    = array($magicWordHandler, 'onLanguageGetMagic');
	$wgHooks['ParserBeforeInternalParse'][]           = array($magicWordHandler, 'onParserBeforeInternalParse');
	
	//
	// Dummy custom action ('attachfile') handler class to avoid "No such action" error.
	// In future actual attach file code may have to be moved into this class.
	//
	$wgActions['attachfile']                          = 'PageAttachment\\Request\\AttachFileAction';
	
}

## Register Notification Job Handler
$wgJobClasses['pageAttachmentNotification'] = 'PageAttachment\\Notification\\NotificationJob';

## ::END::
