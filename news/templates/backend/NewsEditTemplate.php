<?php
    htmlAdminHeading(lang('news_editing'));
    htmlFormOpen('index.php?id=pages&sub_id=news');
    htmlFormHidden('entry_id', $news_entries[0]['id']);    
    htmlFormInput(array('value'=>toText($news_entries[0]['name']),'name'=>'news_name'),lang('news_name'));
    htmlFormInput(array('value'=>toText($news_entries[0]['title']),'name'=>'news_title'),lang('news_title'));
    htmlFormInput(array('value'=>toText($news_entries[0]['description']),'name'=>'news_description'),lang('news_description'));
    htmlFormInput(array('value'=>toText($news_entries[0]['keywords']),'name'=>'news_keywords'),lang('news_keywords'));


    htmlBr();
    echo lang('news_short');
    runHookP('admin_editor_secondary',array(toText($news_entries[0]['short'])));
    htmlBr();
    echo lang('news_full');
    htmlBr();
    runHookP('admin_editor',array(toText($news_entries[0]['full'])));


    echo '<br style="clear:both;" />';
    echo '<div class="date">';
    echo '<div style="float:left;">';
    echo lang('news_year');    
    htmlSelect($years, array('name'=>'year'), '', $date[0]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('news_month');    
    htmlSelect($month, array('name'=>'month'), '', $date[1]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('news_day');    
    htmlSelect($days, array('name'=>'day'), '', $date[2]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('news_hours');    
    htmlSelect($hours, array('name'=>'hour'), '', $date[3]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('news_minutes');    
    htmlSelect($minutes, array('name'=>'minute'), '', $date[4]);
    echo '</div>';
    echo '<div style="float:left;padding-left:5px">';
    echo lang('news_seconds');
    htmlSelect($seconds, array('name'=>'second'), '', $date[5]);
    echo '</div>';
    echo '</div>';

    echo '<br style="clear:both;" />';
    

?>
    <div id="news-options">
    <div id="news-box">
<?php
    htmlFormInput(array('value'=>toText($news_entries[0]['category_name']),'name'=>'category_name'),lang('news_category_name'));
    htmlFormInput(array('value'=>toText($news_entries[0]['category_slug']),'name'=>'category_slug'),lang('news_category_slug'));
?>
    </div>
    <div id="news-toggle" class="category"><?php echo lang('news_category'); ?></div>
    </div>
<?php
    htmlBr();
    htmlFormClose(true,array('value'=>lang('news_save'),'name'=>'edit_news'));
?>