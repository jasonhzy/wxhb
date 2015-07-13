<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$tools=new tools();
$adminid=intval($_SESSION['adminid']);
$rows=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."xianjin_set order by id desc limit 1"));
if($_GET['act']=='add'){
$nid=intval($_GET['nid']);
$htime   =  intval($_POST['htime']);
$times   =  date("Ymd",$htime);
$htotalmoney= intval($_POST['htotalmoney']);
$hminmoney= intval($_POST['hminmoney']);
$hmaxmoney= intval($_POST['hmaxmoney']);
$hdesc= $_POST['hdesc'];
$hfentitle  =  $_POST['hfentitle'];
$hfenimg  =  $_POST['hfenimg'];
$hfendes  =  $_POST['hfendes'];
$hfaci  =  $_POST['hfaci'];
$hlingqu  =  str_replace("，",",",$_POST['hlingqu']);
$hjinzhiqu  =  str_replace("，",",",$_POST['hjinzhiqu']);
if($_FILES['newimg1']['name']!=""){
$path="../uploads/".substr($times,0,4)."/".substr($times,4,2);
$tools->create_dirs($path);
@unlink($path."/".$hfenimg);
$houZhui=$tools->get_files_endname($_FILES['newimg1']['name']);
$hfenimg=date("YmdHis").$tools->get_random(5).'.'.$houZhui;
$tools->upload_img($_FILES['newimg1'],$hfenimg,$path."/");
}
if($nid >0){
$dbconn->noretquery("update ".DBQIAN."xianjin_set set 
       htotalmoney=$htotalmoney,
       hminmoney=$hminmoney,
       hmaxmoney=$hmaxmoney,	   
       hdesc='$hdesc',
	   hfentitle='$hfentitle',
	   hfenimg='$hfenimg',
	   hfendes='$hfendes',
	   hlingqu='$hlingqu',
	   hjinzhiqu='$hjinzhiqu',
	   hfaci='$hfaci' where id=$nid");
}else {
$dbconn->noretquery("insert into ".DBQIAN."xianjin_set(htotalmoney,hminmoney,hmaxmoney,hdesc,hfentitle,hfenimg,hfendes,hfaci,htime,hlingqu,hjinzhiqu,adminid)values
	  ($htotalmoney,$hminmoney,$hmaxmoney,'$hdesc','$hfentitle','$hfenimg','$hfendes','$hfaci',$htime,'$hjinzhiqu','$hjinzhiqu',$adminid)");
}
$dbconn->showalert("保存成功","hongbao.set.php",1);
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
   })
});
</script>
</head>
<body>
<div class="place"> <span>位置：</span>
  <ul class="placeul">
    <li><a href="index.php">首页</a></li>
    <li>红包设置</li>
  </ul>
</div>
<div class="formbody">
  <form name="fadd" method="post" action="?act=add&nid=';echo $rows['id'];;echo '" enctype="multipart/form-data">
   <input type="hidden" name="htime" value="';echo (!isset($rows['htime']) ||$rows['htime']=='') ?time():$rows['htime'];;echo '">
   <input type="hidden" name="hfenimg" value="';echo $rows['hfenimg'];;echo '">
  <ul class="forminfo">
    <li>
      <label>总金额</label>
      <input name="htotalmoney" type="text" value="';echo ($rows['htotalmoney']=='')?1:$rows['htotalmoney'];;echo '" class="dfinput" />
      <i>单位：分，已发送';
$totalrow=$dbconn->fetch($dbconn->query("select sum(umoney) as tmaxmoney from ".DBQIAN."user_xianjin"));
echo "<font color=#FF0000>".($totalrow['tmaxmoney'])." 分</font>";
;echo '</i></li>
    <li>
      <label>最小金额</label>
      <input name="hminmoney" type="text" value="';echo ($rows['hminmoney']=='') ?1:$rows['hminmoney'];;echo '" class="dfinput" />
      <i>单位：分，<font color="#FF0000">不能小于1分</font></i></li>
    <li>
      <label>最大金额</label>
      <input name="hmaxmoney" type="text" value="';echo ($rows['hmaxmoney']=='') ?0:$rows['hmaxmoney'];;echo '" class="dfinput" />
      <i>默认值：0，如果不设置最大金额，每个红包为最小金额数</i></li>
    <li>
      <label>描述信息</label>
      <input name="hdesc" type="text" value="';echo $rows['hdesc'];;echo '" class="dfinput" />
      <i>必填，不能加任何符号,32字以内</i></li>
    <li>
      <label>触发关键词</label>
      <input name="hfaci" type="text" value="';if($rows['hfaci']=='') echo "H";else echo $rows['hfaci'];;echo '" class="dfinput" />
      <i>需要输入的关键词弹出红包，默认“H”</i></li>
    <li>
      <label>分享标题</label>
      <input name="hfentitle" type="text" value="';echo $rows['hfentitle'];;echo '" class="dfinput" />
      <i>替换符为三个百分号 %%%  ,百分号的地方会替换成微信昵称。例如：%%%的红包</i></li>
    <li>
      <label>分享图片</label>
      <input name="newimg1" type="file" class="dfinput">
      <i></i></li>
    ';
if($rows['hfenimg']!='') {
$times=date("Ymd",$rows['htime']);
$picpath="../uploads/".substr($times,0,4)."/".substr($times,4,2).'/';
;echo '    <li><label>已上传预览</label><img src="';echo $picpath.$rows['hfenimg'];;echo '" height="80px;" />  </li>
    ';};echo '    <li>
      <label>分享内容</label>
      <textarea name="hfendes" class="textinput" style="height:50px;">';echo $rows['hfendes'];echo '</textarea>
      <i></i></li>
    <li>
      <label>领取地区</label>
      <input name="hlingqu" type="text" value="';echo $rows['hlingqu'];;echo '" class="dfinput" />
      <i>默认空,不限制,多个地区英文逗号隔开,指定这些地区领取</i></li>
    <li>
      <label>禁止地区</label>
      <input name="hjinzhiqu" type="text" value="';echo $rows['hjinzhiqu'];;echo '" class="dfinput" />
      <i>默认空,不限制，多个地区英文逗号隔开，设定这些地区不能领取</i></li>
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