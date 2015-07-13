<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=$_SESSION['adminid'];
$stype=intval($_GET['stype']);
if($_GET['act']=='del'){
$id=intval($_GET['id']);
$dbconn->noretquery("delete from ".DBQIAN."news_key where id=$id");
$dbconn->noretquery("delete from ".DBQIAN."news_send where kid=$id");
header("Location: huifu.lists.php?stype=$stype");
}
switch($stype){
case 0:
$titles='被关注自动回复';
break;
case 1:
$titles='关键词自动回复';
break;
}
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
   });
});
</script>
</head>

<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li><a href="huifu.list.php">微信回复</a></li>
    <li>';echo $titles;;echo '</li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      ';
$nums=$dbconn->countn(DBQIAN."news_key","adminid=$adminid and stype=$stype");
if($nums==0 ||$stype==1) {
;echo '      <li><a href="huifu.add.php?stype=';echo $stype;;echo '"><span><img src="images/t01.png" /></span>添加关键词</a></li> 
      ';
}
;echo '      <li style="background:#FFF; text-indent:1em; border:0">
      <form name="fsoso" method="get" action="">关键词：
      <input name="stype" type="hidden" class="dfinput"  value="';echo $stype;;echo '">
      <input name="sname" type="text" class="dfinput" style="width:200px">
      <input name="submit" class="btn" value="查询" type="submit">
      </form>
      </li>
    </ul>
  </div>
    <table class="tablelist">
    <thead>
      <tr>
        <th width="51%">关键词</th>
        <th width="23%">回复类型</th>
        <th width="26%">操作</th>
      </tr>
    </thead>
    <tbody>
      ';
$page = ($_GET['page'] == ''||!isset($_GET['page'])) ?1:$_GET['page'];
$pagesize=12;
$sql=" adminid=$adminid and stype=$stype ";
if($_GET['sname']!=''){
$sql=$sql." and sname like '%$_GET[sname]%' ";
}
$num=$dbconn->countn(DBQIAN."news_key",$sql);
$pagelist = new page($page,$pagesize,$num ,10,2,0);
$query=$dbconn->news_list(" select * from ".DBQIAN."news_key where $sql order by id desc ",$page,$pagesize);
while($row=$dbconn->fetch($query)){
;echo '      <tr height="45">
        <td>';echo $row['sname'];;echo '</td>
        <td>
		';
if($row['kcode']==0){
echo "<font color='#FF0000'> 文本 </font>";
}else if($row['kcode']==1){
echo "<font color='#FF0000'> 图文 </font>";
}
;echo '</td>
        <td>
        <img src="images/leftico03.png" width="14"> 
        <a href="huifu.add.php?nid=';echo $row['id'];echo '&stype=';echo $stype;;echo '">编辑</a>&nbsp;
        <img src="images/leftico01.png" width="14"> 
        <a href="huifu.contentlist.php?kid=';echo $row['id'];echo '&stype=';echo $stype;;echo '">回复内容管理</a>&nbsp;
        <img src="images/t03.png" width="14"> 
        <a onClick="if(confirm(\'您确认要删除吗？\')){window.location.href=\'?act=del&id=';echo $row['id'];echo '&stype=';echo $stype;;echo '\'}" href="#" class="tablelink">删除</a>
        </td>
      </tr>
      ';
}
;echo '    </tbody>
  </table>
</div>
<div style=" width:90%; padding:10px 0 10px 0; text-align:center">
  ';if($num!=0) echo $pagelist->showpages();else echo "<font color='#ff0000'>暂无数据</font>";;echo '</div>
</body>
</html>';
?>