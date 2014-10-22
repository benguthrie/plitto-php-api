-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spPlittoFriendsFromFb`(friendString TEXT)
BEGIN

SET @q = CONCAT('
select group_concat(id SEPARATOR ",") as puids from tuser where fbuid in (',friendString,')');

prepare stmt from @q;
execute stmt;
deallocate prepare stmt;

END