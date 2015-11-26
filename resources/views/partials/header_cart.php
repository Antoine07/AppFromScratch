<div class="cart">
    <?php if($cart->total()>0): ?>
    <a href="<?php echo url('cart'); ?>">cart total: </a> <?php echo $cart->total(); ?>&euro;
    <?php else: ?>
        Empty Cart
    <?php endif; ?>
</div>

