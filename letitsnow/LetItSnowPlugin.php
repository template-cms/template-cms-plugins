<?php

    /**
     *  Let It Snow plugin
     *  @package TemplateCMS
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 Romanenko Sergey / Awilum
     *  @version 1.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Let It Snow',
                    '1.0.0',
                    'Let It Snow plugin for Template CMS :)',
                    'Awilum',
                    'http://template-cms.ru/');


    addHook('theme_header','letitsnowHeader');


    function letitsnowHeader() {
         echo ('
               <script type="text/javascript" src="'.getSiteUrl(false).'plugins/letitsnow/js/snowstorm-min.js"></script>
               <script type="text/javascript">
               snowStorm.snowColor = "#99ccff"; // blue-ish snow!?
               snowStorm.flakesMaxActive = 96;  // show more snow on screen at once
               snowStorm.useTwinkleEffect = true; // let the snow flicker in and out of view
               </script>
         ');
    }