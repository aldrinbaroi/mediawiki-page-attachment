# Dynamic Exclusion #

The magic word `__NOATTACHMENTS__` can be used within a Wiki page content to disallow attachments on a page when that page is configured to have attachments through either namespace or, category settings.

By default, this option is turned off.  To turn it on, set the following to "true":
```
$wgPageAttachment_disllowAttachmentsUsingMagicWord = false;
```

If you prefer a different magic word other than `__NOATTACHMENTS__` then set the following to the keyword of you choice.
```
$wgPageAttachment_magicWordToDisallowAttachments = '__NOATTACHMENTS__';
```