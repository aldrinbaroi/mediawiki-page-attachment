**Watch Attachments**

On wathced pages, to send notification on attachment activity:

  * Enable MediaWiki email
    * See [MediaWiki Manual - $wgEnableEmail](http://www.mediawiki.org/wiki/Manual:$wgEnableEmail)
    * See [MediaWiki Manual - Configuration settings](http://www.mediawiki.org/wiki/Manual:Configuration_settings)
  * In the site specific configuration file, set the following:
```
$wgPageAttachment_enableNotification = true;
```

To send the emails using MediaWiki Job Queue

  * Setup MediaWiki's Job Queue processing
    * See [MediaWiki Manual - Job queue](http://www.mediawiki.org/wiki/Manual:Job_queue)
  * In the site specific configuration file, set the following:
```
$wgPageAttachment_useJobQueueForNotification = true;
```

Notification emails are formatted using a template. Two templates are included:

  * Plain Text
  * HTML

**Note:** You must specify which template to use.

To use plain-text template, set the following:
```
$wgPageAttachment_messageFormat = 'plaintext';
```

To use HTML template, set the following:
```
$wgPageAttachment_messageFormat = 'html';
```