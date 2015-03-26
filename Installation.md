# Installation Instructions #

# Prerequisites #

MediaWiki file upload functionality must be enabled for this extension to work.  If file upload is not enabled, only "view" activity is allowed.

Please consult: [Configuring MediaWiki File Uploads](http://www.mediawiki.org/wiki/Manual:Configuring_file_uploads)

# Installation #

**Step 1**

Download a PageAttachment ZIP file suitable for your platform. See [Download Section](Download.md)

**Step 2**

Unzip the downloaded ZIP file under MediaWiki's extension directory.  See [Manual:Extensions](http://www.mediawiki.org/wiki/Manual:Extensions) for more details.

**Step 3**

Add the following to [LocalSettings.php](http://www.mediawiki.org/wiki/Manual:LocalSettings.php):
```
  require_once("$IP/extensions/PageAttachment/SetupExtension.php");
```
See [Manual:LocalSettings.php](http://www.mediawiki.org/wiki/Manual:LocalSettings.php) for more details.

**Step 4**

Review the following file for available & default configurations:
```
  configuration/DefaultConfigurations.php
```
You can also review the configuration details on this Wiki. See [Configuration](Configuration.md)

**Step 5**

To override defaults and to set site specific configurations, create and update the following file:
```
  configuration/SiteSpecificConfigurations.php
```
Alternatively, you can copy the example file from "example-configuration" directory, remove the examples and add your site specific settings.

For example see the following file contained in the downloaded package:
```
  exmaple-configuration/SiteSpecificConfigurations.php
```
**Step 6**

Run MediaWiki's update script to create required database tables.
```
  php maintenance/update.php
```
Please Consult: [Manual:Update](http://www.mediawiki.org/wiki/Manual:Update.php)


<br />
**NOTE: Installation Issues**

Please see [Installation Issues](InstallationIssues.md) section for answers on frequently encountered installation issues. If your issue is not covered, please open a discussion on [PageAttachment's Forum](http://groups.google.com/group/mediawiki-page-attachment-discuss) that way I'll get a notification of your comment.

If you believe, you encountered a bug, please open a bug report at the following link [PageAttachment Issues](http://code.google.com/p/mediawiki-page-attachment/issues/list)