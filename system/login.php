<?php
session_start();
if(isset($_GET['act']) &&$_GET['act']=='quit'){
session_destroy();
}
;echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/login.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery.js"></script>
<script src="js/cloud.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$(function(){
       $(\'.loginbox\').css({\'position\':\'absolute\',\'left\':($(window).width()-692)/2});
	   $(window).resize(function(){  
           $(\'.loginbox\').css({\'position\':\'absolute\',\'left\':($(window).width()-692)/2});
        });
	});
	$(".loginbtn").click(function(){
	   var uname=$("input[name=\'uname\']").val();
	   var upass=$("input[name=\'upass\']").val();
	   if(uname==\'\' || upass===\'\'){
		   alert("填写错误");
		   return false;   
	   }
	   $.post("ajax.login.php",{uname:uname,upass:upass},function(data){
		   if(data==0){
		      alert("登录失败");
		      return false;   
		   } else if(data==1) {
			  location.href=\'main.php\';
		   }
	   });
	});
});  
</script>
</head>

<body style="background-color:#1c77ac; background-image:url(images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">
<div id="mainBody">
  <div id="cloud1" class="cloud"></div>
  <div id="cloud2" class="cloud"></div>
</div>
<div class="logintop"> <span>欢迎登录后台管理界面平台</span>
</div>

<div class="loginbody"> <span class="systemlogo" style="display:block"></span>
  <div class="loginbox">  
    <ul>
      <li>
        <input name="uname" type="text" class="loginuser" placeholder=\'用户名\'/>
      </li>
      <li>
        <input name="upass" type="password" class="loginpwd" placeholder=\'密码\'/>
      </li>
      <li>
        <input name="button" type="button" class="loginbtn" value="登录"/>       
      </li>
    </ul>
  </div>
</div>
<div class="loginbm">@2015 版权所有 </div>
</body>
</html>';
?>