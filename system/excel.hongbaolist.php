<?php
session_start();
include("../includes/conn.php");
$dbconn->admin_logincheck();
$sql=" select ".DBQIAN."user_list.uickname,".DBQIAN."user_xianjin.umoney,".DBQIAN."user_list.udizhi from ".DBQIAN."user_xianjin left join ".DBQIAN."user_list on ".DBQIAN."user_xianjin.ucode=".DBQIAN."user_list.ucode order by ".DBQIAN."user_xianjin.id desc ";
$result = mysql_query($sql);
$str = "昵称,地址,金额(分)\n";
$str = iconv('utf-8','utf-8',$str);
while($row=mysql_fetch_array($result)){
$uickname = $row['uickname'];
$udizhi = $row['udizhi'];
$umoney = $row['umoney'];
$str .= $uickname.",".$udizhi.",".$umoney."\n";
}
$filename = date('Ymd').'.csv';
export_csv($filename,$str);
function export_csv($filename,$data) {
header("Content-type:text/csv");
header("Content-Disposition:attachment;filename=".$filename);
header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
header('Expires:0');
header('Pragma:public');
echo $data;
}
?>