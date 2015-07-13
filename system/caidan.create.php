<?php
session_start();
include("../includes/conn.php");
include("../includes/wxtoken.php");
$dbconn->admin_logincheck();
$adminid=$_SESSION['adminid'];
$tools=new tools();
$wxtoken=new wxtoken($adminid);
$access_token=$wxtoken->getAccessToken();
$dataStart="{
     \"button\":[";
$dataEnd="]
     }";
$dataContent="";
$i=1;
$zhunum=$dbconn->countn(DBQIAN."caidan_list"," adminid=$adminid and cid=0");
$query=$dbconn->query("select * from ".DBQIAN."caidan_list where adminid=$adminid and cid=0 order by cnum desc,id desc ");
while($row=$dbconn->fetch($query)){
$num=$dbconn->countn(DBQIAN."caidan_list"," cid=$row[id] ");
if($num==0){
if($row['ctype']==0){
$menus= "{
			 \"type\":\"view\",
			 \"name\":\"".iconv("GBK","UTF-8",$row['cname'])."\",
			 \"url\":\"".iconv("GBK","UTF-8",$row['curl'])."\"
			 }";
}else {
$menus= "{ 
		     \"type\":\"click\",
			 \"name\":\"".iconv("GBK","UTF-8",$row['cname'])."\",
			 \"key\":\"".iconv("GBK","UTF-8",$row['ckey'])."\"
			 }";
}
if($i<$zhunum){
$menus=$menus.',';
}
$dataContent=$dataContent.$menus;
}else {
$j=1;
$mquery=$dbconn->query("select * from ".DBQIAN."caidan_list where adminid=$adminid and cid=$row[id] order by cnum desc,id desc ");
$nextMenuStart="{
			\"name\":\"".iconv("GBK","UTF-8",$row['cname'])."\",
			\"sub_button\":[";
$nextMenuEnd="]
		    }";
$nextMenuContent="";
while($nextrow=$dbconn->fetch($mquery)){
if($nextrow['ctype']==0){
$menus= "{
			     \"type\":\"view\",
			     \"name\":\"".iconv("GBK","UTF-8",$nextrow['cname'])."\",
			     \"url\":\"".iconv("GBK","UTF-8",$nextrow['curl'])."\"
			     }";
}else {
$menus= "{
		         \"type\":\"click\",
			     \"name\":\"".iconv("GBK","UTF-8",$nextrow['cname'])."\",
			     \"key\":\"".iconv("GBK","UTF-8",$nextrow['ckey'])."\"
			     }";
}
if($j<$num){
$menus=$menus.',';
}
$nextMenuContent=$nextMenuContent.$menus;
$j++;
}
$dataContent=$dataContent.$nextMenuStart.$nextMenuContent.$nextMenuEnd;
if($i<$zhunum){
$dataContent=$dataContent.',';
}
}
$i++;
}
$data=$dataStart.$dataContent.$dataEnd;
$menuurl="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$reString=$tools->http_curl_post($menuurl,$data);
$str=json_decode($reString);
echo $str->errmsg;
?>