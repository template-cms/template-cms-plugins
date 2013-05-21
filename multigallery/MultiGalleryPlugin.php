<?php

    /**
     *	MultiGallery plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Murlika
     *  base on code simplegallery by Romanenko Sergey / Awilum
     *	@version 1.0.5 non official release by Awilum
     *  usage:
     *  as hook <?php runHookP('MultiGallery_site','Gallery_name'); ?> in template
     *  as metateg [gal]Gallery_name[/gal],[cover]Gallery_name[/cover],[rand]Gallery_name[/rand],[img]Gallery_name/Image_name[/img] in page text
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'MultiGallery',
                   '1.0.5',
                   'MultiGallery plugin <a href="index.php?id=pages&sub_id=multigallery">&rarr; admin</a> <a href="../multigallery" target="_blank">&rarr; see</a>',
                   'Murlika',
                   'http:///',
                   'MultiGalleryAdmin',
                   'multigallery');


    // Get language file for this plugin
    getPluginLanguage('MultiGallery');

    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('MultiGallery_submenu'),'multigallery'));

   //Add script header
    addHook('theme_header','MultiGalleryThemeHeaders');
   addHook('admin_header','MultiGalleryAdminHeaders');

    // Add hooks as component / template hooks
    addHook('multigallery_content','MultiGalleryContent',array());
	addHook('multigallery_site','MultiGalleryContent',array());

    //Add filter as [php]...[/php],[cover][/cover],etc
    addFilter('content', 'MultiGalleryphp');

	addHook('MultiGallery_template','MultiGalleryTemplate',array());
    addHook('multigallery_title','MultiGalleryTitle',array());

    // Include MultiGallery Admin
    getPluginAdmin('MultiGallery');

    /**
     * Get gallery content
     */
    function MultiGalleryContent($current_path){

     if (is_array($current_path)) {
      if (count($current_path)>1)
            $current_path=$current_path[1];
      else   {
            $action="directory";
            $current_path='';
            }
      }

     if (!$current_path)  $action="directory";
     	else   $action="images";

    //var_dump($action==="images");

     if ($action==="images") {
     $images = array();
      $images = listFiles('../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$current_path.'/thumbs/');
        echo createMultiGallery($images,$current_path);
        }
     if ($action==="directory") {
       $directorys = array();

       $directorys = listOfFolders(TEMPLATE_CMS_DATA_PATH.'multigallery');

       //var_dump($directorys);
       echo  createMultiGalleryDir($directorys);

     	}

    }

    /**
     * Get gallery title
     */
    function MultiGalleryTitle() {
        echo lang('MultiGallery_name');
    }

    /**
     * Get gallery cover
     */
    function MultiGalleryCover($current_path) {
          if (is_array($current_path)) {
      if (count($current_path)>1)
            $current_path=$current_path[1];
      else   {
            $action="directory";
            $current_path='';
            }
      }

     $directory = ''; //@Awilum  -> Notice: Undefined variable $directory

     $images_path =TEMPLATE_CMS_DATA_PATH.'multigallery/'.$directory.'/';
     $MultiGallery_config = TEMPLATE_CMS_DATA_PATH.'multigallery/'.$directory.'/config/';

     if(is_dir($images_path.$current_path)) {

       $txt= createMultiGalleryDir(array($current_path));
       return $txt;
		}

    }

     /**
     * Get gallery random image
     */
   function MultiGalleryRandom($current_path) {
          if (is_array($current_path)) {
      if (count($current_path)>1)
            $current_path=$current_path[1];
      else   {
            $action="directory";
            $current_path='';
            }
      }

     $ext = '.xml';


	 $images_path =TEMPLATE_CMS_DATA_PATH.'multigallery/'.$current_path.'/';
     $MultiGallery_config = TEMPLATE_CMS_DATA_PATH.'multigallery/'.$current_path.'/config/';

     if(is_dir($images_path)) {
     //var_dump($current_path);
     $desc_xml_db = getXMLdb($images_path.'MultiGallery_image_info'.$ext);


     $query="//item";
     $xml_arr = selectXMLRecord($desc_xml_db,$query,'all');

     $count_native=count($xml_arr);
     $itm_number=rand(0,$count_native-1);
     if ($count_native>0) {
               $item=$xml_arr[$itm_number];
               $client_image = getimagesize($images_path.'thumbs/'.$item->filename);
               $client_size = 'width="'.$client_image[0].'" height="'.$client_image[1].'"';
              }

     return '<a  href="'.getSiteUrl(false).'multigallery/'.$current_path.'/"> <img '.$client_size.' class="screenshot-image"  src="'.$images_path.'thumbs/'.$item->filename.'"  /></a>';


     }
    }


    /**
     * Get gallery content  into article
     */
    function MultiGalleryGal($current_path) {
          if (is_array($current_path)) {
      if (count($current_path)>1)
            $current_path=$current_path[1];
      else
            $current_path='default';

      }
     if (!$current_path)  $current_path="default";


    //var_dump($current_path);

     $images = array();
     $images = listFiles('../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$current_path.'/thumbs/');
     return createMultiGallery($images,$current_path);


    }

    /**
     * Get gallery img
     */
    function MultiGalleryImg($current_img) {
     if (is_array($current_img)) $current_img=$current_img[1];

     $ext = '.xml';

	 $pattern = "/(\w+) (\d+), (\d+)/i";
	 $replacement = "\${1}1,\$3";
	 $current_img=explode ('/',$current_img); // @Awilum -> split Deprecated

     $images_path =TEMPLATE_CMS_DATA_PATH.'multigallery/'.$current_img[0].'/';
     $MultiGallery_config = TEMPLATE_CMS_DATA_PATH.'multigallery/'.$current_img[0].'/config/';

     $desc_xml_db = getXMLdb($images_path.'MultiGallery_image_info'.$ext);

     $query="//item";
     $xml_arr = selectXMLRecord($desc_xml_db,$query,'all');

     $count_native=count($xml_arr);
     if ($count_native>0) {
               $item=$xml_arr[0];
               $client_image = getimagesize($images_path.'thumbs/'.$item->filename);
               $client_size = 'width="'.$client_image[0].'" height="'.$client_image[1].'"';
              }
     // @Awilum
     return '<a href="'.$images_path.$current_img[1].'" rel="gallery" title="'.$item->desc.'" class="pirobox_gall"><img '.$client_size.' class="screenshot-image"  src="'.$images_path.'thumbs/'.$current_img[1].'"  /></a>';
    }

       /**
     * Get gallery filter on text
     */
    function MultiGalleryphp($str){
         $txt=preg_replace_callback('/\[gal\](.*?)\[\/gal\]/ms','MultiGalleryGal',$str);
         $txt=preg_replace_callback('/\[cover\](.*?)\[\/cover\]/ms','MultiGalleryCover',$txt);
         $txt=preg_replace_callback('/\[image\](.*?)\[\/image\]/ms','MultiGalleryImg',$txt);
         $txt=preg_replace_callback('/\[rand\](.*?)\[\/rand\]/ms','MultiGalleryRandom',$txt);

    	 return $txt;
    }


    /**
     *  Gallery template
     */
    function MultiGalleryTemplate() {
        $template_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'other/MultiGallery_template.xml');
        if($template_xml == null) {
            $template = 'index';
        } else {
            $template = $template_xml->template;
        }
        return $template;
    }

      /**
     * Set MultiGallery themes headers
     */
    function MultiGalleryThemeHeaders() {
        echo '<style>
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
                }
                .galitem {clear:left;list-style-type:none;}
                .galitem img{
                	float:left;
                	margin:5px;
                	}
                .galitem p{margin:0px; }
                .galcount, .galdate{color:#a4a4a4;padding:5px 0;}
            </style>
            <link href="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/css/style.css" class="piro_style" media="screen" title="white" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/js/jquery.min.js"></script>
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/js/jquery-ui-1.8.2.custom.min.js"></script>
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/js/pirobox_extended_min.js"></script>
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
     * Set gallery admin headers
     */
    function MultiGalleryAdminHeaders() {
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
                        margin-bottom:10px; }
                .teg{padding:10px 0;}
                .galcount, .galdate{color:#a4a4a4;}
                .galitem {clear:left;list-style-type:none;}
                .galitem .galitem_txt{margin-left:145px;}
                .galitem img{
                	float:left;
                	margin:5px;
                	}
                .teg .submit{
                	   -moz-border-radius: 3px;
					   -webkit-border-radius: 3px;
					   background-color: #AAB7BB;
					   border-radius: 3px;
					   color: white;
					   font-size: 12px;
					   margin-left: 10px;
					   padding: 2px 8px;
					   text-decoration: none;
                }
              .teg .submit:hover {
					    background:#8E9FA5;
					    color:#FFF;
					    text-decoration:none;
				}

            </style>
            <link href="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/css/style.css" class="piro_style" media="screen" title="white" rel="stylesheet" type="text/css" />
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/js/jquery-ui-1.8.2.custom.min.js"></script>
            <script type="text/javascript" src="'.getSiteUrl(false).'plugins/multigallery/js/pirobox/js/pirobox_extended_min.js"></script>
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
     * Create gallery
     */
      function createMultiGallery($images,$curr_dir='default',$admin_area=false) {
         $ext = '.xml';
         if (!$curr_dir)  $curr_dir="default";
       // Images path
        $images_path = '../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$curr_dir.'/';



        if($admin_area) {
            $images_path = '../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$curr_dir.'/';
            $MultiGallery_config = '../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$curr_dir.'/config/';

            $xml_db = getXMLdb($MultiGallery_config.'MultiGallery_config.xml');

        	 if($xml_db) {

              $MultiGallery_config_xml = selectXMLRecord($xml_db, "option", 'all');
            }

            $admin_width = $MultiGallery_config_xml[0]->thumbnail_width;
            $admin_height = $MultiGallery_config_xml[0]->thumbnail_height;

            $count = 1;

            $admin_width = 'width="64"';

            $height = $admin_height + 40;

            $full_site_url = ''; // @Awilum

        } else {
            $images_path = TEMPLATE_CMS_DATA_PATH.'multigallery/'.$curr_dir.'/';
            $MultiGallery_config = TEMPLATE_CMS_DATA_PATH.'multigallery/'.$curr_dir.'/config/';

            $full_site_url = getSiteUrl(false); // @Awilum

	        $xml_db = getXMLdb($MultiGallery_config.'MultiGallery_config.xml');
	        //var_dump($images_path);
            if($xml_db) {
                $MultiGallery_config_xml = selectXMLRecord($xml_db, "option", 'all');
            }

            if ($MultiGallery_config_xml[0]->publish==0) {
            	return false;
            }
            //var_dump($xml_db);

            $width = $MultiGallery_config_xml[0]->thumbnail_width;
            $height = $MultiGallery_config_xml[0]->thumbnail_height;
            $count = $MultiGallery_config_xml[0]->thumbnail_count;
            $nasv = $MultiGallery_config_xml[0]->nasv;

            $admin_width = '';


        }

        $desc_xml_db = getXMLdb($images_path.'MultiGallery_image_info'.$ext);



        $query="//item";
        $xml_arr = selectXMLRecord($desc_xml_db,$query,'all');



        // Generate gallery
        $c = 1;
        //echo '<p class="MultiGallery_title">'.$nasv.'</p>';

       ob_start();
        echo '<table>';
        if(count($xml_arr) > 0) {
            foreach($xml_arr as $item) {
               $image=$item->filename;
                $c++;
                if($c < 2) {
                    echo '<tr>';
                }

                if(!$admin_area) {
                    // Get real thumbs size
                    // See: http://code.google.com/intl/ru-RU/speed/page-speed/docs/filter-image-optimize.html
                    $client_image = getimagesize($images_path.'thumbs/'.$image);
                    $client_size = 'width="'.$client_image[0].'" height="'.$client_image[1].'"';
                } else {
					$client_size = '';
				}

                    
                echo '<td class="screenshot">';
                echo '        <a href="'.$full_site_url.$images_path.$image.'" rel="gallery" title="'.$item->desc.'" class="pirobox_gall">
                            <img '.$client_size.' '.$admin_width.' class="screenshot-image"  src="'.$full_site_url.$images_path.'thumbs/'.$image.'"  />
                        </a>'; // @Awilum
                if($admin_area) {
                    echo "<td>";
                    htmlBr();
                    htmlFormOpen('index.php?id=pages&sub_id=multigallery&gal='.$curr_dir,'post',true);
                    htmlFormInput(array('value'=>$item->desc,'name'=>'desc','size'=>'30'), lang('MultiGallery_nasv'));
                    htmlFormHidden('filename',$item->filename);
                     htmlBr();
                    echo "<div class='teg'><p class='teg_desc'>".lang('MultiGallery_teg')."</p>";
                    echo "<p class='teg_cod'>[image]".$curr_dir.'/'.$item->filename."[/image]</p>";

                     htmlBr();
                      htmlButtonDelete(lang('pages_delete'), 'index.php?id=pages&sub_id=multigallery&action=delete&gal='.$curr_dir.'&image='.$image);

                      htmlFormClose(true,array('name'=>'save_desc','value'=>lang('pages_edit')));


                              }
                  else {
                  	//echo "<p class='MultiGallery_caption'>".$item->desc."</p>";
                  }
                echo'</td>';
                if($c > $count) {
                    echo '</tr>';
                    $c = 1;
                }
            }
        } else {
            echo lang('MultiGallery_empty');
        }
        echo '</table>';
        $txt = ob_get_contents();
		ob_end_clean();
		return $txt;
    }

   /**
     * Get list of gallery
     */
 function listOfFolders($folder, $type=null) {



    $data = array();
        if(is_dir($folder)) {


   $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), RecursiveIteratorIterator::SELF_FIRST);
	foreach($objects as $name => $object){

   	if ($object->isDir()&&!($object->getFilename()==='thumbs')&&!($object->getFilename()==='config'))  $data[]=$object->getFilename();
	                    //


     }
             return $data;
        } else {
            return false;
        }
 	}


 	    function createMultiGalleryDir($folders,$admin_area=false) {
         $ext = '.xml';

        //
        // Generate list of galleries

        ob_start();

        if(count($folders) > 0) {
            echo "<ul>";
            foreach($folders as $directory) {

               //var_dump($folders);
               //$directory=$item->filename;
              if ($admin_area){
               $images_path ='../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$directory.'/';
               $MultiGallery_config = '../'.TEMPLATE_CMS_DATA_PATH.'multigallery/'.$directory.'/config/';
               }
               else {
               $images_path =TEMPLATE_CMS_DATA_PATH.'multigallery/'.$directory.'/';
               $MultiGallery_config = TEMPLATE_CMS_DATA_PATH.'multigallery/'.$directory.'/config/';
               }

                $xml_db = getXMLdb($MultiGallery_config.'MultiGallery_config.xml');
	     	   //var_dump($images_path);
	            if($xml_db) {
          	      $MultiGallery_config_xml = selectXMLRecord($xml_db, "option", 'all');
        	    }

            if ($MultiGallery_config_xml[0]->publish==1) {

            //var_dump($xml_db);
            $width = $MultiGallery_config_xml[0]->thumbnail_width;
            $height = $MultiGallery_config_xml[0]->thumbnail_height;
            $count = $MultiGallery_config_xml[0]->thumbnail_count;
            $nasv = $MultiGallery_config_xml[0]->nasv;
            $data = $MultiGallery_config_xml[0]->data;


               $desc_xml_db = getXMLdb($images_path.'MultiGallery_image_info'.$ext);

               $query="//item";
        	   $xml_arr = selectXMLRecord($desc_xml_db,$query,'all');

               $count_native=count($xml_arr);
               if ($count_native>0) {
               $item=$xml_arr[0];

               $client_image = getimagesize($images_path.'thumbs/'.$item->filename);
               $client_size = 'width="'.$client_image[0].'" height="'.$client_image[1].'"';
              }

              if(!isset($client_size)) $client_size = ''; // @Awilum
              if(!isset($item)) $item->filename = ''; // @Awilum
              

               echo "<li class='galitem'>";

               if ($admin_area)   echo '<a  href="'.getSiteUrl(false).'admin/index.php?id=pages&sub_id=multigallery&gal='.$directory.'">';
               else  echo '<a  href="'.getSiteUrl(false).'multigallery/'.$directory.'/">';
                echo '  <img '.$client_size.' class="screenshot-image"  src="'.$images_path.'thumbs/'.$item->filename.'"  /></a> '; //@Awilum
               echo "<div class='galitem_txt'><p class='galname'>".$directory."</p>";
                if ($count_native>0) {
               echo "<p class='galcount'>".$count_native.lang('MultiGallery_foto')."</p>";
               }
               else {
               	 echo "<p class='galcount'>".lang('MultiGallery_nofoto')."</p>";
               }
               echo "<p class='galdescript'>".$nasv."</p>";
               echo "<p class='galdate'>".$data."</p>";
               if ($admin_area) {
               echo "<div class='teg'><p class='teg_desc'>".lang('MultiGallery_teg')."</p>";
               echo "<p class='teg_cod'><b>".lang('MultiGallery_teg_all')."</b>[gal]".$directory."[/gal]</p>";
               echo "<p class='teg_cod'><b>".lang('MultiGallery_teg_rand')."</b>[rand]".$directory."[/rand]</p>";
               echo "<p class='teg_cod2'><b>".lang('MultiGallery_teg_gal')."</b>[cover]".$directory."[/cover]</p>";
               htmlButtonDelete(lang('pages_delete'), 'index.php?id=pages&action=delete_folder&sub_id=multigallery&gal='.$directory);
	           htmlButtonEdit(lang('pages_edit'), 'index.php?id=pages&sub_id=multigallery&action=edit_folder&gal='.$directory);
               echo "</div>";
               }

              echo '</div></li>';
                    }

            }
            echo "</ul>";
        } else {
            echo lang('MultiGallery_empty');
        }

        $txt = ob_get_contents();

       	ob_end_clean();
		return $txt;
    }

// Spesial functions to template

 	    function getimg($current_img) { echo MultiGalleryImg($current_img);}
        function getgallery($current_path) { echo MultiGalleryGal($current_path);}
		function getcover($current_path) { echo MultiGalleryCover($current_path);}

