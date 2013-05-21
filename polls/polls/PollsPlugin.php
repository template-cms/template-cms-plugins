<?php

    /**
     *  Polls plugin
     *  @package TemplateCMS
     *  @subpackage Plugins
     *  @author Yudin Evgeniy / Promo360
     *  @copyright 2011 Yudin Evgeniy / Promo360
     *  @version 1.0
     *
     */

    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   'Polls',
                   '1.0',
                   'Polls plugin. <a href="index.php?id=pages&sub_id=polls">&rarr; admin</a> <a href="../polls" target="_blank">&rarr; see</a>',
                   'Promo360',
                   'http://promo360.ru/',
                   'pollsAdmin',
                   'polls');

    // Include language file for this plugin
    getPluginLanguage('Polls');
    
    // Add hooks NAVIGATION
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('polls_name'),'polls'));
    
    getPluginAdmin('Polls');
    
    // Frontend hooks
    addHook('polls_content','pollsContent');

    function pollsContent() {
        pollsShow('all');
    }
    
    function pollsShow($polls_id=0) {
        $polls_path = TEMPLATE_CMS_DATA_PATH.'polls/';
        
        $poll_xml_db = getXMLdb($polls_path.'poll.xml');
        $answer_xml_db = getXMLdb($polls_path.'answer.xml');
     
        /**
         * If a person has voted
         */
        if(isPost('poll_send')) {
            $poll_and_answer = post('poll_answer');
            $poll_id = key($poll_and_answer);
            $answer_array = $poll_and_answer[$poll_id];
            
            /**
             * Increasing the number of voters and setting cookies
             */
            $countVotesNow = 0;
            foreach($answer_array as $answer_id) {
                $answers_votes_xml = selectXMLRecord($answer_xml_db, "//answer[@id='{$answer_id}']", 1);
                $answer_votes = toText($answers_votes_xml[0]->votes) + 1;
                updateXMLRecordWhere($answer_xml_db,"//answer[@id='{$answer_id}']", array('votes'=>$answer_votes));
                SetCookie('poll'.$poll_id, $answer_id, 0x6FFFFFFF, '/');
                $countVotesNow++;
            }
            
            /**
             * Increasing the number of voters for this poll
             */
            $poll_votes_xml = selectXMLRecord($poll_xml_db, "//poll[@id='{$poll_id}']", 1);
            $poll_votes = toText($poll_votes_xml[0]->count_votes) + $countVotesNow;
            updateXMLRecordWhere($poll_xml_db,"//poll[@id='{$poll_id}']", array('count_votes'=>$poll_votes));
            
           redirect($_SERVER['REQUEST_URI']);
        }
        
        /**
         * Show polls
         */                
        if (intval($polls_id) > 0) {
            // Output poll by ID
            $polls[0] = selectXMLRecord($poll_xml_db, '//poll[@id='.$polls_id.']');
        } elseif ($polls_id === 'rand') {
            // Random poll
            $polls_array = selectXMLRecord($poll_xml_db, "//poll",'all');
            $polls_rand_keys = array_rand($polls_array);
            $polls[0] = $polls_array[$polls_rand_keys];
        } elseif ($polls_id === 'all') {
            // All polls
            $polls = selectXMLRecord($poll_xml_db, "//poll",'all');        
        } else {
            //Poll by default
            $config_xml_db = getXMLdb($polls_path.'config.xml');
            $configPolls = selectXMLRecord($config_xml_db, "//config",1);
            $default_poll_id = intval($configPolls[0]->default_poll_id);
            $polls[0] = selectXMLRecord($poll_xml_db, '//poll[@id='.$default_poll_id.']');
        }
        
        
        foreach ($polls as $poll) {
        
            htmlHeading(toText($poll->question),2);
         
            /**
             * The output answers
             */
            $itemsPoll = '';
            $answers = selectXMLRecord($answer_xml_db, "//answer[@poll_id='{$poll['id']}']", "all");
            foreach ($answers as $answer) {
            
                if (empty($_COOKIE['poll'.$poll['id']])) {
                
                    if ($poll->type == 'many' ) {
                        $type_input = 'checkbox';
                    } else {
                        $type_input = 'radio';
                    }
                    
                    $itemsPoll.= '<label><input type="'.$type_input.'" name="poll_answer['.$poll['id'].'][]" value="'.$answer['id'].'">';
                    $itemsPoll.= toText($answer->name).'</label><br/>';
                    
                } else {
                    $percent = (100*$answer->votes)/$poll->count_votes;
                    $itemsPoll.= toText($answer->name).' ('.toText($answer->votes).' / '.sprintf("%01.1f%s", $percent,'%').' )';
                    $itemsPoll.= '<div style="border-top:1px solid #ccc; margin-bottom:5px;"><div style="height:2px; background:green; width:'.$percent.'%;"></div></div>';
                }
            }
            
            /**
             * Output of results or the form for voting
             */
            if (empty($_COOKIE['poll'.$poll['id']])) {
                htmlFormOpen('');
                echo $itemsPoll;
                htmlBr();
                htmlFormHidden('poll_id', $poll->id);
                htmlFormClose(true,array('value'=>lang('polls_vote_submit'),'name'=>'poll_send'));
            } else {
                echo $itemsPoll;
            }
            htmlBr();
        }
    }



