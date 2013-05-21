<?php

    /**
     *	One social button plugin for news plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.2
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'One social button',
                    '1.1.2',
                    'One social button plugin for news plugin',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


    // Add hook
    addHook('news_extra_template_actions', 'oneSocialButton');
    
    /**
     * oneSocialButton
     */
    function oneSocialButton() {
        echo '<p align="right"><script src="http://odnaknopka.ru/ok2.js" type="text/javascript"></script></p>';
    }
    