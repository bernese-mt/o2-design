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
			<div class="content"><?php echo $rs['content'];?></div>
			<?php if($session['user_id']){ ?>
			<div style="padding:10px;" class="am-text-center">
				<input type="button" value="<?php if(fav_check($rs['id'])){ ?>已收藏<?php } else { ?>加入收藏<?php } ?>" class="am-btn am-btn-primary" onclick="$.phpok_app_fav.act('<?php echo $rs['id'];?>',this)" />
			</div>
			<?php } ?>
		</div>
	</article>
	<?php $this->output("block/next_prev","file",true,false); ?>
	<?php if($page_rs['comment_status']){ ?>
		<?php $this->assign("tid",$rs['id']); ?><?php $this->output("block/comment","file",true,false); ?>
	<?php } ?>
</div>
<?php $this->output("foot","file",true,false); ?>