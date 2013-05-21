<?php

// Add hooks
addHook('admin_editor', 'editor', array());
addHook('admin_editor_secondary', 'editor2', array());
addHook('admin_header', 'editorHeaders');

function imperaviInstall() {
    if (!is_dir('../data/files/thumbs')) {
	mkdir('../data/files/thumbs', 0775);
    }
}

/**
 * Render editor
 *
 * @param string $val editor data
 */
function editor($val=null) {
    echo '<div style="padding:5px;"><textarea id="editor_area" name="editor" style="height: 300px; width: 99%;">' . $val . '</textarea></div>';
}

/**
 * Render secondary editor
 *
 * @param string $val editor data
 */
function editor2($val=null) {
    echo '<div style="padding:5px;"><textarea id="editor_area2" name="editor_secondary" style="height: 200px; width: 99%;">' . $val . '</textarea></div>';
}

/**
 * Imperavi Headers
 */
function editorHeaders() {
    echo '
	    <link rel="stylesheet" href="' . getOption('siteurl') . 'plugins/imperavi/redactor/css/redactor.css" />
	    <script src="' . getOption('siteurl') . 'plugins/imperavi/redactor/redactor.min.js"></script>
	    <script type="text/javascript">
		$(function(){
		    $("#editor_area").redactor({
			lang: "' . getOption('language') . '",
			imageUpload: "' . getOption('siteurl') . 'plugins/imperavi/redactor/php/upload.php",
			imageGetJson: "' . getOption('siteurl') . 'plugins/imperavi/redactor/php/json.php", // url or false
			imageUploadFunction: false, // callback function

			fileUpload: "' . getOption('siteurl') . 'plugins/imperavi/redactor/php/upload.php",
			fileDownload: false,
			fileDelete: false,
		    });
		});
	    </script>';
}