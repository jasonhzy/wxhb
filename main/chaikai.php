<?php
require_once("../includes/conn.php");
require_once("../includes/wxtoken.php");
$tools=new tools();
if(!$tools->check_is_weixin()){
	header("Location:guanzhu.php");
	exit;
}
$sysconfig=$dbconn->fetch($dbconn->query("select cappid,cappsecret from ".DBQIAN."sys_config limit 1"));
$hongbaorow=$dbconn->fetch($dbconn->query("select hfentitle,htime,hfenimg,hlingqu,hjinzhiqu,hfendes,hfaci from ".DBQIAN."xianjin_set limit 1"));
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
$ucode=$tools->sql_mag_gpc($_GET['ucode']);
define('APPID', $sysconfig['cappid']);
define('APPSECRET', $sysconfig['cappsecret']);
$wxtoken = new wxtoken();
$signPackage = $wxtoken->GetSignPackage();

$userrow=$dbconn->fetch($dbconn->query("select uickname,uheadimgurl from ".DBQIAN."user_list where ucode='$ucode' limit 1"));
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<title>拆开有惊喜</title>
<link rel="stylesheet" href="../css/chaikai.css" />
<script src="../jscripts/jquery-2.1.1.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  wx.config({	
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: ['checkJsApi','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems',
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
	wx.showOptionMenu();
    wx.onMenuShareTimeline({
         title: "<?php echo str_replace("%%%",$userrow['uickname'],$hongbaorow['hfentitle']);?>",
         link: "<?php echo WEBNAME."main/index.php?utcode=".$ucode."&sk=".$_GET['sk']."&sh=".$_GET['sh'];?>",
         imgUrl: "<?php $times=date("Ymd",$hongbaorow['htime']);
	     echo WEBNAME."uploads/".substr($times,0,4)."/".substr($times,4,2)."/".$hongbaorow['hfenimg']; ?>",
		 trigger: function (res) {
		 },
         success: function (res) {
			  $(".second-box .second-nickname").text('<?php echo $userrow['uickname'];?>'+'的祝福');
	          $(".second-box .second-headimg").attr("src",'../images/hbss_icon.jpg');
			  
			  $('.second-box .money1').hide();
			  $('.second-box .money2').hide();
			  $('.second-box .money3').hide();
			  $('.second-box .money4').hide();
			  $('.second-box .money6').hide();
			  $('.second-box .money5').show();
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
			  $(".second-box .second-nickname").text('<?php echo $userrow['uickname'];?>'+'的祝福');
	          $(".second-box .second-headimg").attr("src",'../images/hbss_icon.jpg');

			  $('.second-box .money1').hide();
			  $('.second-box .money2').hide();
			  $('.second-box .money3').hide();
			  $('.second-box .money4').hide();
			  $('.second-box .money6').hide();
			  $('.second-box .money5').show();
        },
        cancel: function (res) {
        },
        fail: function (res) {
        }
     });
});
$(document).ready(function(){
	
	$(function(){
		$.post("ajax.checkpay.php",{ucode:"<?php echo $ucode;?>",uwxcode:"<?php echo $_GET['uwxcode'];?>"},function(data){
			if(data==1){
				$(".second-box").show();
				$('.second-box .money1').show();
				return;
			}
			if(data==2){
				$(".second-box").show();
				$('.second-box .money2').show();
				return;
			}
			if(data==3){
				$(".second-box").show();
				$('.second-box .money6').show();
				return;
			}
			if(data==4){
				$(".first-box").show();
				return;
			}
		});
	})
	
	$(".hot-click").click(function(){
		 $('.hot-click').unbind();
		 $.post("ajax.pay.php",{ucode:"<?php echo $ucode;?>",uwxcode:"<?php echo $_GET['uwxcode'];?>"},function(data){
		     $(".first-box").hide();
		     $(".second-box").show();
			 if(data==1){
				 $('.second-box .money1').show();
				 return false;
			 }
			 if(data==2){
				 $('.second-box .money2').show();
				 return false;
			 }
			 if(data==4){
				$('.second-box .money4').show();
			    return false;	 
			 }
			 $('.second-box .money3 .money-number').text(data);
             $('.second-box .money3').show();
		 });
	});
	
	$('.sendMoney').click(function(){
		$(".share").show();
	})
	$(".close").click(function(){
		$(".share").fadeTo(500,0,function(){
			$(".share").hide();
		});
	});
});
</script>
</head>
<body>
<?php
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
?>
<div class="first-box" style="display:none"> 
  <img class="body" src="../images/fools_1.png">
  <img class="user-headimg" src="../images/hbss_icon.jpg">
  <div class="hot-click"></div>
  <div class="nickname"><?php echo $wxrow['wxname'];?> 的赠礼</div>
