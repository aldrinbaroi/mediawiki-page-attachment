<?php
/**
 *
 * Copyright (C) 2013 Aldrin Edison Baroi
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

namespace PageAttachment\File\FileStreamer\WorkAround;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class StreamFile extends \StreamFile
{
	/**
	 * NOTE:  This fuction is a copy of "prepareForStream" from MediaWiki's StreamFile
	 *        class, except that "Content-type" is hard coded to be "application/x-download"
	 *        to force the browser to prompt the user to save the file instead of displaying
	 *        the file in the browser & client cache checking is disabled.
	 *
	 * @param $path string Storage path or file system path
	 * @param $info Array|bool File stat info with 'mtime' and 'size' fields
	 * @param $headers Array Additional headers to send
	 * @param $sendErrors bool Send error messages if errors occur (like 404)
	 * @return int|bool READY_STREAM, NOT_MODIFIED, or false on failure
	 */
	public static function prepareForStream(
			$path, $info, $headers = array(), $sendErrors = true
	) {
		if ( !is_array( $info ) ) {
			if ( $sendErrors ) {
				header( 'HTTP/1.0 404 Not Found' );
				header( 'Cache-Control: no-cache' );
				header( 'Content-Type: text/html; charset=utf-8' );
				$encFile = htmlspecialchars( $path );
				$encScript = htmlspecialchars( $_SERVER['SCRIPT_NAME'] );
				echo "<html><body>
				<h1>File not found</h1>
				<p>Although this PHP script ($encScript) exists, the file requested for output
				($encFile) does not.</p>
				</body></html>
				";
			}
			return false;
		}

		// Sent Last-Modified HTTP header for client-side caching
		header( 'Last-Modified: ' . \wfTimestamp( TS_RFC2822, $info['mtime'] ) );

		// Cancel output buffering and gzipping if set
		\wfResetOutputBuffers();

	// <NEW-1>
	$streamForDownload = false;
	if ( isset($info['downloadFileName'] )) {
		$streamForDownload = true;
		header( 'Content-type: application/x-download' );
		header( 'Content-Disposition: attachment; filename="' . $info['downloadFileName'] . '"');
	//	$headers[] = 'Content-type: application/x-download';
	//	$headers[] = 'Content-Disposition: attachment; filename="' . $info['downloadFileName'] . '"';
	/* */
	} else {
	// </NEW-1>

		$type = self::contentTypeFromPath( $path );
		if ( $type && $type != 'unknown/unknown' ) {
		 	header( "Content-type: $type" );
		} else {
		 	// Send a content type which is not known to Internet Explorer, to
		 	// avoid triggering IE's content type detection. Sending a standard
		 	// unknown content type here essentially gives IE license to apply
		 	// whatever content type it likes.
		 	header( 'Content-type: application/x-wiki' );
		}

	// <NEW-2>
	}
	// </NEW-2>
		
		// Don't stream it out as text/html if there was a PHP error
		if ( headers_sent() ) {
			echo "Headers already sent, terminating.\n";
			return false;
		}
	/* * /
		foreach ( $headers as $header ) {
			header( $header );
		}

	/ * */
	// <NEW-3>
	if ($streamForDownload == true) {
		// Do not check for client cache info
		// Do not send additional headers 
	} else {
	// </NEW-3>

		foreach ( $headers as $header ) {
			header( $header );
		}


		// Don't send if client has up to date cache
		if ( !empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
		 	$modsince = preg_replace( '/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
		 	if ( \wfTimestamp( TS_UNIX, $info['mtime'] ) <= strtotime( $modsince ) ) {
		 		ini_set( 'zlib.output_compression', 0 );
		 		header( "HTTP/1.0 304 Not Modified" );
		 		return self::NOT_MODIFIED; // ok
		 	}
		 }

	// <NEW-4>
	}
	// </NEW-4>
	/* */

		header( 'Content-Length: ' . $info['size'] );

		return self::READY_STREAM; // ok
	}

}

## ::END::
