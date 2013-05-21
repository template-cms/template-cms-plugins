<div>
<?php
    htmlButton(lang('mailnewsletter_new_subscriber_add'),'#',null,true,'mailnewsletter-add','data-reveal-id="mailnewsletter-add"');           
    htmlMsgWindow('mailnewsletter-add',
         '<h2>'.lang('mailnewsletter_new_subscriber_add').'</h2><form action="index.php?id=pages&amp;sub_id=mailnewsletter" method="post">
          <br/><label>'.lang('mailnewsletter_new_subscriber').'</label><br />
          <input type="text" value="" name="mailnewsletter" size="30">
          <input type="submit" class="submit" name="add_subcriber" value="'.lang('mailnewsletter_add').'">
          </form>'
    );

?>
 :
<?php
    htmlButton(lang('mailnewsletter_options'),'#',null,true,'mailnewsletter-options','data-reveal-id="mailnewsletter-options"');           
    htmlMsgWindow('mailnewsletter-options',    
         '<h2>'.lang('mailnewsletter_options').'</h2><form action="index.php?id=pages&amp;sub_id=mailnewsletter" method="post">
          <br/><label>'.lang('mailnewsletter_sender').'</label><br />
          <input type="text" value="'.getOption('mailnewsletter_sender').'" name="email" size="30">
          <br/><label>'.lang('mailnewsletter_message').'</label><br />
          <textarea name="message" style="width:600px; height:180px;">'.getOption('mailnewsletter_message').'</textarea>
          <br/>
          <input type="submit" class="submit" name="mailnewsletter_save_options" value="'.lang('mailnewsletter_save_options').'">
          </form>'
    );
?>
 :
<a href="<?php echo getOption('siteurl'); ?>admin/index.php?id=pages&amp;sub_id=mailnewsletter"><?php echo lang('mailnewsletter_new_letter'); ?></a>
</div>

<hr>

<br />

<div>
<table class="admin-table" style="width:950px;">
    <thead class="admin-table-header">
        <tr>
			<td class="admin-table-field"><?php echo lang('mailnewsletter_letter'); ?></td>			
		</tr>
    </thead>
    <tbody class="admin-table-content">
     <tr class="admin-table-tr">  
 	 <td class="admin-table-field" style="padding-left:20px;">
<?php
    htmlFormOpen('index.php?id=pages&sub_id=mailnewsletter');
    htmlFormInput(array('name'=>'recipients','value'=>$letter_recipients,'size'=>'100'), lang('mailnewsletter_recipients'));    
    htmlFormInput(array('name'=>'subject','value'=>$letter_subject,'size'=>'100'), lang('mailnewsletter_subject'));    
	  htmlFormHidden('uid', $letter_uid);
    htmlMemo('body', array('style'=>'width:820px; height:180px;'),$letter_body,lang('mailnewsletter_message'));
    htmlBr();
	  htmlFormButton(array('name'=>'mailnewsletter_save','value'=>lang('mailnewsletter_save')));
	  htmlNbsp(2);
    htmlFormClose(true, array('name'=>'send','value'=>lang('mailnewsletter_send')));    
    htmlBr();
?>
	</td>
</tr>
    </tbody>
</table>
</div>

<br />

<div style="float:left">
<table class="admin-table" style="width:465px;">
    <thead class="admin-table-header">
        <tr>
			<td class="admin-table-field"><?php echo lang('mailnewsletter_subscribers'); ?> - <?php echo $subscribers_count; ?> - <a href="#" class="mailto_select_all"><?php echo lang('mailnewsletter_select_all'); ?></a></td>
			<td align="center"><?php echo lang('mailnewsletter_date'); ?></td>
			<td class="admin-table-field" style="text-shadow:none;" align="right"><?php htmlButtonDelete(lang('mailnewsletter_delete_all'), 'index.php?id=pages&sub_id=mailnewsletter&action=delete_subscribers'); ?></td>
		</tr>
    </thead>
    <tbody class="admin-table-content">
     <?php if(count($subscribers_records) !== 0) {  ?>
     <?php foreach($subscribers_records as $record) { ?>
     <tr class="admin-table-tr">        
        <td class="admin-table-titles admin-table-field" style="width:230px;">
         <a href="#" class="mailto" rel="<?php echo $record->email; ?>"><?php echo $record->email; ?></a>
        </td>
        <td align="center">
            <?php echo dateFormat($record->date,'Y-m-d'); ?>
        </td>
        <td class="admin-table-field" align="right">            
            <?php htmlButtonDelete(lang('mailnewsletter_delete'), 'index.php?id=pages&sub_id=mailnewsletter&action=delete_subscriber&subscriber_id='.$record['id']); ?>
        </td>
     </tr>
    <?php }  } ?>
    </tbody>
</table>
</div>

<div style="float:left;margin-left:20px;">
<table class="admin-table" style="width:465px;">
    <thead class="admin-table-header">
        <tr>
			<td class="admin-table-field"><?php echo lang('mailnewsletter_letters'); ?> - <?php echo $letters_count; ?></a></td>
			<td align="center"><?php echo lang('mailnewsletter_date'); ?></td>
			<td class="admin-table-field" style="text-shadow:none;" align="right"><?php htmlButtonDelete(lang('mailnewsletter_letters_delete_all'), 'index.php?id=pages&sub_id=mailnewsletter&action=delete_letters'); ?></td>
		</tr>
    </thead>
    <tbody class="admin-table-content">
     <?php if(count($letters_records) !== 0) {  ?>
     <?php foreach($letters_records as $record) { ?>
     <tr class="admin-table-tr">        
        <td class="admin-table-titles admin-table-field" style="width:230px;">
         <a href="<?php echo getOption('siteurl'); ?>admin/index.php?id=pages&sub_id=mailnewsletter&action=letter&uid=<?php echo $record->uid; ?>" class="letter" rel="<?php echo $record->uid ?>"><?php if(strlen($record->subject) > 37) echo substr($record->subject,0,37).'...'; else echo $record->subject; ?></a>
        </td>
        <td align="center">
            <?php echo dateFormat($record->date,'Y-m-d'); ?>
        </td>
        <td class="admin-table-field" align="right">            
            <?php htmlButtonDelete(lang('mailnewsletter_delete'), 'index.php?id=pages&sub_id=mailnewsletter&action=delete_letter&letter_id='.$record['id']); ?>
        </td>
     </tr>
    <?php }  } ?>
    </tbody>
</table>

</div>

<br style="clear:both;">