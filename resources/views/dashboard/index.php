<?php $title = 'Home page'; ?>
<?php ob_start() ?>
    <section class="content">
        <?php var_dump($histories); ?>
    </section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>