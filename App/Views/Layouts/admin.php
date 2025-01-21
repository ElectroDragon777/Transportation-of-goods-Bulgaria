<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Gallery Admin Dashboard</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="web/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="web/assets/vendors/iconfonts/ionicons/dist/css/ionicons.css">
        <link rel="stylesheet" href="web/assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="web/assets/vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="web/assets/vendors/css/vendor.bundle.addons.css">
        <link rel="stylesheet" href="web/assets/vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="web/assets/css/shared/style.css">
        <link rel="stylesheet" href="web/assets/css/demo_1/style.css">
        <link rel="shortcut icon" href="web/assets/images/favicon.ico" />
        <script src="<?php echo INSTALL_URL; ?>/web/js/jquery-3.7.1.min.js"></script>
        <script src="<?php echo INSTALL_URL; ?>/web/assets/vendors/js/vendor.bundle.base.js"></script>
        <script src="<?php echo INSTALL_URL; ?>/web/assets/vendors/js/vendor.bundle.addons.js"></script>
        <script src="<?php echo INSTALL_URL; ?>/web/assets/js/shared/off-canvas.js"></script>
        <script src="<?php echo INSTALL_URL; ?>/web/assets/js/shared/misc.js"></script>
        <script src="<?php echo INSTALL_URL; ?>web/js/app.js"></script>
        <script src="<?php echo INSTALL_URL; ?>web/js/script.js"></script>
    </head>
    <body>
        <div class="container-scroller">
            <?php
            include 'App/Views/Layouts/component/top_nav.php';
            ?>
            <div class="container-fluid page-body-wrapper">
                <?php
                include 'App/Views/Layouts/component/nav.php';
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php
                        include $viewPath;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>