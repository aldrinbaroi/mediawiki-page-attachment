# Excluded Pages #

You can specify which pages should not have attachments, even though the page may have attachments through defined namespaces or categories.  This cannot be overriden with dynamic inclusion token `__ALLOW_ATTACHMENTS__` to allow attachments.

  1. For pages in main namespace, just specify the page name.  For example, you don't want any attachment on the home/main wiki page:
```
$wgPageAttachment_excludedPages[] = 'Main Page'; 
```
  1. For pages in namespaces other than main, include namespace as a prefix separated by a colon.  For examle: suppose you have configured to have attachments on talk pages, however you don't want any attachment on the home/main wiki talk page:
```
$wgPageAttachment_excludedPages[] = 'Talk:Main Page';
```
  1. By default, no pages are configured to be excluded.
```
 $wgPageAttachment_excludedPages[] = '';
```