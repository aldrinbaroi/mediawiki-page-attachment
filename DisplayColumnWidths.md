**Display Column Widths**

You can either specify skin & language specific column widths or, override the default or, both. For skin spcific settings, specify skin name, instead of 'default'.  Similarly, for language specific settings, specify language code instead of 'default'.
```
$wgPageAttachment_colWidth['Skin Name']['Language Code']['Column Name']
```

The defaults for Header & Attachment List Rows:
```
$wgPageAttachment_colWidth['default']['default']['Name'        ] = 34;
$wgPageAttachment_colWidth['default']['default']['Description' ] = 23;
$wgPageAttachment_colWidth['default']['default']['Size'        ] = 10; 
$wgPageAttachment_colWidth['default']['default']['DateUploaded'] = 14;
$wgPageAttachment_colWidth['default']['default']['UploadedBy'  ] = 14;
$wgPageAttachment_colWidth['default']['default']['Buttons'     ] = 7;  
```