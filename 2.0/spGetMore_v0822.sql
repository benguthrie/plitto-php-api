-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spGetMore`(
	thetype VARCHAR(20),
	userId INT(10),
	forUserIDs INT(10) ,
	thelists TEXT, 
	thethings TEXT
)
BEGIN

SET @thetype = thetype;
SET @userId = userId; -- This user's user id.
SET @forUserIDs = forUserIDs; -- The target id.
SET @thelists = thelists;	-- Existing
SET @thethings = thethings;	-- Existing




if @thetype like 'list' then
	SET @listadendum = CONCAT(' and a.lid in ',@thelists);
else
	SET @listadendum = '';
end if;


--  Removed duid set @thefields = ' a.id, a.tid, a.lid, a.uid, a.added, a.modified, a.duid, a.state ';
set @thefields = ' a.id, a.tid, a.lid, a.uid, a.added, a.modified, a.state ';
-- set @insertTable = 'insert into temp_splists (`id`,`tid`,`lid`,`uid`,`a`,`m`,`duid`,`state`,`myKey`)';
set @insertTable = 'insert into temp_splists (`id`,`tid`,`lid`,`uid`,`a`,`m`,`state`,`myKey`,`show`,`listid`,`grouporder`)';

SET @defaultlimit = 200;

drop table if exists `temp_splists`;

CREATE table temp_splists (
	`id` INT(11),
	`tid` int(11),
	`lid` int(11),
	`uid` int(11),
	`a` datetime,
	`m` datetime,
	-- Removed. This will move to notifications and dittos. `duid` int(11),
	`state` tinyint(1),
	`myKey` int(11),
	`listid` VARCHAR(20),
	`show` BINARY,
	`grouporder` tinyint(1)
);

-- Dittoable 
SET @dittoable = CONCAT(@insertTable, 
	' select ', @thefields,' , b.id, true as `show`, CONCAT(a.uid,"_",a.lid) as listid, 1 as grouporder
	from tlist a 
	left outer join tlist b on b.lid = a.lid and b.tid = a.tid and b.state = 1 and b.uid = ',@userId,' 
	where a.uid in (',@forUserIDs,') and a.state = 1 and b.state is null 
		',@listadendum,' 
		and a.tid not in (',@thethings,')
	order by a.id desc
	limit ',@defaultlimit);


-- Shared 
SET @shared = CONCAT(@insertTable, 
	' select ', @thefields,' , b.id, true as `show`,CONCAT(a.uid,"_",a.lid) as listid, 2 as grouporder
	from tlist a 
	inner join tlist b on b.lid = a.lid and b.tid = a.tid and b.state = 1 and b.uid = ',@userId,'
	where a.uid in (',@forUserIDs,') and a.state = 1 and b.state = 1  
		',@listadendum,' 
		and a.tid not in (',@thethings,')
	order by a.id desc
	limit ',@defaultlimit);

prepare stmt from @dittoable;
	execute stmt;
	deallocate prepare stmt; 

prepare stmt from @shared;
	execute stmt;
	deallocate prepare stmt;


/* Result Set 1: List Contents */
-- SELECT * FROM temp_splists order by uid desc, lid asc, a desc;
select * from (
select t.id, t.tid, t.lid, t.uid, t.a, t.m, t.state, t.myKey 
	, a.name as thingname, b.name as username, c.name as listname
	, b.fbuid, `show`, listid , 1 as customOrder
from temp_splists t
inner join tthing a on t.tid = a.id
inner join tthing c on t.lid = c.id
inner join tuser b on b.id = t.uid
where uid != @userId


UNION
select t.id, t.tid, t.lid, t.uid, t.a, t.m, t.state, t.myKey 
	, a.name as thingname, b.name as username, c.name as listname
	, b.fbuid, `show`, listid , 2 as customOrder
from temp_splists t
inner join tthing a on t.tid = a.id
inner join tthing c on t.lid = c.id
inner join tuser b on b.id = t.uid
where uid = @userId)
as x order by customOrder desc, listid desc
;

/* Result Set 2: Thing Names 

select * from (
	select l.tid as id, a.name
	from temp_splists l
	inner join tthing a on a.id = l.tid


	union 


	select  l.lid as id, a.name
	from temp_splists l
	inner join tthing a on a.id = l.lid
) 
as b
order by id asc;
*/
/*
 // Result Set 2: User Names 
select distinct a.uid , b.name, b.fbuid
from temp_splists a 
inner join tuser b on a.uid = b.id;
*/




END