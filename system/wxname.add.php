<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=intval($_SESSION['adminid']);
$nid=intval($_GET['nid']);
if($nid >0 ) {
$conrow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."wxname_list where id=$nid"));
}
if($_GET['act']=='add'){
$wxname=$_POST['wxname'];
$wxzhanghao=$_POST['wxzhanghao'];
if($nid >0){
$dbconn->noretquery("update ".DBQIAN."wxname_list set wxname='$wxname',wxzhanghao='$wxzhanghao' where id=".$nid);
}else {
$dbconn->noretquery("insert into ".DBQIAN."wxname_list(wxname,wxzhanghao,adminid)values('$wxname','$wxzhanghao',$adminid)");
}
$dbconn->showalert("保存成功","wxname.list.php",1);
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
    <li><a href="wxname.list.php">微信公众号</a></li>
    <li>编辑</li>
  </ul>
</div>
<div class="formbody">
  <div class="formtitle"><span>基本信息</span></div>
  <form name="fadd" method="post" action="?act=add&nid=';echo $nid;;echo '" >
   <ul class="forminfo">
    <li>
      <label>微信名字</label>
      <input name="wxname" type="text" value="';echo $conrow['wxname'];echo '" class="dfinput" >
      <i></i></li>
    <li>
      <label>微信账号</label>
      <input name="wxzhanghao" type="text" value="';echo $conrow['wxzhanghao'];echo '" class="dfinput" >
      <i></i></li>
    <li>
      <label>&nbsp;</label>
      <input name="button" type="submit" class="btn" value="确认保存"/>
    </li>
  </ul>
  </form>
</div>
</body>
</html>';
?>