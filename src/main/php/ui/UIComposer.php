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
	private $resource;

	function __construct($security, $session, $runtimeConfig, $resouce)
	{
		$this->security = $security;
		$this->session = $session;
		$this->runtimeConfig = $runtimeConfig;
		$this->resource = $resouce;
	}

	function composeAttachmentListTable($protectedPage, $titleRowColumns, $headerRowColumns, $attachmentRows)
	{
		global $wgLang;
		global $wgPageAttachment_colToDisplay;
		global $wgPageAttachment_colWidth;
		global $wgPageAttachment_descriptionMaxLength;
		global $wgPageAttachment_descriptionPopupWidth;
		global $wgPageAttachment_descriptionPopupHeight;

		$skinName = $this->runtimeConfig->getSkinName();
		$userLangCode = $this->runtimeConfig->getUserLanguageCode();
		$rtlLang = $this->runtimeConfig->isRightToLeftLanguage();
		$colWidths = $this->getColWidths($skinName, $userLangCode);
		$colgroupCols = '';
		foreach ($colWidths as $colWidth)
		{
			$colgroupCols .= \HTML::element('col', array('width' => ($colWidth . '%')));
		}
		$colgroup = \HTML::rawElement('colgroup', null, $colgroupCols);
		//
		// Title Row
		//
		$titleRowColKeys = array_keys($titleRowColumns);
		$titleRowCols = '';
		foreach($titleRowColKeys as $colKey)
		{
			$attrs = array('class' => ('titleRow_col_' . $colKey), 'colspan' => $this->getTitleRowColSpan($skinName, $userLangCode, $colKey));
			$titleRowCols .= \HTML::rawElement('th', $attrs, $titleRowColumns[$colKey]);
		}
		$titleRow = \HTML::rawElement('tr', array('class' => 'TitleRow'), $titleRowCols);
		//
		// Header Row
		//
		$headerRowColKeys = array_keys($headerRowColumns);
		$headerRowCols = '';
		foreach ($headerRowColKeys as $colKey)
		{
			$headerRowCols .= \HTML::element('th', array('class' => ('headerRow_col_' . $colKey)), $headerRowColumns[$colKey]);
		}
		$headerRow = \HTML::rawElement('tr', array('class' => 'HeaderRow'), $headerRowCols);
		$thead = \HTML::rawElement('thead', null, ($titleRow . $headerRow));
		//
		// Attachment Rows
		//
		$viewMoreImgURL = $this->resource->getViewMoreImageURL();
		$viewMoreImgIcon = \HTML::rawElement('img', array('src' => $viewMoreImgURL));
		$atRows = '';
		if ($this->security->isViewAttachmentsAllowed($protectedPage))
		{
			if (isset($attachmentRows) && (count($attachmentRows) > 0))
			{
				foreach($attachmentRows as $row)
				{
					$rowCols = $row;
					$rowColKeys = array_keys($rowCols);
					$atRowsCols = '';
					foreach($rowColKeys as $colKey)
					{
						if ($colKey == 'Description')
						{
							if ($rowCols[$colKey] == '')
							{
								$atRowsCols .= \HTML::rawElement('td', array('class' => ('attachmentRow_col_' . $colKey)), '');
							}
							else if (strlen($rowCols[$colKey]) > $wgPageAttachment_descriptionMaxLength)
							{
								$descriptionAndIcon = '';
								$desc = \PageAttachment\Utility\StringUtil::trimText($rowCols[$colKey], $wgPageAttachment_descriptionMaxLength);
								$descriptionAndIcon = $desc . '... ' . $viewMoreImgIcon;
								$atRowsCols .= \HTML::rawElement('td',  array('class' => ('attachmentRow_col_' . $colKey),
								'onmouseover' => 'pageAttachment_showPopup(this, "' . $wgPageAttachment_descriptionPopupWidth . '", "' . 
								$wgPageAttachment_descriptionPopupHeight . '", "' . $rowCols[$colKey] . '", ' .
								(($rtlLang == true) ? "true" : "false" ).');',
								'onmouseout' => 'pageAttachment_removePopup();'), $descriptionAndIcon);
							}
							else
							{
								$atRowsCols .= \HTML::rawElement('td', array('class' => ('attachmentRow_col_' . $colKey)), $rowCols[$colKey]);
							}
						}
						else
						{
							$atRowsCols .= \HTML::rawElement('td', array('class' => ('attachmentRow_col_' . $colKey)), $rowCols[$colKey]);
						}
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

	private function getTitleRowColSpan($skinName, $userLangCode, $colName)
	{
		global $wgPageAttachment_titleRowColSpan;

		$colSpan = 0;
		if (isset($wgPageAttachment_titleRowColSpan[$skinName][$userLangCode][$colName]))
		{
			$colSpan =  $wgPageAttachment_titleRowColSpan[$skinName][$userLangCode][$colName];
		}
		else if (isset($wgPageAttachment_titleRowColSpan['default'][$userLangCode][$colName]))
		{
			$colSpan =  $wgPageAttachment_titleRowColSpan['default'][$userLangCode][$colName];
		}
		else
		{
			$colSpan =  $wgPageAttachment_titleRowColSpan['default']['default'][$colName];
		}
		return $colSpan;
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

	private function getColWidths($skinName, $userLangCode)
	{
		global $wgPageAttachment_colToDisplay;
		global $wgPageAttachment_colWidth;

		$colWidths = array();
		foreach ($wgPageAttachment_colToDisplay as $colToDisplay)
		{
			if (isset($wgPageAttachment_colWidth[$skinName][$userLangCode][$colToDisplay]))
			{
				$colWidths[] =  $wgPageAttachment_colWidth[$skinName][$userLangCode][$colToDisplay];
			}
			else if (isset($wgPageAttachment_colWidth['default'][$userLangCode][$colToDisplay]))
			{
				$colWidths[] =  $wgPageAttachment_colWidth[$skinName][$userLangCode][$colToDisplay];
			}
			else
			{
				$colWidths[] =  $wgPageAttachment_colWidth['default']['default'][$colToDisplay];
			}
		}
		return $colWidths;
	}

}

## ::END::
