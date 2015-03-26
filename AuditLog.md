# Audit Log #

Audit logging allow you to track user activities (add, remove, download, etc.) related to attachments.

By default, audit logging is turned off.

To enable audit logging, set the following:
```
    $wgPageAttachment_enableAuditLog = true;
```


**NOTE:**

If you enable audit logging after initial installation then be sure run MediaWiki's maintenance script using the following command to create additional tables necessary to keep track of audit log entries:
```
  php maintenance/update.php
```