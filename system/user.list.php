<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=$_SESSION['adminid'];
if($_GET['act']=='del'){
$id=intval($_GET['id']);
$dbconn->noretquery("delete from ".DBQIAN."user_list where id=$id");
header("Location: user.list.php?page=$_GET[page]");
};echo '<!DOCTYPE HTML>
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
    <li>用户管理</li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li style="background:#FFF; text-indent:1em; border:0">
      <form name="fsoso" method="get" action="">
        昵称 <input type="text" name="uickname" class="dfinput" style="width:200px" />
      <input name="submit" class="btn" value="查询" type="submit" >
      </form></li>
    </ul>
  </div>
  <table class="tablelist">
    <thead>
      <tr>
        <th width="12%">昵称</th>
        <th width="9%">性别</th>
        <th width="14%">头像</th>
        <th width="19%">地区</th>
        <th width="13%">推荐人</th>
        <th width="14%">时间</th>
        <th width="7%">操作</th>
      </tr>
    </thead>
    <tbody>
      ';
$page = ($_GET['page'] == ''||!isset($_GET['page'])) ?1:$_GET['page'];
$pagesize=8;
$sql="id > 0";
if($_GET['uickname']!=''&&isset($_GET['uickname'])) {
$sql=$sql." and  uickname like '%$_GET[uickname]%'";
}
$num=$dbconn->countn(DBQIAN."user_list",$sql);
$pagelist = new page($page,$pagesize,$num ,10,2,0);
$query=$dbconn->news_list(" select * from ".DBQIAN."user_list where $sql order by id desc ",$page,$pagesize);

while($row=$dbconn->fetch($query)){
;echo '      <tr height="45">
        <td>';echo $row['uickname'];;echo '</td>
        <td>';
if($row['usex']==0) echo '未知';
if($row['usex']==1) echo '男';
if($row['usex']==2) echo '女';
;echo '</td>
        <td>';if($row['uheadimgurl']!='') {;echo '<img src="';echo substr($row['uheadimgurl'],0,-1).'132';;echo '" height="54"/>';};echo '</td>
        <td>';echo $row['udizhi'];;echo '</td>
        <td>';
if($row['utcode']!=''){
$utorow=$dbconn->fetch($dbconn->query(" select * from ".DBQIAN."user_list where ucode='$row[utcode]' "));
echo $utorow['uickname'];
}
;echo '</td>
        <td>';echo date("Y-m-d H:i:s",$row['utime']);;echo '</td>
        <td>
          <img src="images/t03.png" width="14"> 
          <a onClick="if(confirm(\'您确认要删除吗？\')){window.location.href=\'?act=del&id=';echo $row['id'];echo '&page=';echo $page;echo '\'}" href="#" class="tablelink">删除</a>
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