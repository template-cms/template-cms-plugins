<?php   
    /**
     * Polls admin
     */
    addHook('admin_header','pollsHeaders');
	
    /**
     * Files manager headers
     */
    function pollsHeaders() {
        echo '
                <style type="text/css">
                    a.poll-def {
                        display:block;
                        backgfround:#F2F2F2;
                        border:-right 1px solid #ccc;
                        color:#999;
                        font-weight:bold;
                        padding:10px;
                        text-align:center;
                        line-height:10px;
                        text-decoration:none;
                        font-size:15px;
                    }
                    a.poll-def:hover {
                        background:#E5DED7;
                        color:#000;
                    }
                    
                    a.poll-def.active {
                        background:#F2F2F2;
                        color:#000
                    }
                </style>
            ';
    }
    function pollsAdmin() {
    
        $polls_type_array = array('one' => lang('polls_type_one'), 
                                  'many' => lang('polls_type_many'));
        
        $polls_path = '../'.TEMPLATE_CMS_DATA_PATH.'polls/';
        
        function savePolls($arr_poll, $arr_answer, $polls_path, $newPoll=false) {
            /**
             * Record data on the poll
             */
            if ($newPoll) {
                createXMLdb($polls_path.'poll');
                createXMLdb($polls_path.'config');
                $config_xml_db = getXMLdb($polls_path.'config.xml');
                insertXMLRecord($config_xml_db, 'config', array('default_poll_id'=>'1'));
            }
            $poll_xml_db = getXMLdb($polls_path.'poll.xml');
            insertXMLRecord($poll_xml_db, 'poll', $arr_poll);
            $last_id_poll = lastXMLRecordId($poll_xml_db, 'poll');
            
            /**
             * Record answer
             */
            if ($newPoll) {
                createXMLdb($polls_path.'answer');
            }
            $answer_xml_db = getXMLdb($polls_path.'answer.xml');
            foreach ($arr_answer as $arr_ans=>$arr_val) {
                insertXMLRecord($answer_xml_db, 'answer', $arr_val, array('poll_id'=>$last_id_poll));
            }
        }
        
        /**
         * If the script is run first time
         * create a test poll
         */
        if(!is_dir($polls_path)) {
        
            mkdir($polls_path, 0755);
            
            /**
             * demo poll #1
             */
            $arr_poll = array('question'=>lang('polls_test_question'),
                              'date'=>date('Y-m-d'),
                              'type'=>'one',
                              'count_votes'=>'32');
                              
            $arr_answer = array(
                array('name'=>lang('polls_test_yes'), 'votes'=>'28'),
                array('name'=>lang('polls_test_no'), 'votes'=>'4')
            );
            
            savePolls($arr_poll, $arr_answer, $polls_path, true);
            
            /**
             * demo poll #2
             */
            $arr_poll = array('question'=>lang('polls_test_question2'),
                              'date'=>date('Y-m-d'),
                              'type'=>'many',
                              'count_votes'=>'20');
                              
            $arr_answer = array(
                array('name'=>lang('polls_test_black'), 'votes'=>'4'),
                array('name'=>lang('polls_test_white'), 'votes'=>'6'),
                array('name'=>lang('polls_test_green'), 'votes'=>'10')
            );
            
            savePolls($arr_poll, $arr_answer, $polls_path);
        }
        
        /**
         * Load configuration
         */
        $config_xml_db = getXMLdb($polls_path.'config.xml');
        $configPolls = selectXMLRecord($config_xml_db, "//config",1);
        $default_poll_id = intval($configPolls[0]->default_poll_id);
        
        if (isGet('action')) {
            switch (get('action')) {
                case 'add':
                    if(isPost('add_poll')) {
                        /**
                         * Add poll
                         */
                        $arr_poll = array('question'=>post('poll_question'),
                                          'date'=>date('Y-m-d'),
                                          'type'=>post('poll_type'),
                                          'count_votes'=>'0');

                        $answers = explode("\r\n", post('poll_answers'));
                        
                        foreach ($answers as $ans_key => $ans_val) {
                            $arr_answer[$ans_key]['name'] = $ans_val;
                            $arr_answer[$ans_key]['votes'] = 0;
                        }
            
                        savePolls($arr_poll, $arr_answer, $polls_path);
                        redirect('index.php?id=pages&sub_id=polls');
                    }
                    include 'templates/backend/AddPollTemplate.php';
                    break;
                case 'default':
                    if(isGet('poll_id')) {
                        /**
                         * Poll show by default
                         */
                        updateXMLRecordWhere($config_xml_db, '//config[@id=1]', 
                                                    array('default_poll_id'=>get('poll_id')));
                    }
                    redirect('index.php?id=pages&sub_id=polls');
                    break;
                case 'edit':
                    if(isGet('poll_id')) {
                        /**
                         * Edit poll
                         */
                        $poll_xml_db = getXMLdb($polls_path.'poll.xml');
                        $answer_xml_db = getXMLdb($polls_path.'answer.xml');
                        
                        if(isGet('answer_id')) {
                            /**
                             * Delete answer
                             */
                            deleteXMLRecord($answer_xml_db,'answer',get('answer_id'));
                            redirect('index.php?id=pages&sub_id=polls&action=edit&poll_id='.get('poll_id'));
                        } elseif (isPost('answer_add_name') and post('answer_add_name') != '') {
                            /**
                             * Add answer
                             */
                            insertXMLRecord($answer_xml_db, 'answer', array('name'=>post('answer_add_name'), 'votes'=>'0'), 
                                                                      array('poll_id'=>get('poll_id')));
                            redirect('index.php?id=pages&sub_id=polls&action=edit&poll_id='.get('poll_id'));
                        } elseif (isPost('poll_question') and post('poll_question') != '') {
                            /**
                             * Saving after editing
                             */
                            
                            /**
                             * Counting the votes
                             */
                            $poll_answer_votes = post('poll_answer_votes');
                            $poll_answer_name = post('poll_answer');
                            
                            $poll_votes_count = 0;
                            foreach ($poll_answer_votes as $poll_ans_id =>$poll_ans_votes) {
                                $poll_votes_count += intval($poll_ans_votes);
                                
                                updateXMLRecordWhere($answer_xml_db, '//answer[@id='.$poll_ans_id.']', 
                                                    array('name'=>$poll_answer_name[$poll_ans_id], 
                                                          'votes'=>intval($poll_ans_votes)));
                            }
                            
                            updateXMLRecord($poll_xml_db, 'poll', get('poll_id'), array('question'=>post('poll_question'), 
                                                                                        'type'=>post('poll_type'),
                                                                                        'count_votes'=>$poll_votes_count));
                                                                                        
                            redirect('index.php?id=pages&sub_id=polls');
                        }
                        
                        /**
                         * Download data on the poll
                         */
                        $poll = selectXMLRecord($poll_xml_db, '//poll[@id='.get('poll_id').']');
                        
                        /**
                         * Download answers
                         */
                        $answers = selectXMLRecord($answer_xml_db, '//answer[@poll_id='.get('poll_id').']', 'all');
                        
                        include 'templates/backend/EditPollTemplate.php';
                    } else {
                        redirect('index.php?id=pages&sub_id=polls');
                    }
                    break;
                case 'delete': 
                    if(isGet('poll_id')) {
                        /**
                         * Delete question
                         */
                        $poll_xml_db = getXMLdb($polls_path.'poll.xml');
                        deleteXMLRecord($poll_xml_db,'poll',get('poll_id'));
                        
                        /**
                         * Delete answers
                         */
                        $answer_xml_db = getXMLdb($polls_path.'answer.xml');
                        $answer_xml_arr = selectXMLRecord($answer_xml_db, '//answer[@poll_id='.get('poll_id').']', 'all');
                        if(count($answer_xml_arr) !== 0) {
                            for($i=0; $i<=count($answer_xml_arr); $i++) {
                                deleteXMLRecordWhere($answer_xml_db, '//answer[@poll_id='.get('poll_id').']');
                            }
                        }
                    }
                    redirect('index.php?id=pages&sub_id=polls');
                    break;
            }
        } else {
            $poll_xml_db = getXMLdb($polls_path.'poll.xml');
            $polls = selectXMLRecord($poll_xml_db, "//poll",'all');
            include 'templates/backend/PollsTemplate.php';
        }
    }