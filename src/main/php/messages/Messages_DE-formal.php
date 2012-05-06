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
 * @author Daniel Seichter <daniel.seichter@aaronprojects.de>
 * @since Version 2.0.1
 *
 */

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

$messages['de-formal'] = array(
	'pageAttachmentExtensionDescription'        => 'Diese Erweiterung ermöglicht es, Dateien an Seiten in Mediawiki anzuhängen',
    'attachments'                               => 'Anhänge',
    'attachment'                                => 'Anhang',
    'name'                                      => 'Name',
    'description'                               => 'Beschreibung',
    'size'                                      => 'Größe',
    'dateUploaded'                              => 'Datum',
    'uploadedBy'                                => 'Hochgeladen von',
    'uploadAndAttach'                           => 'Datei hochladen und anhängen',
    'browseSearchAttach'                        => 'Durchsuchen & Datei anhängen',
    'removeAttachment'                          => 'Anhang entfernen',
    'downloadAttachment'                        => 'Anhang herunterladen',
    'viewAuditLog'                              => 'Zeige Audit Protokoll',
    'viewHistory'                               => 'Zeige Informationen und Historie des Anhang',
    'viewUserPage'                              => 'Zeige $1\'s Seite',
    'viewAttachmentIsNotPermitted'              => 'Die Anzeige der Anhänge ist nicht erlaubt',
    'addUpdateAttachmentIsNotPermitted'         => 'Hinzufügen/Aktualisieren von Anhängen ist nicht erlaubt',
    'browseSearchAttachIsNotPermitted'          => 'Durchsuchen & Anhänge hinzufügen ist nicht erlaubt',
    'attachmentRemovalIsNotPermitted'           => 'Entfernen des Anhangs ist nicht erlaubt',
    'attachmentDownloadIsNotPermitted'          => 'Herunterladen des Anhangs ist nicht erlaubt',
    'auditLogViewingIsNotPermitted'             => 'Anzeigen des Audit Protokolls ist nicht erlaubt',
    'youMustBeLoggedInToViewAttachments'        => 'Sie müssen angemeldet sein, um Anhänge zu sehen',
    'youMustBeLoggedInToAddUpdateAttachments'   => 'Sie müssen angemeldet sein, um Anhänge hinzufügen/ändern zu können',
    'youMustBeLoggedInToBrowseSearchAttach'     => 'Sie müssen angemeldet sein, um Anhänge durchsuchen & hinzufügen zu können',
    'youMustBeLoggedInToRemoveAttachments'      => 'Sie müssen angemeldet sein, um Anhänge zu entfernen',
    'youMustBeLoggedInToDownloadAttachments'    => 'Sie müssen angemeldet sein, um Anhänge herunterzuladen',
    'youMustBeLoggedInToViewAuditLog'           => 'Sie müssen angemeldet sein, um das Audit-Protokoll anzeigen zu können',
    'attachmentsNone'                           => 'Anhänge: Keine',
    'attachToPageName'                          => 'Anhang hinzufügen zu Seite: $1',
    'pleaseConfirmRemoveAttachment'                      => 'Bitte bestätigen Sie:\n\nEntfernen des folgenden Anhanges?\n\n>> $1 <<\n\n',
    'pleaseConfirmRemoveAttachmentPermanently'           => 'Bitte bestätigen Sie:\n\nPermanentes Entfernen des folgenden Anhanges?\n\n>> $1 <<\n\n',
    'pleaseConfirmRemoveAttachmentPermanently1'          => 'Bitte bestätigen Sie:\n\nPermanentes Entfernen des folgenden Anhanges?\n\n>> $1 <<\n\n' .
                                                            'Hinweis: Dieser Anhang hängt an folgenden Seiten:\n\n$2\n\n' .
                                                            'Und ist eingebettet in folgende Seiten:\n\n$3',
    'pleaseConfirmRemoveAttachmentPermanently2'          => 'Bitte bestätigen Sie:\n\nPermanentes Entfernen des folgenden Anhanges?\n\n>> $1 <<\n\n' .
                                                            'Hinweis: Dieser Anhang hängt an folgenden Seiten:\n\n$2',
    'pleaseConfirmRemoveAttachmentPermanently3'          => 'Bitte bestätigen Sie:\n\nPermanentes Entfernen des folgenden Anhanges?\n\n>> $1 <<\n\n' .
                                                            'Hinweis: Dieser Anhang ist in folgende Seiten eingebettet:\n\n$2',
    'unableToFulfillRemoveAttachmentPermanentlyRequest1' => 'Ausführen des permanenten Entfernens des Anhanges ist nicht möglich.\n\n' . 
                                                            'Der Anhang:\n\n>> $1 <<\n\n' . 
                                                            'ist an folgende Seiten angehängt:\n\n$2\n\n' . 
                                                            'Und ist eingebettet in folgende Seiten:\n\n$3',
    'unableToFulfillRemoveAttachmentPermanentlyRequest2' => 'Ausführen des permanenten Entfernens des Anhanges ist nicht möglich.\n\n' .
                                                            'Der Anhang:\n\n>> $1 <<\n\n' . 
                                                            'ist an folgende Seiten angehängt:\n\n$2',
    'unableToFulfillRemoveAttachmentPermanentlyRequest3' => 'Ausführen des permanenten Entfernens des Anhanges ist nicht möglich.\n\n' .
                                                            'Der Anhang:\n\n>> $1 <<\n\n' . 
                                                            'ist in folgende Seiten eingebettet:\n\n$2',
    'displayTimeZone'                           => 'Zeige Zeitzone',
    'attachmentAdded'                           => 'Anhang hinzugefügt :: $1',
    'attachmentUpdated'                         => 'Anhang aktualisiert :: $1',
    'attachmentRemoved'                         => 'Anhang entfernt :: $1',
    'attachmentRemovedPermanently'              => 'Anhang permantent entfernt :: $1',
    'failedToAddAttachment'                     => 'Fehler: Anhängen der Datei fehlgeschlagen. Dateiname: $1',
    'failedToRemoveAttachment'                  => 'Fehler: Fehler beim Löschen des Anhangs',
    'attachFile'                                => 'Diese Datei anhängen',
    'fileAttached'                              => 'Datei angehängt :: $1',
    'invalidAttachToPage'                       => 'Sicherheitswarnung: Ungültiger Anhang',
    'invalidAttachedToPage'                     => 'Sicherheitswarnung: Ungültiger Anhang angehängt',
    'unableToAuthenticateYourRequest'           => 'Sicherheitswarnung: Ihre Anfrage konnte nicht authentifiziert werden',
    'failedToValidateAttachmentRemovalRequest'  => 'Sicherheitswarnung: Fehler beim Überprüfen Ihrer Löschanfrage',
    'unableToDetermineAttachToPage'             => 'Fehler: Anhängen an Seite konnte nicht abgeschlossen werden',
    'pleaseLoginToActivateDownloadLink'         => 'Bitte melden Sie sich an, um den Downlaod zu ermöglichen',
    'downloadFile'                              => 'Datei herunterladen: $1',
// Special Pages
    'pageattachmentlistfiles'                   => 'Dateien durchsuchen & anhängen',
    'pageattachmentupload'                      => 'Datei Hochladen & anhängen',
    'pageattachmentauditlogviewer'              => 'Anzeigen des Page Attachment Audit Protokoll',
// Audit Log Viewer
    'auditLog'                                  => 'Audit Protokoll',
    'attached_to_page_id'                       => 'Angehängt an Seite',
    'attachment_file_name'                      => 'Name des Anhangs',
    'user_id'                                   => 'Benutzername',
    'activity_time'                             => 'Dauer',
    'activity_type'                             => 'Dauer',
// Activity Types
    'uploadedAndAttachedFile'                   => 'Datei hochgeladen und hinzugefügt',
    'uploadedAndReattachedFile'                 => 'Datei hochgeladen und erneut hinzugefügt',
    'attachedExistingFile'                      => 'Vorhandene Datei hinzugefügt',
    'reattachedExistingFile'                    => 'Vorhandene Datei erneut hinzugefügt',
    'removedFile'                               => 'Datei entfernt',
    'removedFilePermanently'                    => 'Datei permanent entfernt',
    'removeFilePermanentlyFailed'               => 'Permanentes Entfernen fehlgeschlagen',
    'removedDeletedFile'                        => 'Gelöschte Datei entfernt',
    'reattachedUndeletedFile'                   => 'Wiederhergestellte Datei erneut hinzugefügt',
//
    'utc'                                       => 'UTC',
    'unableToDetermineDownloadFromPage'         => 'Download von Seite konnte nicht abgeschlossen werden',
    'unableToDetermineDownloadFileName'         => 'Download von Datei konnte nicht abgeschlossen werden',
    'requestedDownloadFileDoesNotExist'         => 'Angeforderte Datei existiert nicht',
    'unknownDownloadError'                      => 'Unbekannter Fehler beim Herunterladen',
// Attachment Category
    'selectCategory'                            => 'Bitte wählen Sie eine Kategorie',
// Attachment Change Notification
    'attachmentChangeNotification'              => 'Benachrichtigung bei Änderungen des Anhangs'
);


##::End::
