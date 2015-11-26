<?php $title = 'Home page'; ?>
<?php ob_start() ?>
    <section class="content">
        <?php foreach ($products as $product): ?>
            <h1><a href="<?php echo url('product', $product->id); ?>">
                    <?php echo $product->title ?>
                </a></h1>
            <?php if ($im = $image->productImage($product->id)): ?>
                <a href="<?php echo url('product', $product->id); ?>"><img width="200"
                                                                           src="<?php echo url('uploads', $im->uri) ?>"></a>
            <?php endif; ?>
            <p class="excerpt">price: <?php echo $product->price ?></p>
            <?php if ($tags = $tag->productTags($product->id)): ?>
                tags:
                <?php foreach ($tags as $t): ?>
                    <?php echo $t->name; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>