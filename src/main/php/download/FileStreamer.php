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

namespace PageAttachment\Download;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

interface FileStreamer 
{
	/**
	 * 
	 * @param string $downloadFileName
	 * @throws FileStreamerException
	 */
	public function streamFile($downloadFileName);
}

/*
class FileStreamer
{

	function streamFile($downloadFileName)
	{
		$aTitle = \Title::newFromText($downloadFileName, NS_FILE);
		$fileName =  $aTitle->getText();
		$file = \wfFindFile($aTitle);
		$fileUrl = $file->getFullUrl();
		$fileSize =  $file->getSize();
		header("Pragma: public");
		header("Expires: 0");
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
		header('Last-Modified: '. gmdate('D, d M Y H:i:s') . ' GMT');
		$browser = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/MSIE 5.5/', $browser) || preg_match('/MSIE 6.0/', $browser))
		{
			header('Pragma: private');
			header('Cache-control: private, must-revalidate');
		}
		header("Content-Length: " . $fileSize);
		header('Content-Type: application/x-download');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		header('Content-Transfer-Encoding: binary');
		//$username = 'aldrin';
		//$password = 'aldrin';
		//$cred = sprintf('Authorization: Basic %s', base64_encode("$username:$password") );
		$allHeaders = \getallheaders();
		foreach ($allHeaders as $key => $value)
		{
			if (\strtolower($key) == 'authorization')
			{
				$credential = $value;
			}
		}
		if (isset($credential))
		{
			$authorization = 'Authorization: ' . $credential;
			$opts = array(
					'http' => array(
							'method' => 'GET',
							'header' => array($authorization))
			);
			$ctx = stream_context_create($opts);
			$fp = \fopen($fileUrl, 'rb', false, $ctx);
		}
		else
		{
			$fp = \fopen($fileUrl, 'rb');
		}
		fpassthru($fp);
		fclose($fp);
	}

}
*/

## ::END::
