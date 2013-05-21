<?php

    /**
     *	Sandbox plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.1
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Sandbox',        		// Plugin name
                   '1.1.1',            		// Plugin version
                   'Sandbox plugin',  	        // Plugin desription
                   'Awilum',           	        // Plugin Author
                   'http://localhost/',	        // Plugin athor contacts
                   'sandboxAdmin');      	// Plugin admin function


    // Get language file for this plugin
    getPluginLanguage('Sandbox');

    // Include Sandbox Admin
    getPluginAdmin('Sandbox');
