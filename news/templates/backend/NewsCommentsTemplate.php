<?php htmlAdminHeading(toText($news_entries[0]['title'])); ?>
<br style="clear:both;" />
<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('news_comments'); ?></td><td class="admin-table-field" align="center"><?php echo lang('pages_date'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php
	    if (count($news_comments) > 0) {
            foreach($news_comments as $comment) {
     ?>
     <tr class="admin-table-tr">
        <td class="admin-table-titles admin-table-field">
            <?php echo toText($comment['name']); ?> | <?php echo toText($comment['email']); ?>
            <hr>
            <?php echo toText($comment['message']); ?>
        </td>
        <td class="admin-table-field date" align="center">
            <?php echo date("j.n.Y - H:m:s",(int)$comment['date']); ?>
        </td>
        <td class="admin-table-field" align="right">
               <?php htmlButtonDelete(lang('filesmanager_delete'), 'index.php?id=pages&sub_id=news&action=delete_comment&entry_id='.$comment['entry_id'].'&comment_id='.$comment['id']); ?>
        </td>
     </tr>
    <?php
            }
        }
    ?>
    </tbody>
</table>
    