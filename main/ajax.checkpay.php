<?php
require_once("../includes/conn.php");	
$tools=new tools();
$hongbaorow=$dbconn->fetch($dbconn->query("select hminmoney,htotalmoney from ".DBQIAN."xianjin_set limit 1"));

$ucode=$_POST['ucode'];
$uwxcode=$_POST['uwxcode'];
if($ucode=='' || $uwxcode==''){
	echo 1;//错误
	exit;
}
$ulingnum=$dbconn->countn(DBQIAN."user_xianjin"," ucode='$ucode' and uwxcode='$uwxcode' and xtype=1 ");
if($ulingnum > 0){
	echo 2;//已领过
	exit;
}

//判断红包是否领取完毕
$totalrow=$dbconn->fetch($dbconn->query("select sum(umoney) as tmaxmoney from ".DBQIAN."user_xianjin"));
$minmoney= $hongbaorow['htotalmoney'] - $totalrow['tmaxmoney'];
if($minmoney < $hongbaorow['hminmoney']){
	echo 3;//没有钱了
	exit;
}
echo 4;//未领过
?>