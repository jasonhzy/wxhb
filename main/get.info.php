<?php
require_once("../includes/conn.php");
$tools=new tools();
$uwxcode=$_GET['uwxcode'];
$sk=intval($_GET['sk']);
$sh=intval($_GET['sh']);

$sysconfig=mysql_fetch_array(mysql_query("select cappid,cappsecret,cdenglucode from ".DBQIAN."sys_config order by id desc limit 1"));
//获取用户的openid
if(isset($_GET['code']) && $_GET['code']!=''){
   $WXCODE=$_GET['code'];
   $getucodeurl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$sysconfig['cappid']."&secret=".$sysconfig['cappsecret']."&code=".$WXCODE."&grant_type=authorization_code";
   $getucodejson=json_decode($tools->http_curl_get($getucodeurl));
   $ucode=$getucodejson->openid;
   $wxlintoken=$getucodejson->access_token;
   if($ucode==''){
      header("Location:chaikai.index.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode);
      exit;
   }
   
   $usernum=$dbconn->countn(DBQIAN."user_list"," ucode='$ucode' ");
   $geturl=$getutxturl="https://api.weixin.qq.com/sns/userinfo?access_token=".$wxlintoken."&openid=".$ucode."&lang=zh_CN";
   $getutxtjson =json_decode($tools->http_curl_get($geturl));
   
   $uickname    =iconv("UTF-8","GBK",$getutxtjson->nickname);
   $usex        =intval($getutxtjson->sex);
   $headimgurl  =$getutxtjson->headimgurl;
   $udizhi      =iconv("UTF-8","GBK",$getutxtjson->province).iconv("UTF-8","GBK",$getutxtjson->city);	
   $utime=time();
   if($usernum > 0){
		$dbconn->noretquery("update ".DBQIAN."user_list set uickname='$uickname',
					  usex=$usex,uheadimgurl='$headimgurl',udizhi='$udizhi' where ucode='$ucode' ");
	} else {
		$dbconn->noretquery("insert into ".DBQIAN."user_list(ucode,uickname,usex,uheadimgurl,udizhi,utime)values
				  ('$ucode','$uickname',$usex,'$headimgurl','$udizhi',$utime)");
	}
	
    header("Location:chaikai.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode."&ucode=".$ucode);
    exit;
} else {
      header("Location:chaikai.index.php?sk=".$sk."&sh=".$sh."&uwxcode=".$uwxcode);
      exit;
}
?>