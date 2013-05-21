<?php
htmlAdminHeading(lang('guestbook_title'));
htmlBr();

if(isGet('flash')) {
    if(get('flash') == 'create_database') {
        echo '<div class="message-ok">'.lang('guestbook_create_db').'</div>';
    }
}

if(count($comments) > 0) {
    foreach($comments as $comment) {
        ?>
<p>
<h4><?php echo toText($comment->name); ?> (<?php echo toText($comment->email); ?>)</h4>
        <?php echo toText($comment->message); ?>
<p align="right">
    <i><?php echo date("j.n.Y - H:i:s",(int)$comment->date); ?></i>
            <?php htmlButtonDelete(lang('guestbook_delete_comment'), 'index.php?id=pages&sub_id=guestbook&action=delete_comment&delete='.$comment['id']); ?>
</p>		
</p>	
        <?php
    }
}
?>