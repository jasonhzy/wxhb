<?php
class page {
	private $currentpage;
	private $pagesize;
	private $nums;
	private $pagenums;
	private $disnums;
	private $pagestyle;
	private $disjump;
	private $css;
	function __construct($currentpage = 1, $pagesize, $nums, $disnums = 5, $pagestyle = 1, $disjump = 0, $css = '') {
		$this->currentpage = ($currentpage == '') || ($currentpage < 1) ? 1 : intval ( $currentpage );
		$this->pagesize = intval ( $pagesize );
		$this->nums = intval ( $nums );
		$this->pagenums = ceil ( $this->nums / $this->pagesize );
		$this->disnums = intval ( $disnums );
		$this->pagestyle = intval ( $pagestyle );
		$this->disjump = intval ( $disjump );
		$this->css = $css;
		$this->showpages ();
	}
	function showpages() {
		$urls = parse_url ( $_SERVER ['REQUEST_URI'] );
		$url = $urls ['path'];
		$urlquery = $urls ['query'];
		$urlquery = str_replace ( "&page=" . $this->currentpage, "", $urlquery );
		$urlquery = str_replace ( "page=" . $this->currentpage, "", $urlquery );
		if ($urlquery != '')
			$urlquery = $urlquery . '&';
		$lastpg = $this->pagenums;
		$prev = $this->currentpage - 1;
		$next = $this->currentpage + 1;
		$prev = max ( 1, $prev );
		$next = min ( $next, $lastpg );
		switch ($this->pagestyle) {
			case 1 :
				return $this->pagestyle1 ( $url, $urlquery, $lastpg, $prev, $next );
				break;
			case 2 :
				return $this->pagestyle2 ( $url, $urlquery, $lastpg, $prev, $next );
				break;
			case 3 :
				return $this->pagestyle3 ( $url, $urlquery, $lastpg, $prev, $next );
				break;
			case 4 :
				return $this->pagestyle4 ( $url, $urlquery, $lastpg, $prev, $next );
				break;
			default :
				echo '分页类型pagestyle';
				break;
		}
	}
	function pagestyle1($url, $urlquery, $lastpg, $prev, $next) {
		$showpage = "<a href=$url?" . $urlquery . "page=1>首页</a>  <a href=$url?" . $urlquery . "page=$prev>上一页</a>  <a href=$url?" . $urlquery . "page=$next>下一页</a>  <a href=$url?" . $urlquery . "page=$lastpg>末页</a>";
		$showpage .= $this->jumpage ( $url, $urlquery );
		return $showpage;
	}
	function pagestyle2($url, $urlquery, $lastpg, $prev, $next) {
		$showpage = "共" . $this->nums . "条 第" . $this->currentpage . "页/共" . $lastpg . "页  <a href=$url?" . $urlquery . "page=1><</a>  <a href=$url?" . $urlquery . "page=$prev><<</a>";
		$maxdis = ($this->currentpage + $this->disnums <= $this->pagenums) ? $this->currentpage + $this->disnums - 1 : $this->pagenums;
		for($i = $this->currentpage; $i <= $maxdis; $i ++) {
			$showpage .= "  <a href=$url?" . $urlquery . "page=$i>" . $i . "</a>";
		}
		$showpage .= "  <a href=$url?" . $urlquery . "page=$next>>></a>  <a href=$url?" . $urlquery . "page=$lastpg>></a>  ";
		$showpage .= $this->jumpage ( $url, $urlquery );
		return $showpage;
	}
	function pagestyle3($url, $urlquery, $lastpg, $prev, $next) {
		$showpage = " <a href=$url?" . $urlquery . "page=1><font size=5><</font></a>  <a href=$url?" . $urlquery . "page=$prev> <font size=5><<</font> </a>";
		$maxdis = ($this->currentpage + $this->disnums <= $this->pagenums) ? $this->currentpage + $this->disnums - 1 : $this->pagenums;
		for($i = $this->currentpage; $i <= $maxdis; $i ++) {
			if ($i == $this->currentpage) {
				$s = "&nbsp;<font color=\"#97C815\" size=5><strong>" . $i . "</strong></font>&nbsp;";
				$showpage .= $s;
			} else {
				$showpage .= "  &nbsp;<a href=$url?" . $urlquery . "page=$i><font size=5>" . $i . "</font></a>&nbsp;";
			}
		}
		$showpage .= "  <a href=$url?" . $urlquery . "page=$next> <font size=5>>></font> </a>  <a href=$url?" . $urlquery . "page=$lastpg><font size=5>></font></a>  ";
		$showpage .= $this->jumpage ( $url, $urlquery );
		return $showpage;
	}
	function pagestyle4($url, $urlquery, $lastpg, $prev, $next) {
		$showpage = "共" . $this->nums . "条 第" . $this->currentpage . "页/共" . $lastpg . "页  <a href=$url?" . $urlquery . "page=1><</a>  <a href=$url?" . $urlquery . "page=$prev><<</a>";
		$maxdis = ($this->currentpage + $this->disnums <= $this->pagenums) ? $this->currentpage + $this->disnums - 1 : $this->pagenums;
		for($i = $this->currentpage; $i <= $maxdis; $i ++) {
			$showpage .= "  <a href=$url?" . $urlquery . "page=$i>" . $i . "</a>";
		}
		$showpage .= "  <a href=$url?" . $urlquery . "page=$next>>></a> <a href=$url?" . $urlquery . "page=$lastpg>></a>  ";
		$showpage .= " 每页 <input name=\"pagesize\" size=\"3\" value=\"" . $this->pagesize . "\" onBlur=\"javascript:window.open('" . $url . "?" . $urlquery . "pagesize='+value,'_self')\";>";
		$showpage .= $this->jumpage ( $url, $urlquery );
		return $showpage;
	}
	function jumpage($url, $urlquery) {
		if ($this->disjump == 1) {
			$showpage .= " 跳转至";
			$showpage .= " <select name='jump' onchange=\"javascript:window.open('" . $url . "?" . $urlquery . "page='+this.options[this.selectedIndex].value,'_self')\">\n";
			$maxdis = ($this->currentpage + $this->disnums + 50 <= $this->pagenums) ? $this->currentpage + $this->disnums + 49 : $this->pagenums;
			$mindis = $maxdis - 49;
			if ($mindis <= 0)
				$mindis = 1;
			for($i = $mindis; $i <= $maxdis; $i ++) {
				$showpage .= "<option value=" . $i;
				if ($i == $this->currentpage)
					$showpage .= " selected";
				$showpage .= ">" . $i . "</option>\n";
			}
			$showpage .= "</select>\n";
		}
		return $showpage;
	}
	function __destruct() {
		unset ( $currentpage );
		unset ( $pagesize );
		unset ( $nums );
		unset ( $pagenums );
		unset ( $disnums );
		unset ( $style );
		unset ( $disjump );
		unset ( $css );
	}
}
?>