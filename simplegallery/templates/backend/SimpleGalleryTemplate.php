<br style="clear:both;" />
<div class="simple-gallery-panel">
<?php createSimpleGallery($images, true); ?>
<div style="clear:both"></div>
</div>
<br />
<div class="simple-gallery-panel">
    <div style="float:left">
    <?php
        htmlFormOpen('index.php?id=pages&sub_id=simplegallery');
        htmlFormInput(array('value'=>$simplegallery_config_xml[0]->thumbnail_width,'name'=>'thumbnail_width','size'=>'30'), lang('simplegallery_thumbnail_width'));
        htmlFormInput(array('value'=>$simplegallery_config_xml[0]->thumbnail_height,'name'=>'thumbnail_height','size'=>'30'), lang('simplegallery_thumbnail_height'));
        htmlFormInput(array('value'=>$simplegallery_config_xml[0]->thumbnail_count,'name'=>'thumbnail_count','size'=>'30'), lang('simplegallery_thumbnail_count'));htmlBr(2);
        htmlFormClose(true,array('name'=>'simplegallery_save_options','value'=>lang('simplegallery_save_options')));
    ?>
    </div>
    <div style="float:right">
        <?php
            htmlFormOpen('index.php?id=pages&sub_id=simplegallery','post',true);
            htmlFormFile('file',25); htmlBr(2);
            htmlFormClose(true,array('name'=>'upload_image','value'=>lang('simplegallery_upload')));
        ?>
        <p align="center">
        <?php
            htmlBr(6);
            htmlLink(lang('simplegallery_see'), getSiteUrl(false).'gallery', '_blank');
         ?>
            </p>
    </div>
    <div style="clear:both"></div>
</div>
<div style="clear:both"></div>