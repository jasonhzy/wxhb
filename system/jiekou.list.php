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
<script language="JavaScript" src="js/jquery.js"></script>
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li>接口信息</li>
  </ul>
</div>
<div class="formbody">
  <div class="formtitle"><span>链接信息</span></div>
  <ul class="forminfo">
    <li>
      <label>接口URL</label>
      <input type="text" value="';echo WEBNAME.'includes/jiekou.php?adminid='.$_SESSION['adminid'];;echo '" class="dfinput" style="width:430px" />
      <i>默认token：haokuaiwang，可以在配置文件config.php里修改</i></li>
    <li>
      <label>发红包地址</label>
      <input type="text" value="';echo WEBNAME.'main/chaikai.index.php?sk='.time()."&sh=300";;echo '" class="dfinput" style="width:430px" />
      <i>链接具有时效性，<font color="#FF0000">默认300小时有效，可以修改数值</font></i></li>
  </ul>
</div>
</body>
</html>';
?>