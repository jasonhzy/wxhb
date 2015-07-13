<?php
session_start();
include("../includes/conn.php");
$adminid=$_SESSION['adminid'];
$upass=$_POST['upass'];
$dbconn->noretquery("update ".DBQIAN."sys_user set upass='$upass' where id=$adminid ");
?>