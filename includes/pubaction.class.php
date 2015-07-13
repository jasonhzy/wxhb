<?php
class pubaction extends mysql {
	const LOGINURL = 'login.php';
	function admin_logincheck() {
		if (! isset ( $_SESSION ['adminid'] ) || $_SESSION ['adminid'] == '') {
			echo "<script>window.parent.location.href='login.php';</script>";
			exit ();
		}
	}
	function user_logincheck() {
		if (! isset ( $_SESSION ['ucode'] ) || $_SESSION ['ucode'] == '') {
			echo "<script>location.href='guanzhu.php';</script>";
			exit ();
		}
	}
	function user_online($logintime = 8200) {
		$now = mktime ();
		if (! $this->checkbasename () && ($now - $_SESSION ['ontime'] > $logintime)) {
			session_destroy ();
			$this->showmsg ( "登陆已超时，请重新登录", self::LOGINURL );
		} else {
			$_SESSION ['ontime'] = mktime ();
		}
	}
	function showalert($msg = '编辑成功！', $tourl = '', $type = 1) {
		echo "<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />";
		if ($type == 1)
			echo "<script language=javascript>alert('$msg');location='$tourl';</script>";
		else
			echo "<script language=javascript>location='$tourl';</script>";
	}
}
?>