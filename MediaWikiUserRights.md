# MediaWiki User Rights #

The following MediaWiki user rights must be set for PageAttachment extension to function correctly.

To enable attaching file directly from upload:

  * Enable file upload
    * See [MediaWiki Manual - Configuring file uploads](http://www.mediawiki.org/wiki/Manual:Uploads)

For watch (attachment change activity) notification:

  * Enable Email
    * See [MediaWiki Manual - $wgEnableEmail](http://www.mediawiki.org/wiki/$wgEnableEmail) and
    * See [MediaWiki Manual - Configuration settings](http://www.mediawiki.org/wiki/Configuration_settings)

The following would block attaching file directly from upload:

  * User is blocked
  * Wiki is in readonly mode