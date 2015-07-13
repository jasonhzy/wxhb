<?php
require_once ("config.php");
$db = mysql_connect ( DBHOST, DBUSER, DBPASS ) or die ( "数据库连接错误，请与管理员联系" );
mysql_query ( "SET NAMES 'UTF-8'" );
mysql_select_db ( DBDATA, $db );
?>