<div id="wrap2">
<div id="tabs">
		<ul>
			<li><a href="#tabs-3"><?php echo lang('fmanager_tabcat_edit'); ?></a></li>
		</ul>
			<div id="tabs-3">
				<h2><?php echo lang('fmanager_cat_edit'); ?></h2>
				<hr style="margin-top: 5px; height: 0px;">
				<?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
				<?php htmlSelect($cat_list,array('name'=>'category_parent'),lang('fmanager_parentcategoryname'),toText($category_edit[0]['category_parent']));
					  htmlFormInput(array('type'=>'text','name'=>'category_name','size'=>'90','value'=>toText($category_edit[0]['category_name'])),lang('fmanager_namelabel')); 
					  htmlFormInput(array('type'=>'text','name'=>'category_img','size'=>'90','value'=>toText($category_edit[0]['category_img'])),lang('fmanager_category_imgcapture')); 
					  htmlFormHidden('entry_id', $category_edit[0]['id']); 
					  
				?>
				<?php htmlMemo('editor2',array('style'=>'width:100%;height:80px;'),toText($category_edit[0]['description']),lang('fmanager_descriptionlabel')); ?>
				<div style="margin: 20px 0;">
					<input type="checkbox" size="100" name="category_show" value="checked" checked="<?php echo toText($category_edit[0]['published']) ?>">
					<?php echo lang('fmanager_category_publish'); ?>
				</div>
				<?php htmlFormClose(true,array('name'=>'edit_category','value'=>lang('fmanager_savecategory'))); ?>
			</div>
		</div>
	</div>

<div style="clear:both"></div>