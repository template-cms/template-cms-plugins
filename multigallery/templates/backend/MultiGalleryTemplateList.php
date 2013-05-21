<br style="clear:both;" />
<div class="simple-gallery-panel">
 <p class="gallery_folder"><?php echo lang('MultiGallery_nasv_gal'); ?> <?php echo $current_path ?></p> <br />
 <p class="gallery_folder"> <?php echo lang('MultiGallery_teg'); ?> </p>
 <p class='teg_cod'><b><?php echo lang('MultiGallery_teg_all'); ?> </b>[gal]<?php echo $current_path ?>[/gal]</p>
 <p class='teg_cod'><b><?php echo lang('MultiGallery_teg_rand'); ?></b>[rand]<?php echo $current_path ?>[/rand]</p>
 <p class='teg_cod2'><b><?php echo lang('MultiGallery_teg_gal'); ?> </b>[cover]<?php echo $current_path ?>[/cover]</p>
 <?php
        htmlFormOpen('index.php?id=pages&sub_id=multigallery&gal='.$current_path);
        htmlFormInput(array('value'=>$MultiGallery_config_xml[0]->thumbnail_width,'name'=>'thumbnail_width','size'=>'30'), lang('MultiGallery_thumbnail_width'));
        htmlFormInput(array('value'=>$MultiGallery_config_xml[0]->thumbnail_height,'name'=>'thumbnail_height','size'=>'30'), lang('MultiGallery_thumbnail_height'));
        htmlFormInput(array('value'=>$MultiGallery_config_xml[0]->thumbnail_count,'name'=>'thumbnail_count','size'=>'30'), lang('MultiGallery_thumbnail_count'));
        htmlFormInput(array('value'=>$MultiGallery_config_xml[0]->nasv,'name'=>'nasv','size'=>'30'), lang('MultiGallery_desc'));

        htmlBr(1);
        echo '<input type="checkbox" value="1" name="publish"';
        if ($MultiGallery_config_xml[0]->publish==1) echo " checked ";
        echo ' >'.lang('MultiGallery_publish');

  	    htmlBr(2);

 	    htmlFormClose(true,array('name'=>'MultiGallery_save_options','value'=>lang('MultiGallery_change_desc')));
    ?>

</div>

 <div class="simple-gallery-panel">
  <?php
            htmlFormOpen('index.php?id=pages&sub_id=multigallery&gal='.$current_path,'post',true);
            htmlFormFile('file',25); htmlBr(2);
            htmlFormClose(true,array('name'=>'upload_image','value'=>lang('MultiGallery_upload')));
    ?>
 </div>

<div class="simple-gallery-panel">
<?php
echo createMultiGallery($images,$current_path, true);
?>
<div style="clear:both"></div>
</div>