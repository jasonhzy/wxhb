<?php
require_once("../includes/conn.php");
$tools=new tools();
$uwxcode=$_GET['uwxcode'];
$sk=intval($_GET['sk']);
$sh=intval($_GET['sh']);

$sysconfig=$dbconn->fetch($dbconn->query("select cappid,cappsecret,cdenglucode from ".DBQIAN."sys_config limit 1"));
//获取用户的openid
if(isset($_GET['code']) && $_GET['code']!=''){
   $WXCODE=$_GET['code'];
   $getucodeurl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$sysconfig['cappid']."&secret=".$sysconfig['cappsecret']."&code=".$WXCODE."&grant_type=authorization_code";
   $getucodejson=json_decode($tools->http_curl_get($getucodeurl,true));
   $ucode=$getucodejson->openid;
   $wxlintoken=$getucodejson->access_token;
   if($ucode==''){
      header("Location:chaikai.index.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode);
      exit;
   }
   $urow=$dbconn->fetch($dbconn->query("select uheadimgurl from ".DBQIAN."user_list where ucode='$ucode' order by id desc limit 1"));
   if($sysconfig['cdenglucode'] == 2 || $urow['uheadimgurl']!=''){
      header("Location:chaikai.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode."&ucode=".$ucode);
	  exit;
   }
   if($sysconfig['cdenglucode'] == 1 && $urow['uheadimgurl']==''){
       $locaurl=urlencode(WEBNAME."main/get.info.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode);
       $urls="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$sysconfig['cappid']."&redirect_uri=".$locaurl."&response_type=code&scope=snsapi_userinfo#wechat_redirect";
       header("Location:".$urls);
	   exit;
   }
} else {
    header("Location:chaikai.index.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode);
	exit;
}
?>