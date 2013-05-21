<?php

    /**
     *	Smileys plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.2
     *
     */


    $smile = '<img src="../plugins/smileys/img/smiley-cool.png">';

    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Smileys',
                    '1.1.2',
                    'Smileys plugin '.$smile,
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


    // Add filter
    addFilter('content', 'smileys');
	addFilter('comments', 'smileys');
    
    /**
     * Smileys
     * @param string $str Content
     * @return string
     */
    function smileys($str) {
        $site_url = getSiteUrl(false);        
        $patern = array(
            ':)'=>'<img src="'.$site_url.'plugins/smileys/img/smiley.png" alt="" />',
            '???'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-confuse.png.png" alt="" />',
            '8)'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-cool.png" alt="" />',
            ':\'('=>'<img src="'.$site_url.'plugins/smileys/img/smiley-cry.png" alt="" />',
            ':o'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-eek.png" alt="" />',
            '>:D'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-evil.png" alt="" />',
            ';D'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-grin.png" alt="" />',
            ':-*'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-kiss.png" alt="" />',
            '::kitty::'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-kitty.png" alt="" />',
            ':D'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-lol.png" alt="" />',
            '>:('=>'<img src="'.$site_url.'plugins/smileys/img/smiley-mad.png" alt="" />',
            '::$$::'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-money.png" alt="" />',
            ':-['=>'<img src="'.$site_url.'plugins/smileys/img/smiley-red.png" alt="" />',
            '::)'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-roll.png" alt="" />',
            ':('=>'<img src="'.$site_url.'plugins/smileys/img/smiley-sad.png" alt="" />',
            '::sleep::'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-sleep.png" alt="" />',
            ';)'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-wink.png" alt="" />',
            ':-X'=>'<img src="'.$site_url.'plugins/smileys/img/smiley-zipper.png" alt="" />'
        );
        return strtr($str,$patern);
    }
    