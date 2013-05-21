<form action="" method="post">
<div><label><?php echo lang('contacts_subject'); ?></label></div>
<div><input type="text" value = "<?php echo $post_name; ?>" name = "contacts_subject" size = "50" /></div>
<div><label><?php echo lang('contacts_contact'); ?></label></div>
<div><input type="text" value = "<?php echo $post_contact; ?>" name = "contacts_contact" size = "50" /></div>
<div><label><?php echo lang('contacts_message'); ?></label></div>
<div><textarea name="contacts_msg" style = "width:350px;height:200px;"><?php echo $post_msg; ?></textarea></div>
<div><?php echo lang('i_am_not_a_robot'); ?> <input type="checkbox" name="i_am_not_a_robot"></div><br />
<?php if(count($errors) > 0) { ?>
<?php foreach($errors as $error) { ?>
		<span style="color:#FA7660"><li><?php echo $error; ?></li></span>
<?php } ?>
		<br />
<?php }	?>
<div><input type="submit" class="submit" name="contacts_send" value="<?php echo lang('contacts_send'); ?>" /></div>
</form> 