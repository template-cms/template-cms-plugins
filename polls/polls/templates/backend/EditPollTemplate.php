<?php 
    htmlAdminHeading(lang('polls_edit'));
    htmlFormOpen('index.php?id=pages&sub_id=polls&action=edit&poll_id='.get('poll_id'));
    htmlFormInput(array('name'=>'poll_question','value'=>$poll->question),lang('polls_question'));

    $i=1;
    foreach ($answers as $answer) {
        htmlBr();
        echo $i++ . lang('polls_answer') . "<input type='text' name='poll_answer[{$answer['id']}]' value='{$answer->name}' style='width:400px'/>";
        htmlNbsp(3);
        echo "<input type='text' name='poll_answer_votes[{$answer['id']}]' value='{$answer->votes}' style='width:40px'/>";
        htmlButtonDelete(lang('users_delete'), 'index.php?id=pages&sub_id=polls&action=edit&poll_id='.$poll['id'].'&answer_id='.$answer['id']);
    }
    htmlSelect($polls_type_array,array('name'=>'poll_type','style'=>'width:300px;'),'',$poll->type);
    htmlNbsp(4);
    htmlFormClose(true,array('value'=>lang('polls_save'),'name'=>'add_poll'));
    
    htmlAdminHeading(lang('polls_add_answer'));
    htmlFormOpen('index.php?id=pages&sub_id=polls&action=edit&poll_id='.get('poll_id'));
    htmlFormInput(array('name'=>'answer_add_name', 'style'=>'width:300px;'));
    htmlNbsp(3);
    htmlFormClose(true,array('value'=>lang('polls_save'),'name'=>'add_poll'));