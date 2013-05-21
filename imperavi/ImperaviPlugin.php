<?php

    /**
     *	Imperavi plugin
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
                    'Imperavi',
                    '1.0',
                    'Imperavi WYSIWYG editor http://imperavi.ru/redactor/',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


    // Add hooks
    addHook('admin_editor','editor',array());
    addHook('admin_editor_secondary','editor2',array());
    addHook('admin_header','editorHeaders');


    /**
     * Render editor
     * @param string $val editor data
     */
    function editor($val=null) {        
		echo '<textarea id="editor" name="editor" style="height: 300px; width: 800px;">'.$val.'</textarea>';
    }

    /**
     * Render secondary editor
     * @param string $val editor data
     */	
    function editor2($val=null) {        
		echo '<textarea id="editor2" name="editor_secondary" style="height: 300px; width: 800px;">'.$val.'</textarea>';
    }

    /**
     * Set editor headers
     */
    function editorHeaders() {        

        $site_url = getSiteUrl(false);        

        echo '<script type="text/javascript" src="'.$site_url.'plugins/imperavi/imperavi/js/editor/editor.js"></script>
              <link rel="stylesheet" href="'.$site_url.'plugins/imperavi/imperavi/js/editor/css/editor.css" type="text/css" />';

		echo '<script type="text/javascript">			                    
                    $().ready(function() {                                                
                        $("#editor").editor({ focus: true, toolbar: "classic" });                                                            
                        $("#editor2").editor({ toolbar: "classic" });                                    
                    });
              </script>';
    }