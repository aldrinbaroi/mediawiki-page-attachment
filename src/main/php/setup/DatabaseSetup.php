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

namespace PageAttachment\Setup;

class DatabaseSetup
{
	function __construct() {
	}

	function setupDatabase()
	{
		global $wgDBtype;
		global $wgDatabase;
		global $wgExtNewTables;
		global $wgExtNewIndexes;
		global $wgPageAttachment_useInternalCache;
		global $wgPageAttachment_internalCacheType;
		global $wgPageAttachment_enableAuditLog;

		switch ($wgDBtype)
		{
			case 'mysql':
			case 'postgres':
				break;
			default:
				throw new \ErrorException('Fatal Error: [' . $wgDBtype . '] is not yet supported!');
		}

		$sqlFileDir = dirname( __FILE__ ) . '/sql/'. $wgDBtype . '/';

		# Attachment Data Table
		$tableName['data'] = 'page_attachment_data';
		$tableSqlFile['data'] = $sqlFileDir . 'data.table.sql';
		$indexSqlFile['data'] = $sqlFileDir . 'data.index.sql';

		# Attacment Delete Data Table
		$tableName['deleteData'] = 'page_attachment_delete_data';
		$tableSqlFile['deleteData'] = $sqlFileDir . 'deletedata.table.sql';
		$indexSqlFile['deleteData'] = $sqlFileDir . 'deletedata.index.sql';

		# Cache Table
		$tableName['cache'] = 'page_attachment_cache';
		$tableSqlFile['cache'] = $sqlFileDir . 'cache.table.sql';
		$indexSqlFile['cache'] = $sqlFileDir . 'cache.index.sql';

		# Audit Log Table
		$tableName['auditLog'] = 'page_attachment_audit_log';
		$tableSqlFile['auditLog'] = $sqlFileDir . 'auditlog.table.sql';
		$indexSqlFile['auditLog'] = $sqlFileDir . 'auditlog.index.sql';

		$wgExtNewTables[]  = array($tableName['data'], $tableSqlFile['data']);
		$wgExtNewIndexes[] = array($tableName['data'], $tableName['data'], $indexSqlFile['data']);
		$wgExtNewTables[]  = array($tableName['deleteData'], $tableSqlFile['deleteData']);
		$wgExtNewIndexes[] = array($tableName['deleteData'], $tableName['deleteData'], $indexSqlFile['deleteData']);

		if (isset($wgPageAttachment_useInternalCache) && ($wgPageAttachment_useInternalCache == true))
		{
			if (isset($wgPageAttachment_internalCacheType) && ($wgPageAttachment_internalCacheType == 'Database'))
			{
				$wgExtNewTables[]  = array($tableName['cache'], $tableSqlFile['cache']);
				$wgExtNewIndexes[] = array($tableName['cache'], $tableName['cache'], $indexSqlFile['cache']);
			}
		}
		if (isset($wgPageAttachment_enableAuditLog) && ($wgPageAttachment_enableAuditLog == true))
		{
			{
				$wgExtNewTables[]  = array($tableName['auditLog'], $tableSqlFile['auditLog']);
				$wgExtNewIndexes[] = array($tableName['auditLog'], $tableName['auditLog'], $indexSqlFile['auditLog']);
			}
		}
		return true;
	}

}

## ::END::
