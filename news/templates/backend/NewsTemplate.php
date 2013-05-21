    
<div id="news-options">
<div id="news-toggle" class="options"><?php echo lang('news_options'); ?></div>
    <div id="news-box">
<?php
    htmlFormOpen('index.php?id=pages&sub_id=news');
    htmlFormInput(array('name'=>'news_per_page','value'=>$news_options[0]->news_per_page,'size'=>'30'), lang('news_option_news_per_page'));
    htmlFormInput(array('name'=>'news_last_count','value'=>$news_options[0]->news_last_count,'size'=>'30'), lang('news_option_news_last_count'));
    htmlBr();
    htmlFormClose(true, array('name'=>'save_news_option','value'=>lang('news_option_save')));
?>
    </div>
    
    </div>

<div id="section-bar">
    <?php htmlButton(lang('news_add'),'?id=pages&sub_id=news&action=add_news'); ?>
    <?php runHook('admin_news_extra_buttons'); ?>
</div>

<br style="clear:both;" />

<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('news_news'); ?></td><td align="center"><?php echo lang('news_category'); ?></td><td align="center"><?php echo lang('news_date'); ?></td><td align="center"><?php echo lang('news_comments'); ?></td><td></td></tr>

    </thead>
    <tbody class="admin-table-content">
    <?php    
        if (count($news_entries) != 0) {
            foreach ($news_entries as $entry) {
                $id = $entry['id'];
                $records = selectXMLRecord($xml_db_comments,"//news_comment[entry_id=$id]",'all');
                $news_comments = selectXMLfields($records, array('id','entry_id','name','date'),'date','DESC');                  
    ?>
     <tr class="admin-table-tr">
         <td class="admin-table-titles admin-table-field" style="width:450px;">
            <a href="<?php echo getSiteUrl(false).'news/'.toText($entry['id'].'/'.$entry['name']); ?>" target="_blank"><?php echo toText($entry['title']); ?></a>
        </td>
        <td  align="center">
            <a target="_blank" href="<?php echo getSiteUrl(false).'news/category/'.$entry['category_slug'] ?>"><?php echo toText($entry['category_name']); ?></a>
        </td>
        <td  align="center">
            <?php echo dateFormat($entry['date'],'Y-m-d'); ?>
        </td>
        <td  align="center">
            <a href="index.php?id=pages&sub_id=news&action=comments_news&entry_id=<?php echo $entry['id']; ?>"><?php echo count($news_comments); ?> &rarr;</a>
        </td>        
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('news_edit'), 'index.php?id=pages&sub_id=news&action=edit_news&entry_id='.$entry['id']); ?>
            <?php htmlButtonDelete(lang('news_delete'), 'index.php?id=pages&sub_id=news&action=delete_news&entry_id='.$entry['id']); ?>
        </td>
     </tr>
    <?php
            }
        }
    ?>
    </tbody>
</table>