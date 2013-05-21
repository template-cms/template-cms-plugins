<?php

    /**
     *	Tags plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.0
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Tags',
                   '1.0.0',
                   'Tags plugin',
                   'Awilum',           	      
                   'http://awilum.webdevart.ru/',
                   '',
                   'tags');      	

    // Add template hook
    /* use: <?php templateHook('tags_cloud'); ?> */
    addHook('tags_cloud','getTagsCloud');

    // Add template hooks
	addHook('tags_content','tagsContent',array());
    addHook('tags_template','tagsTemplate',array());
	addHook('tags_title','tagsTitle',array());
	
    /**
     * Tags title
     */
	function tagsTitle($uri) {
		return 'tags';
	}

    /** 
     * Get content for current tag
     */
	function tagsContent($uri) {

        // Pages folder
        $pages_folder   = 'data/pages';

        // Get list of pages
		$pages = listOfFiles($pages_folder,'.xml');

        // Get all pages for current tag  
		foreach($pages as $page) {
    		if($page !== 'error404.xml') {
    			$page_xml = getXML($pages_folder.'/'.$page);
     			$pos = strrpos($page_xml->keywords,$uri[1]);	                
		        if ($pos === false) { 
		            // No pages for current tag...
		        } else {
                    if(trim($page_xml->parent) !== '') {
                        echo '<a href="'.getSiteUrl(false).$page_xml->parent.'">'.$page_xml->parent.'</a><span>&rarr;</span><a href="'.getSiteUrl(false).$page_xml->parent.'/'.$page_xml->slug.'"'.$page_xml->slug.'</a><br />';
                    } else { 
                        echo '<a href="'.getSiteUrl(false).$page_xml->slug.'">'.$page_xml->slug.'</a><br />';   
                     } 
		        }
    		}
    	}      
	}

    /**
     * Get tags template
     */
    function tagsTemplate($data) {
        $template_xml = getXML(TEMPLATE_CMS_DATA_PATH.'other/tags_template.xml');
        if($template_xml == NULL) {
            return 'index';
        } else {
            return $template_xml->template;
        }
    }

    /**
     * Get tags 
     */
    function getTagsCloud() {
    	    	    
        // Init vars
        // Tags database folder
        $tags_db_folder = 'data/other';
        // Tags database
        $tags_db        = 'tags'; 
        // Pages folder
        $pages_folder   = 'data/pages';
		// Temp string for all pages tags
		$_str = '';
        // Recount set false
        $recount = false;
        
        $pages = listOfFiles($pages_folder,'.xml');    

        // If there is no tags database then create tags database and create tags cloud
        if(!fileExists($tags_db_folder.'/'.$tags_db.'.xml')) {  
        	createTagsCloud(createTags($pages));            
        } else {   
            // If some page is modified then set recount true
            foreach($pages as $page) {
                if($page !== 'error404.xml') {
                    if(filemtime('data/pages/'.$page) > filemtime($tags_db_folder.'/'.$tags_db.'.xml')) {               
                        $recount = true;
                        break;
                    }
                }
            }
            // If recount true then create tags database again and create tags cloud
            if($recount) {
                createTagsCloud(createTags($pages));                                      
            } else { // Get tags name and tags weight from tags database
                $tags_xml = getXMLdb($tags_db_folder.'/'.$tags_db.'.xml');                    
                $tags = selectXMLRecord($tags_xml, '//tags','all');
                foreach($tags[0] as $key => $tg) {
                    $tag_weight[$key] = (int)$tg;
                    
                }               
                createTagsCloud($tag_weight);                
            }
        }     	                 
    }

    /**
     * Create tags
     */
    function createTags($pages) {
        // Init vars
        // Tags database folder
        $tags_db_folder = 'data/other';
        // Tags database
        $tags_db        = 'tags'; 
        // Pages folder
        $pages_folder   = 'data/pages';
        // Temp string for all pages tags
        $_str = '';

        foreach($pages as $page) {
            if($page !== 'error404.xml') {
                $page_xml = getXML($pages_folder.'/'.$page);    
                $_str .= $page_xml->keywords.',';                   
            }
        }
        
        // Create string with tags separate by comas and replace last coma
        $tags_string = substr($_str, 0, strlen($_str)-1);           
    
        // Explode tags in tags array
        $tags = explode(',',$tags_string);      

        // Trim tags
        array_walk($tags, create_function('&$val', '$val = trim($val);')); 

        // Create tags database        
        createXMLdb($tags_db_folder.'/'.$tags_db);

        // Get tags database
        $tags_xml = getXMLdb($tags_db_folder.'/'.$tags_db.'.xml');

        // Replace empty tags
        $tags = array_diff($tags, array(''));

        // Count weight for all tags    
        foreach($tags as $tag) {
            if(in_array($tag,$tags)) @$tags_weight[$tag] += 1;
        }

        // Insert tags name and tags weight in tags database
        insertXMLRecord($tags_xml,'tags',$tags_weight);

        return $tags_weight;
    }

    /**
     * Create tags cloud   
     * @param integer $tags_weight Tags weight
     */
    function createTagsCloud($tags_weight) {
        $site_url = getSiteUrl(false);

        $max_size = 26; // max font size in pixels
        $min_size = 12; // min font size in pixels

        $tag_count = 0;
        $tag_count_max = 4;

        $max_qty = max(array_values($tags_weight));
        $min_qty = min(array_values($tags_weight));

        // find the range of values
        $spread = $max_qty - $min_qty;

        // we don't want to divide by zero
        if ($spread == 0) $spread = 1;
        
        // set the font-size increment
        $step = ($max_size - $min_size) / ($spread);

        
        foreach($tags_weight as $key => $value) {
            // calculate font-size
            // find the $value in excess of $min_qty
            // multiply by the font-size increment ($size)
            // and add the $min_size set above            
            $size = round($min_size + (($value - $min_qty) * $step));

            $tag_count++;     
            if($tag_count > $tag_count_max) {  echo '<br />'; $tag_count=0; }

            echo '<a href="'.$site_url.'tags/'.$key.'" style="font-size: ' . $size . 'px" title="' . $value . ' things tagged with ' . $key . '">' . $key . '</a>&nbsp;';
        }
        

    }
