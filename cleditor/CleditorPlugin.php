<?php

    /**
     *	Cleditor plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Cleditor',
                    '1.0.0',
                    'Cross browser, extensible, WYSIWYG HTML editor http://premiumsoftware.net/cleditor/',
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
        echo '<div id="editor_panel" style="width: 800px;"></div>';
        echo '<textarea style="width: 800px; height:350px;" id="editor_area" name="editor" >'.$val.'</textarea>';
    }

    /**
     * Render secondary editor
     * @param string $val editor data
     */	
    function editor2($val=null) {        
        echo '<div id="editor_panel2" style="width: 800px;"></div>';
        echo '<textarea style="width: 800px; height:200px;" id="editor_area2" name="editor_secondary" >'.$val.'</textarea>';
    }

    /**
     * Set editor headers
     */
    function editorHeaders() {                
        echo '
    <link rel="stylesheet" type="text/css" href="'.getSiteUrl(false).'/plugins/cleditor/cleditor/jquery.cleditor.css" />    
    <script type="text/javascript" src="'.getSiteUrl(false).'/plugins/cleditor/cleditor/jquery.cleditor.min.js"></script>
    <script type="text/javascript" src="'.getSiteUrl(false).'/plugins/cleditor/cleditor/jquery.cleditor.xhtml.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {        
        $.cleditor.defaultOptions.width = 800;
        $.cleditor.defaultOptions.height = 400;        
        $("#editor_area,#editor_area2").cleditor()[0].focus();        
      });

    </script>

        ';
    }