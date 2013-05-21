<?php
	/*
	*	TinyMCE plugin
	*	@package TemplateCMS
	*	@subpackage Plugins
	*	@author Mashevsky Sergey
	*	@copyright 2012 Mashevsky Sergey
	*	@version 0.92
	*
	*/

	// Register plugin
	registerPlugin( getPluginId(__FILE__),
		getPluginFilename(__FILE__),
		'TinyMCE',
		'0.92',
		'JavaScript WYSIWYG editor http://tinymce.org/',
		'S.Mashevsky',
		'http://antrea-skt.ru',
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
		echo '<textarea class="tinymce" name="editor" style="width:100%;height:600px;">'.$val.'</textarea>';
	}

	/*
	* Render secondary editor
	* @param string $val editor data
	*/	
	function editor2($val=null) {
		echo '<textarea class="tinymce" name="editor_secondary" style="width:100%;height:600px;">'.$val.'</textarea>';
	}

	/**
	* Set editor headers
	*/
	function tinyHeaders() {
		$site_url = getSiteUrl(false);        
		echo '
	<script type="text/javascript" src="'.$site_url.'plugins/tinymce/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
			// General options
			mode : "textareas",
			theme : "advanced",
			language : "ru",
			editor_selector : "tinymce",
			document_base_url : "/",
			relative_urls : false,
			remove_script_host : true,
			extended_valid_elements : "div[*],p[*]",
  				
			style_formats : [
				{title : "Bold text", inline : "b"},
				{title : "Red text", inline : "span", styles : {color : "#ff0000"}},
				{title : "Red header", block : "h1", styles : {color : "#ff0000"}},
				{title : "Example 1", inline : "span", classes : "example1"},
				{title : "Example 2", inline : "span", classes : "example2"},
				{title : "Table styles"},
				{title : "Table row 1", selector : "tr", classes : "tablerow1"}
			],
			
			plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,visualblocks,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,visualblocks,nonbreaking,template,blockquote,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// Skin options
			skin : "o2k7",
			skin_variant : "silver",

			// Example content CSS (should be your site CSS)
			content_css : "'.$site_url.'themes/default/css/minify.style.css",
        		
			file_browser_callback: "openKCFinder"
		});
        
		function openKCFinder(field_name, url, type, win) {
			tinyMCE.activeEditor.windowManager.open({
				file            : "'.$site_url.'plugins/tinymce/tiny_mce/plugins/kcfinder/browse.php?opener=tinymce&type=images&lang=ru&dir='.$site_url.'data/files&type=" + type,
				status		: 1,
				toolbar		: 1,
				location	: 1,
				menubar		: 1,
				directories	: 1,
				resizable	: 0,
				scrollbars	: 1,
				width		: 1160,
				height		: 660,
				
				inline          : 1,
				close_previous  : 1,
				popup_css       : 1,
			}, {
				window          : win,
				input           : field_name
			});
				return false;
			}
	</script>
	';
	}
?>
