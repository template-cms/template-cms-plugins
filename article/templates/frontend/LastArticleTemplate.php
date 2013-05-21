<?php foreach ($article as $art): ?>
    <li><a href="<?php getSiteUrl();?>article/<?php echo toText($art['slug']);?>"><?php echo toText($art['title']); ?></a></li>
<?php endforeach;?>