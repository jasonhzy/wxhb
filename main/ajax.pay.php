<?php
require_once("../includes/public.inc.php");	
require_once("../includes/tools.class.php");	
require_once('pay/WxXianjinHelper.php');

$sysconfig=mysql_fetch_array(mysql_query("select cappid,cappsecret,cappkey,cmchid,cyongjin from ".DBQIAN."sys_config limit 1"));
$hongbaorow=mysql_fetch_array(mysql_query("select htotalmoney,hminmoney,hmaxmoney,hdesc from ".DBQIAN."xianjin_set limit 1"));

define('ROOT_PATH', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('APPID', $sysconfig['cappid']);
define('APPSECRET', $sysconfig['cappsecret']);
define('PARTNERKEY',$sysconfig['cappkey']);//密钥
define('MCHID', $sysconfig['cmchid']);//商户号
define("TOTALMONEY",$hongbaorow['htotalmoney']);//发送总金额，分为单位

$tools=new tools();
$commonUtil = new CommonUtil();
$wxHongBaoHelper = new WxHongBaoHelper();

//状态码说明，0正常 1ucode错误 2领取过了 3领取成功返回领取金额 4系统错误
$actioncode=0;
$ucode=$_POST['ucode'];
$uwxcode=$_POST['uwxcode'];
if($ucode=='' || $uwxcode==''){
	$actioncode=1;
}
//判断是否领过了
$ulingnum=mysql_num_rows(mysql_query("select * from ".DBQIAN."user_xianjin where ucode='$ucode' and uwxcode='$uwxcode' and xtype=1 limit 1"));
if($ulingnum > 0 && $actioncode == 0 ){
	$actioncode=2;
}

//计算大小
if($actioncode==0){
    $thismoney=intval($hongbaorow['hminmoney']);
	if($thismoney ==0 ){
		$thismoney=1;
	}
    if($hongbaorow['hmaxmoney'] > $hongbaorow['hminmoney'] && $hongbaorow['hmaxmoney'] > 0 && $hongbaorow['hminmoney'] > 0){
	     $thismoney=rand($hongbaorow['hminmoney'],$hongbaorow['hmaxmoney']);
    }
	$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串
	$wxHongBaoHelper->setParameter("partner_trade_no", MCHID.date('His').rand(10000, 99999));//商户订单号 
	$wxHongBaoHelper->setParameter("mchid", MCHID);//商户号
	$wxHongBaoHelper->setParameter("mch_appid", APPID); //公众账号appid
	$wxHongBaoHelper->setParameter("openid", $ucode);//用户openid
	$wxHongBaoHelper->setParameter("check_name","NO_CHECK");//校验用户姓名选项
	$wxHongBaoHelper->setParameter("amount", $thismoney);//金额
	$wxHongBaoHelper->setParameter("re_user_name", "李四");//企业付款描述信息
	$wxHongBaoHelper->setParameter("desc", $hongbaorow['hdesc']);//企业付款描述信息
	$wxHongBaoHelper->setParameter("spbill_create_ip", $wxHongBaoHelper->Getip());

	$postXml = $wxHongBaoHelper->create_hongbao_xml();
	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
	
	$responseObj = simplexml_load_string($responseXml);
	if( $responseObj->result_code=="SUCCESS" && $responseObj->return_code=="SUCCESS"){
		 $actioncode=$thismoney/100;
	     $utime=time();
		 $utxt="";
		 $utxtarr=array();
		 $query=mysql_query("select tcontent from ".DBQIAN."user_txt order by id desc");
		 $utxtnum=mysql_num_rows($query);
		 if($utxtnum > 0){
			 while($row=mysql_fetch_array($query)){
			    $utxtarr[]=$row['tcontent'];	 
			 }
			 $thisnum=rand(0,count($utxtarr)-1);
			 $utxt=$utxtarr[$thisnum];
		 }		 
	     mysql_query("insert into ".DBQIAN."user_xianjin(ucode,uwxcode,umoney,utxt,utime)values
		 ('$ucode','$uwxcode',$thismoney,'$utxt',$utime)");
	} else {
		 $tools->create_file("log.txt","",$responseXml);
		 $actioncode=4;
	}
}

$utnums=0;
$row=mysql_fetch_array(mysql_query("select utcode,uystate from ".DBQIAN."user_list where ucode='$ucode' limit 1"));
if($row['utcode']!='' && $row['uystate']==1 && $sysconfig['cyongjin'] > 0){
	$utcode=$row['utcode'];
    $thismoney=$sysconfig['cyongjin'];
	$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串
	$wxHongBaoHelper->setParameter("partner_trade_no", MCHID.date('His').rand(10000, 99999));//商户订单号 
	$wxHongBaoHelper->setParameter("mchid", MCHID);//商户号
	$wxHongBaoHelper->setParameter("mch_appid", APPID); //公众账号appid
	$wxHongBaoHelper->setParameter("openid", $utcode);//用户openid
	$wxHongBaoHelper->setParameter("check_name","NO_CHECK");//校验用户姓名选项
	$wxHongBaoHelper->setParameter("amount", $thismoney);//金额
	$wxHongBaoHelper->setParameter("re_user_name", "李四");//企业付款描述信息
	$wxHongBaoHelper->setParameter("desc", $hongbaorow['hdesc']);//企业付款描述信息
	$wxHongBaoHelper->setParameter("spbill_create_ip", $wxHongBaoHelper->Getip());

	$postXml = $wxHongBaoHelper->create_hongbao_xml();
	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
	
	$responseObj = simplexml_load_string($responseXml);
	if( $responseObj->result_code=="SUCCESS" && $responseObj->return_code=="SUCCESS"){
		 $utime=time();
	     mysql_query("insert into ".DBQIAN."user_xianjin(ucode,umoney,xtype,utime)values('$utcode',$thismoney,2,$utime)");
		 mysql_query("update ".DBQIAN."user_list set uystate=2 where ucode='$ucode'");
	} else {
		 $tools->create_file("log.txt","",$responseXml);
	}
}
echo $actioncode;
mysql_close();
?>