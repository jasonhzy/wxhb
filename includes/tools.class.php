<?php
/**
* 公共类
*/
class tools {
	//控制超长文本中的输出
   function txt_substr($str,$start,$len){
      $strlen=$start+$len;
      for($i=0;$i<$strlen;$i++){
         if(ord(substr($str,$i,1))>0xa0){
	        $tmpstr.=substr($str,$i,2);
		    $i++;
	      } else {
	        $tmpstr.=substr($str,$i,1);
	      }
      }
    return $tmpstr;
   }
   //截取字符串,string:需要截取的字符串，length：默认截取长度，etc：截取后默认后补的字符串
   function txt_substr_length($string, $length = 80, $etc = '...')
   {
       if ($length == 0)
          return '';
       if (strlen($string) > $length) {
          $length -= min($length, strlen($etc));
          for($i = 0; $i < $length ; $i++) {
             $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
	      }
		  return $strcut.$etc;
		 } else {
           return $string;
         }
     }
   //对文本中的特殊字符进行替换
   function html_replace($content){
      $content=htmlspecialchars($content);//转换文本中的特殊字符
      $content=str_replace(chr(13),"<br>",$content);
      $content=str_replace(chr(32),"&nbsp;",$content);
      $content=str_replace("[_[","<",$content);
      $content=str_replace(")_)",">",$content);
      $content=str_replace("|_|","",$content);
      return trim($content);
   }
   //检测字符串中是否带特殊字符，如果带特殊字符，单引号 (')双引号 (")反斜杠 (\) NULL，在指定的预定义字符前添加反斜杠
   function sql_mag_gpc($str)
   {
      if(get_magic_quotes_gpc()==1)
         return $str;
      else
         return addslashes($str);
    }
   //取得length位的str内的随机数
   function get_random ($length)
   { 
	   $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
       $result = '';
       $l = strlen($str);
       for($i = 0;$i < $length;$i++)
       {
           $num = rand(0, $l-1);
           $result .= $str[$num];
       }
       return $result;
   }
	//创建文件夹
    function create_dirs($dir){
       return is_dir($dir) or ($this->create_dirs(dirname($dir)) and mkdir($dir, 0777));
    }
   //复制文件$fileUrl 源文件 $aimUrl目标地址
   function copy_file($fileUrl,$aimUrl) {
       if (!file_exists($fileUrl)) {
          return false;
       }
       if(file_exists($aimUrl)) {
          @unlink($aimUrl);
       }
       copy($fileUrl, $aimUrl);
       return true;
   }
   //获取文件后缀名 
   function get_files_endname($file_name)
   {
      $extend =explode("." , $file_name);
      $va=count($extend)-1;
      return $extend[$va];
   }
   //获取文件前缀名
   function get_file_starname($file_name)
  {
      $extend =explode("." , $file_name);
      return $extend[0];
   }
   //上传图片$img:上传文件的$_FILES数组，imgname：图片新名字，filepath:上传路径
   function upload_img($img,$imgname,$filepath,$maxfilesize=2){
	   $fileType=array('jpg','gif','png','JPG','GIF','PNG');//允许上传的文件类型
	   if(!in_array(substr($img['name'],-3,3),$fileType))
		   die("<script>alert('不允许上传该类型的文件！');history.back();</script>");
	   if(strpos($img['type'],'image')===false)
		   die("<script>alert('不允许上传该类型的文件！');history.back();</script>");
	   if($img['size']> $maxfilesize*1024000)
		   die( "<script>alert('文件过大！');history.back();</script>");
	   if($img['error'] !=0)
		   die("<script>alert('未知错误，文件上传失败！');history.back();</script>");
	   if(@move_uploaded_file($img['tmp_name'], $filepath.$imgname)){
		   $string='图片上传成功！';
	   }  else {
		   $string= '图片上传失败';
	   }
    }
   //取得当前访问页面IP地址
   function get_ip(){
       if($_SERVER['HTTP_CLIENT_IP']){
          $onlineip=$_SERVER['HTTP_CLIENT_IP'];//HTTP_CLIENT_IP 客户端，及浏览器所在的电脑，的ip地址
       }elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
          $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
       }else{
          $onlineip=$_SERVER['REMOTE_ADDR'];
       }
	  if($onlineip=='::1'){
	     $onlineip='127.0.0.1';
	  }
	   return $onlineip;
   }
  //取得当前页面的后缀参数
   function get_url_query(){
	  $urls = parse_url($_SERVER['REQUEST_URI']);
	  $url = $urls['path'];
	  $urlquery = $urls['query'];
	  return $urlquery;
   }
   //取得当前页面的名字 
  function get_url_self(){
    $php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
    return $php_self;
   }
  //判断是否是微信浏览器
  function check_is_weixin(){ 
	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
		return true;
	}	
	return false;
  }
  //curl get获取
   function http_curl_get($url) {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	  curl_setopt ($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $res = curl_exec($curl);
      curl_close($curl);
      return $res;
   }
   //curl post提交
   function http_curl_post($url, $data = null)
   {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
      if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      }
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($curl);
      curl_close($curl);
      return $output;
   }
   //遍历文件夹里面的图片
   function get_dirs_img($path){
      if(!is_dir($path)) return;
      $handle  = opendir($path);
      $files = array();
      while(false !== ($file = readdir($handle))){         
         if($file != '.' && $file!='..'){
            $path2= $path.'/'.$file;
            if(is_dir($path2)){
               $this->get_dirs_img($path2);         
            }else{
               if(preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)){
                  $files[] = $file;
               }
            }         
         }
      }
      return $files;
   }
   //创建文件
   function create_file($name,$path,$content)
  {
      $toppath=$path.$name;
      $Ts=fopen($toppath,"a+");
      fputs($Ts,$content."\r\n");
      fclose($Ts);
   }
   
   //发送邮件,
   function send_mail($femail,$fpass,$fsmtp,$to, $subject = 'No subject', $body) {
       $loc_host = "test";            //发信计算机名，可随意
       $smtp_acc = $femail; //Smtp认证的用户名，类似fuweng@im286.com，或者fuweng
       $smtp_pass=$fpass;          //Smtp认证的密码，一般等同pop3密码
       $smtp_host=$fsmtp;    //SMTP服务器地址，类似 smtp.tom.com
       $from=$femail;       //发信人Email地址，你的发信信箱地址
       $headers = "Content-Type: text/plain; charset=\"utf-8\"\r\nContent-Transfer-Encoding: base64";
       $lb="\r\n";                    //linebreak
       $hdr = explode($lb,$headers);     //解析后的hdr
       if($body) {$bdy = preg_replace("/^\./","..",explode($lb,$body));}//解析后的Body
           $smtp = array(
                //1、EHLO，期待返回220或者250
                array("EHLO ".$loc_host.$lb,"220,250","HELO error: "),
                //2、发送Auth Login，期待返回334
                array("AUTH LOGIN".$lb,"334","AUTH error:"),
                //3、发送经过Base64编码的用户名，期待返回334
                array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "),
                //4、发送经过Base64编码的密码，期待返回235
                array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : "));
        //5、发送Mail From，期待返回250
        $smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");
        //6、发送Rcpt To。期待返回250
        $smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");
        //7、发送DATA，期待返回354
        $smtp[] = array("DATA".$lb,"354","DATA error: ");
        //8.0、发送From
        $smtp[] = array("From: ".$from.$lb,"","");
        //8.2、发送To
        $smtp[] = array("To: ".$to.$lb,"","");
        //8.1、发送标题
        $smtp[] = array("Subject: ".$subject.$lb,"","");
        //8.3、发送其他Header内容
        foreach($hdr as $h) {$smtp[] = array($h.$lb,"","");}
        //8.4、发送一个空行，结束Header发送
        $smtp[] = array($lb,"","");
        //8.5、发送信件主体
        if($bdy) {foreach($bdy as $b) {$smtp[] = array(base64_encode($b.$lb).$lb,"","");}}
        //9、发送"."表示信件结束，期待返回250
        $smtp[] = array(".".$lb,"250","DATA(end)error: ");
        //10、发送Quit，退出，期待返回221
        $smtp[] = array("QUIT".$lb,"221","QUIT error: ");
        //打开smtp服务器端口
        $fp = @fsockopen($smtp_host, 25);
        if (!$fp) echo "Error: Cannot conect to ".$smtp_host."";
        while($result = @fgets($fp, 1024)){if(substr($result,3,1) == " ") { break; }}
        $result_str="";
        //发送smtp数组中的命令/数据
        foreach($smtp as $req){
                //发送信息
                @fputs($fp, $req[0]);
                //如果需要接收服务器返回信息，则
                if($req[1]){
                        //接收信息
                        while($result = @fgets($fp, 1024)){
                                if(substr($result,3,1) == " ") { break; }
                        };
                        if (!strstr($req[1],substr($result,0,3))){
                                $result_str.=$req[2].$result."";
                        }
                }
        }
        //关闭连接
        @fclose($fp);
        return $result_str;
   }
}
?>