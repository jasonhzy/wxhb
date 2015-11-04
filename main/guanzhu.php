<?php
require_once("../includes/conn.php");
require_once("../includes/wxtoken.php");
$tools=new tools();
$sk=intval($_GET['sk']);
$sh=intval($_GET['sh']);
$utcode=$_GET['utcode'];
$nowtime=time();
if($sk > 0 && $sh > 0){
	$chash=(int)(($nowtime-$sk)/3600);
	if($chash >= $sh){
       exit;
	}
}
if($sk==0 || $sh==0){
   exit;
}

$wxtoken = new wxtoken();
$signPackage = $wxtoken->GetSignPackage();

$hongbaorow=$dbconn->fetch($dbconn->query("select hfaci,hlingqu,hjinzhiqu,hfentitle,htime,hfenimg,hfendes from ".DBQIAN."xianjin_set limit 1"));
if($hongbaorow['hlingqu']!="" || $hongbaorow['hjinzhiqu']!=""){
    $uip=$tools->get_ip();
	$res1 = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$uip); 
	$res1 = json_decode($res1);
	$uipdizhi='';
	$uipdizhi=$uipdizhi.$res1->data->country;
	$uipdizhi=$uipdizhi.$res1->data->region;
	$uipdizhi=$uipdizhi.$res1->data->city;
	$uipdizhi=$uipdizhi.$res1->data->isp;
	
	if($hongbaorow['hlingqu']!=""){
	   $diqu=@explode(",",$hongbaorow['hlingqu']);
	   $lingcode=0;
	   foreach($diqu as $val){
		   if(stristr($uipdizhi,$val)!==false){
			   $lingcode=1;
			   break;
		   }
	   }
	   if($lingcode==0){exit;}
	}
	if($hongbaorow['hjinzhiqu']!=""){
	   $diqu=@explode(",",$hongbaorow['hjinzhiqu']);
	   foreach($diqu as $val){
		   if(stristr($uipdizhi,$val)!==false){
			   exit;
		   }
	   }
	}
}

//随机公众号
$wxarr=array();
$query=$dbconn->query("select id from ".DBQIAN."wxname_list order by id desc");
while($wxrow=$dbconn->fetch($query)){
   	$wxarr[]=$wxrow['id'];
}
$wxnums=count($wxarr)-1;
$wxnums=rand(0,$wxnums);
$wxnumsid=intval($wxarr[$wxnums]);
$wxrow=$dbconn->fetch($dbconn->query("select wxname,wxzhanghao from ".DBQIAN."wxname_list where id=$wxnumsid"));
$userrow=$dbconn->fetch($dbconn->query("select uheadimgurl,uickname from ".DBQIAN."user_list where ucode='$utcode' limit 1"));
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<title><?php echo $wxrow['wxname'];?></title>
<style>
body { background-color:#fcc803; margin:0px; padding:0px; font: 14px Verdana, Arial, Helvetica, sans-serif; color:#7a6100 }
.tips { width:100px; height:42px; background:url(../images/tips.jpg) no-repeat; line-height:42px; color:#fff; font-weight:bold; text-indent:1em }
.row { width:100%; margin:10px 0px; padding:0px; }
.text-center { text-align:center }
</style>
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
         link: "<?php echo WEBNAME."main/index.php?utcode=".$utcode."&sk=".$_GET['sk']."&sh=".$_GET['sh'];?>",
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
        link: "<?php echo WEBNAME."main/index.php?utcode=".$utcode."&sk=".$_GET['sk']."&sh=".$_GET['sh'];?>",
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
<div class="row">
  <div class="tips">第一步</div>
</div>
<div class="row text-center"> <img src="http://open.weixin.qq.com/qr/code/?username=<?php echo $wxrow['wxzhanghao'];?>" width="180"/> </div>
<div class="row text-center" >
  <div style="font-size:22px;color:#fff;font-weight:bold;line-height:40px;color:#fa3137">"长按图片，识别图中二维码"</div>
  <div style="line-height:30px;"> 如长按图片无效，搜索以下微信号关注即可 </div>
  <div style="font-size:28px;font-weight:bold;line-height:60px;color:#000000"><span style="border:1px dashed #000000;padding:5px 10px"><?php echo $wxrow['wxzhanghao'];?></span></div>
  <div style="line-height:30px;">长按虚线框，拷贝微信号</div>
</div>
<div class="row">
  <div class="tips">第二步</div>
</div>
<div class="row text-center" >
  <div style="font-size:22px;color:#fff;font-weight:bold;line-height:40px;color:#fa3137">发送"<?php echo $hongbaorow['hfaci'];?>"给公众号</div>
  <div style="line-height:30px;">点击图文消息，给朋友</div>
  <div style="line-height:30px;">每多一个朋友成功领取，你也会再得到一个！</div>
</div>
</body>
</html>