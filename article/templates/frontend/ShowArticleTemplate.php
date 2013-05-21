<h1><a href="<?php getSiteUrl();?>article">Статьи</a> &rarr; <?php echo toText($article['title']); ?></h1>

<div><?php echo $article['message'];?></div><br/>

<div><?php echo lang('article_views');?> <b><?php echo intval($article['views']);?></b></div><br/>

<?php if ($_SESSION['admin']):?>
<a href="<?php getSiteUrl();?>admin/index.php?id=pages&sub_id=article&action=edit&art_id=<?php echo $article['id'];?>"><?php echo lang('article_edit');?></a>
<?php endif;?>