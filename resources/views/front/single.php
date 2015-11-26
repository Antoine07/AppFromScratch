<?php $title = 'Single product'; ?>
<?php ob_start() ?>
<section class="content">
    <h1><?php echo $product->title ?></h1>

    <?php if ($image->productImage($product->id)): ?>
        <img width="200" src="<?php echo url('uploads', $image->productImage($product->id)->uri) ?>">
    <?php endif; ?>
    <p class="excerpt">price: <?php echo $product->price ?></p>

    <form action="<?php echo url('command'); ?>" method="post">
        <input type="hidden" name="price" value="<?php echo $product->price; ?>"/>
        <input type="hidden" name="name" value="<?php echo $product->id; ?>"/>
        <?php echo token(); ?>
        <select name="quantity" id="quantity">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
        <input class="button-primary" type="submit" value="Submit">
    </form>

</section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>