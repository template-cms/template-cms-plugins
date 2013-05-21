<?php 
    htmlAdminHeading(lang('article_edit_title'));
    htmlFormOpen('index.php?id=pages&sub_id=article&action=edit&art_id='.get('art_id'));
    htmlFormInput(array('name'=>'title','value'=>$art_title),lang('article_title'));
    htmlFormInput(array('name'=>'slug','value'=>$art_slug),lang('article_slug'));
    htmlFormInput(array('name'=>'description','value'=>$art_description),lang('article_description'));
    htmlFormInput(array('name'=>'keywords','value'=>$art_keywords),lang('article_keywords'));
    htmlBr(1);
    if (!empty($art_notshow)) {
        echo '<label><input type="checkbox" value="1" name="notshow" checked="checked"/> '.lang('article_notshow').'</label>';
    } else {
        echo '<label><input type="checkbox" value="1" name="notshow"/> '.lang('article_notshow').'</label>';
    }
    htmlBr(2);
    runHookP('admin_editor',array($art_edit));
?>
    <label><?php echo lang('article_template_art');?></label><br/>
    <select name="templates" style="width:200px;">
        <option value="0"><?php echo lang('article_template_def');?></option>';
        <?php foreach($templates_array as $t):?>
        <option value="<?php echo $t;?>"<?php if ($art_template == $t) echo ' selected="selected"';?>><?php echo $t;?></option>
        <?php endforeach;?>
    </select>
<?php
    htmlBr(2);
    htmlFormButton(array('value'=>lang('pages_save'),'name'=>'edit_page'));
    htmlNbsp();
    htmlFormButton(array('value'=>lang('pages_save_and_exit'),'name'=>'edit_page_and_exit'));
    
    htmlFormClose();
    
    htmlBr(2);
    echo lang('article_more_info');