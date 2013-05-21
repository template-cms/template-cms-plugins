<div id="wrap2">
<div id="tabs">
		<ul>
			<li><a href="#tabs-2"><?php echo lang('fmanager_tabcat_add'); ?></a></li>
		</ul>
			<div id="tabs-2">
				<h2><?php echo lang('fmanager_file_add').' '.$f_filename; ?></h2>
				<hr style="margin-top: 5px; height: 0px;">
				<?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
				<?php htmlSelect($cat_list,array('name'=>'f_category'),lang('fmanager_parentcategoryname'));
					  htmlFormInput(array('type'=>'text','name'=>'f_name','size'=>'50','value'=>''),lang('fmanager_namelabel')); 
					  htmlFormHidden('entry_filename', $f_filename);
					  
				?>
				<?php htmlMemo('editor2',array('style'=>'width:100%;height:80px;'),'',lang('fmanager_descriptionlabel')); ?>
				<div style="margin: 20px 0;">
					<input type="checkbox" size="100" name="f_show" value="checked" checked="checked">
					<?php echo lang('fmanager_file_publish'); ?>
				</div>
				<?php htmlFormClose(true,array('name'=>'add_file','value'=>lang('fmanager_save_options'))); ?>
			</div>
		</div>
</div>

<div style="clear:both"></div>