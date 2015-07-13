<?php
//加密方式：php源码混淆类加密。免费版地址:http://www.zhaoyuanma.com/phpjm.html 免费版不能解密,可以使用VIP版本。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (免费版）在线逆向还原，QQ：7530782 
?>
<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=$_SESSION['adminid'];
$stype=intval($_GET['stype']);
$nid=intval($_GET['nid']);
if($nid >0 ) {
$conrow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."news_key where id=$nid"));
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
$sname=$_POST['sname'];
$kcode=intval($_POST['kcode']);
$times=date("Y-m-d H:i:s");
if($nid >0){
$dbconn->noretquery("update ".DBQIAN."news_key set sname='$sname',kcode=$kcode where id=".$nid);
}else {
$dbconn->noretquery("insert into ".DBQIAN."news_key(sname,stype,kcode,adminid,stime)values
	   ('$sname',$stype,$kcode,$adminid,'$times')");
}
$dbconn->showalert("保存成功","huifu.lists.php?stype=".$stype,1);
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
    <li><a href="huifu.lists.php?stype=';echo $stype;;echo '">';echo $titles;;echo '</a></li>
    <li>编辑</li>
  </ul>
</div>
<div class="formbody">
  <div class="formtitle"><span>基本信息</span></div>
  <form name="fadd" method="post" action="?act=add&stype=';echo $stype;;echo '&nid=';echo $nid;;echo '" >
   <ul class="forminfo">
    <li>
      <label>关键词</label>
      <input name="sname" type="text" value="';echo $conrow['sname'];echo '" class="dfinput" >
      <i>关键词“默认”，在其他关键词找不到的时候推送默认关键词的内容</i></li>
    <li>
      <label>消息类型</label>
      <div style="line-height:34px;">
        <input type="radio" name="kcode" value="0" ';if ($conrow['kcode']==0 ||$conrow['kcode']=='') echo "checked";;echo ' >  文本消息&nbsp;
        <input type="radio" name="kcode" value="1" ';if ($conrow['kcode']==1) echo "checked";;echo ' > 图文消息
      </div>
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