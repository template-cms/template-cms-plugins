<h3><?php echo lang('comments_comments'); ?> ( <?php echo $count; ?> )</h3>
<?php
    if(count($comments) > 0)
    foreach ($comments as $comment) {
?>
    <div class="comment">
        <div class="comment-header">
            <div class="comment-author">
                <?php echo toText($comment['name']); ?>
            </div>
            <div class="comment-date">
                <?php echo dateFormat($comment['date']); ?>
            </div>
        </div>
        <div class="comment-body">
			<?php echo applyFilters('comments', toText($comment['message'])); ?>
        </div>
    </div>
<?php
    }
    htmlBr();
?>
<h3><?php echo lang('comments_leave_a_reply'); ?></h3>

<form action="" method="post">
<div><label><?php echo lang('comments_name'); ?></label></div>
<div><input type="text" value = "<?php echo $post_name; ?>" name = "comments_name" size = "50" /></div>
<div><label><?php echo lang('comments_email'); ?></label></div>
<div><input type="text" value = "<?php echo $post_email; ?>" name = "comments_email" size = "50" /></div>
<div><label><?php echo lang('comments_message'); ?></label></div>
<div><textarea name="comments_message" style = "width:400px;height:150px;" ><?php echo $post_msg; ?></textarea></div>
<div>
<?php
    if(count($errors) > 0) {
        foreach($errors as $error) {
            echo '<span style="color:#FA7660"><li>'.$error.'</li></span>';
        }
    }
?>
</div>
<div><label><?php echo lang('i_am_not_a_robot'); ?></label><input type="checkbox" name = "i_am_not_a_robot" size = "100" /></div>
<div><input type="submit" class="submit"  name="send_comment" value="<?php echo lang('comments_send'); ?>" class="wymupdate" /></div>
</form>