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
<link href="css/top.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery.js"></script>
<script type="text/javascript">
$(function(){
	//顶部导航切换
	$(".nav li a").click(function(){
		$(".nav li a.selected").removeClass("selected")
		$(this).addClass("selected");
	});
	$("#deldata").click(function(){
		 if(confirm("您确定要清空数据吗？")){
			  if(confirm("再次确认？")){
				   $.post("ajax.delalldata.php",{},function(data){
					   if(data==1)
					     alert(\'清空完毕\');
				   });
			  }
		 }
	});
});
</script>
</head>
<body style="background:url(images/topbg.gif) repeat-x;">
<div class="topleft"><img src="images/logo.png" title="系统首页" /></div>
<ul class="nav">
  <li><a href="index.php" target="rightFrame" class="selected"><img src="images/icon01.png" title="系统首页" />
    <h2>系统首页</h2>
    </a></li>
  <li id="deldata"><a href="#"><img src="images/icon03.png"  />
    <h2>清空数据</h2>
    </a></li>
    <!--
  <li><a href="imglist.html"  target="rightFrame"><img src="images/icon03.png" title="模块设计" />
    <h2>清空数据</h2>
    </a></li>
  <li><a href="imgtable.html" target="rightFrame"><img src="images/icon02.png" title="模型管理" />
    <h2>模型管理</h2>
    </a></li>
  <li><a href="tools.html"  target="rightFrame"><img src="images/icon04.png" title="常用工具" />
    <h2>常用工具</h2>
    </a></li>
  <li><a href="computer.html" target="rightFrame"><img src="images/icon05.png" title="文件管理" />
    <h2>文件管理</h2>
    </a></li>
  -->
</ul>
<div class="topright">
  <ul>
    <li><a href="login.php?act=quit" target="_parent">退出</a></li>
  </ul>
  <div class="user"> 
  <span>';echo $urow['uname'];;echo '&nbsp;</span>
  <i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</i>
</div>
</div>
</body>
</html>';
?>