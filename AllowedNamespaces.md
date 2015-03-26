# Allowed Namespaces #

Attachments are allowed only on pages under configured namespaces.

By default, only "Main" namespace is enabled:
```
    $wgPageAttachment_allowedNameSpaces[] = NS_MAIN;
```
To add other namespaces, for example, "Talk", "User", and "User Talk" add the following in the site specific configuration file:
```
    $wgPageAttachment_allowedNameSpaces[] = NS_TALK;
    $wgPageAttachment_allowedNameSpaces[] = NS_USER;
    $wgPageAttachment_allowedNameSpaces[] = NS_USER_TALK;
```