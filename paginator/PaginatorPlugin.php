<?php

    /**
     *	Paginator plugin
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
                   'Paginator',
                   '1.0.0',
                   'Paginator plugin',
                   'Awilum',           	      
                   'http://awilum.webdevart.ru/'
                   );      	

    // Add filter
    addFilter('content', 'paginator');

    function paginator($str) {

        // Tag
        $tag = '[page]';

        $symbol = ' : ';

        // Ckeck is $tag exists
        $pos = strrpos($str,$tag);

        // If no then retrun $str without pagination
        if ($pos === false) { 
            return $str;
        } else {
            // Separate content with $tag 
            $page_content = explode($tag,$str);   

            // Count pages
            $pages_count  = count($page_content);          

            // If is get page then load current page else load 0 page
            if(isGet('page')) {
              $page = (int)get('page');                            
            } else {
              $page = 0;
            }

            // Create paginator
            $paginator = '';
            $page_num  = 0;          
            for($i=0; $i<$pages_count; $i++) {
                $page_num = $i+1;
                if($page == $page_num) $current = '<u>'.$page_num.'</u>'; else $current = $page_num;
                if($page == 0 && $page_num == 1) $current = '<u>'.$page_num.'</u>';
                $paginator .= '<a href="?page='.$page_num.'">'.$current.'</a> ';
                if($page_num !== $pages_count) $paginator .= $symbol;
            }  

            if(isset($page_content[$page-1])) {
                return $page_content[$page-1].'<div style="margin-top:5px;">'.$paginator.'</div>';
            } else {
                return $page_content[0].'<div style="margin-top:5px;">'.$paginator.'</div>';
            } 
        }

    }