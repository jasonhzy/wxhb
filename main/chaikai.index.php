<?php
require_once("../includes/public.inc.php");
$uwxcode=$_GET['uwxcode'];

$sk=intval($_GET['sk']);
$sh=intval($_GET['sh']);
$nowtime=time();
if($sk >0 && $sh > 0){
	$chash=(int)($nowtime-$sk)/3600;
	if($chash >= $sh){
       exit;
	}
}
if($sk==0 || $sh==0){
   exit;
}
$sysconfig=mysql_fetch_array(mysql_query("select cappid,cdenglucode from ".DBQIAN."sys_config order by id desc limit 1"));
$locaurl=urlencode(WEBNAME."main/check.info.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode);
$urls="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$sysconfig['cappid']."&redirect_uri=".$locaurl."&response_type=code&scope=snsapi_base#wechat_redirect";
mysql_close();
header("Location:".$urls);
?>