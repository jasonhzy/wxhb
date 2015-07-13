<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=intval($_SESSION['adminid']);
$sysconfig=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."sys_config where adminid=$adminid"));
;echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/right.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="js/jquery.js"></script>
<script>
$(document).ready(function(){	
   $(function(){
      $(\'.rightinfo tbody tr:odd\').css("backgroundColor","#f5f8fa");	
   })
   $(".btn").click(function(){
	  var cyongjin=$("input[name=\'cyongjin\']").val();
	  var cappid=$("input[name=\'cappid\']").val();
	  var cappsecret=$("input[name=\'cappsecret\']").val();
	  var cmchid=$("input[name=\'cmchid\']").val();
	  var cappkey=$("input[name=\'cappkey\']").val();
	  var cdenglucode=$("input[name=\'cdenglucode\']:checked").val();	  
	  
	  if(cappid==\'\' || cappsecret==\'\'){
		 alert("微信Appid、微信Appsecret不能为空");
		 return false;  
      }
	   $.post("ajax.config.php",{
			 cyongjin:cyongjin,
		     cappid:cappid,
			 cdenglucode:cdenglucode,
		     cappsecret:cappsecret,
			 cmchid:cmchid,
			 cappkey:cappkey
		   },function(data){
		     alert("修改成功");
	   });
   });
});
</script>
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li>公众号设置</li>
  </ul>
</div>
<div class="formbody">
  <ul class="forminfo">
    <li>
      <label>服务号appid</label>
      <input name="cappid" type="password" value="';echo $sysconfig['cappid'];;echo '" class="dfinput" />
      <i>服务号appid</i></li>
    <li>
      <label>服务号secret</label>
      <input name="cappsecret" type="password" value="';echo $sysconfig['cappsecret'];;echo '" class="dfinput" />
      <i>服务号appsecret</i></li>
    <li>
      <label>获取头像</label>
      <input name="cdenglucode" type="checkbox" value="1" ';if(intval($sysconfig['cdenglucode'])==1) echo "checked";;echo ' >
      <i>如果勾选，第一次登陆带登陆框,可以获取用户信息</i></li>
    <li>
      <label>微信商户号</label>
      <input name="cmchid" type="text" value="';echo $sysconfig['cmchid'];;echo '" class="dfinput" />
      <i></i></li>
    <li>
      <label>商户密钥</label>
      <input name="cappkey" type="text" value="';echo $sysconfig['cappkey'];;echo '" class="dfinput" />
      <i></i></li>
    <li>
      <label>分享后红包</label>
      <input name="cyongjin" type="text" value="';echo ($sysconfig['cyongjin']=='')?0:$sysconfig['cyongjin'];;echo '" class="dfinput" />
      <i>分 默认0，分享给别人，自己获得的红包奖励</i></li>
    <li>
      <label>&nbsp;</label>
      <input name="button" type="button" class="btn" value="确认保存"/>
    </li>
  </ul>
</div>
</body>
</html>
';$tools->check_kuai();
?>