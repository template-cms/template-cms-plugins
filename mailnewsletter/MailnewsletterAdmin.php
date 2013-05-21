<?php

    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('mailnewsletter_submenu'),'mailnewsletter'));
    addHook('admin_header','mailnewsletterAdminHeaders');


    /**
     * Mailnewsletter Admin Headers
     */
    function mailnewsletterAdminHeaders() {

        // JS and hacked styles    
        echo '
              <script>
                $(document).ready(function() {
					// Select subscriber
                    $(".mailto").click(function() {                        
                        var val = $("[name=recipients]").val() + $(this).attr("rel") + ",";                        
                        $("[name=recipients]").val(val);
                    });             

					// Select all subscribers
					$(".mailto_select_all").click(function() {
                        var arr = $(".mailto").get();
						var val = "";
						for(i=0;i<arr.length;i++) {
							val = val + $(arr[i]).attr("rel") + ",";
						}
                    	$("[name=recipients]").val(val);
					});
                });                
              </script>
              <style>				
                  #mailnewsletter-add {
                      margin-left: -140px!important;
                      width: 260px!important;
                  }

 				  .admin-table-tr:hover {
					 background:#fff!important; 	
 				  }
              </style>
              ';
    }


    /**
     * Mailnewsletter install
     */
	function mailnewsletterInstall() {
		$mailnewsletter_main_dir  = '../data/mailnewsletter/';

		// Create subscribers,letters directory and database	
		if(!is_dir($mailnewsletter_main_dir))  mkdir($mailnewsletter_main_dir, 0755);
		createXMLdb($mailnewsletter_main_dir.'subscribers');
		createXMLdb($mailnewsletter_main_dir.'letters');

        $user = selectXMLRecord(getXMLdb('../data/users/users.xml'), "//user[@id='".$_SESSION['user_id']."']");

		// Add main Mail newsletter options
        addOption('mailnewsletter_sender',$user->email);
        addOption('mailnewsletter_message','Link to unsubscribe: [unsubscribe_link]');
	}


    /**
     * Mailnewsletter admin function
     */
    function mailnewsletterAdmin() {
        $mailnewsletter_main_dir  = '../data/mailnewsletter/';
        $mailnewsletter_subscribers_xml_db = getXMLdb($mailnewsletter_main_dir.'subscribers.xml');
        $mailnewsletter_letters_xml_db = getXMLdb($mailnewsletter_main_dir.'letters.xml');

		// Send ok
		if(isGet('send') && get('send') == 'done') flashMessage(lang('mailnewsletter_done_successful'));		

        // Check for get actions
        if (isGet('action')) {
            // Switch actions
            switch (get('action')) {
                case "delete_subscriber":
                    $id = (int)get('subscriber_id');
                    deleteXMLRecord($mailnewsletter_subscribers_xml_db, 'subscriber', $id);
                    redirect('index.php?id=pages&sub_id=mailnewsletter');
                break;
                case "delete_subscribers":
					$records = selectXMLRecord($mailnewsletter_subscribers_xml_db, 'subscriber', 'all');
					foreach($records as $record) {
	                    deleteXMLRecord($mailnewsletter_subscribers_xml_db, 'subscriber', $record[0]['id']);
					}
                    redirect('index.php?id=pages&sub_id=mailnewsletter');
                break;	
                case "delete_letter":
                    $id = (int)get('letter_id');
                    deleteXMLRecord($mailnewsletter_letters_xml_db, 'letter', $id);
                    redirect('index.php?id=pages&sub_id=mailnewsletter');
                break;
                case "delete_letters":
					$records = selectXMLRecord($mailnewsletter_letters_xml_db, 'letter', 'all');
					foreach($records as $record) {
	                    deleteXMLRecord($mailnewsletter_letters_xml_db, 'letter', $record[0]['id']);
					}
                    redirect('index.php?id=pages&sub_id=mailnewsletter');
                break;	
				case "letter":
					$letter_uid = get('uid');
					$letter_record = selectXMLRecord($mailnewsletter_letters_xml_db,'letter[uid="'.$letter_uid.'"]','all');
					$letter_uid = $letter_record[0]->uid;
					$letter_recipients = $letter_record[0]->recipients;
					$letter_subject = $letter_record[0]->subject;
					$letter_body = $letter_record[0]->body;

					// Get subscribers
				    if((isset($mailnewsletter_subscribers_xml_db)) and ($mailnewsletter_subscribers_xml_db !== false)) {
				        $subscribers_records = selectXMLRecord($mailnewsletter_subscribers_xml_db,'subscriber','all');
				    }          

					// Get letters
				    if((isset($mailnewsletter_letters_xml_db)) and ($mailnewsletter_letters_xml_db !== false)) {
				        $letters_records = selectXMLRecord($mailnewsletter_letters_xml_db,'letter','all');
				    }          

				    
					// Get subscribers count
					$subscribers_count = count($subscribers_records);


					// Get letters count
					$letters_count = count($letters_records);

					// Load template
				    include 'templates/backend/MailNewsLetterTemplate.php';

				break;			
            }   
        } else {

			if(isPost('mailnewsletter_save')) {
				if(post('uid') !== '0') { 
					updateXMLRecordWhere($mailnewsletter_letters_xml_db,'letter[uid="'.post('uid').'"]',array('recipients'=>post('recipients'),
																											  'subject'=>post('subject'),                                                                          	  
																											  'body'=>post('body'),
																											  'date'=>mktime()));	
					redirect('index.php?id=pages&sub_id=mailnewsletter&action=letter&uid='.post('uid'));					
				} else {
					$letter_uid = substr(md5(post('mailnewsletter').mktime()),0,10);
    	            insertXMLRecord($mailnewsletter_letters_xml_db,'letter',array('uid'=>$letter_uid,
																			  'recipients'=>post('recipients'),
																			  'subject'=>post('subject'),                                                                          	  
																			  'body'=>post('body'),						
		                                                                      'date'=>mktime()));

					redirect('index.php?id=pages&sub_id=mailnewsletter&action=letter&uid='.$letter_uid);
				}                
			}

            // Send newsletter
            if(isPost('send')) {
                $recipients[] = explode(',',post('recipients'));         
               
                $email = getOption('mailnewsletter_sender');
                $subject = post('subject');
                $header = "From: <" . $email . ">\r\n"; 

                foreach($recipients[0] as $recipient) {  
                    if($recipient !== '') {
						// @todo CEHCK!	
						$recipient_record = selectXMLRecord($mailnewsletter_subscribers_xml_db,'subscriber[email="'.$recipient.'"]');
		                $body = preg_replace(array('/\[unsubscribe_link]/ms'), array(getOption('siteurl').'mailnewsletter/unsubscribe/'.$recipient_record[0]->hash.''), post('body'));        
						@mail($recipient, $subject, $body, $header); 
					}
                }


				if(post('uid') !== '0') { 
					updateXMLRecordWhere($mailnewsletter_letters_xml_db,'letter[uid="'.post('uid').'"]',array('recipients'=>post('recipients'),
																											  'subject'=>post('subject'),                                                                          	  
																											  'body'=>post('body'),
 																											  'date'=>mktime()));	
					redirect('index.php?id=pages&sub_id=mailnewsletter&action=letter&uid='.post('uid').'&send=done');					
				} else {
					$letter_uid = substr(md5(post('mailnewsletter').mktime()),0,10);
    	            insertXMLRecord($mailnewsletter_letters_xml_db,'letter',array('uid'=>$letter_uid,
																			  'recipients'=>post('recipients'),
																			  'subject'=>post('subject'),                                                                          	  
																			  'body'=>post('body'),						
		                                                                      'date'=>mktime()));

					redirect('index.php?id=pages&sub_id=mailnewsletter&action=letter&uid='.$letter_uid.'&send=done');
				} 

            }


			// Add new subscriber and send email to him	
            if(isPost('add_subcriber')) {
                $hash = substr(md5(post('mailnewsletter').mktime()),0,10);
                insertXMLRecord($mailnewsletter_subscribers_xml_db,'subscriber',array('email'=>post('mailnewsletter'),
                                                                          			  'hash'=>$hash,
		                                                                              'date'=>mktime()));
                
                $recipient = post('mailnewsletter');                                                                                               
                $email = getOption('mailnewsletter_sender');                
                $body = preg_replace(array('/\[unsubscribe_link]/ms'), array(getOption('siteurl').'mailnewsletter/unsubscribe/'.$hash.''), getOption('mailnewsletter_message'));        
                $subject = 'Subscribe';
                $header = "From: <" . $email . ">\r\n"; 
                
                @mail($recipient, $subject, $body, $header);                                                                       
            }

			// Save plugin options
            if(isPost('mailnewsletter_save_options')) {
                updateOption('mailnewsletter_sender',post('email'));        
                updateOption('mailnewsletter_message',post('message'));
            }

			// Get subscribers
            if((isset($mailnewsletter_subscribers_xml_db)) and ($mailnewsletter_subscribers_xml_db !== false)) {
                $subscribers_records = selectXMLRecord($mailnewsletter_subscribers_xml_db,'subscriber','all');
            }          

			// Get letters
            if((isset($mailnewsletter_letters_xml_db)) and ($mailnewsletter_letters_xml_db !== false)) {
                $letters_records = selectXMLRecord($mailnewsletter_letters_xml_db,'letter','all');
            }          

            
			// Get subscribers count
			$subscribers_count = count($subscribers_records);


			// Get letters count
			$letters_count = count($letters_records);

			if(!isset($letter_uid)) $letter_uid = 0;
			if(!isset($letter_recipients)) $letter_recipients = '';
			if(!isset($letter_subject)) $letter_subject = '';
			if(!isset($letter_body)) $letter_body = '';

			// Load template
            include 'templates/backend/MailNewsLetterTemplate.php';
        }
             
       
    }


	
