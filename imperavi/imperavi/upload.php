<?php
	
	include "config.php";

	$path['unix'] = '../../../data/files/';
	$path['windows'] = '..\..\..\data\files\\';


	$_FILES['file']['type'] = strtolower($_FILES['file']['type']);

	if ($_FILES['file']['type'] == 'image/png' 
		|| $_FILES['file']['type'] == 'image/jpg' 
		|| $_FILES['file']['type'] == 'image/gif' 
		|| $_FILES['file']['type'] == 'image/jpeg'
		|| $_FILES['file']['type'] == 'image/pjpeg') {	

		copy($_FILES['file']['tmp_name'], $path['unix'].md5(date('YmdHis')).'.jpg');
		
		$site_url = 'http://'.$_SERVER["SERVER_NAME"].str_replace(array("plugins/imperavi/imperavi/upload.php"),"",$_SERVER['PHP_SELF']);
		
		echo $site_url.'data/files/'.md5(date('YmdHis')).'.jpg';
	}

?>
