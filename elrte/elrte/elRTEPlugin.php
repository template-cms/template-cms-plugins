<?php
	/*
	*	Ckeditor plugin
	*	@package TemplateCMS
	*  @subpackage Plugins
	*	@author Romanenko Sergey / Awilum
	*	@copyright 2011 Romanenko Sergey / Awilum
	*	@version 1.0
	*
	*/

	// Register plugin
	registerPlugin( getPluginId(__FILE__),
		getPluginFilename(__FILE__),
		'elRTE',
		'0.90',
		'JavaScript WYSIWYG editor http://elrte.org/',
		'S.Mashevsky',
		'http://antrea-skt.ru/',
		'');

	// Add hooks
	addHook('admin_editor','editor',array());
	addHook('admin_editor_secondary','editor2',array());
	addHook('admin_header','tinyHeaders');


	/*
	* Render editor
	* @param string $val editor data
	*/
	function editor($val=null) {
		echo '<textarea id="elrte" name="editor" style="width:100%;height:400px;">'.$val.'</textarea>';
	}

	/*
	* Render secondary editor
	* @param string $val editor data
	*/	
	function editor2($val=null) {
		echo '<textarea id="elrte" name="editor_secondary" style="width:100%;height:400px;">'.$val.'</textarea>';
	}

	/**
	* Set editor headers
	*/
	function tinyHeaders() {
		$site_url = getSiteUrl(false);        
		echo '
	<link rel="stylesheet" href="'.$site_url.'plugins/elrte/elrte/css/smoothness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="'.$site_url.'plugins/elrte/elrte/css/elrte.min.css"                          type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="'.$site_url.'plugins/elrte/elfinder/css/elfinder.css" 						  type="text/css" media="screen" charset="utf-8" />
 
	<script src="'.$site_url.'plugins/elrte/elrte/js/jquery-1.6.6.min.js"           	type="text/javascript" charset="utf-8"></script>
	<script src="'.$site_url.'plugins/elrte/elrte/js/jquery-ui-1.8.13.custom.min.js" 	type="text/javascript" charset="utf-8"></script>
	<script src="'.$site_url.'plugins/elrte/elrte/js/elrte.min.js"                  	type="text/javascript" charset="utf-8"></script>
	<script src="'.$site_url.'plugins/elrte/elrte/js/i18n/elrte.ru.js"              	type="text/javascript" charset="utf-8"></script>

	<script src="'.$site_url.'plugins/elrte/elfinder/js/elfinder.min.js"            	type="text/javascript" charset="utf-8"></script>
	<script src="'.$site_url.'plugins/elrte/elfinder/js/i18n/elfinder.ru.js"    		type="text/javascript" charset="utf-8"></script>

	<script type="text/javascript" charset="utf-8">
		$().ready(function() {
			var opts = {
				lang         : "ru",
				fmAllow		 : true,
				absoluteURLs : false,
				styleWithCSS : false,
				height       : 400,
				toolbar      : "maxi",
				cssfiles : ["'.$site_url.'plugins/elrte/elrte/css/elrte-inner.css"],
				fmOpen : function(callback) {
					$("<div />").elfinder({
						root : "'.$site_url.'data/files/", 
						url : "'.$site_url.'plugins/elrte/elfinder/connectors/php/connector.php",
						lang : "ru",
						placesFirst: true,
						dialog : { width : 900, modal : true, title : "elFinder - file manager for web" },
						closeOnEditorCallback : true,
						fileURL: true,
						editorCallback : callback
					})
				}
				
         };
         $("#elrte").elrte(opts);
     });
 </script>
	';
	}
?>
