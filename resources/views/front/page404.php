<?php $title = 'Single product'; ?>
<?php ob_start() ?>
    <section class="content">
        <h1>404</h1>
        <img src="<?php echo url('assets/images/darkvador.jpg'); ?>" alt=""/>
    </section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>