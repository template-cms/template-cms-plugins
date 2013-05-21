<?php


    /**
     *	Star Rating plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.1
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Star Rating',
                   '1.0.0',
                   'Star Rating plugin',
                   'Awilum',           	      
                   'http://awilum.webdevart.ru/'
                   );      	

    // Get language file for this plugin
    getPluginLanguage('StarRating');

    addHook('theme_header','starRatingHeaders');   
    
    // Add template hook
    /* use: <?php templateHook('star_rating'); ?> */
    addHook('star_rating','getStarRating');            

    /**
     * Star Rating theme headers
     */
    function starRatingHeaders() {
    	echo '<link href="'.getSiteUrl(false).'plugins/starrating/starrating/jquery.rating.css" type="text/css" rel="stylesheet"/>
        	  <script src="'.getSiteUrl(false).'plugins/starrating/starrating/jquery.rating.pack.js" type="text/javascript"></script>
        	  <script>
				$(document).ready(function() {
					$(".rating-cancel").remove();  
				});
				</script><style>.sr_desc {color:#ccc; font-size:10px;}</style>';	  
		
    }


	/**
	 * Get Star Rating
	 */
	function getStarRating() {

		// Star rating directories
		$sr_dir = TEMPLATE_CMS_DATA_PATH.'starrating';		
		
		// Check is directories exists
		if(!is_dir($sr_dir)) {
			createDir($sr_dir);		
		}

		// Get uri and create page uris string
		$uri = getUri();
		$sr_page_uri_str = '';

		foreach($uri as $part) {
			$sr_page_uri_str .= $part.'.';
		}

		$sr_page_uri_str = substr($sr_page_uri_str, 0, -1);


		// Create page rating db and load it
		if(!fileExists($sr_dir.'/'.$sr_page_uri_str.'.xml')) {
			createXMLdb($sr_dir.'/'.$sr_page_uri_str);
			$sr_xml = getXMLDb($sr_dir.'/'.$sr_page_uri_str.'.xml');
		} else {
			$sr_xml = getXMLDb($sr_dir.'/'.$sr_page_uri_str.'.xml');
		}


		// Vote!
		if(isPost('sr_vote')) {			
			$mark_ip = selectXMLRecord($sr_xml,'//vote[ip="'.$_SERVER['REMOTE_ADDR'].'"]','all');									
			if(empty($mark_ip)) insertXMLRecord($sr_xml,'vote',array('mark'=>post('star'),'ip'=>$_SERVER['REMOTE_ADDR']));
		}

		// Select marks
		$marks = selectXMLRecord($sr_xml,'vote/mark','all');
	
		foreach($marks as $_m) {
			$_marks[] = (int)$_m;			
		}		

		// Count mark
		if(!empty($marks)) $sum = array_sum($_marks) / count($_marks); else $sum = 0;

		// Render Star Rating 
		echo '
		<form method="post" action="" id="sr_form">
		<input name="star" type="radio" value="1" class="star" '; if((int)$sum == 1) echo 'checked="checked"';  echo '/>
		<input name="star" type="radio" value="2" class="star" '; if((int)$sum == 2) echo 'checked="checked"';  echo '/>
		<input name="star" type="radio" value="3" class="star" '; if((int)$sum == 3) echo 'checked="checked"';  echo '/>
		<input name="star" type="radio" value="4" class="star" '; if((int)$sum == 4) echo 'checked="checked"';  echo '/>
		<input name="star" type="radio" value="5" class="star" '; if((int)$sum == 5) echo 'checked="checked"';  echo '/>
		<input type="submit" class="sr_vote" name="sr_vote" value="'.lang('sr_vote').'">
		</form> <span class="sr_desc">'.lang('sr_rating').': '.$sum.' /  '.lang('sr_votes').': '.count($_marks).'</span>
		';

	}     
	

?>