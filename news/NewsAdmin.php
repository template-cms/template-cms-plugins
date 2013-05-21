<?php


   addHook('admin_header','newsAdminHeaders');

   function newsAdminHeaders() {
        echo '<style>
                #news-options {
                    width:700px;
                    margin-top:20px;
                }
                #news {
                    width:700px;
                    margin-left:40px;
                    margin-top:20px;
                }
                .options {
                    -webkit-border-top-left-radius: 5px;
                    -webkit-border-top-right-radius: 5px;
                    -moz-border-radius-topleft: 5px;
                    -moz-border-radius-topright: 5px;
                    border-top-left-radius: 5px;
                    border-top-right-radius: 5px;
                }
                .category {
                    -webkit-border-bottom-right-radius: 5px;
                    -webkit-border-bottom-left-radius: 5px;
                    -moz-border-radius-bottomright: 5px;
                    -moz-border-radius-bottomleft: 5px;
                    border-bottom-right-radius: 5px;
                    border-bottom-left-radius: 5px
                }
                #news-toggle {
                border:1px solid #ccc;
                    background:#EEEEEE;                    
                    cursor:pointer;
                    text-align:center;
                    font-size: 0.8em;
                }
                #news-toggle:hover {
                    background:#E9EAEA;
                }
                #news-box {
                    display:none;
                    border:1px solid #ccc;
                    padding-left:15px;
                    padding-bottom:10px;
                }
              </style>
              <script>
               $().ready(function() {
                    $("#news-toggle").click(function() {
                        $("#news-box").slideToggle("slow");
                    });
               });
              </script>';
    }



    /**
     * News admin
     */
    function newsAdmin() {

        $ext = '.xml';

        $news_entries = array();

        // news database folder
        $news_entries_dir = '../data/news/';
        // news entries database
        $news_entries_file = $news_entries_dir.'news_entries';
        // news comments database
        $news_comments_file = $news_entries_dir.'news_comments';
        // news comments database
        $news_options_file = $news_entries_dir.'news_options';

        $years   = range(2000, 2032);
        $month   = range(1, 12);
        $days    = range(1, 31);
        $hours   = range(0, 23);
        $minutes = range(0, 59);
        $seconds = range(0, 59);
        

        // If news database folder exists then try to get news entries and comments database
        if(is_dir($news_entries_dir)) {
            if(file_exists($news_entries_file.$ext)) {
                $xml_db_entries = getXMLdb($news_entries_file.$ext);
            } else {
                createXMLdb($news_entries_file);
            }
            if(file_exists($news_comments_file.$ext)) {
                $xml_db_comments = getXMLdb($news_comments_file.$ext);
            } else {
                createXMLdb($news_comments_file);
            }
            if(file_exists($news_options_file.$ext)) {
                $xml_db_options = getXMLdb($news_options_file.$ext);
            } else {
                createXMLdb($news_options_file);
                $xml_db_options = getXMLdb($news_options_file.$ext);
                insertXMLRecord($xml_db_options, 'news_option', array('news_per_page'=>'5'));
            }
        } else {
            mkdir($news_entries_dir, 0755);
            createXMLdb($news_entries_file);
            createXMLdb($news_comments_file);
            createXMLdb($news_options_file);
            $xml_db_options = getXMLdb($news_options_file.$ext);
            insertXMLRecord($xml_db_options, 'news_option', array('news_per_page'=>'5',
                                                                  'news_last_count'=>'5'));
        }

        if(isset($xml_db_entries)) {
            if($xml_db_entries !== false) {
                if(isPost('add_news')) {

                    $category_name = post('category_name');
                    $category_slug = post('category_slug');

                    if($category_slug == '') {
                        $category_slug = safeName($category_name);
                    } else {
                        $category_slug = safeName(post('category_slug'));
                    }

                    $date = mktime(post('hour'),post('minute'),post('second'),post('month'),post('day'),post('year'));

                    insertXMLRecord($xml_db_entries, 'news_entry', array('name'=>safeName(post('news_name')),
                                                                         'title'=>post('news_title'),
                                                                         'description'=>post('news_description'),
                                                                         'keywords'=>post('news_keywords'),
                                                                         'category_name'=>$category_name,
                                                                         'category_slug'=>$category_slug,
                                                                         'short'=>post('editor_secondary'),
                                                                         'full'=>post('editor'),
                                                                         'date'=>$date));

                }
            }
        }

        if(isPost('edit_news')) {

            $category_name = post('category_name');
            $category_slug = post('category_slug');

            if($category_slug == '') {
                $category_slug = safeName($category_name);
            } else {
                $category_slug = safeName(post('category_slug'));
            }

            $date = mktime(post('hour'),post('minute'),post('second'),post('month'),post('day'),post('year'));

            updateXMLRecord($xml_db_entries, 'news_entry', (int)post('entry_id'), array('name'=>safeName(post('news_name')),
                                                                                        'title'=>post('news_title'),
                                                                                        'description'=>post('news_description'),
                                                                                        'keywords'=>post('news_keywords'),
                                                                                        'short'=>post('editor_secondary'),
                                                                                        'category_name'=>$category_name,
                                                                                        'category_slug'=>$category_slug,
                                                                                        'full'=>post('editor'),
                                                                                        'date'=>$date));
        }

        if(isPost('save_news_option')) {
            updateXMLRecord($xml_db_options, 'news_option', 1, array('news_per_page'=>(int)post('news_per_page'),
                                                                     'news_last_count'=>(int)post('news_last_count')));
        }

        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                case "add_news":
                    $date = explode('-',dateFormat(time(),'Y-m-d-H-i-s'));
                    

                    include 'templates/backend/NewsAddTemplate.php';
                break;
                case "edit_news":
                    $id = (int)get('entry_id');

                    $records = selectXMLRecord($xml_db_entries,"//news_entry[@id='".$id."']",'all');
                    $news_entries = selectXMLfields($records, array('id','name','title','description','keywords','category_name','category_slug','short','full','date'),'date','ASC');

                    $date = explode('-',dateFormat($news_entries[0]['date'],'Y-m-d-H-i-s'));

                    include 'templates/backend/NewsEditTemplate.php';
                break;
                case "comments_news":
                    $id = (int)get('entry_id');

                    // Get news entries
                    $records = selectXMLRecord($xml_db_entries,"//news_entry[@id='".$id."']",'all');
                    $news_entries = selectXMLfields($records, array('id','name','title','date'),'date','ASC');

                    // Get comments
                    $records = selectXMLRecord($xml_db_comments,"//news_comment[entry_id=$id]",'all');
                    $news_comments = selectXMLfields($records, array('id','entry_id','name','email','message','date'),'date','ASC');
                    
                    include 'templates/backend/NewsCommentsTemplate.php';
                break;
                case "delete_comment":
                    $entry_id = (int)get('entry_id');
                    $comment_id = (int)get('comment_id');
                    deleteXMLRecordWhere($xml_db_comments, "//news_comment[@id=$comment_id]");
                    redirect('index.php?id=pages&sub_id=news&action=comments_news&entry_id='.$entry_id);
                break;
                case "delete_news":
                    $id = (int)get('entry_id');
                    deleteXMLRecord($xml_db_entries, 'news_entry', $id);
                    redirect('index.php?id=pages&sub_id=news');
                break;
            }
            // Its mean that you can add your own actions for this plugin
            runHook('admin_news_extra_actions');
        } else { // Load main template

            if(isset($xml_db_entries)) {
                if($xml_db_entries !== false) {
                    $records = selectXMLRecord($xml_db_entries,"news_entry",'all');
                    $news_entries = selectXMLfields($records, array('name','title','category_name','category_slug','date'),'date','DESC');
                }
            }
            
            // Get news options
            if(isset($xml_db_options)) {
                if($xml_db_options !== false) {
                    $news_options = selectXMLRecord($xml_db_options, "news_option", 'all');
                }
            }
            include 'templates/backend/NewsTemplate.php';            
        }
    }

    /**
     * News template save
     */
    function newsTemplateComponentSave() {
        if(isPost('news_component_save')) {
            // Prepare content before saving
            $content = '<?xml version="1.0" encoding="UTF-8"?>';
            $content .= '<root>';
            $content .= '<template>'.post('news_template').'</template>';
            $content .= '</root>';

            createFile('../'.TEMPLATE_CMS_DATA_PATH.'other/news_template.xml',$content);
            redirect('index.php?id=themes');
        }
    }

    /**
     * News template
     */
    function newsTemplateComponent() {
        $current_theme = getSiteTheme(false);
        $themes_templates = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme, 'Template.php');
        $template_xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'other/news_template.xml');

        foreach($themes_templates as $file) {
            $pos = strpos($file, 'minify');
            if ($pos !== false) {
            } else {            
                $templates[] = basename($file,'Template.php');
            }
        }

        if(isset($template_xml->template)) {
            $template = $template_xml->template;            
        } else {
            $template = 'index';
        }

        htmlFormOpen('index.php?id=themes');
        htmlSelect($templates, array('style'=>'width:200px;','name'=>'news_template'), lang('news_news'), $template);
        htmlNbsp();
        htmlFormClose(true, array('value'=>lang('news_option_save'),'name'=>'news_component_save'));
    }