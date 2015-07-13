<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$kid=intval($_GET['kid']);
if($kid >0 ) {
$keyrow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."news_key where id=$kid"));
}
$stype=intval($_GET['stype']);
$nid=intval($_GET['id']);
switch($stype){
case 0:
$titles='被关注自动回复';
break;
case 1:
$titles='关键词自动回复';
break;
}
if($_GET['act']=='del'){
$row=$dbconn->fetch($dbconn->query(" select * from ".DBQIAN."news_send where id=$nid "));
$path="../uploads/".substr($row['stime'],0,4)."/".substr($row['stime'],5,2);
if(file_exists($path."/".$row['spic']))
@unlink($path."/".$row['spic']);
$dbconn->noretquery("delete from ".DBQIAN."news_send where id=$nid");
$dbconn->showalert("成功","huifu.contentlist.php?kid=$kid&stype=$stype",0);
}
if($_GET['act']=='up'){
$dbconn->noretquery("update ".DBQIAN."news_send set snum=snum-1 where id=$nid");
$dbconn->showalert("成功","huifu.contentlist.php?kid=$kid&stype=$stype",0);
}
if($_GET['act']=='down'){
$dbconn->noretquery("update ".DBQIAN."news_send set snum=snum+1 where id=$nid");
$dbconn->showalert("成功","huifu.contentlist.php?kid=$kid&stype=$stype",0);
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
    <li><a href="huifu.lists.php?stype=';echo $stype;echo '">';echo $titles;;echo '</a></li>
    <li>回复内容</li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li><a href="huifu.contentadd.php?stype=';echo $stype;;echo '&kid=';echo $kid;echo '"><span><img src="images/t01.png" /></span>添加回复内容</a></li> 
    </ul>
  </div>
  <table class="tablelist">
    <thead>
      <tr>
        ';if($keyrow['kcode']==0) {;echo '        <th width="65%">标题</th>
        ';}else {;echo '        <th width="20%">标题</th>
        <th width="24%">图片</th>
        <th width="21%">链接</th>
        ';};echo '        <th width="22%">排序</th>
        <th width="13%">操作</th>
      </tr>
    </thead>
    <tbody>
      ';
$page = ($_GET['page'] == ''||!isset($_GET['page'])) ?1:$_GET['page'];
$pagesize=10;
$sql="kid=$kid";
$num=$dbconn->countn(DBQIAN."news_send",$sql);
$pagelist = new page($page,$pagesize,$num ,10,2,0);
$query=$dbconn->news_list(" select * from ".DBQIAN."news_send where ".$sql." order by id desc ",$page,$pagesize);
while($row=$dbconn->fetch($query)){
;echo '      <tr height="45">
      ';if($keyrow['kcode']==0) {;echo '        <td>';echo $row['sdec'];echo '</td>
      ';}else {;echo '        <td>';echo $row['sname'];echo '</td>
        <td>';if($row['spic']!=""){;echo '        <img src="../uploads/';echo substr($row['stime'],0,4);echo '/';echo substr($row['stime'],5,2);echo '/';echo $row['spic'];echo '" height="50" />';};echo '</td>
        <td>';echo $row['surl'];echo '</td>
      ';};echo '        <td>';
if($row['snum']>=1){
echo "<a href='?nid=$row[id]&kid=$kid&act=down&stype=$stype'>下移</a>";
}
if($row['snum']>1){
echo " <a href='?nid=$row[id]&kid=$kid&act=up&stype=$stype'>上移</a>";
}
;echo '        </td>
        <td>
          <img src="images/leftico03.png" width="14"> 
          <a href="huifu.contentadd.php?nid=';echo $row['id'];echo '&stype=';echo $stype;;echo '&kid=';echo $kid;echo '">编辑</a>&nbsp;
          <img src="images/t03.png" width="14"> 
          <a onClick="if(confirm(\'您确认要删除吗？\')){window.location.href=\'?act=del&id=';echo $row['id'];echo '&kid=';echo $kid;echo '&stype=';echo $stype;;echo '\'}" href="#" class="tablelink">删除</a>
        </td>
      </tr>
      ';
}
;echo '    </tbody>
  </table>
</div>
<div style=" width:90%; padding:10px 0 10px 0; text-align:center">
  ';if($num==0) echo "<font color='#ff0000'>暂无数据</font>";;echo '</div>
</body>
</html>';
?>