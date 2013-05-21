<?php

    /**
     *  Christmas Lights plugin
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
                    'Christmas lights',
                    '1.0.0',
                    'Christmas Lights plugin for Template CMS :)',
                    'Awilum',
                    'http://template-cms.ru/');


    addHook('theme_header', 'christmaslightsHeader');


    // Add to your templats this code: <div id="lights"><!-- lights go here --></div>
    function christmaslightsHeader() {
         echo ('
              <link rel="stylesheet" media="screen" href="'.getSiteUrl(false).'plugins/christmaslights/lights/christmaslights.css" />
              <script type="text/javascript" src="'.getSiteUrl(false).'plugins/christmaslights/lights/soundmanager2-nodebug-jsmin.js"></script>
              <script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo-dom-event/yahoo-dom-event.js&2.6.0/build/animation/animation-min.js"></script>
              <script type="text/javascript" src="'.getSiteUrl(false).'plugins/christmaslights/lights/christmaslights.js"></script>
              <script type="text/javascript">
              var urlBase = "'.getSiteUrl(false).'plugins/christmaslights/lights/";
              soundManager.url = "'.getSiteUrl(false).'plugins/christmaslights/lights/";
              </script>
         ');
    }