<?php htmlAdminHeading(lang('news_last')); ?>
<br />
<div id="news">
<?php
    if(count($news_entries) > 0) {
    foreach($news_entries as $entry) {
?>
        <div class="news-subject">
            <a href="<?php echo getSiteUrl(false).'news/'.$entry['id'].'/'.$entry['name'] ?>"><?php echo toText($entry['title']); ?></a>
        </div>
        <div class="news-body">
        <?php echo applyFilters('content', $entry['short']); ?>
        </div>
        <div class="news-date"><i><?php echo lang('news_date').': '; echo date("j.n.Y",(int)$entry['date']); ?> / <?php echo lang('news_category'); ?>: <a target="_blank" href="<?php echo getSiteUrl(false).'news/category/'.$entry['category_slug'] ?>"><?php echo toText($entry['category_name']); ?></a> / <?php echo lang('news_comments').': '.newsGetCommentsCount($entry['id'])?></i></div>
        <div class="news-bottom"></div> 
<?php }
    }
?>
</div>
<a href="<?php echo getSiteUrl(false).'news/archive' ?>"><?php echo lang('news_archive'); ?></a>