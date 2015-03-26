# Installation Issues #

<h3>NOTE:</h3>

  * If your issue is not covered, please open a discussion on [PageAttachment's Forum](http://groups.google.com/group/mediawiki-page-attachment-discuss) that way I'll get a notification of your comment.
  * If you believe, you encountered a bug, please open a bug report at the following link [PageAttachment Issues](http://code.google.com/p/mediawiki-page-attachment/issues/list)

<h3>Installation Issues</h3>

  1. **I have a blue line on the bottom of the pages, but no option to attach a file**
    1. Make sure you are using compatible versions of MediaWiki, PageAttachment, and PHP.  See [Compatibility](Compatibility.md) section for more details.
    1. Be sure to run MediaWiki's maintenance script to create the tables necessary for PageAttachment to function
```
    php maintenance/update.php 
```
      * <font color='red'>NOTE:</font> If you run Web Update, for some reason it does not create the tables. So, be sure to run "update.php" from the command line as specified in step# 2
    1. Make sure JavaScript is enabled in the Web Browser
    1. Make sure your Web Browser accepts "Cookie"

