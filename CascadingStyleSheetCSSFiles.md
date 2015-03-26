**Cascading Style Sheet Files**

Three "Cascading Style Sheet" (CSS) files are used to control the look & feel. The "common" applies to both "left-to-right" & "right-to-left" languages.  The others to apply to either "left-to-right" or "right-to-left" languages"

You can either specify skin specific CSS file or, override the default or, both. For skin spcific settings, specify skin name, instead of 'default'.

The default CSS files are:
```
$wgPageAttachment_cssFileCommon['default'] = 'common.css';
$wgPageAttachment_cssFileLTR['default']    = 'ltr-lang.css';
$wgPageAttachment_cssFileRTL['default']    = 'rtl-lang.css';
```