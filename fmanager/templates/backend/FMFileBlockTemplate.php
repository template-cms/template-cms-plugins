<?php 
	$s_id = 1;
	$settings = selectXMLRecord($xml_db,"//fmanager_option[@id='".$s_id."']",'all');
	$setting = selectXMLfields($settings, array('enabled','description','directlinks','counter','referer'),'enabled','ASC'); 
	if (toText($setting[0]['enabled']) != 'checked') {
		echo '<div><p class="error"><b>'.lang('fmanager_error_enabled').'</b></p></div>';
	}
	if ($error!='') echo '<div><p class="error"><b>'.$error.'</b></p></div>';
?>
<div id="wrap2">
<div id="tabs">
		<ul>
			<li><a href="#tabs-2"><?php echo lang('fmanager_fileblock_title'); ?></a></li>
		</ul>
			<div id="tabs-2">
				<h2><?php echo lang('fmanager_fileblock_title').': '.$f_filename; ?></h2>
				<hr style="margin-top: 5px; height: 0px;">
				<?php htmlFormOpen('index.php?id=pages&sub_id=fmanager','post',true); ?>
				
				<?php htmlFormInput(array('type'=>'text','name'=>'f_loginname','size'=>'50','value'=>$f_loginname),lang('fmanager_fileblock_login'));
					  htmlFormInput(array('type'=>'password','name'=>'f_pass1','size'=>'50','value'=>$f_pass1),lang('fmanager_fileblock_pass1'));
					  htmlFormInput(array('type'=>'password','name'=>'f_pass2','size'=>'50','value'=>$f_pass2),lang('fmanager_fileblock_pass2')); 
					  htmlFormHidden('id_filename', $f_id);
					  htmlFormHidden('f_filename', $f_filename);
				?>
				<div style="margin: 20px 0;">
					<input type="checkbox" size="100" name="f_block" value="checked" <?php echo $f_block; ?>>
					<?php echo lang('fmanager_fileblock_enable'); ?>
				</div>
				<?php	  htmlFormClose(true,array('name'=>'block_file','value'=>lang('fmanager_save_options'))); 
				?>
			</div>
		</div>
</div>

<div style="clear:both"></div>