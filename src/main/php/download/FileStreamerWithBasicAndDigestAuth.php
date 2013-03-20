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

namespace PageAttachment\Download;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}


class FileStreamerWithBasicAndDigestAuth extends AbstractFileStreamer implements FileStreamer
{

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// TODO Cache the authorization info for X seconds in session
	protected function openFileStream($fileUrl)
	{
		$filePointer = false;
		try
		{
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
				$filePointer = \fopen($fileUrl, 'rb', false, $ctx);
			}
			else
			{
				\wfDebugLog('PageAttachment', 'Failed open file stream. URL: ' . $fileUrl . '\r\n' .
						'  Error: PageAttachment configuration is set to use Basic/Digest authentication.\r\n' .
						'  However unable to retrive authentication information from HTTP header.' );
			}
		}
		catch(Exception $e)
		{
			\wfDebugLog('PageAttachment', 'Failed open file stream. URL: ' . $fileUrl . '  Error: ' . $e->getMessage());
		}
		return $filePointer;
	}

}

## ::END::
