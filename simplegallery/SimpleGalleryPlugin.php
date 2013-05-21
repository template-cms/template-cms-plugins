<?php

    /**
     *	SimpleGallery plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.4
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Simple Gallery',
                   '1.1.4',
                   'Simple Gallery plugin <a href="index.php?id=pages&sub_id=simplegallery">&rarr; admin</a> <a href="../gallery" target="_blank">&rarr; see</a>',
                   'Awilum',           	        
                   'http://awilum.webdevart.ru/',
                   'simpleGalleryAdmin',
                   'gallery');      


    // Get language file for this plugin
    getPluginLanguage('SimpleGallery');

    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('simplegallery_submenu'),'simplegallery'));

    // Add some other hooks
    addHook('admin_header','simpleGalleryAdminHeaders');
    addHook('theme_header','simpleGalleryThemeHeaders');

    // Add hooks as component / template hooks
    addHook('gallery_content','galleryContent',array());
    addHook('gallery_title','galleryTitle',array());
    addHook('gallery_template','galleryTemplate',array());
    
    // Include SimpleGallery Admin    
    getPluginAdmin('SimpleGallery');

    /**
     * Set simplegallery admin headers
     */
    function simpleGalleryAdminHeaders() {
        echo '<style>
                .screenshot {
                    margin: 3px;
                    padding: 3px;                    
                    text-align: center;	
                }

                .screenshot-image {
                    border: 3px solid #F2F2F2;
                }

                .screenshot-image:hover{
                    border: 3px solid #D9D9D9;
                }
                
                .simple-gallery-panel {
                        border:1px solid #DDD;                                                
                        padding:10px 20px;
                        width:100%;
                }
            </style>
            <link href="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/css/style.css" class="piro_style" media="screen" title="white" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/js/jquery-ui-1.8.2.custom.min.js"></script>
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/js/pirobox_extended_min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {
                    $().piroBox_ext({
                            piro_speed : 900,
                            bg_alpha : 0.1,
                            piro_scroll : true //pirobox always positioned at the center of the page
                });
            });
            </script>
            ';
    }

    /**
     * Set simplegallery themes headers
     */
    function simpleGalleryThemeHeaders() {
        echo '<style>'.compressCSS('
                .screenshot {
                    margin: 3px;
                    padding: 3px;
                    float:left;
                    text-align: center;
                }
                .screenshot-image {
                    border: 3px solid #F2F2F2;
                }
                .screenshot-image:hover{
                    border: 3px solid #D9D9D9;
                }').
           '</style>';
     echo  '<link href="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/css/style.css" class="piro_style" media="screen" title="white" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/js/jquery.min.js"></script>
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/js/jquery-ui-1.8.2.custom.min.js"></script>
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/simplegallery/js/pirobox/js/pirobox_extended_min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {
                    $().piroBox_ext({
                            piro_speed : 900,
                            bg_alpha : 0.1,
                            piro_scroll : true //pirobox always positioned at the center of the page
                });
            });
            </script>
            ';
    }

    /**
     * Get gallery content
     */
    function galleryContent(){
        $images = array();
        $images = listFiles(TEMPLATE_CMS_DATA_PATH.'simplegallery/thumbs/');
        createSimpleGallery($images);         
    }
	
   /**
     * Create simple gallery
     * @param array $images array of thumbs
     * @param boolean $admin_area access
     */
    function createSimpleGallery($images, $admin_area=false) {
        if($admin_area) {
            $images_path = '../'.TEMPLATE_CMS_DATA_PATH.'simplegallery/';
            $simplegallery_config = '../'.TEMPLATE_CMS_DATA_PATH.'simplegallery/config/';
            $xml_db = getXMLdb($simplegallery_config.'simplegallery_config.xml');
            if($xml_db) {
                $simplegallery_config_xml = selectXMLRecord($xml_db, "simplegallery_option", 'all');
            }

            $admin_width = $simplegallery_config_xml[0]->thumbnail_width;
            $admin_height = $simplegallery_config_xml[0]->thumbnail_height;
        
            $count = 10;

            $admin_width = 'width="64"';

            $height = $admin_height + 40;

            $nbsp = '&nbsp;&nbsp;';

            $paginator = '';

        } else {
            $images_path = TEMPLATE_CMS_DATA_PATH.'simplegallery/';
            $simplegallery_config = TEMPLATE_CMS_DATA_PATH.'simplegallery/config/';
            $xml_db = getXMLdb($simplegallery_config.'simplegallery_config.xml');
            if($xml_db) {
                $simplegallery_config_xml = selectXMLRecord($xml_db, "simplegallery_option", 'all');
            }
            
            $width = $simplegallery_config_xml[0]->thumbnail_width;
            $height = $simplegallery_config_xml[0]->thumbnail_height;
            $count = $simplegallery_config_xml[0]->thumbnail_count+1;

            $admin_width = '';     
            
            $nbsp = '';   
            
            // Get installed plugins
            $plugins = getPluginInfo();        
            // If paginator plugin installed then create paginator links
            if(isset($plugins['paginator'])) $paginator = '</table>[page]'; else $paginator = '';
        }


        // Generate gallery
        $c = 1;
        $gallery = '<table>';
        if(count($images) > 0) {
            foreach($images as $image) {                
                $c++;
                if($c < 3) {
                    $gallery .= '<tr>';                    
                }

                if(!$admin_area) {
                    // Get real thumbs size
                    // See: http://code.google.com/intl/ru-RU/speed/page-speed/docs/filter-image-optimize.html
                    $client_image = getimagesize($images_path.'thumbs/'.$image);
                    $client_size = 'width="'.$client_image[0].'" height="'.$client_image[1].'"';
                } else {
					$client_size = '';
				}

                $gallery .= '<td class="screenshot">';
                $gallery .= $nbsp.'<a href="'.$images_path.$image.'" rel="gallery"  class="pirobox_gall">
                            <img '.$client_size.' '.$admin_width.' class="screenshot-image" src="'.$images_path.'thumbs/'.$image.'" alt="" />
                        </a>';
                if($admin_area) {
                    $gallery .= '<br />';
                    $url = "index.php?id=pages&sub_id=simplegallery&action=delete&image=$image";
                    $title = lang("simplegallery_delete");
                    $txt = lang("simplegallery_delete");
                    $gallery .= '<span class="btn-delete"><a href="'.$url.'" title="'.$title.'" onclick="return confirmDelete(\''.$title.'\')">'.$txt.'</a></span>';                    
                }
                $gallery .='</td>';
                //if($c > $count) {
                if($c == $count) {
                    $gallery .= '</tr>'.$paginator;                    
                    $c = 1;
                }

            }
        } else {
            $gallery .= lang('simplegallery_empty');
        }
        $gallery .= '</tr></table>';   
        
        echo applyFilters('content',$gallery);
    }


    /**
     * Get gallery title
     */
    function galleryTitle() {
        echo lang('simplegallery_name');
    }

    /**
     *  Gallery template
     */
    function galleryTemplate() {
        $template_xml = getXML(TEMPLATE_CMS_DATA_PATH.'other/simplegallery_template.xml');
        if($template_xml == null) {
            $template = 'index';
        } else {
            $template = $template_xml->template;
        }
        return $template;
    }