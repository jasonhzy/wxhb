<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
;echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/right.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li>微信自动回复</li>
  </ul>
</div>
<div class="rightinfo">
  <ul class="imglist">
      <li class="selected">
        <a href="huifu.lists.php?stype=0">
        <span><img src="images/wx_1.png" width="110" height="110" /></span>
        <p>被关注自动回复</p>
        </a>
      </li>
      <li class="selected">
        <a href="huifu.lists.php?stype=1">
        <span><img src="images/wx_3.png" width="110" height="110" /></span>
        <p>关键词自动回复</p>
        </a>
      </li>
    </ul>
</div>
</body>
</html>';
?>