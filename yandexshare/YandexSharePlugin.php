<?php

    /**
     *	Yandex share button
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.1
     *
     */
 
	 
	 
	// Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Yandex share button',
                    '1.0.1',
                    'Yandex share button',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');
 

    // Add template hook
	/* use: <?php templateHook('yandex_share_button'); ?> */
    addHook('yandex_share_button', 'yandexShareButton');
    
	
    /**
     * Yandex share button
     */
    function yandexShareButton() {
        echo '<p align="right">
			 <script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
			 <div class="yashare-auto-init" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,friendfeed,moimir,lj"></div> 
			 </p>';
    }   