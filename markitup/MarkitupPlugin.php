<?php

    /**
     *	Markitup plugin
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
                    'Markitup',
                    '1.0.0',
                    'Universal markup editor http://markitup.jaysalvat.com/',
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
        echo '<textarea style="width: 800px; height:350px;" id="editor_area" name="editor" >'.$val.'</textarea>';
    }

    /**
     * Render secondary editor
     * @param string $val editor data
     */	
    function editor2($val=null) {        
        echo '<textarea style="width: 800px; height:200px;" id="editor_area2" name="editor_secondary" >'.$val.'</textarea>';
    }

    /**
     * Set editor headers
     */
    function editorHeaders() {                
        echo '
        
        <script type="text/javascript" src="'.getSiteUrl(false).'plugins/markitup/markitup/jquery.markitup.js"></script>
        <link rel="stylesheet" type="text/css" href="'.getSiteUrl(false).'plugins/markitup/markitup/skins/tcms/style.css" />
        <script type="text/javascript" src="'.getSiteUrl(false).'plugins/markitup/markitup/sets/html/set.js"></script>        
        <link rel="stylesheet" type="text/css" href="'.getSiteUrl(false).'plugins/markitup/markitup/sets/html/style.css" />
        <script type="text/javascript">
            $(document).ready(function() {
                $("#editor_area").markItUp(tcmsSettings);
                $("#editor_area2").markItUp(tcmsSettings);
            });
        </script>
        ';
    }