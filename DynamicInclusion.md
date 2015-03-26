# Dynamic Inclusion #

The magic word `__ATTACHMENTS__` can be used within a Wiki page content to allow attachments on a page when that page is not configured to have attachments through either namespace or, category settings.

By default, this option is turned off. To turn it on, set the following to "true"
```
$wgPageAttachment_allowAttachmentsUsingMagicWord = false;
```

If you prefer a different magic word other than `__ATTACHMENTS__` then set the following to the keyword of you choice.
```
$wgPageAttachment_magicWordToAllowAttachments = '__ATTACHMENTS__';
```