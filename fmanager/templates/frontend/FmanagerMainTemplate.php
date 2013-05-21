<?php 
	$error2 = '';
	if (toText($setting[0]['enabled']) != 'checked') {
		$error2 = '<div><p class="error"><b>'.lang('fmanager_error_enabled').'</b></p></div>';
		echo $error2;
		exit;
	}
?>
<?php echo $error; ?>
<div style="margin-top: 20px;width:100%;">
	<div class="nav">
	<?php echo $catlist; ?>
	</div>
	<?php 
		if($fileslist == '') {
			echo '<div style="margin-top: 20px;width:100%;text-align:center;font-weight:bold;">'.lang('fmanager_nofiles').'</div>';
		} else echo $fileslist; 
	?>
</div>
