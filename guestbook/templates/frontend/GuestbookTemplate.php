<div class="admin-heading"><?php echo lang('guestbook_title'); ?></div><br />
<?php if(count($comments) > 0) { ?>
<?php	foreach($comments as $comment) { ?>
<p>
	<h4><?php echo toText($comment['name']); ?></h4>
	<?php echo toText($comment['message']); ?>
	<p align="right">
		<i><?php echo date("j.n.Y - H:i:s",(int)$comment['date']); ?></i>
	</p>
</p>	
<?php } ?>
<?php } else { ?>
    <div style="height:100px;"></div>
<?php } ?>