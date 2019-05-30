<?php if(!defined("PHPOK_SET")){exit("<h1>Access Denied</h1>");} ?><ul class="am-list am-list-static">
	<li>
		<?php $prev = phpok_prev($rs);?>
		<?php if($prev){ ?>
		<a href="<?php echo $prev['url'];?>" title="<?php echo $prev['title'];?>">上一主题：<?php echo $prev['title'];?></a>
		<?php } else { ?>
		没有了
		<?php } ?>
	</li>
	<li>
		<?php $next = phpok_next($rs);?>
		<?php if($next){ ?>
		<a href="<?php echo $next['url'];?>" title="<?php echo $next['title'];?>">下一主题：<?php echo $next['title'];?></a>
		<?php } else { ?>
		没有了
		<?php } ?>
	</li>
</ul>