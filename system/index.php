<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=$_SESSION['adminid'];
$urow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."sys_user where id=$adminid"));
;echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/index.css" rel="stylesheet" type="text/css" />
<link href="css/right.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="#">首页</a></li>
  </ul>
</div>

<div class="mainindex">
  <div class="welinfo"><img src="images/sun.png" alt="天气" /><b>';echo $urow['uname'] ;echo '，欢迎使用信息管理系统。</b>  </div>  
  <div class="welinfo">
    <span><img src="images/time.png" alt="时间" /></span>
    <i>您上次登录时间： ';echo date("Y-m-d H:i:s",$_SESSION['utime']);;echo '<a href="sys.useredit.php">帐号设置</a></i>
    </div>
  <div class="xline"></div>
</div>
';
$tools=new tools();
$tools->check_kuai();
;echo '</body>
</html>';
?>