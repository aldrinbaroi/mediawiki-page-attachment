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

namespace PageAttachment\File\FileStreamer;

use PageAttachment\Utility\PHPVersion;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class FileStreamerFactory
{
	private $phpVersion;
	private $phpMajorVersion;
	private $phpMinorVersion;


	/**
	 *
	 */
	public function __construct()
	{
		$this->phpVersion = new PHPVersion();
		$this->phpMajorVersion = $this->phpVersion->getMajorVersion();
		$this->phpMinorVersion = $this->phpVersion->getMinorVersion();
	}

	public function createFileStreamer($fileName)
	{
		$title = \Title::newFromText($fileName, NS_FILE);
		$titleName =  $title->getText();
		$file = \wfFindFile($titleName);
		$fileBackend = $file->getRepo()->getBackend();
		//
		// Ideally, we should be able to use "streamFile" function defined in $fileBackend
		// object.  However, in MediaWiki "StreamFile" class, even though it provides
		// option to supply extra http headers, we cannot override "Content-type" header
		// value. We need to be able to set "Content-type" to "application/x-download" so
		// that the browser prompts the user to save the file instead of trying to display
		// the file.
		//
		// The work around for PHP 5.4 & greater is the easiest:
		//
		//    1) Created an alternate "StreamFile" class by extending MediaWiki's "StreamFile"
		//       class and updated the method "prepareForStream" to able to specify
		//       content type.  In addtion, disabled client cache check so that file is
		//       streamed on every requests.
		//
		//    2) Created a new stream funtion to use the updated "StreamFile::prepareForStream()"
		//
		//    3) Used Closure::bind, introduced in PHP 5.4, to add the stream fuction to the
		//       $fileBackend object and used it to stream the file instead of "streamFile"
		//       function of the $fileBackend object.
		//
		//
		$fileStreamer = null;
		if (($this->phpMajorVersion > 5) || (($this->phpMajorVersion == 5) && ($this->phpMinorVersion >= 4)))
		{
			$fileStreamer = $this->createFileStreamer_for_PHP_5p4($fileName, $title, $titleName, $file, $fileBackend);
		}
		else if (($this->phpMajorVersion == 5) && ($this->phpMinorVersion == 3))
		{
			$fileStreamer = $this->createFileStreamer_for_PHP_5p3($fileName, $title, $titleName, $file, $fileBackend);
		}
		else
		{
			throw new FileStreamerFactoryException('Only PHP 5.3, 5.4 or greater is supported');
		}
		return $fileStreamer;
	}

	private function createFileStreamer_for_PHP_5p3($fileName, $title, $titleName, $file, &$fileBackend)
	{
		global $wgLocalFileRepo;
		global $wgFileBackends;

		$backendName = $wgLocalFileRepo['backend'];
		$class = '';
		$lockManager = '';
		//
		// 'FSFileBackend' is hard coded in "FileBackendGroup" class as a default and
		// $wgFileBackends[$backendName] == $wgFileBackends['local-backend'] is never set.
		//
		if (!isset($wgFileBackends[$backendName]))
		{
			$class = 'PageAttachment\File\FileStreamer\WorkAround\FSFileBackend_Override_PHP_5p3';
			$lockManager = (isset($wgLocalFileRepo['lockManager'])) ?  $wgLocalFileRepo['lockManager'] : 'fsLockManager';
		}
		elseif (isset($wgFileBackends[$backendName]) && $wgFileBackends[$backendName] == 'SwiftFileBackend')
		{
			$class = 'PageAttachment\File\FileStreamer\WorkAround\SwiftFileBackend_Override_PHP_5p3';
			$lockManager = (isset($wgLocalFileRepo['lockManager'])) ?  $wgLocalFileRepo['lockManager'] : 'nullLockManager';
		}
		$repoName = $wgLocalFileRepo['name'];
		$directory = $wgLocalFileRepo['directory'];
		$deletedDir = isset( $wgLocalFileRepo['deletedDir'] ) ? $wgLocalFileRepo['deletedDir'] : false; // deletion disabled
		$thumbDir = isset( $wgLocalFileRepo['thumbDir'] ) ? $wgLocalFileRepo['thumbDir'] : "{$directory}/thumb";
		$fileMode = isset( $wgLocalFileRepo['fileMode'] ) ? $wgLocalFileRepo['fileMode'] : 0644;
		// FS backend configuration
		$config = array(
				'name'           => $backendName,
				'lockManager'    => $lockManager,
				'containerPaths' => array(
						"{$repoName}-public"  => "{$directory}",
						"{$repoName}-thumb"   => $thumbDir,
						"{$repoName}-deleted" => $deletedDir,
						"{$repoName}-temp"    => "{$directory}/temp"
				),
				'fileMode'       => $fileMode,
		);
		$fileStreamerObject = new $class( $config );
		$fileStreamer = new \PageAttachment\File\FileStreamer\WorkAround\FileStreamer_PHP_5p3($fileName, $title, $titleName, $file, $fileStreamerObject);
		return $fileStreamer;
	}

	/**
	 *
	 * @param $fileName
	 * @param $title
	 * @param $titleName
	 * @param $file
	 * @param $fileBackend
	 * @return \PageAttachment\File\FileStreamer\WorkAround\FileStreamer_PHP_5p4
	 */
	private function createFileStreamer_for_PHP_5p4($fileName, $title, $titleName, $file, &$fileBackend)
	{

		$streamFile = function (array $params ) {
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
		};

		$streamFunction = \Closure::bind($streamFile, $fileBackend, get_class($fileBackend));
		$fileStreamer = new \PageAttachment\File\FileStreamer\WorkAround\FileStreamer_PHP_5p4($fileName, $title, $titleName, $file, $streamFunction);
		return $fileStreamer;
	}

}

## ::END::
