# Features #
  * View attachments
  * Upload & attach a file
  * Browse/search for existing (uploaded) files and attach
  * Download an attachment
  * Remove an attachment (Does not delete the file from MediaWiki repository)
    * Remove an attachment permanently (Deletes the file from MediaWiki repository)
      * **NOTE:** MediaWiki archives the deleted files. You would have to run the maintenance script <br /><i>php deleteArchivedFiles.php --delete</i><br />to remove the files from the archive.  See: [Manual:DeleteArchivedFiles](http://www.mediawiki.org/wiki/Manual:DeleteArchivedFiles.php)
  * View an attachment file's history
  * View audit log
    * For a specific attachment
    * For all attachmnets for a page
  * Auto remove an attachment when the attachment file is deleted from MediaWiki repository
  * Auto restore an attachment when a file deleted from MediaWiki repository is restored and if it was attached to any page
  * Set attachment category during upload
  * Watch Attachments
  * <font color='red'>New in 3.0.0, 2.2.0 versions</font>
    * Specify default pages to have attachments using category names
    * Specify exclusion pages through configuration settings
    * Allow attachments to a page through the use of "`__ATTACHMENTS__`" token
    * Exclude attachments to a page through the use of "`__NOATTACHMENTS__`" token
  * <font color='red'>New in 3.1.0, 2.3.0, and 1.5.0 versions</font>
    * Auto set file links (back links)
      * Maintenance script to update file links for existing attachments is included
        * You can run the script using the following command:<br /><i>php extensions/PageAttachment/maintenance/AddMissingFileLinks.php</i><br />


**Note: By default, followings are turned off**
  * Audit logging
  * Set attachment category during upload
  * Permanent file deletion
  * Watch Attachments
  * Default pages to have attachments using category names
  * Exclusion pages through configuration settings
  * Allow attachments to a page through the use of "`__ATTACHMENTS__`" token
  * Exclude attachments to a page through the use of "`__NOATTACHMENTS__`" token