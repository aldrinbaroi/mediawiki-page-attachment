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

class FSFileBackend_Override_PHP_5p3 extends \FSFileBackend
{

	public function __construct(array $config)
	{
		parent::__construct($config);
	}

	public function streamFile__(array $params)
	{
		\wfProfileIn( __METHOD__ );
		\wfProfileIn( __METHOD__ . '-' . $this->name );
		$status = \Status::newGood();

		$info = $this->getFileStat( $params );
		if ( !$info ) { // let StreamFile handle the 404
			$status->fatal( 'backend-fail-notexists', $params['src'] );
		}

		// Set output buffer and HTTP headers for stream
		$extraHeaders = isset( $params['headers'] ) ? $params['headers'] : array();
			
		if (isset( $params['downloadFileName'] ))                                                                                    // New
		{                                                                                                                            // New
			$info['downloadFileName'] = $params['downloadFileName'];                                                                 // New
		}                                                                                                                            // New
		// $res = StreamFile::prepareForStream( $params['src'], $info, $extraHeaders );                                              // MediaWiki Original
		$res = \PageAttachment\File\FileStreamer\WorkAround\StreamFile::prepareForStream( $params['src'], $info, $extraHeaders );    // New
		if ( $res == \StreamFile::NOT_MODIFIED ) {
			// do nothing; client cache is up to date
		} elseif ( $res == \StreamFile::READY_STREAM ) {
			\wfProfileIn( __METHOD__ . '-send' );
			\wfProfileIn( __METHOD__ . '-send-' . $this->name );
			$status = $this->doStreamFile( $params );
			\wfProfileOut( __METHOD__ . '-send-' . $this->name );
			\wfProfileOut( __METHOD__ . '-send' );
		} else {
			$status->fatal( 'backend-fail-stream', $params['src'] );
		}

		\wfProfileOut( __METHOD__ . '-' . $this->name );
		\wfProfileOut( __METHOD__ );
		return $status;
	}
}