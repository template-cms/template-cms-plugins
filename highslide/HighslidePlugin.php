<?php

    /**
     *	Highslide plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Yudin Evgeniy / JEEN
     *	@copyright 2012 Yudin Evgeniy / JEEN
     *	@version 1.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Highslide',
                    '1.0.0',
                    'Highslide',
                    'JEEN',
                    'http://lovetcms.ru/',
                    '');


    // Add hooks
    addHook('theme_header','highslideThemeHeaders');

    /**
     * Set editor headers
     */
    function highslideThemeHeaders() {
        
        $dir_slide = getSiteUrl(false).'plugins/highslide/highslide';
        
        echo '
            <link href="'.$dir_slide.'/highslide.css" media="screen" rel="stylesheet" />
            <script src="'.$dir_slide.'/highslide.min.js"></script>
            <script type="text/javascript">
                hs.showCredits = 0;
                hs.graphicsDir = "'.$dir_slide.'/graphics/";
            </script>
            
            <script type="text/javascript">
            $(document).ready(function() {
                $("a.lightbox").click(function() {
                    return hs.expand(this);
                });
            });
            </script>';
    }