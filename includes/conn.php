<?php
require_once("config.php");
require_once("page.class.php");
require_once("tools.class.php");
require_once("mysql.class.php");
require_once("pubaction.class.php");
$dbconn=new pubaction(DBHOST,DBUSER,DBPASS,DBDATA,"UTF-8");
?>