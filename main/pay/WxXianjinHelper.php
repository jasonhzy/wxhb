<?php

include_once ("CommonUtil.php");
include_once ("SDKRuntimeException.class.php");
include_once ("MD5SignUtil.php");
class WxHongBaoHelper {
	var $parameters;
	function __construct() {
	}
	function setParameter($parameter, $parameterValue) {
		$this->parameters [CommonUtil::trimString ( $parameter )] = CommonUtil::trimString ( $parameterValue );
	}
	function getParameter($parameter) {
		return $this->parameters [$parameter];
	}
	protected function create_noncestr($length = 30) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for($i = 0; $i < $length; $i ++) {
			$str .= substr ( $chars, mt_rand ( 0, strlen ( $chars ) - 1 ), 1 );
		}
		return $str;
	}
	function check_sign_parameters() {
		if ($this->parameters ["mch_appid"] == null || $this->parameters ["mchid"] == null || $this->parameters ["nonce_str"] == null || $this->parameters ["partner_trade_no"] == null || $this->parameters ["openid"] == null || $this->parameters ["check_name"] == null || $this->parameters ["re_user_name"] == null || $this->parameters ["amount"] == null || $this->parameters ["desc"] == null || $this->parameters ["spbill_create_ip"] == null) {
			$commonUtil = new CommonUtil ();
			$content = $commonUtil->arrayToXml ( $this->parameters );
			$toppath = "sign.txt";
			$Ts = fopen ( $toppath, "a+" );
			fputs ( $Ts, $content . "\r\n" );
			fclose ( $Ts );
			return false;
		}
		return true;
	}
	protected function get_sign() {
		try {
			if (null == PARTNERKEY || "" == PARTNERKEY) {
				throw new SDKRuntimeException ( "" );
			}
			if ($this->check_sign_parameters () == false) {
				throw new SDKRuntimeException ( "" );
			}
			$commonUtil = new CommonUtil ();
			ksort ( $this->parameters );
			$unSignParaString = $commonUtil->formatQueryParaMap ( $this->parameters, false );
			$md5SignUtil = new MD5SignUtil ();
			return $md5SignUtil->sign ( $unSignParaString, $commonUtil->trimString ( PARTNERKEY ) );
		} catch ( SDKRuntimeException $e ) {
			die ( $e->errorMessage () );
		}
	}
	function create_hongbao_xml($retcode = 0, $reterrmsg = "ok") {
		try {
			$this->setParameter ( 'sign', $this->get_sign () );
			$commonUtil = new CommonUtil ();
			return $commonUtil->arrayToXml ( $this->parameters );
		} catch ( SDKRuntimeException $e ) {
			die ( $e->errorMessage () );
		}
	}
	function curl_post_ssl($url, $vars, $second = 30, $aHeader = array()) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_TIMEOUT, $second );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $ch, CURLOPT_SSLCERT, ROOT_PATH . DS . 'pay' . DS . 'apiclient_cert.pem' );
		curl_setopt ( $ch, CURLOPT_SSLKEY, ROOT_PATH . DS . 'pay' . DS . 'apiclient_key.pem' );
		curl_setopt ( $ch, CURLOPT_CAINFO, ROOT_PATH . DS . 'pay' . DS . 'rootca.pem' );
		if (count ( $aHeader ) >= 1) {
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $aHeader );
		}
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $vars );
		$data = curl_exec ( $ch );
		if ($data) {
			curl_close ( $ch );
			return $data;
		} else {
			$error = curl_errno ( $ch );
			curl_close ( $ch );
			return false;
		}
	}
	function Getip() {
		if ($_SERVER ['HTTP_CLIENT_IP']) {
			$onlineip = $_SERVER ['HTTP_CLIENT_IP'];
		} elseif ($_SERVER ['HTTP_X_FORWARDED_FOR']) {
			$onlineip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
		} else {
			$onlineip = $_SERVER ['REMOTE_ADDR'];
		}
		return $onlineip;
	}
	function curl_get_ssl($url) {
		$file_content = "";
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$file_content = curl_exec ( $ch );
		curl_close ( $ch );
		return $file_content;
	}
}

?>