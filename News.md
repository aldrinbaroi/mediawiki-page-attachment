**January 21, 2011**
<br />

**New Versions Released:**
  * 3.1.0
  * 3.1.0 PHP 5.2.3
  * 2.3.0
  * 2.3.0 PHP 5.2
  * 1.5.0
  * 1.5.0 PHP 5.2

  * _See compatibility section to choose the right version for you platform_

The foregoing releases contain the following fixes/enhancements:

> <b><i>Version 3.0.0 Only</i></b>

  * Issue# 67 - Browse/Search & Attach Files function returns error message...
    * Part 1) Fixed - _IContextSource issue
      * Impacted PHP 5.2.3 only (MediaWiki 1.19 & 1.20)
    * Part 2) Fixed - Attachment option disappears on browsing beyond first page or, executing search function
      * Impacted PHP 5.3 & 5.4 (MediaWiki 1.20 Only)
    * Part 3) Fixed - Search not working_

> <b><i>Version 3.1.0, 2.3.0, 1.5.0</i></b>

  * Issue# 65 - PageAttachment not setting filelinks (backlinks)
    * Maintenance script to update file links for existing attachments is included
      * You can run the script using the following command from MediaWiki install directory:<br /><i>php extensoins/PageAttachment/maintenance/AddMissingFileLinks.php</i><br />



&lt;hr/&gt;



**December 28, 2012**
<br />

**New Versions Released:**
  * 3.0.0
  * 3.0.0 PHP 5.2.3
  * 2.2.0
  * 2.2.0 PHP 5.2

  * _See compatibility section to choose the right version for you platform_

The foregoing releases contain the following fixes/enhancements:

> <b><i>Version 3.0.0 Only</i></b>

  * Issue# 39 - Fatal error: Call to private method LinksUpdate::getExistingCategories() from context 'PageAttachment\Category\CategoryManager'
  * Issue# 40 - Page Attachments List Section Does Not Load
  * Issue# 41 - Catchable fatal error: Argument 1 passed to ImageListPager::construct() must implement interface IContextSource...
  * Issue# 47 - Get "No such action" error page

> <b><i>Version 2.2.0 & 3.0.0</i></b>

  * Issue# 50 - Ability to remove the attachment box on pages? NoAttachment for example?
  * Issue# 52 - Specify default pages to have attachments using category names
  * Issue# 53 - Specify exclusion pages through configuration settings
  * Issue# 54 - Allow attachments to a page through the use of "ATTACHMENT" token
  * Issue# 55 - Exclude attachments to a page through the use of "NOATTACHMENT" token
  * Issue# 57 - Removing attached files (utf8 pages)
  * Issue# 62 - Attaching files works but downloading attachments come accross as 0 bytes.