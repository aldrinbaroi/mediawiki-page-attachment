**Date Format**

Date formatting is based on MediaWiki settings. You can override this and specify language specific settings. By default, for English language, MediaWiki date format is overriden with the following format:
```
$wgPageAttachment_dateFormat['en'] = 'M d, Y h:i a';
```
The rendered date looks like, e.g.:<br /> Jul 24, 2011 11:17 pm<br />
To disable this, unset the foregoing setting as follows:
```
$wgPageAttachment_dateFormat['en'] = null;
```