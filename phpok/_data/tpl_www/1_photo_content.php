<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("menutitle",$page_rs['title']); ?><?php $this->output("head","file",true,false); ?>
<?php if($cate_rs['banner'] || $page_rs['banner']){ ?>
<div class="banner" style="background-image:url('<?php echo $cate_rs['banner'] ? $cate_rs['banner']['filename'] : $page_rs['banner']['filename'];?>');"><img src="images/blank.gif" alt="<?php echo $cate_rs['title'];?>" /></div>
<?php } ?>
<div class="main">
	<?php $this->output("block/breadcrumb","file",true,false); ?>
	<article class="am-article">
		<div class="am-article-hd">
			<h1 class="am-article-title"><?php echo $rs['title'];?></h1>
			<p class="am-article-meta">
				发布日期：<span><?php echo time_format($rs['dateline']);?></span>&nbsp; &nbsp;
				浏览次数：<span id="lblVisits"><?php echo $rs['hits'];?></span>
				<?php if($rs['tag']){ ?>
				&nbsp; &nbsp; 关键字：
					<?php $rs_tag_id["num"] = 0;$rs['tag']=is_array($rs['tag']) ? $rs['tag'] : array();$rs_tag_id = array();$rs_tag_id["total"] = count($rs['tag']);$rs_tag_id["index"] = -1;foreach($rs['tag'] as $key=>$value){ $rs_tag_id["num"]++;$rs_tag_id["index"]++; ?>
					<a href="<?php echo $value['url'];?>" title="<?php echo $value['alt'];?>" target="<?php echo $value['target'];?>" style="color:#999;"><?php echo $value['title'];?></a>
					<?php } ?>
				<?php } ?>
			</p>
		</div>
		<div class="am-article-bd">
			<?php if($rs['note']){ ?><p class="am-article-lead"><?php echo nl2br($rs['note']);?></p><?php } ?>
			<?php if($rs['pictures']){ ?>
			<div data-am-widget="slider" class="am-slider am-slider-c3" data-am-slider='{"controlNav":false}'>
				<ul class="am-slides">
					<?php $tmpid["num"] = 0;$rs['pictures']=is_array($rs['pictures']) ? $rs['pictures'] : array();$tmpid = array();$tmpid["total"] = count($rs['pictures']);$tmpid["index"] = -1;foreach($rs['pictures'] as $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
					<li>
						<img src="<?php echo $value['gd']['auto'];?>" />
						<div class="am-slider-desc"><div class="am-slider-counter"><span class="am-active"><?php echo $tmpid['num'];?></span>/<?php echo $tmpid['total'];?></div><?php echo $value['title'];?></div>
					</li>
					<?php } ?>
				</ul>
			</div>

			<?php } ?>
			<?php if($rs['content']){ ?>
			<div class="content"><?php echo $rs['content'];?></div>
			<?php } ?>
		</div>
	</article>
	<hr data-am-widget="divider" style="" class="am-divider am-divider-dotted" />
	<ul class="am-avg-sm-2">
		<li>上一主题：
			<?php $prev = phpok_prev($rs);?>
			<?php if($prev){ ?>
			<a href="<?php echo $prev['url'];?>" title="<?php echo $prev['title'];?>"><?php echo $prev['title'];?></a>
			<?php } else { ?>
			没有了
			<?php } ?>
		</li>
		<li style="text-align:right;">下一主题：
			<?php $next = phpok_next($rs);?>
			<?php if($next){ ?>
			<a href="<?php echo $next['url'];?>" title="<?php echo $next['title'];?>"><?php echo $next['title'];?></a>
			<?php } else { ?>
			没有了
			<?php } ?>
		</li>
	</ul>
	<?php if($page_rs['comment_status']){ ?><?php $this->assign("tid",$rs['id']); ?><?php $this->output("block/comment","file",true,false); ?><?php } ?>
</div>
<?php $this->output("foot","file",true,false); ?>
