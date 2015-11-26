<?php if (!empty($categories)): ?>
    <nav class="nav__header" id="nav__header">
        <ul class="nav__menu" id="nav__menu">
            <?php foreach ($categories as $category): ?>
                <li><a href="<?php echo url('category', $category->id) ?>"><?php echo $category->title; ?></a></li>
            <?php endforeach; ?>
            <?php if (auth_guest()): ?>
                <li><a href="<?php echo url('dashboard'); ?> ">dashboard</a></li>
                <li><a href="<?php echo url('logout'); ?> ">logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>