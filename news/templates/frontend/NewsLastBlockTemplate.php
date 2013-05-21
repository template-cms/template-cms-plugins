<div id="news">
<?php
    foreach($news_entries as $entry) {
?>
        <div class="news-subject">
            <a href="<?php echo getSiteUrl(false).'news/'.$entry['id'].'/'.$entry['name'] ?>"><?php echo toText($entry['title']); ?></a>
        </div>
        <div class="news-body">
        <?php echo $entry['short']; ?>
        </div>
        <div class="news-date"><i><?php echo lang('news_date').': '; echo date("j.n.Y",(int)$entry['date']); ?></i></div>
        <div class="news-bottom"></div>
<?php }
?>
</div>
<a href="<?php echo getSiteUrl(false).'news/archive' ?>"><?php echo lang('news_archive'); ?></a>