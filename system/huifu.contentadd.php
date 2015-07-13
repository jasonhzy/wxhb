<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$tools=new tools();
$stype=intval($_GET['stype']);
$kid=intval($_GET['kid']);
$nid=intval($_GET['nid']);
if($kid >0 ) {
$keyrow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."news_key where id=$kid"));
}
if($nid >0 ) {
$conrow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."news_send where id=$nid"));
}
switch($stype){
case 0:
$titles='被关注自动回复';
break;
case 1:
$titles='关键词自动回复';
break;
}
if($_GET['act']=='add'){
$stime=$_POST['stime'];
$spic=$_POST['spic'];
$sname=$_POST['sname'];
$surl=$_POST['surl'];
$sdec=$_POST['sdec'];
if($_FILES['newimg']['name']!=""){
$path="../uploads/".substr($stime,0,4)."/".substr($stime,5,2);
$tools->create_dirs($path);
@unlink($path."/".$spic);
$houZhui=$tools->get_files_endname($_FILES['newimg']['name']);
$spic=date("YmdHis").$tools->get_random(5).'.'.$houZhui;
$tools->upload_img($_FILES['newimg'],$spic,$path."/");
}
if($nid >0){
$dbconn->noretquery("update ".DBQIAN."news_send set spic='$spic',sname='$sname',surl='$surl',sdec='$sdec' where id=".$nid);
}else {
$dbconn->noretquery("insert into ".DBQIAN."news_send(kid,sname,sdec,spic,surl,stime)values
		($kid,'$sname','$sdec','$spic','$surl','$stime')");
}
$dbconn->showalert("保存成功","huifu.contentlist.php?stype=".$stype."&kid=".$kid,1);
}
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
    <li><a href="huifu.list.php">微信回复</a></li>
    <li><a href="huifu.lists.php?stype=';echo $stype;echo '">';echo $titles;;echo '</a></li>
    <li><a href="huifu.contentlist.php?stype=';echo $stype;echo '&kid=';echo $kid;;echo '">回复内容</a></li>
    <li>编辑</li>
  </ul>
</div>
<div class="formbody">
  <div class="formtitle"><span>基本信息</span></div>
  <form name="fadd" method="post" action="?act=add&stype=';echo $stype;;echo '&nid=';echo $nid;;echo '&kid=';echo $kid;;echo '" enctype="multipart/form-data">
    <input type="hidden" name="stime" value="';echo ($conrow['stime']=='')?date("Y-m-d H:i:s"):$conrow['stime'];;echo '">
    <input type="hidden" name="spic" value="';echo $conrow['spic'];;echo '">
   <ul class="forminfo">
   ';if($keyrow['kcode']==1) {;echo '    <li>
      <label>标题</label>
      <input name="sname" type="text" value="';echo $conrow['sname'];echo '" class="dfinput" >
      <i></i></li>
    <li>
      <label>图片</label>
      <input name="newimg" type="file" class="dfinput">
      <i></i></li>
    <li>
      <label>链接</label>
      <input name="surl" type="text" value="';echo $conrow['surl'];echo '" class="dfinput" >
      <i><a href="jiekou.list.php" target="_blank">【公共接口】</a></i></li>
    ';};echo '    <li>
      <label>内容</label>
      <textarea name="sdec" cols="50" rows="5" class="textinput">';echo $conrow['sdec'];echo '</textarea>
      <i>如果添加链接，格式为：&lt;a href=&quot;网址&quot;&gt;文字&lt;/a&gt;</i></li>
    <li>
      <label>&nbsp;</label>
      <input type="submit" name="button" class="btn" value="确认保存" />
    </li>
  </ul>
  </form>
</div>
</body>
</html>';
?>