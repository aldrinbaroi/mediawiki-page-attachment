# Page Attachment User Rights #
PageAttahcment has very flexible user rights/permissions setting option.

**Note:**
  * View must be permitted to allowed all other actions
  * All users & groups must be valid MediaWiki users & groups
  * Few default settings are included:
    * [Default settings for Normal Pages](PageAttachmentUserRightsOnNormalPages.md)
    * [Default settings for Protected Pages](PageAttachmentUserRightsOnProtectedPages.md)
      * For definition of protected page, see MediaWiki's help on protected pages: [Help:Protected pages](http://www.mediawiki.org/wiki/Help:Protected_pages)

The following keywords are used to identify the actions:

| **Action**                                 | **Keyword for Normal Pages**   | **Keyword for Protected Pages**    |
|:-------------------------------------------|:-------------------------------|:-----------------------------------|
| View                                     | view                         | viewOnProtectedPages             |
| Upload a file and attach                 | uploadAndAttach              | uploadAndAttachOnProtectedPages  |
| Browse/search for a file and then attach | browseSearch                 | browseSearchOnProtectedPages     |
| Remove                                   | remove                       | removeOnProtectedPages           |
| Download                                 | download                     | downloadOnProtectedPages         |
| View audit log                           | viewAuditLog                 | viewAuditLogOnProtectedPages     |
| View history                             | viewHistory                  | viewHistoryOnProtectedPages      |


The following keyword are used to identify whether the rights/permission setting is for a group or user (same as MediaWiki):

| **Group/User**  | **Keyword** |
|:----------------|:------------|
| Group         | group     |
| User          | user      |


The following keywords are used to identify user groups (same as MediaWiki):

| **Group**       | **Keyword/Symbol** |
|:----------------|:-------------------|
| All           | `*`              |
| User          | user             |
| Administrator | sysop            |


First, set whether for a particular action, user must login or not.  Replace <Action Keyword> with actual action keyword.

```
$wgPageAttachment_permissions['<Action Keyword>' ]['loginRequired'] = true;

Example:

$wgPageAttachment_permissions['view'             ]['loginRequired'] = false;

```
  * Replace <Action Keyword> with actual action keyword


If login is NOT required, then use the following format to set rights/permissions:

```
$wgPageAttachment_permissions['<Action Keyword>' ]['allowed'] = true;

Example:

$wgPageAttachment_permissions['view'             ]['allowed'] = true;

```
  * Replace <Action Keyword> with actual action keyword

If login is required, then use the following format to set rights/permissions for a group:

```
$wgPageAttachment_permissions['<Action Keyword>' ]['group']['<Group Name>'] = true;

Example:

$wgPageAttachment_permissions['view'           ]['group']['sysop'       ] = true;

```
  * Replace <Action Keyword> with actual action keyword
  * Replace <Group Name> with actual group name

If login is required, then use the following format to set rights/permissions for a specific user:

```
$wgPageAttachment_permissions['<Action Keyword>' ]['user' ]['<User Id>'] = true;

Example:

$wgPageAttachment_permissions['view'           ]['user' ]['johndoe   ] = true;

```
  * Replace <Action Keyword> with actual action keyword
  * Replace <User Id> with actual user ID
