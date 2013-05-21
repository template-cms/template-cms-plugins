<div>
<?php
    htmlButton(lang('hearts_new_heart_create'),'#',null,true,'hearts-add','data-reveal-id="hearts-add"');           
    htmlMsgWindow('hearts-add',
         '<h2>'.lang('hearts_new_heart_create').'</h2><form action="index.php?id=pages&amp;sub_id=hearts" method="post">
          <br/><label>'.lang('hearts_title').'</label><br />
          <input type="text" value="'.lang('hearts_title').'" name="title" size="30">
          <input type="submit" class="submit" name="create_heart" value="'.lang('hearts_create').'">
          </form>'
    );

?>
</div>

<hr>

<br />

<table class="admin-table">
    <thead class="admin-table-header">
        <tr>
            <td class="admin-table-field"><?php echo lang('hearts_hearts'); ?></a></td>
            <td align="center"><?php echo lang('hearts_code_template'); ?></td>
            <td align="center"><?php echo lang('hearts_code_text'); ?></td>
            <td align="center"><?php echo lang('hearts_counter'); ?></td>
            <td class="admin-table-field" style="text-shadow:none;" align="right"></td>
        </tr>
    </thead>
    <tbody class="admin-table-content">
     <?php if(count($hearts_index_records) !== 0) {  ?>
     <?php foreach($hearts_index_records as $hearts_index_record) { ?>     
          
     <?php 
          htmlMsgWindow('hearts-edit-'.$hearts_index_record['id'],                  
               '<p align="left"><h2>'.lang('hearts_heart_edit').'</h2><form action="index.php?id=pages&amp;sub_id=hearts" method="post">
                <br/><label>'.lang('hearts_title').'</label><br />
                <input type="hidden" value="'.$hearts_index_record['id'].'" name="heart_id" />
                <input type="text" value="'.$hearts_index_record->title.'" name="title" size="30">
                <input type="submit" class="submit" name="edit_heart" value="'.lang('hearts_edit').'">
                </p>
                </form>'
          );
      ?>
     
     <tr class="admin-table-tr">        
        <td class="admin-table-titles admin-table-field" style="width:230px;">
            <?php echo $hearts_index_record->title; ?>
        </td>
        <td align="center">
            &lt;?php heart('<?php echo $hearts_index_record->uid; ?>'); ?&gt;
        </td>
        <td align="center">
            [php] heart('<?php echo $hearts_index_record->uid; ?>'); [/php]
        </td>
        <td align="center">
            <?php echo $hearts_index_record->counter; ?>
        </td>
        <td class="admin-table-field" align="right">                        
            <span rel="<?php echo $hearts_index_record['id']; ?>" class="hearts-edit-<?php echo $hearts_index_record['id']; ?> btn-edit reval-edit"><a href="#" data-reveal-id="hearts-edit-<?php echo $hearts_index_record['id']; ?>" title="<?php echo lang('hearts_edit'); ?>"><?php echo lang('hearts_edit'); ?></a></span>
            <?php htmlButtonDelete(lang('mailnewsletter_delete'), 'index.php?id=pages&sub_id=hearts&action=delete_heart&heart_id='.$hearts_index_record['id']); ?>
        </td>
     </tr>
    <?php }  } ?>
    </tbody>
</table>