</div>

<div class="second-box" style="display:none">
  <div class="second-wrapper"> 
    <img class="second-bg" src="../images/fools_2.png"> 
    <img class="second-headimg" src="<?php echo substr($userrow['uheadimgurl'],0,-1).'132';?>">
    <div class="second-nickname">
	   <?php echo $wxrow['wxname'];?> 的赠礼
       <p style="font-size:13px; color:#999999">恭喜发财，大吉大利！</p>
    </div>
    <!--ucode错误-->
    <div class="money1">
      <p style="color:rgb(214, 86, 69);font-size:1.2em;">发送"<?php echo $hongbaorow['hfaci'];?>"给"<?php echo $wxrow['wxname'];?>"公众号<br>领取礼物</p>
      <p><img src="http://open.weixin.qq.com/qr/code/?username=<?php echo $wxrow['wxzhanghao'];?>" width="180"/></p>
      <p style="color:#F00;font-size:1.2em;">长按二维码，识别进入公众号</p>
    </div>
    <!--领取过了-->
    <div class="money2">
      <div class="needMore">
        <p style="color:rgb(214, 86, 69);font-size:1.2em;">给好友发礼物自己也可以再领哦~</p>
        <a href="javascript:void(0);" class="lg-btn sendMoney">立即发礼物</a>
      </div>
    </div>
    <!--领取成功-->
    <div class="money3"><span class="money-number"></span> <span class="yuan">元</span> 
      <p style="color:#70A0D7;font-size:1em;">已存入零钱,可直接提现</p>
      <p>还想要?告诉你个秘密...</p>
      <p style="color:rgb(214, 86, 69);font-size:1.2em;">给好友发礼物自己也可以再领礼物哦~</p>
      <a href="javascript:void(0);" class="lg-btn sendMoney">立即发礼物</a>
    </div>
    <!--系统错误-->
    <div class="money4">
      <p style="color:rgb(214, 86, 69);font-size:1.2em;">人太多了，请重新打开！</p>
    </div>
    <!--没有钱了-->
    <div class="money6">
       <p style="color:rgb(214, 86, 69);font-size:1.2em;">来晚了,没有了！</p>
       <a href="javascript:void(0);" class="lg-btn sendMoney">立即发礼物</a>
    </div>
    <!--如果有第二个公众号-->
    <div class="money5">
      <p style="color:rgb(214, 86, 69);font-size:1.2em;">发送"<?php echo $hongbaorow['hfaci'];?>"给"<?php echo $wxrow['wxname'];?>"公众号<br>获得更多惊喜</p>
      <p><img src="http://open.weixin.qq.com/qr/code/?username=<?php echo $wxrow['wxzhanghao'];?>" width="180"/></p>
      <p style="color:#F00;font-size:1.2em;">长按二维码，识别进入公众号</p>
    </div>
  </div>
</div>
<div class="share" style="display:none"> <span class="share-p">发给好友，享受下金雨的快感！！！</span> 
  <img class="pointer" src="../images/share_arrow.gif">
  <img class="close" src="../images/close-black.gif"> 
</div>
</body>
</html>