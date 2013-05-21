<?php

addHook('admin_header', 'fancyboxAdminHeaders');

function fancyboxAdminHeaders() {
    echo '
	    <link rel="stylesheet" href="' . getOption('siteurl') . '/plugins/fancybox/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
	    <script type="text/javascript" src="' . getOption('siteurl') . '/plugins/fancybox/fancybox/jquery.fancybox.pack.js"></script>
	    <script>
	    $(document).ready(function() {
		var imgs = \'.filesmanager-td p a[href$=".jpg"], .filesmanager-td p a[href$=".jpeg"], .filesmanager-td p a[href$=".gif"], .filesmanager-td p a[href$=".png"]\';
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