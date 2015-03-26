**PageAttachment's Internal Cache**

**Note:** Not recommended for production use

You can use one of the two internal cache implementations.

  * SQLite3 Cache
  * Database Cache

Use internal cache implementations for development & testing purposes only.

To use SQLite3 internal cache:
  * Set the following in the site-specific configuration file:
```
    $wgPageAttachment_useInternalCache = true;
    $wgPageAttachment_internalCacheType = 'SQLite3';
    $wgPageAttachment_sqlite3CacheDirectory = 'Absolute path to a directory where web server has read-write access';
```
To use Database cache:
  * Set the following in the site-specific configuration file:
```
    $wgPageAttachment_useInternalCache = true;
    $wgPageAttachment_internalCacheType = 'Database';
```
  * Run MediaWiki's update script to create the required database tables
```
    php maintenance/update.php 
```