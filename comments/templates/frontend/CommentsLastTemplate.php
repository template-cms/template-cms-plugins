<?php
    if(count($comments) > 0)
    foreach ($comments as $comment) {
?>
    <div>
        <b><?php echo toText($comment['name']); ?></b> <i><?php echo dateFormat($comment['date']); ?></i>
    </div>
    <div>
        <?php echo toText($comment['message']); ?>
    </div>
<?php
    }
?>