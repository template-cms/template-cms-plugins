<div class="admin-heading"><?php echo lang('guestbook_add_comment'); ?></div><br />
<form action="" method="post">
<div><label><?php echo lang('guestbook_name'); ?></label></div>
<div><input type="text" value = "<?php echo $post_name; ?>" name = "comment_name" size = "100" /></div>
<div><label><?php echo lang('guestbook_email'); ?></label></div>
<div><input type="text" value = "<?php echo $post_email; ?>" name = "comment_email" size = "100" /></div>
<div><label><?php echo lang('guestbook_message'); ?></label></div>
<div><textarea name="comment_message" style = "width:650px;height:200px;" ><?php echo $post_msg; ?></textarea></div>
<div>
<?php if((getOption('captcha_installed') !== null) and (getOption('captcha_installed') == 'true')) { ?>
<table>  
  <tr><td><?php echo lang('captcha_crypt'); ?>:<input type="text" name="code"></td><td><?php dsp_crypt(0,1); ?></td></tr>  
</table>
<?php } ?>
</div>
<?php if(count($errors) > 0) { ?>
<?php   foreach($errors as $error) { ?>
            <span style="color:#FA7660"><li><?php echo $error; ?></li></span>
<?php   } ?>
        <br />
<?php } ?>
<div><input type="submit" class="submit"  name="comment_send" value="<?php echo lang('guestbook_send'); ?>" /></div>
</form>