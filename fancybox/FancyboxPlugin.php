<?php

/**
 * 	Fancybox plugin
 * 	@package TemplateCMS
 * 	@subpackage Plugins
 * 	@author Mamay Alexander
 * 	@copyright 2012 Mamay Alexander
 * 	@version 1.0.1
 *
 */
// Register plugin
registerPlugin(getPluginId(__FILE__), getPluginFilename(__FILE__), 'Fancybox', '1.0.1', '<a href="http://fancyapps.com/fancybox/">Fancybox</a>', 'Mamay Alexander', 'http://alexander.mamay.su/', '');


// Add hooks
addHook('theme_header', 'fancyboxHeaders');

getPluginAdmin('Fancybox');

function fancyboxHeaders() {
    echo '
	    <link rel="stylesheet" href="' . getOption('siteurl') . '/plugins/fancybox/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
	    <script type="text/javascript" src="' . getOption('siteurl') . '/plugins/fancybox/fancybox/jquery.fancybox.pack.js"></script>
	    <script>
	    $(document).ready(function() {
		var imgs = \'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".gif"], a[href$=".png"], a.lightbox\';
                $(imgs).attr("rel", "group");
		$(imgs).fancybox({
		    "cyclic": true,
		    "transitionIn"      : "elastic",
		    "transitionOut"     : "elastic",
		    "openEffect"        : "elastic",
		    "closeEffect"       : "elastic",
		    "prevEffect"        : "none",
		    "nextEffect"        : "none",
		    "helpers" : {
			"title" : {
			    "type" : "inside"
			}
		    }
		});
            });
	    </script>';
}