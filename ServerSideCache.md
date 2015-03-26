PageAttachment extension uses cache to store page attachment list and other data.

By default MediaWiki cache is used.  Consult MediaWiki cache setup documentation to choose and setup cache appropriate for your installation.

**Recommendation:** Whenever possible, setup & use MediaWiki's cache.

For `[small installation`], you can choose not to use any cache.  In this case, no configuration changes for MediaWiki cache is necessary.

For `[medium to large installation`], it is recommended to use some form of caching.  Specifically, use one of the caching options provided by MediaWiki.

For `[single server`] installation, you can choose to use Page Attachment extension's internal cache implementations, either SQLite3 or Database (MediaWiki).

**Note:** List caching will sometime display incosistent data to users other than the user performing the add/update/remove action.