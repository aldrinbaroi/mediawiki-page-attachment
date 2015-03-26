An attachment, when removed, is not permanently deleted from MediaWiki's repository.

To allow permanent deletion of a file, when the attachment is removed, do the following:

  * Allow user to delete file through MediaWiki permission settings
    * See: [Manual:User rights](http://www.mediawiki.org/wiki/Manual:User_rights)
  * Set the following to allow file deletion:
```
  $wgPageAttachment_removeAttachments['permanently'] = true;
```

  * Please, note that if the file is attached to other pages or, embedded in a page through file or media link, then file removal request will not honored.
  * Set the following to allow removal of a file even it is attached to another page:
```
  $wgPageAttachment_removeAttachments['ignoreIfAttached'] = true;
```

  * Set the following to allow removal of a file even it is embedded in a page through a file or media link:
```
  $wgPageAttachment_removeAttachments['ignoreIfEmbedded'] = true;
```


**NOTE:**

MediaWiki archives the deleted files. You would have to run the following maintenance script to remove the files from the archive:
```
  php deleteArchivedFiles.php --delete
```
See: [Manual:DeleteArchivedFiles](http://www.mediawiki.org/wiki/Manual:DeleteArchivedFiles.php)