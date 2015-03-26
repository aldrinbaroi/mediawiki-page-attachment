**Status Message Format**

The default format applies to all skins. You can override the default and/or provide skin specific formats.
The default status message format is:
```
$wgPageAttachment_statusMessageFormat['default'] = '&nbsp;&#187; STATUS_MESSAGE &#171;&nbsp;';
```
The foregoing is rendered as the following:

> » STATUS\_MESSAGE «


The STATUS\_MESSAGE is replaced with the actual message.
To change the default format, replace the YOUR\_HTML\_CODE with your actual HTML code. Please, leave the STATUS\_MESSAGE token in:
```
$wgPageAttachment_statusMessageFormat['default'] = 'YOUR_HTML_CODE STATUS_MESSAGE YOUR_HTML_CODE';
```
To specify skin specific format, add additional settings and replace 'default' with the actual skin name. Example:
```
$wgPageAttachment_statusMessageFormat['skin name'] = 'YOUR_HTML_CODE STATUS_MESSAGE YOUR_HTML_CODE';
```