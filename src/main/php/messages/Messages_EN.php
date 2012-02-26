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

$messages['en'] = array(
	'pageAttachmentExtensionDescription'        => 'This extension extends MediaWiki to allow adding files to a page as attachments.',
    'attachments'                               => 'Attachments',
    'attachment'                                => 'Attachment',
    'name'                                      => 'Name',
    'description'                               => 'Description',
    'size'                                      => 'Size',
    'dateUploaded'                              => 'Date Uploaded',
    'uploadedBy'                                => 'Uploaded By',
    'uploadAndAttach'                           => 'Upload and Attach File',
    'browseSearchAttach'                        => 'Browse/Search & Attach File',
    'removeAttachment'                          => 'Remove Attachment',
    'downloadAttachment'                        => 'Download Attachment',
    'viewAuditLog'                              => 'View Audit Log',
    'viewHistory'                               => 'View Attachment Information and History',
    'viewUserPage'                              => 'View $1\'s Page',
    'viewAttachmentIsNotPermitted'              => 'View Attachments is Not Permitted',
    'addUpdateAttachmentIsNotPermitted'         => 'Add/Update Attachment is Not Permitted',
    'browseSearchAttachIsNotPermitted'          => 'Browse/Search & Attach File is Not Permitted',
    'attachmentRemovalIsNotPermitted'           => 'Attachment Removal is Not Permitted',
    'attachmentDownloadIsNotPermitted'          => 'Attachment Download is Not Permitted',
    'auditLogViewingIsNotPermitted'             => 'Audit Log Viewing is Not Permitted',
    'youMustBeLoggedInToViewAttachments'        => 'You Must be Logged in to View Attachments',
    'youMustBeLoggedInToAddUpdateAttachments'   => 'You Must be Logged in to Add/Update Attachments',
    'youMustBeLoggedInToBrowseSearchAttach'     => 'You Must be Logged in to Browse/Search & Attach File',
    'youMustBeLoggedInToRemoveAttachments'      => 'You Must be Logged in to Remove Attachments',
    'youMustBeLoggedInToDownloadAttachments'    => 'You Must be Logged in to Download Attachments',
    'youMustBeLoggedInToViewAuditLog'           => 'You Must be Logged in to View AuditLog',
    'attachmentsNone'                           => 'Attachments: None',
    'attachToPageName'                          => 'Attach to Page Name: $1',
    'pleaseConfirmRemoveAttachment'                      => 'Please Confirm:\n\nRemove the following attachment?\n\n>> $1 <<\n\n',
    'pleaseConfirmRemoveAttachmentPermanently'           => 'Please Confirm:\n\nRemove the following attachment, permanently?\n\n>> $1 <<\n\n',
    'pleaseConfirmRemoveAttachmentPermanently1'          => 'Please Confirm:\n\nRemove the following attachment, permanently?\n\n>> $1 <<\n\n' .
                                                            'Note: This attachment is attached to the following pages:\n\n$2\n\n' .
                                                            'And embedded in the following pages:\n\n$3',
    'pleaseConfirmRemoveAttachmentPermanently2'          => 'Please Confirm:\n\nRemove the following attachment, permanently?\n\n>> $1 <<\n\n' .
                                                            'Note: This attachment is attached to the following pages:\n\n$2',
    'pleaseConfirmRemoveAttachmentPermanently3'          => 'Please Confirm:\n\nRemove the following attachment, permanently?\n\n>> $1 <<\n\n' .
                                                            'Note: This attachment is embedded in the following pages:\n\n$2',
    'unableToFulfillRemoveAttachmentPermanentlyRequest1' => 'Unable to Fulfill Remove Attachment Permanently Request.\n\n' . 
                                                            'The following attachment:\n\n>> $1 <<\n\n' . 
                                                            'is attached to the following pages:\n\n$2\n\n' . 
                                                            'and is embedded in the following pages:\n\n$3',
    'unableToFulfillRemoveAttachmentPermanentlyRequest2' => 'Unable to Fulfill Remove Attachment Permanently Request.\n\n' .
                                                            'The following attachment:\n\n>> $1 <<\n\n' . 
                                                            'is attached to the following pages:\n\n$2',
    'unableToFulfillRemoveAttachmentPermanentlyRequest3' => 'Unable to Fulfill Remove Attachment Permanently Request.\n\n' .
                                                            'The following attachment:\n\n>> $1 <<\n\n' . 
                                                            'is embedded in the following pages:\n\n$2',
    'displayTimeZone'                           => 'Display Time Zone',
    'attachmentAdded'                           => 'Attachment Added :: $1',
    'attachmentUpdated'                         => 'Attachment Updated :: $1',
    'attachmentRemoved'                         => 'Attachment Removed :: $1',
    'attachmentRemovedPermanently'              => 'Attachment Removed Permanently :: $1',
    'failedToAddAttachment'                     => 'Error: Failed to add attachment. File Name: $1',
    'failedToRemoveAttachment'                  => 'Error: Failed to remove attachment',
    'attachFile'                                => 'Attach this File',
    'fileAttached'                              => 'File Attached :: $1',
    'invalidAttachToPage'                       => 'Security Warning: Invalid Attach to Page',
    'invalidAttachedToPage'                     => 'Security Warning: Invalid Attached to Page',
    'unableToAuthenticateYourRequest'           => 'Security Warning: Unable to Authenticate Your Request',
    'failedToValidateAttachmentRemovalRequest'  => 'Security Warning: Failed to Validate Attachment Removal Request',
    'unableToDetermineAttachToPage'             => 'Error: Unable to Determine Attach to Page',
    'pleaseLoginToActivateDownloadLink'         => 'Please Login to Activate Download Link',
    'downloadFile'                              => 'Download File: $1',
// Special Pages
    'pageattachmentlistfiles'                   => 'Browse/Search & Attach File',
    'pageattachmentupload'                      => 'Upload & Attach File',
    'pageattachmentauditlogviewer'              => 'Page Attachment Audit Log Viewer',
// Audit Log Viewer
    'auditLog'                                  => 'Audit Log',
    'attached_to_page_id'                       => 'Attached to Page Name',
    'attachment_file_name'                      => 'Attachment Name',
    'user_id'                                   => 'User ID/Name',
    'activity_time'                             => 'Activity Time',
    'activity_type'                             => 'Activity',
// Activity Types
    'uploadedAndAttachedFile'                   => 'Uploaded & Attached File',
    'uploadedAndReattachedFile'                 => 'Uploaded & Reattached File',
    'attachedExistingFile'                      => 'Attached Existing File',
    'reattachedExistingFile'                    => 'Reattached Existing File',
    'removedFile'                               => 'Removed File',
    'removedFilePermanently'                    => 'Removed File Permanently',
    'removeFilePermanentlyFailed'               => 'Remove File Permanently Failed',
    'removedDeletedFile'                        => 'Removed Deleted File',
    'reattachedUndeletedFile'                   => 'Reattached Undeleted File',
//
    'utc'                                       => 'UTC',
    'unableToDetermineDownloadFromPage'         => 'Unable to Determine Download from Page',
    'unableToDetermineDownloadFileName'         => 'Unable to Determine Download File Name',
    'requestedDownloadFileDoesNotExist'         => 'Requested Download File Does Not Exist',
    'unknownDownloadError'                      => 'Unknown Download Error',
// Attachment Category
    'selectCategory'                            => 'Select Category',
// Attachment Change Notification
    'attachmentChangeNotification'              => 'Attachment Change Notification'
);


##::End::
