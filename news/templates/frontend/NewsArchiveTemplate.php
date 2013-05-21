<?php htmlAdminHeading(lang('news_archive')); ?>
<br />
<div id="news">
<?php
    if(count($news_entries) > 0) {
    foreach($news_entries as $entry) {
?>        
        <div class="news-subject">
            <?php echo date("j.n.Y",(int)$entry['date']); ?> - <a href="<?php echo getSiteUrl(false).'news/'.$entry['id'].'/'.toText($entry['name']) ?>"><?php echo toText($entry['title']); ?></a>
        </div>
        <div class="news-bottom"></div>        
<?php }
    }
?>
</div>