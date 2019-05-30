<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}
class XPage extends X{
    
    
	public static function admin($num, $perpage, $curr_page, $mpurl, $maxpage){   
		$multipage ='';
		$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
		if($num>$perpage){   
			$page    = $maxpage;   
			$offset  = floor($page * 0.5);
			$pages   = ceil($num/$perpage);   
			$from    = $curr_page -$offset;   
			$to      = $curr_page + $page - $offset - 1;   
			if($page > $pages){
				$from = 1;    
				$to   = $pages;
			}else{
				if($from<1){
					$to   = $curr_page + 1 - $from;
					$from =1;
					if(($to - $from)<$page && ($to - $from) < $pages){
						$to = $page;
					}
				}elseif($to>$pages){   
					$from = $curr_page - $pages + $to;
					$to   = $pages;
					if(($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}
			$multipage .="<td align='center' class='page_redirect' style='cursor:pointer' onmouseover=\"this.className='on_page_redirect';\" onmouseout=\"this.className='page_redirect';\" onclick=\"window.location.href='".$mpurl."page=1';\" title='首页'><img src='".parent::$urlpath."tpl/static/images/page_home.gif'></td>";
			for($i=$from;$i<=$to;$i++){   
				if($i!=$curr_page){
					$multipage.="<td align='center' class='page_number' style='cursor:pointer' onmouseover=\"this.className='on_page_number';\" onmouseout=\"this.className='page_number';\" onclick=\"window.location.href='".$mpurl."page=".$i."';\" title='第".$i."页'>".$i."</td>";
				}else{
					$multipage.="<td align='center' class='page_curpage' title='第".$i."页'>".$i."</td>";
				}
			}
			$multipage.=$pages > $page ? "<td align='center' class='page_redirect' style='cursor:pointer' onmouseover=\"this.className='on_page_redirect';\" onmouseout=\"this.className='page_redirect';\" onclick=\"window.location.href='".$mpurl."page=".$pages."';\" title='尾页'><img src='".parent::$urlpath."tpl/static/images/page_end.gif'></td>":"<td align='center' class='page_redirect' style='cursor:pointer' onmouseover=\"this.className='on_page_redirect';\" onmouseout=\"this.className='page_redirect';\" onclick=\"window.location.href='".$mpurl."page=".$pages."';\" title='尾页'><img src='".parent::$urlpath."tpl/static/images/page_end.gif'></td>";
			
			$multipage .="<td align='center'><input name='page' title='输入页码 按回车可跳转' type='text'  class='page_input' onkeypress=\"if(event.keyCode==13) window.location.href='".$mpurl."page='+value\" /></td>";
		}
		if(!$pages){
			$recordnav = "<td align='center' class='page_total' title='总记录/每页".$perpage."个'>&nbsp;".$num."&nbsp;</td>";
		}else{
			$recordnav = "<td align='center' class='page_total' title='总记录/每页".$perpage."个'>&nbsp;".$num."&nbsp;</td>";
			$recordnav.= "<td align='center' class='page_pages' title='当前页码/总页码'>&nbsp;".$curr_page."/".$pages."&nbsp;</td>";
		}
		$tabdiv.= "  <div style='float:center;'>";
		$tabdiv.= "    <table border='0' cellpadding='0' cellspacing='1'>";
		$tabdiv.= "      <tr>";
		$tabdiv = $tabdiv.$recordnav;
		$tabdiv = $tabdiv.$multipage;
		$tabdiv.= "      </tr>";
		$tabdiv.= "    </table>";
		$tabdiv.= "  </div>";

		return $tabdiv;
	}
    
    
	public static function index($num, $perpage, $curr_page, $mpurl, $maxpage = 10){
		$multipage ='';
        if (false === strpos($mpurl, '?')) {
            $mpurl .= '?';
        }
        else {
            $mpurl .= '&amp;';
        }
		if ($num>$perpage) {   
			$page    = $maxpage;
			$pages   = ceil($num/$perpage);
            
			$offset  = floor($page * 0.5);
			$from    = $curr_page -$offset;   
			$to      = $curr_page + $page - $offset - 1;   
			if ($page > $pages) {
				$from = 1;   
				$to   = $pages;
			}
            else {
				if ($from<1) {
					$to   = $curr_page + 1 - $from;
					$from =1;
					if (($to - $from)<$page && ($to - $from) < $pages) {
						$to = $page;
					}
				}
                elseif ($to>$pages) {   
					$from = $curr_page - $pages + $to;
					$to   = $pages;
					if (($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}
            
			
			if ($curr_page>1) {
				$tippage = "<a href=\"".$mpurl."page=".($curr_page-1)."\">上一页</a>";
			}
            else {
				
			}
            
			
			for ($i=$from; $i<=$to; $i++){   
				if ($i != $curr_page) {
					$multipage .= "<a href=\"".$mpurl."page=".$i."\">".$i."</a>";
				}
                else {
					$multipage .= "<a href=\"".$mpurl."page=".$i."\" class='on'>".$i."</a>";
				}
			}
			$tippage = $tippage.$multipage;
            
			
			if ($pages>1 && $pages!=$curr_page) {
				$tippage .= "<a href=\"".$mpurl."page=".($curr_page+1)."\" class='end'>下一页</a>";
			}
            else {
				
			}
			return $tippage;
			
		}
        else {
			return "";
		}
	}
    
     
	public static function indexHtml($num, $perpage, $curr_page, $mpurl, $maxpage = 10){
		$multipage ='';
		if ($num>$perpage) {   
			$page    = $maxpage;
			$pages   = ceil($num/$perpage);
            
			$offset  = floor($page * 0.5);
			$from    = $curr_page -$offset;   
			$to      = $curr_page + $page - $offset - 1;   
			if ($page > $pages) {
				$from = 1;   
				$to   = $pages;
			}
            else {
				if ($from<1) {
					$to   = $curr_page + 1 - $from;
					$from =1;
					if (($to - $from)<$page && ($to - $from) < $pages) {
						$to = $page;
					}
				}
                elseif ($to>$pages) {   
					$from = $curr_page - $pages + $to;
					$to   = $pages;
					if (($to - $from) < $page && ($to - $from) < $pages) {
						$from = $pages - $page + 1;
					}
				}
			}
            
			
			if ($curr_page>1) {
				$tippage = "<a href=\"".$mpurl."page_".($curr_page-1).".html\">上一页</a>";
			}
            else {
				
			}
            
			
			for ($i=$from; $i<=$to; $i++){   
				if ($i != $curr_page) {
					$multipage .= "<a href=\"".$mpurl."page_".$i.".html\">".$i."</a>";
				}
                else {
					$multipage .= "<a href=\"".$mpurl."page_".$i.".html\" class='on'>".$i."</a>";
				}
			}
			$tippage = $tippage.$multipage;
            
			
			if ($pages>1 && $pages!=$curr_page) {
				$tippage .= "<a href=\"".$mpurl."page_".($curr_page+1).".html\" class='end'>下一页</a>";
			}
            else {
				
			}
			return $tippage;
			
		}
        else {
			return "";
		}
	}

}
?>
