<?php


    // Add hooks to setup Simple Gallery form template
    addHook('admin_themes_extra_template_actions','simplegalleryFormComponent');
    addHook('admin_themes_extra_actions','simplegalleryFormComponentSave');


    /**
     * Sandbox admin
     */
    function simpleGalleryAdmin() {

        $ext = '.xml';

        // Images path
        $images_path = '../'.TEMPLATE_CMS_DATA_PATH.'simplegallery/';

        // Images thumbs path
        $images_thumbs_path = '../'.TEMPLATE_CMS_DATA_PATH.'simplegallery/thumbs/';

        // Simple Gallery config folder
        $simplegallery_config = '../'.TEMPLATE_CMS_DATA_PATH.'simplegallery/config/';


        /**
         * Create folders for simplegallery in data folder
         * and create simple database for it. Simple and elegant :)
         */
        if(is_dir($images_path)) {
            if(!is_dir($images_thumbs_path)) {
                mkdir($images_thumbs_path, 0755);
            }
            if(!is_dir($simplegallery_config)) {
                mkdir($simplegallery_config, 0755);
                createXMLdb($simplegallery_config.'simplegallery_config'.$ext);
                insertXMLRecord($xml_db, 'option', array('thumbnail_width'=>'120',
                        'thumbnail_height'=>'120',
                        'thumbnail_count'=>'7'));
            } else {
                if(file_exists($simplegallery_config.'simplegallery_config'.$ext)) {
                    $xml_db = getXMLdb($simplegallery_config.'simplegallery_config'.$ext);
                } else {
                    createXMLdb($simplegallery_config.'simplegallery_config'.$ext);
                    $xml_db = getXMLdb($simplegallery_config.'simplegallery_config'.$ext);
                }
            }
        } else {
            mkdir($images_path, 0755);
            mkdir($images_thumbs_path, 0755);
            mkdir($simplegallery_config, 0755);
            createXMLdb($simplegallery_config.'simplegallery_config');
            $htaccess = "Options -Indexes \n
                         Allow from all";
            file_put_contents($images_path.'.htaccess', $htaccess);
            $xml_db = getXMLdb($simplegallery_config.'simplegallery_config'.$ext);
            insertXMLRecord($xml_db, 'simplegallery_option', array('thumbnail_width'=>'120',
                    'thumbnail_height'=>'120',
                    'thumbnail_count'=>'7'));
        }

        /**
         * Get records from simplegallery config database
         */
        if($xml_db) {
            $simplegallery_config_xml = selectXMLRecord($xml_db, "simplegallery_option", 'all');
        }

        /**
         * Upload image to simplegallery folder and create thumb
         */
        if(isPost('upload_image')) {
            if ($_FILES['file']) {
                if($_FILES['file']['type'] == 'image/jpeg' ||
                   $_FILES['file']['type'] == 'image/png' ||
                   $_FILES['file']['type'] == 'image/gif') {
                   move_uploaded_file($_FILES['file']['tmp_name'],$images_path.$_FILES['file']['name']);
                   createThumb($images_path.$_FILES['file']['name'], $simplegallery_config_xml[0]->thumbnail_width, $simplegallery_config_xml[0]->thumbnail_height,$_FILES['file'],$images_path.'thumbs/'.$_FILES['file']['name'], 100, true);

                }
            }
        }

        /**
         * Save sumplegalery options
         */
        if(isPost('simplegallery_save_options')) {
            updateXMLRecord($xml_db, 'simplegallery_option', 1, array('thumbnail_width'=>(int)post('thumbnail_width'),
                                                                        'thumbnail_height'=>(int)post('thumbnail_height'),
                                                                        'thumbnail_count'=>(int)post('thumbnail_count')));
        }


        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                case "delete":
                    deleteFile($images_path.get('image'));
                    deleteFile($images_thumbs_path.get('image'));
                    redirect('index.php?id=pages&sub_id=simplegallery');
                break;
            }
        } else { // Load main template
            $images = array();
            $images = listFiles('../'.TEMPLATE_CMS_DATA_PATH.'simplegallery/thumbs/');
            include 'templates/backend/SimpleGalleryTemplate.php';
        }
    }
 
    
    function createThumb($src_file, $max_w, $max_h, &$image_info, $dst_file = null, $quality = 100, $overwrite = true) {
        // check params
        $max_w = @(int)$max_w;
        $max_h = @(int)$max_h;
        if ((empty($src_file)) || ((null !== $dst_file) && empty($dst_file))
                || ($max_w <= 0) || ($max_h <= 0)
                || ($quality < 1) || ($quality > 100)
        )
            throw new Exception('Wrong incoming params specified.');

        // setup funcs for supported types
        $mime_types=array(
                'image/jpeg'  => array('imageCreateFromJpeg', 'imageJpeg')
                ,'image/gif'   => array('imageCreateFromGif',  'imageGif')
                ,'image/png'   => array('imageCreateFromPng',  'imagePng')
        );

        // check if file names are appropriate
        $src_file = realpath($src_file);
        $dst_real_file = realpath($dst_file);
        if (empty($src_file) || !file_exists($src_file))
            throw new Exception("Source file '{$src_file}' does not exist.");
        if (null !== $dst_file)
            if ((!$overwrite) && (!empty($dst_real_file)) && file_exists($dst_real_file))
                throw new Exception("Overwriting option is disabled, but target file '{$dst_real_file}' exists.");
        if ($src_file === $dst_real_file)
            throw new Exception('Source path equals to destination path.');

        // try to obtain source image size and type
        @list($src_w, $src_h, $src_type) = array_values(getimagesize($src_file));
        $src_type = image_type_to_mime_type($src_type);
        if (empty($src_w) || empty($src_h) || empty($src_type))
            throw new Exception('Failed to obtain source image properties.');

        // check if constraining required
        if (!(($src_w > $max_w) || ($src_h > $max_h))) {
            $image_info = array($src_w, $src_h, $src_type);

            // return raw contents
            if (null === $dst_file) {
                $raw_data = file_get_contents($src_file);
                if (empty($raw_data))
                    throw new Exception('Constraining is not required, but failed to get source raw data.');
                return $raw_data;
            }

            // just copy the file
            if (!copy($src_file, $dst_file))
                throw new Exception('Constraining is not required, but failed to copy source file to destination file.');
            return null;
        }

        // calculate new dimensions
        $dst_w = $max_w;
        $dst_h = $max_h;
        if (($src_w - $max_w) > ($src_h - $max_h))
            $dst_h = (int)(($max_w / $src_w) * $src_h);
        else
            $dst_w = (int)(($max_h / $src_h) * $src_w);
        $image_info = array($dst_w, $dst_h, $src_type);

        // check if source type supported
        @list($create_callback, $write_callback) = $mime_types[$src_type];
        if (empty($mime_types[$src_type])
                || (!function_exists($create_callback))
                || (!function_exists($write_callback))
        )
            throw new Exception("Source image type '{$src_type}' is not supported.");

        // create source image resource and determine its colors number
        $src_img = call_user_func($create_callback, $src_file);
        if (empty($src_img))
            throw new Exception("Failed to create source image with {$create_callback}().");
        $src_colors = imagecolorstotal($src_img);

        // create destination image (indexed, if possible)
        if ($src_colors > 0 && $src_colors <= 256)
            $dst_img = imagecreate($dst_w, $dst_h);
        else
            $dst_img = imagecreatetruecolor($dst_w, $dst_h);
        if (empty($dst_img))
            throw new Exception("Failed to create blank destination image.");

        // preserve non-alpha transparency, if it is defined
        $transparent_index = imagecolortransparent($src_img);
        if ($transparent_index >= 0) {
            $t_c = imagecolorsforindex($src_img, $transparent_index);
            $transparent_index = imagecolorallocate($dst_img, $t_c['red'], $t_c['green'], $t_c['blue']);
            if (false === $transparent_index)
                throw new Exception('Failed to allocate transparency index for image.');
            if (!imagefill($dst_img, 0, 0, $transparent_index))
                throw new Exception('Failed to fill image with transparency.');
            imagecolortransparent($dst_img, $transparent_index);
        }

        // or preserve alpha transparency for png
        elseif ('image/png' === $src_type) {
            if (!imagealphablending($dst_img, false))
                throw new Exception('Failed to set alpha blending for PNG image.');
            $transparency = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
            if (false === $transparency)
                throw new Exception('Failed to allocate alpha transparency for PNG image.');
            if (!imagefill($dst_img, 0, 0, $transparency))
                throw new Exception('Failed to fill PNG image with alpha transparency.');
            if (!imagesavealpha($dst_img, true))
                throw new Exception('Failed to save alpha transparency into PNG image.');
        }

        // resample the image with new sizes
        if (!imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h))
            throw new Exception('Failed to resample image.');

        // recalculate quality value for png image
        if ('image/png' === $src_type) {
            $quality = round(($quality / 100) * 10);
            if ($quality < 1)
                $quality = 1;
            elseif ($quality > 10)
                $quality = 10;
            $quality = 10 - $quality;
        }

        // write into destination file or into output buffer
        if (null === $dst_file)
            ob_start();
        if (!call_user_func($write_callback, $dst_img, $dst_file, $quality)) {
            // do not forget to cleanup buffer ;-)
            if (null === $dst_file)
                ob_end_clean();
            throw new Exception('Failed to write destination image.');
        }
        if (null === $dst_file)
            return ob_get_clean();

        return null;
    }

    /**
     * Simple Gallery form template save
     */
    function simplegalleryFormComponentSave() {
        if(isPost('simplegallery_component_save')) {
            // Prepare content before saving
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<root>';
            $content .= '<template>'.post('simplegallery_form_template').'</template>';
            $content .= '</root>';

            createFile('../'.TEMPLATE_CMS_DATA_PATH.'other/simplegallery_template.xml',$content);
            redirect('index.php?id=themes');
        }
    }

    /**
     * Guestbook form template
     */
    function simplegalleryFormComponent() {
        $current_theme = getSiteTheme(false);
        $themes_templates = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme, 'Template.php');
        $template_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'other/simplegallery_template.xml');

        foreach($themes_templates as $file) $templates[] = basename($file,'Template.php');

        htmlFormOpen('index.php?id=themes');
        htmlSelect($templates, array('style'=>'width:200px;','name'=>'simplegallery_form_template'), lang('simplegallery_name'), $template_xml->template);
        htmlNbsp();
        htmlFormClose(true, array('value'=>lang('simplegallery_save_options'),'name'=>'simplegallery_component_save'));
    }