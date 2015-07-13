<?php
require_once("../includes/conn.php");
require_once("../includes/wxtoken.php");
$tools=new tools();
if(!$tools->check_is_weixin()){
	header("Location:guanzhu.php");
	exit;
}
$sysconfig=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."sys_config order by id desc limit 1"));
$hongbaorow=$dbconn->fetch($dbconn->query("select * from ".DBQIAN."xianjin_set order by id desc limit 1"));
define('APPID', $sysconfig['cappid']);
define('APPSECRET', $sysconfig['cappsecret']);

$wxtoken = new wxtoken();
$signPackage = $wxtoken->GetSignPackage();

$utcode = ( isset($_GET['utcode']) && $_GET['utcode']!='' ) ? $_GET['utcode']:'';
//获取用户的openid
if(isset($_GET['code']) && $_GET['code']!=''){
   $WXCODE=$_GET['code'];
   $getucodeurl="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".APPID."&secret=".APPSECRET."&code=".$WXCODE."&grant_type=authorization_code";
   $getucodejson=json_decode($tools->http_curl_get($getucodeurl,true));
   $ucode=$getucodejson->openid;//自己的ucode
   
   if($ucode==''){
      header("Location:index.php?utcode=".$utcode."&sk=".$_GET['sk']."&sh=".$_GET['sh']);
      exit;
   }
   
   $usernum=$dbconn->countn(DBQIAN."user_list"," ucode='$ucode' ");
   $access_token=$wxtoken->getAccessToken();
   $geturl="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$ucode."&lang=zh_CN";
   $getutxtjson =json_decode($tools->http_curl_get($geturl,true));
   if($getutxtjson->subscribe==1){
	  $uickname    =iconv("UTF-8","GBK",$getutxtjson->nickname);
	  $usex        =intval($getutxtjson->sex);
	  $headimgurl  =$getutxtjson->headimgurl;
	  $udizhi      =iconv("UTF-8","GBK",$getutxtjson->province).iconv("UTF-8","GBK",$getutxtjson->city);	
	  $utime=time();
	  if($usernum == 0){
		   $dbconn->noretquery("insert into ".DBQIAN."user_list(ucode,utcode,uickname,usex,uheadimgurl,udizhi,utime)values
				  ('$ucode','$utcode','$uickname',$usex,'$headimgurl','$udizhi',$utime)");
	  }
   } else {
	   $utime=time();
	   if($usernum == 0){
	      $dbconn->noretquery("insert into ".DBQIAN."user_list(ucode,utcode,utime)values('$ucode','$utcode',$utime)");
	   }
   }
} else {
    header("Location:index.php?utcode=".$utcode."&sk=".$_GET['sk']."&sh=".$_GET['sh']);
    exit;
}

$userrow=$dbconn->fetch($dbconn->query("select uheadimgurl,uickname from ".DBQIAN."user_list where ucode='$utcode' limit 1"));
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<title>Duang！一大波钱正在来袭！</title>
<link rel="stylesheet" href="../css/index.css" />
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({	
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
    ]
  });
wx.ready(function () {
    wx.onMenuShareTimeline({
         title: "<?php echo str_replace("%%%",$userrow['uickname'],$hongbaorow['hfentitle']);?>",
         link: "<?php echo WEBNAME."main/index.php?utcode=".$ucode."&sk=".$_GET['sk']."&sh=".$_GET['sh'];?>",
         imgUrl: "<?php $times=date("Ymd",$hongbaorow['htime']);
	     echo WEBNAME."uploads/".substr($times,0,4)."/".substr($times,4,2)."/".$hongbaorow['hfenimg']; ?>",
		 trigger: function (res) {
		 },
         success: function (res) {       
         },
         cancel: function (res) {            
         },
		 fail: function (res) {
		 }
    });
    wx.onMenuShareAppMessage({
        title: "<?php echo str_replace("%%%",$userrow['uickname'],$hongbaorow['hfentitle']);?>",
        desc: "<?php echo $hongbaorow['hfendes'];?>",
        link: "<?php echo WEBNAME."main/index.php?utcode=".$ucode."&sk=".$_GET['sk']."&sh=".$_GET['sh'];?>",
        imgUrl: "<?php $times=date("Ymd",$hongbaorow['htime']);
	     echo WEBNAME."uploads/".substr($times,0,4)."/".substr($times,4,2)."/".$hongbaorow['hfenimg']; ?>",
        trigger: function (res) {
        },
        success: function (res) {
        },
        cancel: function (res) {
        },
        fail: function (res) {
        }
     });
});
</script>
</head>

<body>
<img src="../images/back.png" width="100%" /> <img class="avatar" src="<?php echo substr($userrow['uheadimgurl'],0,-1).'132';?>" width="60" height="60"/>
<p class="nickname"><?php echo $userrow['uickname'];?>的礼物</p>
<a href="guanzhu.php?sh=<?php echo $_GET['sh'];?>&sk=<?php echo $_GET['sk'];?>&utcode=<?php echo $ucode;?>" style="text-decoration: none;"><button class="btn-give">我也要</button></a>
<div class="already-get">看看都有谁在抢...</div>

<div class="content">
  <ul>
    <?php
	$sql="select ".DBQIAN."user_xianjin.utxt,".DBQIAN."user_xianjin.utime,".DBQIAN."user_list.uheadimgurl,".DBQIAN."user_list.uickname from ".DBQIAN."user_xianjin left join ".DBQIAN."user_list on ".DBQIAN."user_xianjin.ucode=".DBQIAN."user_list.ucode where ".DBQIAN."user_xianjin.xtype=1 order by ".DBQIAN."user_xianjin.id desc limit 0,25";
    $query=$dbconn->query($sql);
	while($row=$dbconn->fetch($query)){
	?>
    <li> <img class="receiver_avatar" width="36" height="36" src="<?php echo substr($row['uheadimgurl'],0,-1).'132';?>" />
      <div class="receiver_info">
        <div style="margin-bottom: 2px;"> <span class="nickname"><?php echo $row['uickname'];?></span> <span class="time"><?php echo date("m/d H:i:s",$row['utime']);?></span> </div>
        <div class="desc"><?php echo $row['utxt'];?></div>
      </div>
      <div class="receiver_flow"> 
        <!--<div class="red">0.10元</div>--> 
      </div>
    </li>
    <?php } ?>
  </ul>
</div>
</body>
</html>