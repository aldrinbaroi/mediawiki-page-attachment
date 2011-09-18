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

namespace PageAttachment\UI;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class UIComposer
{
	private $security;
	private $session;
	private $runtimeConfig;
	
	function __construct($security, $session, $runtimeConfig)
	{
		$this->security = $security;
		$this->session = $session;
		$this->runtimeConfig = $runtimeConfig;
	}

	function composeAttachmentListTable($titleRowColumns, $headerRowColumns, $attachmentRows)
	{
		global $wgLang;
		
		$rtlLang = $wgLang->isRTL() ? true : false;
		$colWidths = $this->getColWidths();
		$cols = $rtlLang ? array_reverse($colWidths) : $colWidths;
		$colgroupCols = '';
		foreach ($cols as $col)
		{
			$colgroupCols .= \HTML::element('col', array('width' => ($col . '%')));
		}
		$colgroup = \HTML::rawElement('colgroup', null, $colgroupCols);
		## Title Row
		$cols = $rtlLang ? array_reverse($titleRowColumns) : $titleRowColumns;
		$colCount = 0;
		$titleRowCols = '';
		foreach ($cols as $col)
		{
			$attrs = array('class' => ('titleRow_col_' . ++$colCount), 'colspan' => $col['span']);
			$titleRowCols .= \HTML::rawElement('th', $attrs, $col['value']);
		}
		$titleRow = \HTML::rawElement('tr', array('class' => 'TitleRow'), $titleRowCols);
		## Header Row
		$cols = $rtlLang ? array_reverse($headerRowColumns) : $headerRowColumns;
		$colCount = 0;
		$headerRowCols = '';
		foreach ($cols as $col)
		{
			$headerRowCols .= \HTML::element('th', array('class' => ('headerRow_col_' . ++$colCount)), $col);
		}
		$headerRow = \HTML::rawElement('tr', array('class' => 'HeaderRow'), $headerRowCols);
		$thead = \HTML::rawElement('thead', null, ($titleRow . $headerRow));
		## Attachment Rows
		$atRows = '';
		if ($this->security->isViewAttachmentsAllowed())
		{
			if (isset($attachmentRows) && (count($attachmentRows) > 0))
			{
				foreach($attachmentRows as $row)
				{
					$colCount = 0;
					$rowCols = $rtlLang ? array_reverse($row) : $row;
					$atRowsCols = '';
					foreach($rowCols as $col)
					{
						$atRowsCols .= \HTML::rawElement('td', array('class' => ('attachmentRow_col_' . ++$colCount)), $col);
					}
					$atRows .= \HTML::rawElement('tr', array('class' => 'AttachmentRow'), $atRowsCols);
				}
			}
			else
			{
				$colSpan = count($colWidths);
				$atRowsCol = \HTML::element('td', array('class' => 'messageAttachmentsNone', 'colspan' => $colSpan), \wfMsg('AttachmentsNone'));
				$atRows = \HTML::rawElement('tr', array('class' => 'MessageRow'), $atRowsCol);
			}
			// Status Message
			$statusMsg = $this->session->getStatusMessage();
			if (isset($statusMsg))
			{
				$statusMsg = $this->formatStatusMessage($statusMsg);
				$colSpan = count($colWidths);
				$atRowCol = \HTML::rawElement('td', array('class' => 'message', 'colspan' => $colSpan), $statusMsg);
				$atRows .= \HTML::rawElement('tr', array('class', 'MessageRow'), $atRowCol);
			}
		}
		else
		{
			if ($this->security->isViewAttachmentsRequireLogin() && !$this->security->isLoggedIn())
			{
				$statusMsg = \wfMsg('YouMustBeLoggedInToViewAttachments');
			}
			else
			{
				$statusMsg = \wfMsg('ViewAttachmentIsNotPermitted');
			}
			$statusMsg = $this->formatStatusMessage($statusMsg);
			$colSpan = count($colWidths);
			$atRowCol = \HTML::rawElement('td', array('class' => 'message', 'colspan' => $colSpan), $statusMsg);
			$atRows .= \HTML::rawElement('tr', array('class', 'MessageRow'), $atRowCol);
		}
		$tbody = \HTML::rawElement('tbody', null, $atRows);
		$data = \HTML::rawElement('table', array('cellspacing' => '0'), ($colgroup . $thead . $tbody));
		return $data;
	}

	private function formatStatusMessage($msg)
	{
		global $wgPageAttachment_statusMessageFormat;
	
		if (isset($msg))
		{
			$formattedMsg =  str_replace('STATUS_MESSAGE', $msg, $wgPageAttachment_statusMessageFormat);
			if (is_array($formattedMsg))
			{
				$formattedMsg = implode('', $formattedMsg);
			}
		}
		else
		{
			$formattedMsg = null;
		}
		return $formattedMsg;
	}
	
	private function getColWidths()
	{
		global $wgPageAttachment_colWidth;

		$skinName = $this->runtimeConfig->getSkinName();
		if (isset($wgPageAttachment_colWidth[$skinName]))
		{
			return $wgPageAttachment_colWidth[$skinName];
		}
		else
		{
			return $wgPageAttachment_colWidth['default'];
		}
	}

}

## ::END::
