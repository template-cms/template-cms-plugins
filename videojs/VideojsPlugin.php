<?php

    /**
     *	VideoJS plugin
     *
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'VideoJS',
                    '1.0.0',
                    'Video.js is the most widely used HTML5 Video Player available.',
                    'Awilum',
                    'http://template-cms.ru/');


    // Add hooks
    addHook('theme_header', 'videoJSHeaders');
    
    // Add shortcode
    addShortcode('video', 'videoJSShortcode');


    /**
     * VideoJS Headers
     */
    function videoJSHeaders() {
        echo ('<link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
               <script src="http://vjs.zencdn.net/c/video.js"></script>');
    }


    /**
     * VideoJS Shortcode
     *
     *  <code>
     *      {video mp4="http://video-js.zencoder.com/oceans-clip.mp4"}
     *
     *      {video webm="http://video-js.zencoder.com/oceans-clip.webm"}
     *
     *      {video width="640" height="480" poster="image.png" mp4="http://video-js.zencoder.com/oceans-clip.mp4"}
     *  </code>
     *
     */
    function videoJSShortcode($attributes) {
        
        // Extract
        extract($attributes);

        // MP4 Source Supplied
        if (isset($mp4)) $mp4_source = '<source src="'.$mp4.'" type=\'video/mp4\' />'; else $mp4_source = '';

        // WebM Source Supplied
        if (isset($webm)) $webm_source = '<source src="'.$webm.'" type=\'video/webm; codecs="vp8, vorbis"\' />'; else $webm_source = '';

        // Ogg source supplied
        if (isset($ogg)) $ogg_source = '<source src="'.$ogg.'" type=\'video/ogg; codecs="theora, vorbis"\' />'; else $ogg_source = '';
  
        // Poster image supplied
        if (isset($poster)) $poster_attribute = ' poster="'.$poster.'"'; else $poster_attribute = '';
  
        // Preload the video?
        if (isset($preload)) $preload_attribute = 'preload="'+$preload+'"'; else $preload_attribute = '';

        // Autoplay the video?
        if (isset($autoplay)) $autoplay_attribute = " autoplay"; else $autoplay_attribute = "";

        // Width
        if (isset($width)) $width = $width; else $width = 640;

        // Height
        if (isset($height)) $height = $height; else $height = 264;

        // Video.js
        return ('<!-- Begin Video.js -->
                 <video class="video-js vjs-default-skin" width="'.$width.'" height="'.$height.'"'.$poster_attribute.' controls '.$preload_attribute.$autoplay_attribute.' data-setup="{}">
                    '.$mp4_source.'
                    '.$webm_source.'
                    '.$ogg_source.'
                </video>
                <!-- End Video.js -->');
    
    }