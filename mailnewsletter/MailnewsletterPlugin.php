<?php

    /**
     *	Mail Newsletter plugin
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
                   'Mail Newsletter',
                   '1.0.3',            	
                   'Mail Newsletter plugin',
                   'Awilum',           	
                   'http://awilum.webdevart.ru/',
                   'mailnewsletterAdmin',
                   'mailnewsletter');

    
    // Get language file for this plugin
    getPluginLanguage('Mailnewsletter');
    
    // Include Microblog Admin
    getPluginAdmin('Mailnewsletter');


    // Add template hook
    /* use: <?php templateHook('mailnewsletter_subscribe'); ?> */
    addHook('mailnewsletter_subscribe','mailnewsletterSubscribe');

    // Add hooks as component / template hooks
    addHook('mailnewsletter_template','mailnewsletterTemplate',array());
    addHook('mailnewsletter_title','mailnewsletterTitle',array());
    addHook('mailnewsletter_content','mailnewsletterContent',array());

    addHook('theme_header','mailnewsletterHeaders');

    function mailnewsletterTemplate() { return 'index'; }
    function mailnewsletterTitle() { return 'Subcribe'; }


    /**
     * Mailnewsletter Headers
     */
    function mailnewsletterHeaders() {
        echo '<script>
                function mailnewsletterValidateEmail(form_id,email) { 
                var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
                var address = document.forms[form_id].elements[email].value;
                    if(reg.test(address) == false) { alert("'.lang('mailnewsletter_invalid_email').'"); return false; }
                }
              </script>';
    }


    /**
     * Mailnewsletter main frontend function
     */
    function mailnewsletterContent($uri) {
        $mailnewsletter_main_dir  = 'data/mailnewsletter/';
        $mailnewsletter_xml_db = getXMLdb($mailnewsletter_main_dir.'subscribers.xml');

        if(isset($uri[1])) {          
            if($uri[1] == 'unsubscribe') {              
                if(isset($uri[2])) {                  
                    $records = selectXMLRecord($mailnewsletter_xml_db,'subscriber[hash="'.$uri[2].'"]','all');                                                          
                    if(!empty($records)) { 
                      deleteXMLRecord($mailnewsletter_xml_db, 'subscriber', $records[0]['id']);                                       
                      echo lang('mailnewsletter_unsubscribe_done');
                    } 
                }
            }
            if($uri[1] == 'subscribe') {              
                if(isPost('subscribe')) {
                    $hash = substr(md5(post('mailnewsletter').mktime()),0,10);
                    insertXMLRecord($mailnewsletter_xml_db,'subscriber',array('email'=>post('email'),
                                                                              'hash'=>$hash,
                                                                              'date'=>mktime()));
                    $recipient = post('email');
                    $email = getOption('mailnewsletter_sender');                
                    $body = preg_replace(array('/\[unsubscribe_link]/ms'), array(getOption('siteurl').'mailnewsletter/unsubscribe/'.$hash.''), getOption('mailnewsletter_message'));        
                    $subject = 'Subscribe';
                    $header = "From: <" . $email . ">\r\n"; 
                    
                    @mail($recipient, $subject, $body, $header);      
                    
                    redirect(getOption('siteurl'));
                    
                }
            }
        } else {
            // yes, not user friendly...
            // @todo            
        }
    }


    /**
     *  Mailnewsletter subscribe
     */
    function mailnewsletterSubscribe() {        
        $subscribers_count = countXMLRecords(getXMLdb('data/mailnewsletter/subscribers.xml'));        
        include 'templates/frontend/MailNewsLetterSubcribeTemplate.php';
    }
