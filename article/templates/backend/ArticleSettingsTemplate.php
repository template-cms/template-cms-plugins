<?php 
    htmlAdminHeading(lang('article_template'));
    htmlFormOpen('');
    htmlSelect($templates_array,array('name'=>'templates','style'=>'width:200px;'),'',getOption('article_template'));
    htmlNbsp(2);
    htmlFormClose(true, array('name'=>'submit','value'=>lang('article_ready')));
    
    htmlAdminHeading(lang('article_limit'));
    htmlFormOpen('');
    htmlFormInput(array('name'=>'limit','size'=>'50','value'=>getOption('article_limit')));
    htmlNbsp(2);
    htmlFormClose(true, array('name'=>'submit','value'=>lang('article_ready')));