<h1><?php echo lang('article_name');?></h1>
    
<?php foreach ($article as $art): ?>
    <?php $art['message'] = getArticleMessage($art['id']);?>
    <h2><a href="<?php getSiteUrl();?>article/<?php echo toText($art['slug']);?>"><?php echo toText($art['title']); ?></a></h2>
    <div><?php echo articleMore($art['message'],toText($art['slug']));?></div><br/>
<?php endforeach;?>