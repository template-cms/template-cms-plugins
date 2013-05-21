<?php

    /**
     *	Ckeditor plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Ckeditor',
                    '1.0',
                    'JavaScript WYSIWYG editor http://ckeditor.com/',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


    // Add hooks
    addHook('admin_editor','editor',array());
    addHook('admin_editor_secondary','editor2',array());
    addHook('admin_header','editorHeaders');


    /**
     * Render editor
     * @param string $val editor data
     */
    function editor($val=null) {        
		echo '<textarea class="ckeditor" name="editor">'.$val.'</textarea>';
    }

    /**
     * Render secondary editor
     * @param string $val editor data
     */	
    function editor2($val=null) {        
		echo '<textarea class="ckeditor" name="editor_secondary">'.$val.'</textarea>';
    }

    /**
     * Set editor headers
     */
    function editorHeaders() {        

        $site_url = getSiteUrl(false);
        
        echo '
        <script type="text/javascript" src="'.$site_url.'plugins/ckeditor/ckeditor/ckeditor.js"></script>';
		echo "
        <script type=\"text/javascript\">
			if ( window.CKEDITOR )
			{
				(function()
				{
					var showCompatibilityMsg = function()
					{
						var env = CKEDITOR.env;

						var html = '<p><strong>Your browser is not compatible with CKEditor.</strong>';

						var browsers =
						{
							gecko : 'Firefox 2.0',
							ie : 'Internet Explorer 6.0',
							opera : 'Opera 9.5',
							webkit : 'Safari 3.0'
						};

						var alsoBrowsers = '';

						for ( var key in env )
						{
							if ( browsers[ key ] )
							{
								if ( env[key] )
									html += ' CKEditor is compatible with ' + browsers[ key ] + ' or higher.';
								else
									alsoBrowsers += browsers[ key ] + '+, ';
							}
						}

						alsoBrowsers = alsoBrowsers.replace( /\+,([^,]+), $/, '+ and $1' );

						html += ' It is also compatible with ' + alsoBrowsers + '.';

						html += '</p><p>With non compatible browsers, you should still be able to see and edit the contents (HTML) in a plain text field.</p>';

						var alertsEl = document.getElementById( 'alerts' );
						alertsEl && ( alertsEl.innerHTML = html );
					};

					var onload = function()
					{
						// Show a friendly compatibility message as soon as the page is loaded,
						// for those browsers that are not compatible with CKEditor.
						if ( !CKEDITOR.env.isCompatible )
							showCompatibilityMsg();
					};

					// Register the onload listener.
					if ( window.addEventListener )
						window.addEventListener( 'load', onload, false );
					else if ( window.attachEvent )
						window.attachEvent( 'onload', onload );
				})();
			}
        </script>
        ";
    }