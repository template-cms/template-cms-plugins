<?php
    htmlFormOpen('index.php?id=pages&sub_id=comments');
    htmlFormInput(array('name'=>'widget_comments_count','size'=>30,'value'=>$xml_db_comments_config['xml_object']->comments_option->widget_comments_count),lang('comments_last_comments_widget'));
    htmlNbsp(1);
    htmlFormClose(true, array('name'=>'comments_save_options','value'=>lang('comments_save')));
?>
<br style="clear:both;" />
<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('comments_comments'); ?></td><td class="admin-table-field" align="center"><?php echo lang('pages_date'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php
	    if (count($comments) > 0) {
            foreach($comments as $comment) {
     ?>
     <tr class="admin-table-tr">
        <td class="admin-table-titles admin-table-field">
            <?php echo toText($comment['name']); ?> | <?php echo toText($comment['email']); ?> | <a target="_blank" href="<?php echo toText($comment['page_url']); ?>"><?php echo lang('comments_page'); ?></a>
            <hr>
            <?php echo toText($comment['message']); ?>
        </td>
        <td class="admin-table-field date" align="center">
            <?php echo date("j.n.Y - H:m:s",(int)$comment['date']); ?>
        </td>
        <td class="admin-table-field" align="right">
               <?php htmlButtonDelete(lang('comments_delete'), 'index.php?id=pages&sub_id=comments&action=delete_comment&comment_id='.$comment['id']); ?>
        </td>
     </tr>
    <?php
            }
        }
    ?>
    </tbody>
</table>