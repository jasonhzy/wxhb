<?php
session_start ();
include ("../includes/conn.php");
$dbconn->admin_logincheck ();
$adminid = $_SESSION ['adminid'];
$cid = intval ( $_GET ['cid'] );
$nid = intval ( $_GET ['nid'] );
if ($nid > 0) {
	$conrow = $dbconn->fetch ( $dbconn->query ( "select * from " . DBQIAN . "caidan_list where id=$nid" ) );
}
if ($_GET ['act'] == 'add') {
	$cnum = intval ( $_POST ['cnum'] );
	$cname = $_POST ['cname'];
	$ctype = $_POST ['ctype'];
	$curl = $_POST ['curl'];
	$ckey = $_POST ['ckey'];
	if ($nid > 0) {
		$dbconn->noretquery ( "update " . DBQIAN . "caidan_list set cname='$cname',cnum=$cnum,ctype=$ctype,curl='$curl',ckey='$ckey' where id=" . $nid );
	} else {
		$dbconn->noretquery ( "insert into " . DBQIAN . "caidan_list(cid,cname,ctype,curl,ckey,cnum,adminid)values
		($cid,'$cname',$ctype,'$curl','$ckey',$cnum,$adminid)" );
	}
	$dbconn->showalert ( "保存成功", "caidan.list.php?cid=" . $cid, 1 );
}
;
echo '<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎登录后台管理系统</title>
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/right.css" rel="stylesheet" type="text/css">
<link href="css/select.css" rel="stylesheet" type="text/css">

<script src="js/jquery.js"></script>
<script src="js/select-ui.min.js"></script>

<script>
$(document).ready(function(){	
   $(".select1").uedSelect({
		width : 345			  
   });
   
   $("input[name=\'ctype\']").click(function(){
	   var ctype=parseInt($("input[name=\'ctype\']:checked").val());
	   if(ctype==0){$("#guanjianci").hide();$("#weburl").show();}
	   if(ctype==1){$("#guanjianci").show();$("#weburl").hide();}
   });   
});
</script>
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li><a href="caidan.list.php">微信菜单管理</a></li>
    ';
if ($cid > 0) {
	;
	echo '    <li><a href="caidan.list.php?cid=';
	echo $cid;
	echo '">二级菜单</a></li>
    ';
}
;
echo '    <li>编辑</li>
  </ul>
</div>
<div class="formbody">
  <div class="formtitle"><span>基本信息</span></div>
  <form name="fadd" method="post" action="?act=add&cid=';
echo $cid;
;
echo '&nid=';
echo $nid;
;
echo '">
  <ul class="forminfo">
    <li>
      <label>菜单名字</label>
      <input name="cname" type="text" value="';
echo $conrow ['cname'];
;
echo '" class="dfinput" />
      <i></i></li>
    <li>
      <label>菜单顺序</label>
      <input name="cnum" type="text" value="';
echo ($conrow ['cnum'] == '') ? 1 : $conrow ['cnum'];
;
echo '" class="dfinput" />
      <i>数字越大越靠前</i></li>
    <li>
      <label>菜单类型</label>
      <input name="ctype" type="radio" value="0" ';
if ($conrow ['ctype'] == 0 || $conrow ['ctype'] == '')
	echo "checked";
;
echo ' > 网址跳转&nbsp;
      <input name="ctype" type="radio" value="1" ';
if ($conrow ['ctype'] == 1)
	echo "checked";
;
echo ' > 关键词推送
      <i>说明：网址跳转是，点击这个菜单会跳转到一个网页。关键词推送是，点击这个菜单会弹出一个消息框，消息框的内容需要设置。</i></li>
    <li id="weburl" ';
if ($conrow ['ctype'] == 1) {
	;
	echo ' style="display:none" ';
}
;
echo '>
      <label>跳转网址</label>
      <input name="curl" type="text" value="';
echo $conrow ['curl'];
;
echo '" class="dfinput" />
      <i>如果菜单类型选择了网址跳转，此项必填。网址以http开头</i></li>
    <li id="guanjianci" ';
if ($conrow ['ctype'] == 0 || $conrow ['ctype'] == '') {
	;
	echo ' style="display:none" ';
}
;
echo '>
      <label>关键词</label>
      <div class="vocation">
      <select name="ckey" class="select1">
       ';
$query = $dbconn->query ( "select * from " . DBQIAN . "news_key where stype=1 order by id desc" );
while ( $row = $dbconn->fetch ( $query ) ) {
	;
	echo '          <option value="';
	echo $row ['sname'];
	;
	echo '" ';
	if ($conrow ['ckey'] == $row ['sname'])
		echo "selected";
	;
	echo '  >
		  ';
	echo $row ['sname'];
	;
	echo '</option>
       ';
}
;
echo '        </select>
        </div>
      <i><a href="huifu.lists.php?stype=1"><font color="#FF0000">关键词管理</font></a> 如果菜单类型选择了关键词推送，此项必选</i></li>
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