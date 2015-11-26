<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo url('assets/css/normalize.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/skeleton.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo url('assets/css/app.min.css'); ?>">
    <!-- Favicon  -->
    <link rel="icon" type="image/png" href="<?php echo url('assets/images/favicon.png'); ?>">

</head>
<body>
<div class="container u-full-width">
    <div class="header">
        <a class="header__icon" id="header__icon" href="#"></a>

        <h1 class="header__title"><a href="<?php echo url(); ?>">Stars
                Wars </a><?php echo (auth_guest()) ? 'dashboard' : ''; ?></h1>
        <?php echo $menu; ?>
        <?php echo $cart; ?>
    </div>
    <?php if (!empty($_SESSION['flashMessage'])) : ?>
        <p><?php echo $_SESSION['flashMessage'];
            $_SESSION['flashMessage'] = ''; ?></p>
    <?php endif; ?>
    <div class="row main">
        <div class="five columns">

        </div>
        <div class="seven columns">
            <?php echo $content ?>
        </div>
    </div>
    <div class="container footer">
        <footer class="footer__wrapper">
            footer
        </footer>
    </div>
</div>
<!-- #main -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo url('assets/js/app.min.js'); ?>"></script>
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function (b, o, i, l, e, r) {
        b.GoogleAnalyticsObject = l;
        b[l] || (b[l] =
            function () {
                (b[l].q = b[l].q || []).push(arguments)
            });
        b[l].l = +new Date;
        e = o.createElement(i);
        r = o.getElementsByTagName(i)[0];
        e.src = '//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e, r)
    }(window, document, 'script', 'ga'));
    ga('create', 'UA-XXXXX-X', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>