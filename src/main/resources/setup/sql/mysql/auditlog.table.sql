--
--
-- Copyright (C) 2011 Aldrin Edison Baroi
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License along
-- with this program; if not, write to the 
--     Free Software Foundation, Inc.,
--     51 Franklin Street, Fifth Floor
--     Boston, MA 02110-1301, USA.
--     http://www.gnu.org/copyleft/gpl.html
--
-- 

CREATE TABLE /*_*/page_attachment_audit_log 
( 
    attached_to_page_id  int NOT NULL, 
    attachment_file_name char(100) character set utf8 not null, 
    user_id              int NOT NULL, 
    activity_time        timestamp NOT NULL, 
    activity_type        char(30) character set utf8 NOT NULL 
) /*$wgDBTableOptions*/;

-- ::End::
