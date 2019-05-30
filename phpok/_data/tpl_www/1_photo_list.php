<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("menutitle",$page_rs['title']); ?><?php $this->output("head","file",true,false); ?>
<?php if($cate_rs['banner'] || $page_rs['banner']){ ?>
<div class="banner" style="background-image:url('<?php echo $cate_rs['banner'] ? $cate_rs['banner']['filename'] : $page_rs['banner']['filename'];?>');"><img src="images/blank.gif" alt="<?php echo $cate_rs['title'];?>" /></div>
<?php } ?>
<div class="main">
	<?php $this->output("block/breadcrumb","file",true,false); ?>
	<div class="left am-panel-group">
		<?php $this->assign("pid",$page_rs['id']); ?><?php $this->assign("cid",$cate_rs['id']); ?><?php $this->assign("title",$page_rs['title']); ?><?php $this->output("block/catelist","file",true,false); ?>
		<?php $this->output("block/contact","file",true,false); ?>
		<?php $this->output("block/hot_article","file",true,false); ?>
	</div>
	<div class="right" style="margin-top:-8px;">
		<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-bordered" data-am-gallery="{  }">
			<?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id = array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist as $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
			<li>
				<div class="am-gallery-item">
					<a href="<?php echo $value['url'];?>" title="<?php echo $value['title'];?>">
						<img src="<?php echo $value['thumb']['gd']['thumb'];?>" alt="<?php echo $value['title'];?>" />
						 <h3 class="am-gallery-title"><?php echo $value['title'];?></h3>
						<div class="am-gallery-desc"><?php echo time_format($value['dateline']);?></div>
					</a>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php $this->output("block/pagelist","file",true,false); ?>
	</div>
</div>
<?php $this->output("foot","file",true,false); ?>
