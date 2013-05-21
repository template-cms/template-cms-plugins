<?php

    /**
     *	Guestbook plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.7
     *
     */


    // For this plugin requires a database.
    // If not, then show the link to create a database
    if(!file_exists('../'.TEMPLATE_CMS_DATA_PATH.'/other/guestbook.xml')) {
        $create_db = '<a href="index.php?id=pages&sub_id=guestbook&flash=create_database">&rarr; create database</a>';
    } else {
        $create_db = '';
    }

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
            getPluginFilename(__FILE__),
            'Guestbook',
            '1.1.6',
            'Guestbook plugin <a href="../guestbook" target="_blank">&rarr; see</a> '.$create_db,
            'Awilum',
            'http://awilum.webdevart.ru/',
            'guestbookAdmin',
            'guestbook');

    // Include language file for this plugin
    getPluginLanguage('Guestbook');


    // Frontend hooks
    addHook('guestbook_content','guestbookContent',array());
    addHook('guestbook_template','guestbookTemplate',array());
    addHook('guestbook_title','guestbookTitle',array());

    // Include Sandbox Admin
    getPluginAdmin('Guestbook');


    /**
     * Set contacts template: indexTemplate.php
     */
    function guestbookTemplate($data) {
        $template_xml = getXML(TEMPLATE_CMS_DATA_PATH.'other/guestbook_template.xml');
        if($template_xml == null) {
            return 'index';
        } else {
            return $template_xml->template;
        }
    }

    /**
     * Get contacts title
     */
    function guestbookTitle($data) {
        echo lang('guestbook_title');
    }

    /**
     * Get contacts contents
     */
    function guestbookContent() {

        
        // Get comments xml database
        $xml_db = getXMLdb(TEMPLATE_CMS_DATA_PATH.'/other/guestbook.xml');

        // Errors array
        $errors = array();

        // Select comments records from database
        if($xml_db) {
            $records = selectXMLRecord($xml_db, "comment", 'all');
            $comments = selectXMLfields($records, array('name','message','date'), 'date', 'ASC');
        }

        // Get guestbook entries template
        include 'templates/frontend/GuestbookTemplate.php';

        // Try send comment
        if(isPost('comment_send')) {

            // Check fields
            if(trim(post('comment_name')) == '') $errors['guestbook_empty_name'] = lang('guestbook_empty_name');
            if(trim(post('comment_email')) == '') {
                $errors['guestbook_empty_email'] = lang('guestbook_empty_email');
            } else {
               if(!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', trim(post('comment_email')))) {
                    $errors['guestbook_wrong_email'] = lang('guestbook_wrong_email');
                }
            }
            if(trim(post('comment_message')) == '') $errors['guestbook_empty_message'] = lang('guestbook_empty_message');            
            
            if((getOption('captcha_installed') !== null) and (getOption('captcha_installed') == 'true'))
                if (!chk_crypt($_POST['code'])) $errors['captcha_robot'] = lang('captcha_robot');

            if(count($errors) == 0) {
                // Insert new record in comments xml datebase
                insertXMLRecord($xml_db, 'comment', array('name'=>post('comment_name'),
                        'email'=>post('comment_email'),
                        'message'=>post('comment_message'),
                        'date'=>time()));
                // Redirect to same page
                redirect(selfUrl());
            }
        }

        // Save fields
        if(isPost('comment_name'))    $post_name  = toText(post('comment_name')); else $post_name = '';
        if(isPost('comment_email'))   $post_email = toText(post('comment_email')); else $post_email = '';
        if(isPost('comment_message')) $post_msg   = toText(post('comment_message')); else $post_msg = '';

        // Get add form template
        include 'templates/frontend/GuestbookAddTemplate.php';

    }
