<br style="clear:both;" />
<div class="simple-gallery-panel">
  <?php
            htmlFormOpen('index.php?id=pages&sub_id=multigallery&action=create_folder','post',true);
            htmlFormInput(array('value'=>'New','name'=>'new_name','size'=>'30'), '');
            htmlFormClose(true,array('name'=>'create_folder','value'=>lang('MultiGallery_create')));
   ?>
</div>
<div class="simple-gallery-panel">
<?php
echo createMultiGalleryDir($folders, true);
?>
<div style="clear:both"></div>
</div>