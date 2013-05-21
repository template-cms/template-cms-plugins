<?php

    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('article_name'),'article'));
    addHook('admin_headers','articleHeaders');


    /**
     * Add some headers to admin
     */
    function articleHeaders() {
        // some headers to admin...
    }
    
    /**
     * Article install
     */
    function articleInstall() {
        $article_dir  = '../data/article/';
        $article_content_dir  = $article_dir.'content/';

        // Create directory and index and data database
        if(!is_dir($article_dir))  mkdir($article_dir, 0755);
        if(!is_dir($article_content_dir))  mkdir($article_content_dir, 0755);
        createXMLdb($article_dir.'article'); 
        
        addOption('article_template','');
    }
    
    /**
     * Article admin
     */
    function articleAdmin() {
        $article_dir  = '../data/article/';
        $article_content_dir  = $article_dir.'content/';
        $article_xml = getXMLdb($article_dir.'article.xml');
        
        $current_theme = getSiteTheme(false);
        $templates_path = TEMPLATE_CMS_THEMES_PATH.$current_theme.'/';
        
        $today = mktime(date('H'), date('i'), date('s'), 
                        date('n'), date('j'), date('Y'));
        //$date = dateFormat($art->date,'d.m.Y');
        
        // Create article
        if(!empty($_POST['article_title_new'])) {
            insertXMLRecord($article_xml,'article',array('title'   =>post('article_title_new'),
                                                         'description' => '',
                                                         'keywords'    => '',
                                                         'slug'    =>safeName(post('article_title_new')),
                                                         'date'    =>$today,
                                                         'notshow' =>'1',
                                                         'views'   => '0'));
            $last_id = lastXMLRecordId($article_xml,'article');
            
            createXMLdb($article_content_dir.$last_id);
            $article_content_xml = getXMLdb($article_content_dir.$last_id.'.xml');
            insertXMLRecord($article_content_xml,'content',array('message'     => ''));
            redirect('index.php?id=pages&sub_id=article&action=edit&art_id='.$last_id);
        }
        
        $templates_list = listFiles($templates_path,'Template.php');
        foreach($templates_list as $file) {
            $pos = strpos($file, 'minify');
            if(!($pos !== false)) {
                $templates_array[] = basename($file,'Template.php');
            }
        }
                    
        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                case "delete":
                    if (!empty($_GET['art_id'])){
                        deleteXMLRecord($article_xml,'article',get('art_id')); 
                        deleteFile($article_content_dir.get('art_id').'.xml');
                    }
                    redirect('index.php?id=pages&sub_id=article');
                    break;
                case "settings":
                    if (!empty($_POST['templates'])) {
                        $template = 'article_template';
                        $template_option = getOption($template);
                        if (empty($template_option)) {
                            addOption($template, post('templates'));
                        } else {
                            updateOption($template, post('templates'));
                        }
                        flashMessage(lang('article_saved'));
                    } elseif (!empty($_POST['limit'])) {
                        $limit = 'article_limit';
                        $limit_option = getOption($limit);
                        $limit_value = intval($_POST['limit'])>0 ? post('limit') : 5;
                        if (empty($limit_option)) {
                            addOption($limit, $limit_value);
                        } else {
                            updateOption($limit, $limit_value);
                        }
                        flashMessage(lang('article_saved'));
                    }
                    include 'templates/backend/ArticleSettingsTemplate.php';
                    break;
                case "edit":
                    if (!empty($_GET['art_id'])){
                        $article_content_xml = getXMLdb($article_content_dir.get('art_id').'.xml');
                        
                        if((isPost('edit_page') or isPost('edit_page_and_exit')) and !empty($_POST['title'])) {
                            
                            updateXMLRecord($article_xml, 'article', get('art_id'), array('title'   => post('title'),
                                                                                          'description' => post('description'),
                                                                                          'keywords'    => post('keywords'),
                                                                                          'slug'    => safeName(post('slug')),
                                                                                          'notshow' => intval(post('notshow')),
                                                                                          'template'=> post('templates')));
                            
                            updateXMLRecord($article_content_xml, 'content', 1, array('message'     => post('editor')));
                                                                                        
                            if(isPost('edit_page_and_exit')) {
                                redirect('index.php?id=pages&sub_id=article');    
                            } else {
                                redirect('index.php?id=pages&sub_id=article&action=edit&art_id='.get('art_id'));
                            }
                        }
                        
                        $article         = selectXMLRecord($article_xml, '//article[@id='.get('art_id').']');
                        $article_content = selectXMLRecord($article_content_xml, '//content[@id=1]');
                        
                        if(isPost('title')) $art_title = post('title'); else $art_title = toText($article->title);
                        if(isPost('description')) $art_description = post('description'); else $art_description = toText($article->description);
                        if(isPost('keywords')) $art_keywords = post('keywords'); else $art_keywords = toText($article->keywords);
                        if(isPost('slug')) $art_slug = post('slug'); else $art_slug = toText($article->slug);
                        if(isPost('editor')) $art_edit = post('editor'); else $art_edit = toText($article_content->message);
                        if(isPost('notshow')) $art_notshow = intval(post('notshow')); else $art_notshow = intval($article->notshow);
                        if(isPost('templates')) $art_template = post('templates'); else $art_template = toText($article->template);
                        
                        include 'templates/backend/EditArticleTemplate.php';
                    }                    
                    break;
            }
            // Its mean that you can add your own actions for this plugin
            runHook('admin_article_extra_actions');
        } else { // Load main template
            $article = selectXMLRecord($article_xml, "//article",'all');
            include 'templates/backend/ArticleTemplate.php';
        }
    }