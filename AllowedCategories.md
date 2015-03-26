# Allowed Categories #

You can specify which MediaWiki categories can have attachments.

By default, no categories are configured to have attachments.

To add a category, for example, "Documentation", where all the pages belonging to "Documentation" category should have attachment option, add the following in the site specific configuration file:
```
    $wgPageAttachment_allowedCategories[] = 'Documentation';
```