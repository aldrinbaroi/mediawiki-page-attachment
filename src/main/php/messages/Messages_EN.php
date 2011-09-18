<?php
/**
*
* Copyright (C) 2011 Aldrin Edison Baroi
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the
*     Free Software Foundation, Inc.,
*     51 Franklin Street, Fifth Floor
*     Boston, MA 02110-1301, USA.
*     http://www.gnu.org/copyleft/gpl.html
*
*/

if (!defined('MEDIAWIKI'))
{
    echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
    exit( 1 );
}

$messages = array();
$messages['en'] = array(
	'PageAttachmentExtensionDescription'       => 'This extension extends MediaWiki to allow adding files to a page as attachments.',
    'Attachments'                              => 'Attachments',
    'Attachment'                               => 'Attachment',
    'Name'                                     => 'Name',
    'Size'                                     => 'Size',
    'DateUploaded'                             => 'Date Uploaded',
    'UploadedBy'                               => 'Uploaded By',
    'UploadAndAttach'                          => 'Upload and Attach File',
    'BrowseSearchAttach'                       => 'Browse/Search & Attach File',
    'RemoveAttachment'                         => 'Remove Attachment',
    'DownloadAttachment'                       => 'Download Attachment',
    'ViewAuditLog'                             => 'View Audit Log',
    'ViewHistory'                              => 'View Attachment Information and History',
    'ViewUserPage'                             => 'View $1\'s Page',
    'ViewAttachmentIsNotPermitted'             => 'View Attachments is Not Permitted',
    'AddUpdateAttachmentIsNotPermitted'        => 'Add/Update Attachment is Not Permitted',
    'BrowseSearchAttachIsNotPermitted'         => 'Browse/Search & Attach File is Not Permitted',
    'AttachmentRemovalIsNotPermitted'          => 'Attachment Removal is Not Permitted',
    'AttachmentDownloadIsNotPermitted'         => 'Attachment Download is Not Permitted',
    'AuditLogViewingIsNotPermitted'            => 'Audit Log Viewing is Not Permitted',
    'YouMustBeLoggedInToViewAttachments'       => 'You Must be Logged in to View Attachments',
    'YouMustBeLoggedInToAddUpdateAttachments'  => 'You Must be Logged in to Add/Update Attachments',
    'YouMustBeLoggedInToBrowseSearchAttach'    => 'You Must be Logged in to Browse/Search & Attach File',
    'YouMustBeLoggedInToRemoveAttachments'     => 'You Must be Logged in to Remove Attachments',
    'YouMustBeLoggedInToDownloadAttachments'   => 'You Must be Logged in to Download Attachments',
    'YouMustBeLoggedInToViewAuditLog'          => 'You Must be Logged in to View AuditLog',
    'AttachmentsNone'                          => 'Attachments: None',
    'AttachToPageName'                         => 'Attach to Page Name: $1',
    'PleaseConfirmRemoveAttachment'            => 'Please Confirm:\n\nRemove the following attachment?\n\n>> $1 <<\n\n',
    'DisplayTimeZone'                          => 'Display Time Zone',
    'AttachmentAdded'                          => 'Attachment Added :: $1',
    'AttachmentUpdated'                        => 'Attachment Updated :: $1',
    'AttachmentRemoved'                        => 'Attachment Removed :: $1',
    'FailedToAddAttachment'                    => 'Error: Failed to add attachment. File Name: $1',
    'FailedToRemoveAttachment'                 => 'Error: Failed to remove attachment',
    'AttachFile'                               => 'Attach this File',
    'FileAttached'                             => 'File Attached :: $1',
    'InvalidAttachToPage'                      => 'Security Warning: Invalid Attach to Page',
    'InvalidAttachedToPage'                    => 'Security Warning: Invalid Attached to Page',
    'UnableToAuthenticateYourRequest'          => 'Security Warning: Unable to Authenticate Your Request',
    'FailedToValidateAttachmentRemovalRequest' => 'Security Warning: Failed to Validate Attachment Removal Request',
    'UnableToDetermineAttachToPage'            => 'Error: Unable to Determine Attach to Page',
    'PleaseLoginToActivateDownloadLink'        => 'Please Login to Activate Download Link',
    'DownloadFile'                             => 'Download File: $1',
    // Special Pages
    'pageattachmentlistfiles'                  => 'Browse/Search & Attach File',
    'pageattachmentupload'                     => 'Upload & Attach File',
    'pageattachmentauditlogviewer'             => 'Page Attachment Audit Log Viewer',
    // Activity Log Viewer 
    'AuditLog'                                 => 'Audit Log',
    'attached_to_page_id'                      => 'Attached to Page Name',
    'attachment_file_name'                     => 'Attachment Name',
    'user_id'                                  => 'User ID/Name',
    'activity_time'                            => 'Activity Time',
    'activity_type'                            => 'Activity',
    'activity_detail'                          => 'Activity Detail',
    //
    'UTC'                                      => 'UTC',
    'UnableToDetermineDownloadFromPage'        => 'Unable to Determine Download from Page',
    'UnableToDetermineDownloadFileName'        => 'Unable to Determine Download File Name',
    'RequestedDownloadFileDoesNotExist'        => 'Requested Download File Does Not Exist',
    'UnknownDownloadError'                     => 'Unknown Download Error'
);


##::End::
