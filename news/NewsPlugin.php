<?php

    /**
     *	News plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.9
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'News',
                   '1.1.8',
                   'News manager plugin for Template CMS 2 <a href="../news" target="_blank">&rarr; see</a> <a href="index.php?id=pages&sub_id=news">&rarr; admin</a>',
                   'Awilum',
                   'http://awilum.webdevart.ru/',
                   'newsAdmin',
                   'news');



    // Get language file for this plugin
    getPluginLanguage('News');
	
    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('news_news'),'news'));

    // Add hooks as component / template hooks
    addHook('news_content','newsContent',array());
    addHook('news_title','newsTitle',array());
    addHook('news_keywords','newsKeywords',array());
    addHook('news_description','newsDescription',array());
    addHook('news_template','newsTemplate',array());

    // Add theme header
    addHook('theme_header','newsThemeHeader');

    // Add template hook
    /* use: <?php templateHook('news_last'); ?> */
    addHook('news_last','getLastNews');


    // Add hooks to setup Simple Gallery form template
    addHook('admin_themes_extra_template_actions','newsTemplateComponent');
    addHook('admin_themes_extra_actions','newsTemplateComponentSave');

    // Include Sandbox Admin
    getPluginAdmin('News');


    /**
     * News themes headers
     */
    function newsThemeHeader() {
        echo '<style>';
        echo compressCSS('#news {
                    width: 100%;
                }
                .news-subject {
                    color:#453A2D;
                    font-weight:bold;
                    font-size:1.2em;                
                }
                .news-body {                
                    padding:5px;
                }
                .news-bottom {
                    margin-bottom:10px;
                    border-bottom:1px dashed #8E9FA5;
                }
                .news-date {
                    border-top:1px dashed #ccc;                    
                    padding: 5px;
                }
                .news-comment-name {
                    color:#453A2D;
                    font-weight:bold;
                    font-size:1.2em;    
                }
                .news-comment-body {
                    padding:5px;
                }
                .news-comment-date {
                    border-top:1px dashed #ccc;
                    padding: 5px;
                }
                .news-comment-bottom {
                    margin-bottom:10px;
                    border-bottom:1px dashed #8E9FA5;
                }
                .news-comment-titles {
                    color:#453A2D;
                    font-weight:bold;
                    font-size:1.5em;
                    margin-bottom:10px;
                }');
             echo '</style>';
    }

    /**
     * Get news database
     * @return object
     */
    function getNewsDatabase() {
        // News database folder
        $news_entries_dir = 'data/news/';

        // News entries database
        $news_entries_file = $news_entries_dir.'news_entries';

        // Database ext
        $ext = '.xml';

        // Get XML database
        $xml_db_entries = getXMLdb($news_entries_file.$ext);

        return $xml_db_entries;
    }

    /**
     * Get comments database
     * @return object
     */
    function getNewsCommentsDatabase() {
        // News comments database folder
        $news_comments_dir = 'data/news/';

        // News entries database
        $news_comments_file = $news_comments_dir.'news_comments';

        // Database ext
        $ext = '.xml';

        // Get XML database
        $xml_db_comments = getXMLdb($news_comments_file.$ext);

        return $xml_db_comments;
    }

    /**
     * Error page
     */
    function newsErrorPage() {
        $pages_xml = getXML(TEMPLATE_CMS_DATA_PATH.'pages/error404.xml');
        echo $pages_xml->content;
    }

    /**
     * Get news content
     * @param array $data uri data
     */
    function newsContent($uri) {        

        // Get XML database
        $xml_db_entries = getNewsDatabase();

        if(isset($uri[1])) {
            if($uri[1] == 'archive') {
                getNewsArchiveContent($xml_db_entries);
            } else {
                if($uri[1] == 'category') {
                    if(isset($uri[2])) {
                        if($uri[2] !== '') {
                            getNewsCategoryContent($xml_db_entries,$uri[2]);
                        }
                    }
                } else {
                    $id = (int)$uri[1];
                    $records = selectXMLRecord($xml_db_entries,"//news_entry[@id='".$id."']",'all');
                    $news_entries = selectXMLfields($records, array('id','name','title','category_name','category_slug','full','date'),'date','ASC');

                    if(count($news_entries) > 0) {
                        foreach($news_entries as $entry) {
                            if($uri[2] == $entry['name'] && $id == $entry['id'] && count($uri) < 4) {
                                getNewsContent($xml_db_entries,$entry);
                            } else {
                                newsErrorPage();
                                statusHeader(404);
                            }
                        }
                    } else {
                        newsErrorPage();
                        statusHeader(404);
                    }
                }
            }         
        } else {
            // News options database
            $news_options_file = 'data/news/news_options';            
            // Get XML database
            $xml_db_options = getXMLdb($news_options_file.'.xml');
            if($xml_db_options) {
                $news_options = selectXMLRecord($xml_db_options, "news_option", 'all');
                getLastNewsContent($xml_db_entries,$news_options[0]->news_per_page);
            }
        }
    }


    /**
     * Get news title
     * @param array $data uri data
     * @return string
     */
    function newsTitle($uri) {

        // Get XML database
        $xml_db_entries = getNewsDatabase();

        if(isset($uri[1])) {
            if($uri[1] == 'archive') {
                return lang('news_archive');
            } else {
                 if($uri[1] == 'category') {
                    if(isset($uri[2])) {
                        if($uri[2] !== '') {
                            return 'News - Category: '.$uri[2];
                        }
                    }
                 } else {
                     $id = (int)$uri[1];
                     $records = selectXMLRecord($xml_db_entries,"//news_entry[@id='".$id."']",'all');
                     $news_entries = selectXMLfields($records, array('id','name','title'),'id','ASC');

                     if(count($news_entries) > 0) {
                        foreach($news_entries as $entry) {
                             if($uri[2] == $entry['name'] && $id == $entry['id'] && count($uri) < 4) {
                                 return $entry['title'];
                             } else {
                                 return 'error 404';
                                 statusHeader(404);
                             }
                        }
                     } else {
                        return 'error 404';
                        statusHeader(404);
                     }
                }
            }
        } else {
            return lang('news_last');
        }

    }


    /**
     * Get news keywords
     * @param array $data uri data
     * @return string
     */
    function newsKeywords($uri) {

        // Get XML database
        $xml_db_entries = getNewsDatabase();

        if(isset($uri[1])) {
            if($uri[1] == 'archive') {
                return lang('news_archive');
            } else {
                if($uri[1] == 'category') {
                    if(isset($uri[2])) {
                        if($uri[2] !== '') {
                            return 'news,category,'.$uri[2];
                        }
                    }
                } else {
                $id = (int)$uri[1];
                $records = selectXMLRecord($xml_db_entries,"//news_entry[@id='".$id."']",'all');
                $news_entries = selectXMLfields($records, array('id','name','keywords'),'id','ASC');

                if(count($news_entries) > 0) {
                    foreach($news_entries as $entry) {
                        if($uri[2] == $entry['name'] && $id == $entry['id'] && count($uri) < 4) {
                            return $entry['keywords'];
                        } else {
                            return 'error 404';
                            statusHeader(404);
                        }
                    }
                } else {
                    return 'error 404';
                    statusHeader(404);
                }
            }
            }
        } else {
            return lang('news_last');
        }
    }

    /**
     * Get news description
     * @param array $data uri data
     * @return string
     */
    function newsDescription($uri) {

        // Get XML database
        $xml_db_entries = getNewsDatabase();

        if(isset($uri[1])) {
            if($uri[1] == 'archive') {
                return lang('news_archive');
            } else {
                if($uri[1] == 'category') {
                    if(isset($uri[2])) {
                        if($uri[2] !== '') {
                            return 'news,category,'.$uri[2];
                        }
                    }
                } else {
                $id = (int)$uri[1];
                $records = selectXMLRecord($xml_db_entries,"//news_entry[@id='".$id."']",'all');
                $news_entries = selectXMLfields($records, array('id','name','description'),'id','ASC');

                if(count($news_entries) > 0) {
                    foreach($news_entries as $entry) {

                        if($uri[2] == $entry['name'] && $id == $entry['id'] && count($uri) < 4) {
                            return $entry['description'];
                        } else {
                            return 'error 404';
                            statusHeader(404);
                        }
                    }
                } else {
                    return 'error 404';
                    statusHeader(404);
                }
            }
            }
        } else {
            return lang('news_last');
        }
    }

    /**
     * Get content for page last news
     * @param array $xml_db_entries xml database entries
     * @param integer $limit news limit
     */
    function getLastNewsContent($xml_db_entries,$limit) {        
        $records = selectXMLRecord($xml_db_entries,"news_entry",$limit);
        $news_entries = selectXMLfields($records, array('id','name','title','category_name','category_slug','short','date'),'date','DESC');
        include 'templates/frontend/NewsLastTemplate.php';
    }


    /**
     * Get comments count for current news
     * @param integer $id id of entries
     * @return integer
     */
    function newsGetCommentsCount($id) {
	$xml_db_comments = getXMLdb('data/news/news_comments.xml');
        $records = selectXMLRecord($xml_db_comments,"//news_comment[entry_id=$id]",'all');
        $note_comments = selectXMLfields($records, array('id','entry_id'),'id','DESC');
        return count($note_comments);
    }


    /**
     * Get content for current news
     * @param array $xml_db_entries xml database entries
     */
    function getNewsContent($xml_db_entries, $entry) {
        // Get comments for current article
        $xml_db_comments = getNewsCommentsDatabase();        
        $id = $entry['id'];

        $records = selectXMLRecord($xml_db_comments,"//news_comment[entry_id=$id]",'all');
        $news_comments = selectXMLfields($records, array('id','name','message','date'),'date','ASC');

        $errors = array();
        
        if(isPost('news_comment_send')) {

            if(trim(post('news_comment_name')) == '')    $errors['news_comment_empty_name']    = lang('news_comments_empty_name');
            if(trim(post('news_comment_message')) == '') $errors['news_comment_empty_message'] = lang('news_comments_empty_message');
            
            if((getOption('captcha_installed') !== null) and (getOption('captcha_installed') == 'true')) {
                if (!chk_crypt($_POST['code'])) $errors['captcha_robot'] = lang('captcha_robot');
            }

            if(trim(post('news_comment_email')) == '') {
                $errors['news_comment_empty_email'] = lang('news_comments_empty_email');
            } else {
                if(!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', trim(post('news_comment_email')))) {
                    $errors['news_wrong_email'] = lang('news_wrong_email');
                }
            }

            if(count($errors) == 0) {
                insertXMLRecord($xml_db_comments, 'news_comment', array('entry_id'=>post('entry_id'),
                                                                        'name'=>post('news_comment_name'),
                                                                        'email'=>post('news_comment_email'),
                                                                        'message'=>post('news_comment_message'),
                                                                        'date'=>time()));
                redirect(selfUrl());
            }
           
        }
		
		if(isPost('news_comment_name'))    $post_name    = toText(post('news_comment_name'));    else $post_name = '';
		if(isPost('news_comment_email'))   $post_email   = toText(post('news_comment_email'));   else $post_email = '';
		if(isPost('news_comment_message')) $post_message = toText(post('news_comment_message')); else $post_message = '';
		
        include 'templates/frontend/NewsCurrentTemplate.php';
    }

    /**
     * Get news archive content
     * @param array $xml_db_entries xml database entries
     */
    function getNewsArchiveContent($xml_db_entries) {
        $records = selectXMLRecord($xml_db_entries,"news_entry",'all');
        $news_entries = selectXMLfields($records, array('id','name','title','date'),'date','DESC');
        include 'templates/frontend/NewsArchiveTemplate.php';
    }

    /**
     * Get content for category
     * @param array $xml_db_entries
     * @param string $category_slug
     */
    function getNewsCategoryContent($xml_db_entries, $category_slug) {
        $records = selectXMLRecord($xml_db_entries,"/root/news_entry[category_slug='".$category_slug."']",'all');
        $news_entries = selectXMLfields($records, array('id','name','category_name','category_slug','title','date'),'date','DESC');
        include 'templates/frontend/NewsCategoryTemplate.php';
    }


    /**
     * Get last news to custom block
     */
    function getLastNews() {
        // News options database
        $news_options_file = 'data/news/news_options';
        // Get XML database
        $xml_db_options = getXMLdb($news_options_file.'.xml');
        // Get records
        $news_options = selectXMLRecord($xml_db_options, "news_option", 'all');
        // Get XML database
        $xml_db_entries = getNewsDatabase();
        // Get records and fields
        $records = selectXMLRecord($xml_db_entries,"news_entry",$news_options[0]->news_last_count);
        $news_entries = selectXMLfields($records, array('id','name','title','short','date'),'date','DESC');
        include 'templates/frontend/NewsLastBlockTemplate.php';                
    }
    

    /**
     * Get news template
     */
    function newsTemplate($data) {
        $template_xml = getXML(TEMPLATE_CMS_DATA_PATH.'other/news_template.xml');
        if($template_xml == NULL) {
            return 'index';
        } else {
            return $template_xml->template;
        }
    }