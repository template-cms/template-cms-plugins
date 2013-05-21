<?php

    /**
     *	Textile plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */

	
	$tags = '
			<style>
				#help-box {
					display: none;
					color:#9A9A9A;
				}
			</style>
			<script language="javascript" type="text/javascript">
				function showTextile() {
					$("#help-box").show();
					return false;
				}			
			</script>
			<span id="help-box">
			<br />
			<a target="_blank" href="http://en.wikipedia.org/wiki/Textile_(markup_language)">http://en.wikipedia.org/wiki/Textile_(markup_language)</a>
			</span>';
	
    
    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Textile',
                    '1.0.0',
                    'Textile markup language plugin <a href="#" onclick="return showTextile();" id="tags">&rarr; help</a>'.$tags,
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


    // Add filters
    addFilter('comments', 'textile');
	addFilter('content', 'textile');
    

    include 'classTextile.php';
    

    /**
     * Textile 
     * @param string $str content
     * @return string
     */
    function textile($str) {
    	$tex = new Textile();
 		return $tex->TextileThis($str);
    }
    