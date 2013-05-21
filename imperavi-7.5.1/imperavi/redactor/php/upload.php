<?php

include '../config.php';
include ROOT . 'template_cms/engine/Security.php';
include ROOT . 'template_cms/helpers/TextHelper.php';
include ROOT . 'template_cms/helpers/ImageHelper.php';

function fileExt($filename) {
    return substr(strrchr($filename, '.'), 1);
}

function get_ico($type)
{
	$fileicons = array('other' => 0, 'avi' => 'avi', 'doc' => 'doc', 'docx' => 'doc', 'gif' => 'gif', 'jpg' => 'jpg', 'jpeg' => 'jpg', 'mov' => 'mov', 'csv' => 'csv', 'html' => 'html', 'pdf' => 'pdf', 'png' => 'png', 'ppt' => 'ppt', 'rar' => 'rar', 'rtf' => 'rtf', 'txt' => 'txt', 'xls' => 'xls', 'xlsx' => 'xls', 'zip' => 'zip');

	if (isset($fileicons[$type])) return $fileicons[$type];
	else return 'other';
}


if (!empty($_FILES['file']['name'])) {

    $_FILES['file']['type'] = strtolower($_FILES['file']['type']);
    $file_ext = strtolower(fileExt($_FILES['file']['name']));

    $filename = safeName(basename($_FILES['file']['name'], $file_ext), '-', true) . '.' . $file_ext;
    copy($_FILES['file']['tmp_name'], ROOT . 'data/files/' . $filename);

    if ($_FILES['file']['type'] == 'image/png'
	    || $_FILES['file']['type'] == 'image/jpg'
	    || $_FILES['file']['type'] == 'image/gif'
	    || $_FILES['file']['type'] == 'image/jpeg'
	    || $_FILES['file']['type'] == 'image/pjpeg') {
	Image::factory(ROOT . 'data/files/' . $filename)->resize(100,74)->save(ROOT . 'data/files/thumbs/' . $filename);
	echo '<img src="/data/files/' . $filename . '" />';
    } else {
	echo '<a href="/data/files/' . $filename . '" rel="'.$file_name.'" class="redactor_file_link redactor_file_ico_'.get_ico($file_ext).'">'.$filename.'</a>';
    }
}
?>