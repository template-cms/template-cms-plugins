<?php

    /**
     *	Edit page plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.2
     *
     */

    
    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    'Edit page',
                    '1.0.2',
                    'Edit page plugin',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    '');


	// Add template hook				
	addHook('theme_post_content','editPage');
   
   
	/** 
	 * Edit page link
	 */
	function editPage() {			
		global $defpage;

		$uri = getUri();
		$site_url = getSiteUrl(false);
		$components = getComponents();

				
		// Get page to edit
		if(isset($uri[0])) {
			if(!in_array($uri[0],$components)) {
				if(isset($uri[1])) {
					$page = $uri[1]; 
					$page_edit = true;
				} else {
					if(isset($uri[0]) && $uri[0] !== '') {
						$page = $uri[0]; 
						$page_edit = true;
					} else  {
						$page = $defpage;
						$page_edit = true;
					}
				}				
			} else {
				$page = '';
				$page_edit = false;
			}
		} 			
		
		// Dirty variant to check is admin login
		// If admin login then show edit link
		if(isset($_SESSION['admin'])) {
			if($_SESSION['admin']) {
				if($page_edit) {
					echo '<div style="margin:5px;"><a target="_blank" href="'.$site_url.'admin/index.php?id=pages&action=edit_page&filename='.$page.'"><img src="'.$site_url.'/plugins/editpage/img/edit.png" alt="" /></a></div>';
				}
			}
		}
	}