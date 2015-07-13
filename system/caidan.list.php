<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$adminid=$_SESSION['adminid'];
$cid=intval($_GET['cid']);
if($_GET['act']=='del'){
$id=intval($_GET['id']);
$dbconn->noretquery("delete from ".DBQIAN."caidan_list where id=$id");
$dbconn->noretquery("delete from ".DBQIAN."caidan_list where cid=$id");
header("Location: caidan.list.php?cid=$cid");
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
   $(".btn").click(function(){
	   $.get("caidan.create.php",{},function(data){
		   if(data==\'ok\'){
			   alert(\'创建成功\');  
			   return false; 
		   } else {
			   alert(\'创建失败:\'+data);   
			   return false;
		   }
	   });   
   });
});
</script>
</head>

<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li><a href="caidan.list.php">微信菜单管理</a></li>
    ';if($cid >0) {;echo '    <li><a href="caidan.list.php?cid=';echo $cid;echo '">二级菜单</a></li>
    ';};echo '  </ul>
</div>
<div class="rightinfo">
  <div class="tools"> 
    <ul class="toolbar">
      <li><a href="caidan.add.php?cid=';echo $_GET['cid'];echo '"><span><img src="images/t01.png" /></span>添加菜单</a></li>       
    </ul>
    <div style="line-height:40px;"><font color="#FF0000">说明：顶级菜单不能超过3个，二级菜单不能超过5个。</font></div>
  </div>
    <table class="tablelist">
    <thead>
      <tr>
        <th width="33%" height="37">菜单名字</th>
        <th width="20%">菜单类型</th>
        <th width="24%">菜单动作</th>
        <th width="23%">操作</th>
      </tr>
    </thead>
    <tbody>
      ';
$sql=" adminid=$adminid and cid=$cid ";
$num=$dbconn->countn(DBQIAN."caidan_list",$sql);
$query=$dbconn->query("select * from ".DBQIAN."caidan_list where $sql order by cnum desc,id desc ");
while($row=$dbconn->fetch($query)){
;echo '      <tr height="45">
        <td>';echo $row['cname'];;echo '</td>
        <td>';echo ($cid >0) ?'二级菜单':'顶级菜单';;echo '</td>
        <td>';echo ($row['ctype'] == 0) ?'网址跳转':'关键词推送';;echo '</td>
        <td>
        ';if($cid==0) {;echo '          <img src="images/leftico01.png" width="14"> 
          <a href="caidan.list.php?cid=';echo $row['id'];;echo '">下级菜单</a>&nbsp;
        ';};echo '        <img src="images/leftico03.png" width="14">
        <a href="caidan.add.php?nid=';echo $row['id'];echo '&cid=';echo $cid;;echo '">编辑</a>&nbsp;
        <img src="images/t03.png" width="14"> 
        <a onClick="if(confirm(\'您确认要删除吗？\')){window.location.href=\'?act=del&id=';echo $row['id'];echo '&cid=';echo $cid;;echo '\'}" href="#" class="tablelink">删除</a>
        </td>
      </tr>
      ';
}
;echo '    </tbody>
  </table>
</div>
<div style=" width:90%; padding:10px 0 10px 0; text-align:center">
  ';if($num!=0) echo "<input name='button' type='button' class='btn' value='生成菜单'/>";else echo "<font color='#ff0000'>暂无数据</font>";;echo '</div>
</body>
</html>';
?>