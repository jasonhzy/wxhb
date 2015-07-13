<?php
session_start();
require_once('../includes/conn.php');
$dbconn->admin_logincheck();
$sql="TRUNCATE TABLE ".DBQIAN."user_list";
$dbconn->noretquery($sql);
$sql="TRUNCATE TABLE ".DBQIAN."user_xianjin";
$dbconn->noretquery($sql);
echo 1;
?>