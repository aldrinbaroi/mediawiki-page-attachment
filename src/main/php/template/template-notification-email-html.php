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

namespace PageAttachment\Template;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

$wgPageAttachment_messageTemplates['html'] = 
'
<html>
	<head>
		<title>HEADER</title>
		<style>
			body,table 
			{
				font-family: verdana;
				font-size: xx-small;
				color: #0066FF;
			}
			caption 
			{
				font-weight: bold;
				color: ##0066FF;
			}
			.label 
			{
				font-weight: bold;
			}
		</style>
	</head>
	<body>
		<table>
			<caption>
				** HEADER **<br />
				<br />&#187; SITENAME Wiki &#171;<br />
				<br />
			</caption>
			<tr>
				<td class="label">ATTACHED_TO_PAGE_NAME_LABEL</td>
				<td>:&nbsp;&nbsp;</td>
				<td>ATTACHED_TO_PAGE_NAME</td>
			</tr>
			<tr>
				<td class="label">ATTACHMENT_NAME_LABEL</td>
				<td>:&nbsp;&nbsp;</td>
				<td>ATTACHMENT_NAME</td>
			</tr>
			<tr>
				<td class="label">ACTIVITY_TYPE_LABEL</td>
				<td>:&nbsp;&nbsp;</td>
				<td>ACTIVITY_TYPE</td>
			</tr>
			<tr>
				<td class="label">ACTIVITY_TIME_LABEL</td>
				<td>:&nbsp;&nbsp;</td>
				<td>ACTIVITY_TIME</td>
			</tr>
			<tr>
				<td class="label">MODIFIED_BY_USER_LABEL</td>
				<td>:&nbsp;&nbsp;</td>
				<td>MODIFIED_BY_USER</td>
			</tr>
		</table>
	</body>
</html>
';

## :: END ::
 
