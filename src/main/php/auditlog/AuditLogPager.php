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

namespace PageAttachment\AuditLog;

if (!defined('MEDIAWIKI'))
{
	echo("This is an extension to the MediaWiki package and cannot be run standalone.\n");
	exit( 1 );
}

class AuditLogPager extends \TablePager
{
	private $userManager;
	private $attachToPage;
	private $attachmentName;
	private $cacheManager;
	private $dateHelper;

	var $mFieldNames = null;
	var $mQueryConds = array();

	function __construct($attachToPage, $attachmentName = NULL)
	{
		global $wgScriptPath;
		global $wgPageAttachment_imgAddUpdateAttachment;

		parent::__construct();
		$this->attachToPage = $attachToPage;
		$this->attachmentName = $attachmentName;
		$this->userManager = new \PageAttachment\User\UserManager();
		$this->cacheManager = new \PageAttachment\Cache\CacheManager();
		$this->dateHelper = new \PageAttachment\Utility\DateUtil();
	}

	function getFieldNames()
	{
		if ( !$this->mFieldNames )
		{
			global $wgMiserMode;

			$this->mFieldNames = array(
				'attached_to_page_id'  => \wfMsg('attached_to_page_id'),
				'attachment_file_name' => \wfMsg('attachment_file_name'),
				'user_id'              => \wfMsg('user_id'),
				'activity_time'        => \wfMsg('activity_time'),
				'activity_type'        => \wfMsg('activity_type')
			);
		}
		return $this->mFieldNames;
	}

	function isFieldSortable( $field )
	{
		static $sortable = array( 'activity_time' );
		return in_array( $field, $sortable );
	}

	function getQueryInfo()
	{
		if (isset($this->attachmentName))
		{
			$dbr =  \wfGetDB( DB_SLAVE );
			$this->mQueryConds = array();
			$this->mQueryConds[] = ' attached_to_page_id  = ' . $this->attachToPage->getId();
			$this->mQueryConds[] = ' attachment_file_name = \'' . $dbr->strencode($this->attachmentName) . '\'';
		}
		else
		{
			$this->mQueryConds = array('attached_to_page_id = ' . $this->attachToPage->getId());
		}
		$tables = array( 'page_attachment_audit_log' );
		$fields = array_keys( $this->getFieldNames() );
		$options = $join_conds = array();
		//$options = array('GROUP BY' => 'attached_to_page_id, attachment_file_name, activity_time' );
		$join_conds = '';
		return array(
				'tables'     => $tables,
				'fields'     => $fields,
				'conds'      => $this->mQueryConds,
				'options'    => $options,
				'join_conds' => $join_conds
		);
	}

	function getDefaultSort()
	{
		return 'activity_time';
	}

	function getIndexField()
	{
		return 'activity_time';
	}

	function formatValue($field, $value)
	{
		switch($field)
		{
			case 'attached_to_page_id':
				$attacheToPageName = $this->cacheManager->retrieveArticleName($value);
				if (isset($attacheToPageName))
				{
					return $attacheToPageName;
				}
				else
				{
					return $value;
				}
				break;

			case 'attachment_file_name':
				return $value;
				break;

			case 'user_id':
				$user = $this->userManager->getUser($value);
				return $user->getUserPageLink();
				break;

			case 'activity_time':
				try
				{
					return $this->dateHelper->formatSQLDate($value);
				}
				catch (Exception $e)
				{
					\wfDebugLog('PageAttachment',$e->getMessage());
					return $value;
				}
				break;
					
			case 'activity_type':
				return \wfMsg(trim($value));
				break;
					
			default:
				return $value;;
		}
	}

	function getForm()
	{
		global $wgRequest, $wgScript, $wgMiserMode;

		$form = \Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript, 'id' => 'pageAttachment-audit-log-form' ) );
		$form .= \Xml::openElement( 'fieldset' );
		$form .= \Xml::element( 'legend', null, \wfMsg( 'AuditLog' ) );
		$form .= \Xml::tags( 'label', null, \wfMsgHtml( 'table_pager_limit', $this->getLimitSelect() ) );
		$form .= ' ';
		$form .= \Xml::submitButton( \wfMsg( 'table_pager_limit_submit' ) ) ."\n";
		$form .= $this->getHiddenFields( array( 'limit') );
		$form .= \Xml::closeElement( 'fieldset' );
		$form .= \Xml::closeElement( 'form' ) . "\n";
		return $form;
	}
	
	function getTitle()
	{
		return \SpecialPage::getTitleFor( 'PageAttachmentAuditLogViewer' );
	}

}

## :: END ::
