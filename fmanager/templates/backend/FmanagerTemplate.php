<?php 
	$s_id = 1;
	$settings = selectXMLRecord($xml_db,"//fmanager_option[@id='".$s_id."']",'all');
	$setting = selectXMLfields($settings, array('enabled','description','directlinks','counter','referer'),'enabled','ASC'); 
	$error2 ='';
	if (toText($setting[0]['enabled']) != 'checked') {
		$error2 = '<div><p class="error"><b>'.lang('fmanager_error_enabled').'</b></p></div>';
	}
	if (toText($setting[0]['counter']) != 'checked') {$s_counter = true;} else $s_counter = false;
?>
<?php 	echo $error; 
	echo $error2; 
?>
<div id="wrap2">
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo lang('fmanager_uploadtab'); ?></a></li>
			<li><a href="#tabs-2"><?php echo lang('fmanager_tabfiles'); ?></a></li>
			<li><a href="#tabs-3"><?php echo lang('fmanager_tabcategory'); ?></a></li>
			<li><a href="#tabs-4"><?php echo lang('fmanager_settings'); ?></a></li>
		</ul>
			<div id="tabs-1">
				<h2><?php echo lang('fmanager_fileupload'); ?></h2><hr style="margin-top: 5px; height: 0px;">
    <?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
	<?php htmlSelect($cat_list ,array('name'=>'fcategory'),lang('fmanager_categoryname'));
		  htmlFormInput(array('type'=>'text','name'=>'fname','size'=>'35','value'=>lang('fmanager_name')),lang('fmanager_namelabel')); 
	?>
	<br>
	<label><?php echo lang('fmanager_descriptionlabel'); ?></label>
	<br>
	<textarea style="width: 100%; height: 80px;" name="editor" id="veditor" ></textarea>
	<?php htmlFormFile('file',50); ?>
	<div style="margin: 20px 0;">
		<input type="checkbox" size="100" name="f_show" value="checked" checked="checked" >
		<?php echo lang('fmanager_file_publish'); ?>
	</div>
    <?php htmlFormClose(true,array('name'=>'upload_file','value'=>lang('fmanager_upload'))); ?>

			</div>
			<div id="tabs-2">
				<h2><?php echo lang('fmanager_filemanage'); ?></h2><hr style="margin: 5px 0 15px 0; height: 0px;">
				<table border="0" cellspacing="5" cellpadding="5" style="width: 100%;">
