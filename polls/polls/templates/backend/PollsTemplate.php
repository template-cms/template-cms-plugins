<div id="section-bar">
    <?php htmlButton(lang('polls_add'),'index.php?id=pages&sub_id=polls&action=add'); ?>
    <?php runHook('admin_users_extra_buttons'); ?>
</div>
<div style="clear:both"></div>
<table class="admin-table">
    <thead class="admin-table-header">
        <tr>
            <td width="10">&nbsp;</td>
            <td class="admin-table-field"><?php echo lang('polls_table_question');?></td>
            <td align="center" width="40"><?php echo lang('polls_date');?></td>
            <td align="center"><?php echo lang('polls_for_template');?></td>
            <td align="center"><?php echo lang('polls_to_page');?></td>
            <td width="50"></td>
            <td width="50"></td>
        </tr>
    </thead>
    <tbody class="admin-table-content">
    <?php        
        foreach ($polls as $poll) {
    ?>
     <tr class="admin-table-tr">
        <td>
            
            <a href="index.php?id=pages&sub_id=polls&action=default&poll_id=<?php echo $poll['id'];?>" class="poll-def <?php if ($default_poll_id == $poll['id']) echo 'active';?>" title="<?php echo lang('polls_default');?>">
                <?php echo '&hearts;'; ?></a>
        </td>
        <td class="admin-table-field">
            <?php echo toText($poll->question); ?>
        </td>
        <td class="admin-table-field" align="center">
            <?php echo toText($poll->date); ?>
        </td>
        <td class="admin-table-field" align="center" width="175">
            <?php echo '&lt;?php pollsShow('.$poll['id'].');?&gt;';?>
        </td>
        <td class="admin-table-field" align="center" width="175">
            <?php echo '[php] pollsShow('.$poll['id'].'); [/php]';?>
        </td>
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('users_edit'), 'index.php?id=pages&sub_id=polls&action=edit&poll_id='.$poll['id']); ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php 
            if ($default_poll_id != $poll['id']) {
                htmlButtonDelete(lang('users_delete'), 'index.php?id=pages&sub_id=polls&action=delete&poll_id='.$poll['id']); 
            }
            ?>
        </td>
     </tr>
    <?php        
        }
    ?>
    </tbody>
</table><br/>

<table width="100%" border="0">
    <tr>
        <td width="33%">
            <h2><?php echo lang('polls_show_all');?></h2><br/>
            <b>&lt;?php pollsShow('all');?&gt;</b>- <?php echo lang('polls_for_template');?><br/>
            <b>[php] pollsShow('all'); [/php]</b> - <?php echo lang('polls_to_page');?>
        </td>
        <td>
            <h2><?php echo lang('polls_show_random');?></h2><br/>
            <b>&lt;?php pollsShow('rand');?&gt;</b> - <?php echo lang('polls_for_template');?><br/>
            <b>[php] pollsShow('rand'); [/php]</b> - <?php echo lang('polls_to_page');?>
        </td>
        <td width="33%">
            <h2><?php echo lang('polls_show_default');?></h2><br/>
            <b>&lt;?php pollsShow();?&gt;</b> - <?php echo lang('polls_for_template');?><br/>
            <b>[php] pollsShow(); [/php]</b> - <?php echo lang('polls_to_page');?>
        </td>
    </tr>
</table>