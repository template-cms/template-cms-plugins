<?php
    htmlAdminHeading(lang('news_creating'));
    htmlFormOpen('index.php?id=pages&sub_id=news');
    htmlFormInput(array('name'=>'news_name'),lang('news_name'));
    htmlFormInput(array('name'=>'news_title'),lang('news_title'));
    htmlFormInput(array('name'=>'news_description'),lang('news_description'));
    htmlFormInput(array('name'=>'news_keywords'),lang('news_keywords'));
    htmlBr();
    echo lang('news_short');
    runHook('admin_editor_secondary');
    htmlBr();
    echo lang('news_full');
    htmlBr();		
    runHook('admin_editor');

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
    htmlFormInput(array('name'=>'category_name'),lang('news_category_name'));
    htmlFormInput(array('name'=>'category_slug'),lang('news_category_slug'));
?>
    </div>
    <div id="news-toggle" class="category"><?php echo lang('news_category'); ?></div>
    </div>
<?php
    htmlBr();
    htmlFormClose(true,array('value'=>lang('news_save'),'name'=>'add_news'));
?>