<?php

    /**
     *	Contacts plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 Romanenko Sergey / Awilum
     *	@version 1.1.5
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Contacts',
                   '1.1.4',
                   'Contact form plugin. <a href="../contacts" target="_blank">&rarr; see</a>',
                   'Awilum',
                   'http://awilum.webdevart.ru/',
                   '',
                   'contacts');
				
    // Include language file for this plugin
    getPluginLanguage('Contacts');

    // Frontend hooks
    addHook('contacts_content','contactsContent',array());
    addHook('contacts_template','contactsTemplate',array());
    addHook('contacts_title','contactsTitle',array());
	
    // Add template hook
    /* use: <?php templateHook('contacts_block'); ?> */
    addHook('contacts_block','contactsBlock');

    // Include Sandbox Admin
    getPluginAdmin('Contacts');


    /**
     * Set contacts template: indexTemplate.php
     */
    function contactsTemplate($data) {
        $template_xml = getXML(TEMPLATE_CMS_DATA_PATH.'other/contact_template.xml');
        if($template_xml == null) {
            return 'index';
        } else {
            return $template_xml->template;
        }
    }

    /**
     * Get contacts title
     */
    function contactsTitle($data) {
        echo lang('contacts_form');
    }

    /**
     * Get contacts contents
     */
    function contactsContent() {

        $system_contact_xml = getXML(TEMPLATE_CMS_DATA_PATH.'system/contact.xml');

        $errors = array();
		
		$letter_sent = false;
        if(isPost('contacts_send')) {
            if(trim(post('contacts_subject')) == '') $errors['empty_name'] = lang('empty_name');
            if(trim(post('contacts_contact')) == '') {
                $errors['empty_contact'] = lang('empty_contact');
            } else {
                if(!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', trim(post('contacts_contact')))) {
                    $errors['contacts_wrong_email'] = lang('contacts_wrong_email');
                }
            }
            if(trim(post('contacts_msg')) == '') $errors['empty_msg'] = lang('empty_msg');
            if(!isPost('i_am_not_a_robot')) $errors['robot'] = lang('robot');

            if(count($errors) == 0) {

                $headers  = "From: ".post('contacts_contact')."\r\n";
                $headers .= "Reply-To: ".post('contacts_contact')."\r\n";
                $headers .= "Return-Path: ".post('contacts_contact')."\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";

                @mail($system_contact_xml->contact,'=?UTF-8?B?'.base64_encode(post('contacts_subject')).'?=',post('contacts_msg'),$headers);

                $letter_sent = true;
                echo '<div style="padding:10px; border:1px solid #F0F0F0; color:#4F4F4F">';
                echo '<script>
                        function delayer(){
                            window.location = "contacts"
                        }
                        setTimeout("delayer()", 1000);
                     </script>';
                echo lang('letter_sent');
                echo '</div>';

            }
        }


        if(isPost('contacts_subject')) $post_name = toText(post('contacts_subject')); else $post_name = '';
        if(isPost('contacts_contact')) $post_contact = toText(post('contacts_contact')); else $post_contact = '';
        if(isPost('contacts_msg'))     $post_msg = toText(post('contacts_msg')); else $post_msg = '';

        if($letter_sent == false) {
			include 'plugins/contacts/templates/frontend/ContactsTemplate.php';
        }
    }
	
	/** 
	 * Get contact form block
	 * clone of contactsContent()
	 */
	function contactsBlock() {
        $system_contact_xml = getXML(TEMPLATE_CMS_DATA_PATH.'system/contact.xml');

        $errors = array();
		
		$letter_sent = false;
        if(isPost('contacts_send')) {
            if(trim(post('contacts_subject')) == '') $errors['empty_name'] = lang('empty_name');
            if(trim(post('contacts_contact')) == '') {
                $errors['empty_contact'] = lang('empty_contact');
            } else {
                if(!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', trim(post('contacts_contact')))) {
                    $errors['contacts_wrong_email'] = lang('contacts_wrong_email');
                }
            }
            if(trim(post('contacts_msg')) == '') $errors['empty_msg'] = lang('empty_msg');
            if(!isPost('i_am_not_a_robot')) $errors['robot'] = lang('robot');

            if(count($errors) == 0) {

                $headers  = "From: ".post('contacts_contact')."\r\n";
                $headers .= "Reply-To: ".post('contacts_contact')."\r\n";
                $headers .= "Return-Path: ".post('contacts_contact')."\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8\r\n";

                @mail($system_contact_xml->contact,'=?UTF-8?B?'.base64_encode(post('contacts_subject')).'?=',post('contacts_msg'),$headers);

                $letter_sent = true;
                echo '<div style="padding:10px; border:1px solid #F0F0F0; color:#4F4F4F">';
                echo '<script>
                function delayer(){
                window.location = location.href
                }
                setTimeout("delayer()", 1000);
                </script>';
                echo lang('letter_sent');
                echo '</div>';
                

            }
        }


        if(isPost('contacts_subject')) $post_name = toText(post('contacts_subject')); else $post_name = '';
        if(isPost('contacts_contact')) $post_contact = toText(post('contacts_contact')); else $post_contact = '';
        if(isPost('contacts_msg'))     $post_msg = toText(post('contacts_msg')); else $post_msg = '';

        if($letter_sent == false) {
			include 'plugins/contacts/templates/frontend/ContactsBlockTemplate.php';
        }		
	}

