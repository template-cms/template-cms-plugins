<?php

/**
 * 	Imperavi plugin
 *
 * 	@package TemplateCMS
 *	@subpackage Plugins
 * 	@author Mamay Alexander
 * 	@copyright 2012 Mamay Alexander
 * 	@version 7.5.1
 *
 */
// Register plugin
registerPlugin(getPluginId(__FILE__),
	getPluginFilename(__FILE__),
	'Imperavi',
	'7.5.1',
	'WYSIWYG Editor on jQuery <a href="http://imperavi.com/redactor/">Imperavi</a>',
	'Mamay Alexander',
	'http://alexander.mamay.su/',
	'');

getPluginAdmin('Imperavi');