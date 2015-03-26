**Icon Image Files**

The default icons apply to all skins. You can override the default and/or provide skin specific icons.

The default icon image files are:
```
$wgPageAttachment_imgSpacer['default']             = 'transparent-16x16.png';
$wgPageAttachment_imgBrowseSearchAttach['default'] = 'tango-folder-saved-search-16x16.png';
$wgPageAttachment_imgUploadAndAttach['default']    = 'tango-mail-attachment-16x16.png';
$wgPageAttachment_imgAttachFile['default']         = 'tango-mail-attachment-16x16.png';
$wgPageAttachment_imgRemoveAttachment['default']   = 'tango-edit-cut-16x16.png';
$wgPageAttachment_imgViewAuditLog['default']       = 'tango-edit-find-16x16.png';
$wgPageAttachment_imgViewHistory['default']        = 'tango-system-file-manager-16x16.png';
```
To provide skin specific icons, add additional settings and replace the 'default' with the skin name.

**Note:** The included image files are obtained from [Tango Desktop Project](http://tango.freedesktop.org/Tango_Desktop_Project) and resized.