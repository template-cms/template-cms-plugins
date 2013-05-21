<div id="news">
    <div class="news-subject">
        <a href="<?php echo getSiteUrl(false).'news'; ?>"><?php echo lang('news_news'); ?></a> &rarr; <a href="<?php echo getSiteUrl(false).'news/'.$entry['id'].'/'.$entry['name'] ?>"><?php echo $entry['title']; ?></a>
    </div>
    <div class="news-body">
        <?php echo applyFilters('content', $entry['full']); ?>
    </div>
    <div class="news-date"><i><?php echo lang('news_date').': '; echo date("j.n.Y",(int)$entry['date']); ?> / <?php echo lang('news_category'); ?>: <a target="_blank" href="<?php echo getSiteUrl(false).'news/category/'.$entry['category_slug'] ?>"><?php echo toText($entry['category_name']); ?></a> / <?php echo lang('news_comments').': '.newsGetCommentsCount($entry['id'])?></i></div>
    <div class="news-bottom"></div>
    <?php runHook('news_extra_template_actions'); ?>
</div>
<div class="news-comment-titles"><?php echo lang('news_comments')?></div>
<div id="news-comments">
    <?php
        if(count($news_comments) > 0) {
        foreach($news_comments as $comment) {
    ?>
        <div class="news-comment-name"><b><?php echo toText($comment['name']); ?></b></div>
        <div class="news-comment-body"><?php echo applyFilters('content', toText($comment['message'])); ?></div>
        <div class="news-comment-date"><i><?php echo lang('news_date').': '; echo date("j.n.Y - H:m:s",(int)$comment['date']); ?></i></div>        
        <div class="news-comment-bottom"></div>
    <?php
        }
        }
    ?>
<br />
<div class="news-comment-titles"><?php echo lang('news_comments_leave')?></div>
    <form action="" method="post">
        <input type="hidden" name="entry_id" value="<?php echo $entry['id']; ?>" />
        <div><label><?php echo lang('news_comments_name'); ?></label><br /><input type="text" value="<?php echo $post_name; ?>" name = "news_comment_name" size = "100" /></div>
        <div><label><?php echo lang('news_comments_email'); ?></label><br /><input type="text" value="<?php echo $post_email; ?>" name = "news_comment_email" size = "100" /></div>
        <div><label><?php echo lang('news_comments_message'); ?></label><br /><textarea name="news_comment_message" style = "width:700px;height:200px" ><?php echo $post_message; ?></textarea></div>
      
      <?php if((getOption('captcha_installed') !== null) and (getOption('captcha_installed') == 'true')) { ?>
      <table>  
        <tr><td><?php echo lang('captcha_crypt'); ?>:<input type="text" name="code"></td><td><?php dsp_crypt(0,1); ?></td></tr>  
      </table>
      <?php } ?>
              
        <?php
            if(count($errors) > 0) {
                foreach($errors as $error) {
        ?>
                    <span style="color:#FA7660"><li><?php echo $error; ?></li></span>
        <?php
                }
                htmlBr(1);
            }
        ?>
        <div><input type="submit" class="submit"  name="news_comment_send" value="<?php echo lang('news_comment_send'); ?>" /></div>
    </form>    
</div>