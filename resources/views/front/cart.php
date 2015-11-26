<?php $title = 'Cart page'; ?>
<?php ob_start() ?>


    <div class="row">
        <div class="eight columns">
            <section class="content">
                <?php foreach ($products as $name => $p): ?>
                    <h1>
                        <?php echo $name; ?>
                    </h1>
                    <?php if ($im = $image->productImage($p['product_id'])): ?>
                        <a href="<?php echo url('product', $p['product_id']); ?>"><img width="200"
                                                                               src="<?php echo url('uploads', $im->uri) ?>"></a>
                    <?php endif; ?>
                    <p> quantity:  <?php echo $p['quantity']; ?>, total <?php echo $p['total'] ?>&euro; ,
                        price: <?php echo $p['price']; ?>&euro;
                    </p>
                    <a href="<?php echo url('restore', $p['product_id']); ?>">restore</a>
                <?php endforeach; ?>
            </section>
        </div>
        <div class="four columns">
            <p>finalize</p>
            <form action="<?php echo url('store'); ?>" method="post">
                <?php echo token(); ?>
                <label for="email">email</label>
                <?php echo (!empty($_SESSION['error']['email']))? '<small class="error">'.$_SESSION['error']['email'].'</small>' : '' ; ?>
                <input type="text" name="email" id="email"
                       value="<?php echo (!empty($_SESSION['old']['email'])) ? $_SESSION['old']['email'] : ''; ?>"/>
                <label for="number">blue card number</label>
                <?php echo (!empty($_SESSION['error']['number']))? '<small class="error">'.$_SESSION['error']['number'].'</small>' : '' ; ?>

                <input type="text" name="number" id="number"/>
                <label for="address">address</label>
                <?php echo (!empty($_SESSION['error']['address']))? '<small class="error">'.$_SESSION['error']['address'].'</small>' : '' ; ?>
                <textarea name="address" class="u-full-width" placeholder="Hi Dave …"
                          id="address"><?php echo (!empty($_SESSION['old']['address'])) ? $_SESSION['old']['address'] : ''; ?></textarea>

                <input class="button-primary" type="submit" value="command">
            </form>
        </div>
    </div>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>