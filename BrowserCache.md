**Browser cache**

Ajax is used to load the page attachment list.

By default Ajax data caching in user's web browser is disabled. The reason for this that Ajax caching will sometime cause inconsistent data display based on when an attachment was added/update/removed and after user's login/logout.

If you want to enable Ajax caching, set the following in the site-specific configuration file:
```
    $wgPageAttachment_ajaxCacheDuration = N;

    N = Number of seconds to cache Ajax data in the user's browser.
```