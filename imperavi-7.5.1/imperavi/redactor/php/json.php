<?php

include '../config.php';
include ROOT . 'template_cms/engine/Security.php';
include ROOT . 'template_cms/helpers/TextHelper.php';
include ROOT . 'template_cms/helpers/ImageHelper.php';
include ROOT . 'template_cms/engine/Filesystem.php';

$files = glob(ROOT . 'data/files/*.{jpg,jpeg,gif,png}', GLOB_BRACE);
$dir_thumbs = ROOT . '/data/files/thumbs';

for ($i = 0, $count = count($files); $i < $count; $i++) {
    if (!file_exists($dir_thumbs . '/' . basename($files[$i]))) {
	Image::factory($files[$i])->resize(100,74)->save($dir_thumbs . '/' . basename($files[$i]));
    }
    $json[$i]['thumb'] = '/data/files/thumbs/' . basename($files[$i]);
    $json[$i]['image'] = '/data/files/' . basename($files[$i]);
}

print_r(json_encode($json));

?>