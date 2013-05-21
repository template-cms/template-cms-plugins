<div style="border:1px dashed #ccc; padding:5px; width:180px;">
<form id="mailnewsletter-validate-email" method="post" action="<?php echo getOption('siteurl'); ?>mailnewsletter/subscribe" onsubmit="javascript:return mailnewsletterValidateEmail('mailnewsletter-validate-email','email');">
    <?php echo lang('mailnewsletter_subscribers_count'); ?>: <?php echo $subscribers_count; ?>
    <hr></label><?php echo lang('mailnewsletter_email'); ?></label><br />
    <input type="text" value="" name="email" /><br />
    <input type="submit" value="<?php echo lang('mailnewsletter_subscribe'); ?>" name="subscribe" />    
</form>
</div>