<div id="section-bar">
    <?php htmlButton(lang('article_settings'),'index.php?id=pages&sub_id=article&action=settings'); ?>
</div>
<?php 
    htmlFormOpen('index.php?id=pages&sub_id=article');
    htmlFormInput(array('name'=>'article_title_new','value'=>$post_title,'size'=>'65'),lang('article_title_new'));
    htmlNbsp(4);
    htmlFormClose(true,array('value'=>lang('article_save'),'name'=>'add_article'));
?>
    <table class="admin-table">
    <thead class="admin-table-header">
        <tr>
            <td class="admin-table-field"><?php echo lang('article_title');?></td>
            <td width="50">&nbsp;</td>
            <td width="50">&nbsp;</td>
        </tr>
    </thead>
    <tbody class="admin-table-content">
    <?php foreach ($article as $art): ?>
     <tr class="admin-table-tr">
        <td class="admin-table-field">
            <a href="<?php getSiteUrl();?>article/<?php echo toText($art->slug); ?>"><?php echo toText($art->title); ?></a> 
            <?php if ($art->notshow == 1) echo lang('article_off');?>
        </td>
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('article_edit'), 'index.php?id=pages&sub_id=article&action=edit&art_id='.$art['id']); ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php 
                htmlButtonDelete(lang('article_delete'), 'index.php?id=pages&sub_id=article&action=delete&art_id='.$art['id']);
            ?>
        </td>
     </tr>
    <?php endforeach; ?>
    </tbody>
</table>