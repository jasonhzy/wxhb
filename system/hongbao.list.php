<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
if($_GET['act']=='del'){
$id=intval($_GET['id']);
$dbconn->noretquery("delete from ".DBQIAN."user_xianjin where id=$id");
header("Location: hongbao.list.php?page=$_GET[page]");
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
    <li>红包发放记录</li>
  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li><a href="excel.hongbaolist.php"><span><img src="images/ico03.png" /></span>导出</a></li> 
      <li><a onClick="if(confirm(\'您确认要清空吗？\')){location.href=\'clear.hongbao.php\'}" href="#"><span><img src="images/t03.png" /></span>清空记录</a></li>

    </ul>
  </div>
  <table class="tablelist">
    <thead>
      <tr>
        <th width="10%">昵称</th>
        <th width="9%">性别</th>
        <th width="12%">地区</th>
        <th width="13%">金额</th>
        <th width="21%">微信Openid</th>
        <th width="13%">红包来源</th>
        <th width="14%">时间</th>
        <th width="8%">操作</th>
      </tr>
    </thead>
    <tbody>
      ';
$page = ($_GET['page'] == ''||!isset($_GET['page'])) ?1:$_GET['page'];
$pagesize=12;
$num=$dbconn->countn(DBQIAN."user_xianjin");
$pagelist = new page($page,$pagesize,$num ,10,2,0);
$query=$dbconn->news_list(" select * from ".DBQIAN."user_xianjin order by id desc ",$page,$pagesize);
while($row=$dbconn->fetch($query)){
$urow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."user_list where ucode='$row[ucode]'"));
;echo '      <tr height="45">
        <td>';echo $urow['uickname'];echo '</td>
        <td>';
if($urow['usex']==0) echo '未知';
if($urow['usex']==1) echo '男';
if($urow['usex']==2) echo '女';
;echo '</td>
        <td>';echo $urow['udizhi'];;echo '</td>
        <td>';echo ($row['umoney']/100).'元';;echo '</td>
        <td>';echo $row['ucode'];;echo '</td>
        <td>
          ';
if($row['xtype']==2){echo "奖励红包";}
;echo '        </td>
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