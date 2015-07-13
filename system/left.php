<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
;echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/left.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery.js"></script>
<script type="text/javascript">
$(function(){	
	//导航切换
	$(".menuson li").click(function(){
		$(".menuson li.active").removeClass("active")
		$(this).addClass("active");
	});	
	$(\'.title\').click(function(){
		var $ul = $(this).next(\'ul\');
		$(\'dd\').find(\'ul\').slideUp();
		if($ul.is(\':visible\')){
			$(this).next(\'ul\').slideUp();
		}else{
			$(this).next(\'ul\').slideDown();
		}
	});
})
</script>
</head>
<body style="background:#f0f9fd;">
<div class="lefttop"><span></span>功能列表</div>
<dl class="leftmenu">
  <dd>
    <div class="title"><span><img src="images/leftico01.png" /></span>基本管理 </div>
    <ul class="menuson">
      <li><cite></cite><a href="jiekou.list.php" target="rightFrame">公共信息</a><i></i></li>
      <li><cite></cite><a href="user.list.php"  target="rightFrame">用户列表</a><i></i></li>
      
      <li><cite></cite><a href="wxname.list.php"  target="rightFrame">公众号管理</a><i></i></li>
      
      <li><cite></cite><a href="hongbao.set.php" target="rightFrame">红包设置</a><i></i></li>
      <li><cite></cite><a href="hongbao.list.php"  target="rightFrame">红包记录</a><i></i></li>
      <li><cite></cite><a href="user.txt.php"  target="rightFrame">调侃语言</a><i></i></li>
      
      <li><cite></cite><a href="huifu.list.php" target="rightFrame">微信自动回复</a><i></i></li>
      <li><cite></cite><a href="caidan.list.php" target="rightFrame">微信菜单管理</a><i></i></li>
      <li><cite></cite><a href="sys.config.php" target="rightFrame">系统设置</a><i></i></li>
      
    </ul>
  </dd>
</dl>
</body>
</html>';
?>