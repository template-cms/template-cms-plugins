<?php 
    htmlAdminHeading(lang('polls_creating'));
    htmlFormOpen('index.php?id=pages&sub_id=polls&action=add');
    htmlFormInput(array('name'=>'poll_question','value'=>$post_name),lang('polls_question'));
    htmlMemo('poll_answers', array('style'=>'width:710px;height:130px;'), '', lang('polls_answers'));
    htmlSelect($polls_type_array,array('name'=>'poll_type','style'=>'width:300px;'));
    htmlNbsp(4);
    htmlFormClose(true,array('value'=>lang('polls_save'),'name'=>'add_poll'));