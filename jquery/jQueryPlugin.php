<?php

    /**
     *  jQuery plugin
     *  @package TemplateCMS
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 - 2012 Romanenko Sergey / Awilum
     *  @version 1.0.0
     *
     */


    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'jQuery',
                   '1.0.0',
                   'jQuery plugin',
                   'Awilum',
                   'http://template-cms.ru/');

    addHook('theme_header', 'jQueryThemesHeaders');

    function jQueryThemesHeaders($version = '1.7.1') {
        echo("<script>
                var jQueryScriptOutputted = false;
                function initJQuery() {
                    if (typeof(jQuery) == 'undefined') {
                        if ( ! jQueryScriptOutputted) {
                            jQueryScriptOutputted = true;
                            document.write('<scr' + 'ipt src=http://ajax.googleapis.com/ajax/libs/jquery/{$version}/jquery.min.js ></scr' + 'ipt>');
                        }
                        setTimeout('initJQuery()', 50);
                    } else {
                        $(function(){});
                    }
                }
                initJQuery();
            </script>");
    }