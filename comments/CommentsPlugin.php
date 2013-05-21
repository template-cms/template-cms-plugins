<?php

    /**
     *	Comments plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.0.3
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Comments',
                   '1.0.3',
                   'Comments plugin <a href="index.php?id=pages&sub_id=comments">&rarr; admin</a>',
                   'Awilum',           	      
                   'http://awilum.webdevart.ru/',
                   'commentsAdmin');      	


    // Get language file for this plugin
    getPluginLanguage('Comments');

    // Include Sandbox Admin
    getPluginAdmin('Comments');

    // Add template hook
    /* use: <?php templateHook('comments_load'); ?> */
    addHook('comments_load','getComments');
    /* use: <?php templateHook('comments_load_last'); ?> */
    addHook('comments_load_last','getCommentsLast');

    // Add template hook
    addHook('theme_header','commentsHeaders');


    /**
     * Get comments
     */
    function getComments() {
        $comments_folder   = 'data/other/';
        $comments_file     = 'comments';
        $ext               = '.xml';
        $path              = $comments_folder.$comments_file.$ext;

        $xml_db_comments = getXMLdb($path);

        if($xml_db_comments) {
            $records  = selectXMLRecord($xml_db_comments,"/root/comment[page_url='".curUrl()."']",'all');
            $comments = selectXMLfields($records, array('page_url','name','email','message','date'),'date','DESC');
            $count = count($comments);
        }

        
        // Errors array
        $errors = array();

        if(isPost('send_comment')) {

            // Check fields
            if(trim(post('comments_name')) == '') $errors['comments_empty_name'] = lang('comments_empty_name');
            if(trim(post('comments_email')) == '') {
                $errors['comments_empty_email'] = lang('comments_empty_email');
            } else {
                if(!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', trim(post('comments_email')))) {
                    $errors['comments_wrong_email'] = lang('comments_wrong_email');
                }
            }
            if(trim(post('comments_message')) == '') $errors['comments_empty_message'] = lang('comments_empty_message');
            if(!isPost('i_am_not_a_robot')) $errors['guestbook_robot'] = lang('comments_robot');

            //If no errors then try to insert new record
            if(count($errors) == 0) {
                // Insert new comment
                insertXMLRecord($xml_db_comments, 'comment', array('page_url'=>curUrl(),
                                                                   'name'=>post('comments_name'),
                                                                   'email'=>post('comments_email'),
                                                                   'message'=>post('comments_message'),
                                                                   'date'=>time()));
                // Redirect to same page
                redirect(selfUrl());
            }       
        }
		
		// Save fields
		if(isPost('comments_name'))    $post_name  = toText(post('comments_name'));    else $post_name = '';
		if(isPost('comments_email'))   $post_email = toText(post('comments_email'));   else $post_email = '';
		if(isPost('comments_message')) $post_msg   = toText(post('comments_message')); else $post_msg = '';

        include 'templates/frontend/CommentsPageTemplate.php';
    }

    /**
     * Get last comments
     */
    function getCommentsLast() {
        $comments_folder      = 'data/other/';
        $comments_file        = 'comments';
        $ext                  = '.xml';
        $path                 = $comments_folder.$comments_file.$ext;
        $comments_config_file = 'comments_config';
        $config_path          = $comments_folder.$comments_config_file.$ext;
        

        $xml_db_comments = getXMLdb($path);

        $xml_db_comments_config = getXMLdb($config_path);

        if($xml_db_comments) {
            $records  = selectXMLRecord($xml_db_comments,"comment",$xml_db_comments_config['xml_object']->comments_option->widget_comments_count);
            $comments = selectXMLfields($records, array('page_url','name','email','message','date'),'date','DESC');
        }

        include 'templates/frontend/CommentsLastTemplate.php';
    }

    /**
     * Comments themes header styles
     */
    function commentsHeaders() {
        echo '<style>';
        echo compressCSS('.comment {
                            -moz-border-radius:2px;
                            -webkit-border-radius:2px;
                            border-radius:2px;
                            border: 1px solid #D5D5D5;
                            padding:5px;
                            margin: 10px 0 10px 0;
                            color: #5F5F5F;
                        }
                        .comment-header {
                            border-bottom: 1px solid #D5D5D5;
                            height:24px;
                        }
                        .comment-author {
                            font-weight: 700;
                            float: left;
                        }
                        .comment-date {
                            float: right;
                            font-size:0.8em;
                            color:#999;
                        }
                        .comment-body {
                            padding:5px;
                        }');
        echo'</style>';
    }