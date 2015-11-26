<?php $title = 'Single product'; ?>
<?php ob_start() ?>
    <section class="content">
        <form action="<?php echo url('login'); ?>" method="post">
            <?php echo token(); ?>
            <label for="email">email</label>
            <?php echo (!empty($_SESSION['error']['email'])) ? '<small class="error">' . $_SESSION['error']['email'] . '</small>' : ''; ?>
            <input type="email" name="email"
                   value="<?php echo (!empty($_SESSION['old']['email'])) ? $_SESSION['old']['email'] : ''; ?>"/>
            <label for="password">password</label>
            <?php echo (!empty($_SESSION['error']['password'])) ? '<small class="error">' . $_SESSION['error']['password'] . '</small>' : ''; ?>
            <input type="password" name="password"/>
            <input class="button-primary" type="submit" value="Submit">
        </form>
    </section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>