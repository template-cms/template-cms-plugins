<div id="wrap2">
<div id="tabs">
		<ul>
			<li><a href="#tabs-2"><?php echo lang('fmanager_tabcat_edit'); ?></a></li>
		</ul>
			<div id="tabs-2">
				<h2><?php echo lang('fmanager_file_edit').' '.toText($f_edit[0]['filename']); ?></h2>
				<hr style="margin-top: 5px; height: 0px;">
				<?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
				<?php htmlSelect($cat_list,array('name'=>'f_category'),lang('fmanager_parentcategoryname'),toText($f_edit[0]['category']));
					  htmlFormInput(array('type'=>'text','name'=>'f_name','size'=>'50','value'=>toText($f_edit[0]['name'])),lang('fmanager_namelabel')); 
					  htmlFormHidden('entry_id', $f_edit[0]['id']); 
					  htmlFormHidden('entry_filename', $f_edit[0]['filename']);
					  
				?>
				<?php htmlMemo('editor2',array('style'=>'width:100%;height:80px;'),toText($f_edit[0]['description']),lang('fmanager_descriptionlabel')); ?>
				<div style="margin: 20px 0;">
					<input type="checkbox" size="100" name="f_show" value="checked" <?php echo toText($f_edit[0]['published']); ?>>
					<?php echo lang('fmanager_file_publish'); ?>
				</div>
				<?php htmlFormClose(true,array('name'=>'edit_file','value'=>lang('fmanager_save_options'))); ?>
			</div>
		</div>
	</div>

<div style="clear:both"></div>