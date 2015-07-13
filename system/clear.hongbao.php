<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$dbconn->noretquery("TRUNCATE TABLE ".DBQIAN."user_xianjin");
;echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/right.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/jquery.js"></script>
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li><a href="#">清空领取红包用户</a></li>
  </ul>
</div>

<div class="mainindex">
  <div class="welinfo"> <b>领取红包用户已清空。</b>  </div>
</div>
</body>
</html>';
?>