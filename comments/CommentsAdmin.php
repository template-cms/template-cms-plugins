<?php

    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('comments_submenu'),'comments'));


    /**
     * Comments admin
     */
    function commentsAdmin() {

        $comments_folder      = '../data/other/';
        $comments_file        = 'comments';
        $comments_config_file = 'comments_config';
        $ext                  = '.xml';
        $path                 = $comments_folder.$comments_file.$ext;
        $config_path          = $comments_folder.$comments_config_file.$ext;

        // If comments database exists then try to get comments.
        if(file_exists($path)) {
            $xml_db_comments = getXMLdb($path);            
        } else {            
            createXMLdb($comments_folder.$comments_file);
            $xml_db_comments = getXMLdb($path);            
        }

        if(file_exists($config_path)) {
            $xml_db_comments_config = getXMLdb($config_path);
        } else {
            createXMLdb($comments_folder.$comments_config_file);
            $xml_db_comments_config = getXMLdb($config_path);
            insertXMLRecord($xml_db_comments_config, 'comments_option', array('widget_comments_count'=>'5'));
        }

        if($xml_db_comments) {
            $records  = selectXMLRecord($xml_db_comments,"comment",'all');            
            $comments = selectXMLfields($records, array('page_url','name','email','message','date'),'date','DESC');
        }

        if(isPost('comments_save_options')) {
            updateXMLRecord($xml_db_comments_config, 'comments_option', 1, array('widget_comments_count'=>(int)post('widget_comments_count')));
        }
        

        if (isGet('action')) {
            switch (get('action')) {
                case "delete_comment":
                    deleteXMLRecord($xml_db_comments,'comment',get('comment_id'));
                    redirect('index.php?id=pages&sub_id=comments');
                break;
            }
            // Its mean that you can add your own actions for this plugin
            runHook('admin_comments_extra_actions');
        }
        
        // Load comments template
        include 'templates/backend/CommentsAdminTemplate.php';

    }