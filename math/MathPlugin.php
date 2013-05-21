<?php

    /**
     *	Math plugin
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
                    'Math',
                    '1.0.0',          
                    'Math plugin',
                    'Awilum',           
                    'http://awilum.webdevart.ru/');      


    // Use JavaScript display engine for mathematics from http://www.mathjax.org/
    addHook('theme_header',function(){echo '<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/1.1-latest/MathJax.js?config=TeX-AMS_HTML"></script>';},array());