<?php foreach ($files_list as $file) { ?>
<?php   $ext = fileExt($file); 
		$records = selectXMLRecord($xml_db,"//file_entry[filename='".$file."']",'all');
		$f_list = selectXMLfields($records, array('name','filename','category','published','description','id','counter','tmpname','password'),'id','ASC');
		$records2 = selectXMLRecord($xml_db,"//category_entry[category_name='".toText($f_list[0]['category'])."']",'all');
		$c_list = selectXMLfields($records2, array('id','category_name'),'id','ASC');
		if (toText($c_list[0]['category_name']) == '') $c_list[0]['id'] = 0;
		
		$f_name = toText($f_list[0]['name']);
		$f_filename = toText($f_list[0]['filename']);
		if ($f_filename == '') $f_filename = $file;
		$f_id = toText($f_list[0]['id']);
		$f_cat = toText($f_list[0]['category']);
		$f_pub = toText($f_list[0]['published']);
		$f_pass = toText($f_list[0]['password']);
		$f_tmpname = toText($f_list[0]['tmpname']);
		if ($f_tmpname != $f_filename && $f_tmpname != '') {
			$f_filename = $f_tmpname;
		}
		$f_count = toText($f_list[0]['counter']);
		if ($f_count == '') $f_count = 0;
		$style ='';
		if ($f_pub != 'checked') {
			$style = 'style="background:#eee"';
		}
		if ($f_pass!='') {
			$icons = $site_url.'plugins/fmanager/templates/frontend/images/icons/lock.png';
			$ilock = '<img alt="lock" src="'.$icons.'">';
		} else {
			$ilock = '';
		}
		$icon_ext = fileExt($files_path.$file);
		$icon = strtoupper($icon_ext).'.png';
		if (!FileExists('../plugins/fmanager/templates/frontend/images/icons/'.$icon)) $icon = 'DEFAULT.png';
		$icons = $site_url.'plugins/fmanager/templates/frontend/images/icons/'.$icon;
?>
<tr class="filesmanager-tr" <?php echo $style ?> >
<?php if(!in_array($ext,$forbidden_types)) { ?>
	<td class="filesmanager-td">					
             <a style="text-decoration:none;" href="<?php echo $site_url.'downloads/category/'.toText($c_list[0]['id']).'/file/'.toText($f_list[0]['id']); ?>"><div><div class="icon"><img class="icons" src="<?php echo $icons; ?>" alt="<?php echo $ext; ?>"></div><div class="clearer"></div><div class="locked"><?php echo $ilock; ?></div></div></a>
	</td>
	<td valign="top" class="filesmanager-td" width="600px">
			<p>
				<strong>
					<?php 
						if ($f_name == '') {
							$f_name = lang('fmanager_unknownfile');
							$f_id = $file;
							$f_cat = lang('fmanager_unknowncategory');
							echo '<div style="color: red;">';
						}
						echo $f_name; 
						if ($f_name == lang('fmanager_unknownfile')) {
							echo '</div>';
						} else {
							echo ' - ';
						}
					?>
				</strong>
				<?php htmlLink($f_filename,$site_url.'downloads/category/'.toText($c_list[0]['id']).'/file/'.toText($f_list[0]['id']),'');?> <br><span class="filesize">(<?php echo convert(filesize($files_path.$f_filename)).', '; echo dateFormat(fileLastChange($files_path.$f_filename)); ?>)</span><small>, <strong> <?php echo lang('fmanager_counter').' </strong>'.$f_count.', '; ?></small>
				<small><strong><?php echo lang('fmanager_tabcategory').': </strong>'; echo $f_cat.' '; ?></small>
			</p>
            <p style="font-size:0.7em;line-height:10px"><?php echo $f_list[0]['description']; ?></p>
	</td>
	<td  class="filesmanager-td" style="text-align:right;">			
            <?php 
				if ($f_name == lang('fmanager_unknownfile')) {
					htmlButtonEdit(lang('fmanager_add'), 'index.php?id=pages&sub_id=fmanager&add_file='.$f_id); 
					htmlButtonDelete(lang('fmanager_delete'), 'index.php?id=pages&sub_id=fmanager&unknown=1&delete_file='.$f_id);
				} else {
					if ($f_pub == 'checked') {
						htmlButtonEdit(lang('fmanager_fileblock'), 'index.php?id=pages&sub_id=fmanager&block_file='.$f_id); 
						htmlButtonEdit(lang('fmanager_unpublish'), 'index.php?id=pages&sub_id=fmanager&unpub_file='.$f_id); 
						htmlButtonEdit(lang('fmanager_edit'), 'index.php?id=pages&sub_id=fmanager&edit_file='.$f_id); 
						htmlButtonDelete(lang('fmanager_delete'), 'index.php?id=pages&sub_id=fmanager&delete_file='.$f_id);
					} else {
						htmlButtonEdit(lang('fmanager_fileblock'), 'index.php?id=pages&sub_id=fmanager&block_file='.$f_id);
						htmlButtonEdit(lang('fmanager_publish'), 'index.php?id=pages&sub_id=fmanager&pub_file='.$f_id); 
						htmlButtonEdit(lang('fmanager_edit'), 'index.php?id=pages&sub_id=fmanager&edit_file='.$f_id); 
						htmlButtonDelete(lang('fmanager_delete'), 'index.php?id=pages&sub_id=fmanager&delete_file='.$f_id);
					}
				}
			?>
	</td>
<?php } ?>
</tr>
<?php } ?>
</table>
			</div>
			<div id="tabs-3">
				<div>
					<h2><?php echo lang('fmanager_categorymanage'); ?></h2>
					<hr style="margin-top: 5px; height: 0px;">
				</div>
				<?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
				<?php htmlSelect($cat_list,array('name'=>'category_parent'),lang('fmanager_parentcategoryname'));
					  htmlFormInput(array('type'=>'text','name'=>'category_name','size'=>'90','value'=>lang('fmanager_newcategory')),lang('fmanager_namelabel')); 
					  htmlFormInput(array('type'=>'text','name'=>'category_img','size'=>'90','value'=>$category_img),lang('fmanager_category_imgcapture')); 
				?>
				<br>
				<label><?php echo lang('fmanager_descriptionlabel'); ?></label>
				<br>
				<textarea style="width: 100%; height: 80px;" name="editor2" id="veditor2" ></textarea>
				<div style="margin: 20px 0;">
					<input type="checkbox" size="100" name="category_show" value="checked" checked="<?php echo toText($category_edit[0]['published']) ?>">
					<?php echo lang('fmanager_category_publish'); ?>
				</div>
				<?php htmlFormClose(true,array('name'=>'save_category','value'=>lang('fmanager_savecategory'))); ?>
				
				<div style="margin-top: 20px;">
					<h2><?php echo lang('fmanager_categorylist'); ?></h2>
					<div style="float:right;margin-top:-20px;">
						<?php htmlButtonEdit(lang('fmanager_category_hideall'), 'index.php?id=pages&sub_id=fmanager&chide=all'); ?>
						<?php htmlButtonEdit(lang('fmanager_category_showall'), 'index.php?id=pages&sub_id=fmanager&cshow=all'); ?>
					</div>
					<hr style="margin: 5px 0 15px 0; height: 0px;">
					<?php echo $categorylist; ?>
				</div>
			</div>
			<div id="tabs-4">
				<h2><?php echo lang('fmanager_settings'); ?></h2><hr style="margin: 5px 0 15px 0; height: 0px;">
				<?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
				<div>
					<input type="checkbox" size="100" name="fmanager_enabled" value="checked" <?php echo toText($fm_conf_entries[0]['enabled']); ?>>
					<?php echo lang('fmanager_settings_enable'); ?>
				</div>
				<div>
					<input type="checkbox" size="100" name="fmanager_description" value="checked" <?php echo toText($fm_conf_entries[0]['description']); ?>>
					<?php echo lang('fmanager_settings_description'); ?>
				</div>
				<div>
					<input type="checkbox" size="100" name="fmanager_elements" value="checked" <?php echo toText($fm_conf_entries[0]['elements']); ?>>
					<?php echo lang('fmanager_settings_elements'); ?>
				</div>
				<div>
					<input type="checkbox" size="100" name="fmanager_counter" value="checked" <?php echo toText($fm_conf_entries[0]['counter']); ?>>
					<?php echo lang('fmanager_settings_counter'); ?>
				</div>
				<div>
					<input type="checkbox" size="100" name="fmanager_referer" value="checked" <?php echo toText($fm_conf_entries[0]['referer']); ?>>
					<?php echo lang('fmanager_settings_referer'); ?>
				</div>
				<div>
					<input type="checkbox" size="100" name="fmanager_directlinks" value="checked" <?php echo toText($fm_conf_entries[0]['directlinks']); ?>>
					<?php echo lang('fmanager_settings_directlinks'); htmlBr(2); ?>
				</div>
				<?php htmlFormClose(true,array('name'=>'save_settings','value'=>lang('fmanager_save_options'))); ?>
			</div>
		</div>
	</div>

<div style="clear:both"></div>