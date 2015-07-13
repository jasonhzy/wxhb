<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=intval($_SESSION['adminid']);
if($_GET['act']=='del'){
$nid=intval($_GET['nid']);
$dbconn->noretquery("delete from ".DBQIAN."wxname_list where id=$nid");
header("Location: wxname.list.php");
exit;
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
    <li>公众号管理</li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li><a href="wxname.add.php"><span><img src="images/t01.png" /></span>添加公众号</a></li> 
      <li style="background:#FFF; text-indent:1em; border:0">
      <form name="fsoso" method="get" action="">
      <input name="wxname" type="text" class="dfinput" style="width:200px">
      <input name="submit" class="btn" value="查询" type="submit">
      </form>
      </li>
    </ul>
  </div>
    <table class="tablelist">
    <thead>
      <tr>
        <th width="40%">公众号名字</th>
        <th width="34%">公众号账号</th>
        <th width="26%">操作</th>
      </tr>
    </thead>
    <tbody>
      ';
$page = ($_GET['page'] == ''||!isset($_GET['page'])) ?1:$_GET['page'];
$pagesize=12;
$sql=" adminid=$adminid ";
if($_GET['wxname']!=''){
$sql=$sql." and wxname like '%$_GET[wxname]%' or wxzhanghao like '%$_GET[wxname]%' ";
}
$num=$dbconn->countn(DBQIAN."wxname_list",$sql);
$pagelist = new page($page,$pagesize,$num ,10,2,0);
$query=$dbconn->news_list(" select * from ".DBQIAN."wxname_list where $sql order by id desc ",$page,$pagesize);
while($row=$dbconn->fetch($query)){
;echo '      <tr height="45">
        <td>';echo $row['wxname'];;echo '</td>
        <td>';echo $row['wxzhanghao'];;echo '</td>
        <td>
        <img src="images/leftico03.png" width="14"> 
        <a href="wxname.add.php?nid=';echo $row['id'];;echo '">编辑</a>&nbsp;
        <img src="images/t03.png" width="14"> 
        <a onClick="if(confirm(\'您确认要删除吗？\')){window.location.href=\'?act=del&nid=';echo $row['id'];echo '\'}" href="#" class="tablelink">删除</a>
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