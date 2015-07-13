<?php
session_start();
include("../includes/conn.php");
$tools=new tools();
$adminid=intval($_SESSION['adminid']);
$cyongjin=$_POST['cyongjin'];
$cdenglucode=( intval($_POST['cdenglucode'])==0 ) ?2:1;
$cappid= trim($_POST['cappid']);
$cappsecret= trim($_POST['cappsecret']);
$cmchid= $tools->sql_mag_gpc(trim($_POST['cmchid']));
$cappkey= $tools->sql_mag_gpc(trim($_POST['cappkey']));
$num=$dbconn->countn(DBQIAN."sys_config"," adminid=$adminid ");
if($num==1){
$dbconn->noretquery("update ".DBQIAN."sys_config set 
   cyongjin=$cyongjin,
   cappid='$cappid',
   cappsecret='$cappsecret',
   cmchid='$cmchid',
   cdenglucode=$cdenglucode,
   cappkey='$cappkey' where adminid=$adminid");
}else {
$dbconn->noretquery("insert into ".DBQIAN."sys_config(cyongjin,cappid,cappsecret,cdenglucode,cmchid,cappkey,adminid)values
	($cyongjin,'$cappid','$cappsecret',$cdenglucode,'$cmchid','$cappkey',$adminid)");
}
?>