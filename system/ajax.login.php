<?php
session_start();
include("../includes/conn.php");
$tools=new tools();
$uname=iconv("UTF-8","GBK",$tools->sql_mag_gpc($_POST['uname']));
$upass=$tools->sql_mag_gpc($_POST['upass']);
$num=$dbconn->countn(DBQIAN."sys_user"," uname='$uname' and upass='$upass' ");
if($num==1){
$row=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."sys_user where uname='$uname' and upass='$upass'"));
$times=time();
$_SESSION['adminid']=$row['id'];
$_SESSION['utime']=$row['utime'];
$dbconn->noretquery("update ".DBQIAN."sys_user set utime=$times where id=$row[id]");
echo 1;
}else {
echo 0;
}
?>