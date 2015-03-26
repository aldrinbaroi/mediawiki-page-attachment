# Add Missing File Links #

PageAttachment extension, prior to the following versions, did not set the file links (back links) for the attachment files.

  * Prior to 3.1 version
    * 3.x series for MediaWiki 1.19, 1.20
  * Prior to 2.3 version
    * 2.x series for MediaWiki 1.18
  * Prior to 1.5 version
    * 1.x series for MediaWiki 1.16, 1.27

The name of the maintenance script is:
```
  AddMissingFileLinks.php
```
The script is located under the following directory:
```
  [MediaWiki Install Directory]/extensions/PageAttachment/maintenance/AddMissingFileLinks.php
```

To update the file links for existing attachments, you can run the script using the following command:
```
  php extensions/PageAttachment/maintenance/AddMissingFileLinks.php
```