<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/icon/144.png">

    <?php require_once(__DIR__ . '/manifest.partial.php') ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/site.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/fontawesome.css">

    <script>var URL_PATH = '<?= URL_PATH ?>';</script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/sedna.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/theme.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/nprogress.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/conmon.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
</head>

<body itemscope itemtype="http://schema.org/WebPage">
    <div class="SiteLayout" id="SiteLayout">
        <div class="SiteLayout-header ">
            <header class="SiteHeader MainContainer" itemscope itemtype="http://schema.org/WPHeader">
                <div class="SiteHeader-left">
                    <div class="Branding" itemscope itemtype="http://schema.org/Organization">
                        <a class="Branding-link" href="<?= URL_PATH ?>" itemprop="url">
                            <img class="Branding-logo" alt="Logotipo de Sedna" itemprop="logo" src="<?= URL_PATH ?>/assets/images/icon/144.png">
                            <span class="Branding-name"><?= APP_NAME ?></span>
                        </a>
                    </div>
                </div>
                <div class="SiteHeader-right">
                    <div class="SiteHeader-nav">
                        <div class="icon-menu" id="SiteMenu-toggle"><i class="fas fa-bars"></i></div>
                        <nav class="SiteMenu-wrapper" itemscope itemtype="http://schema.org/SiteNavigationElement" role="navigation" id="SiteMenu-wrapper">
                            <div class="SiteMenu-content">
                                <ul class="SiteMenu SnMenu" id="SiteMenu">
                                    <li itemprop="url"><a href="<?= URL_PATH ?>" target="" itemprop="name" title="Inicio">Home</a></li>
                                </ul>
                                <div class="SnSwitch SnMl-2"><input class="SnSwitch-control" id="themeMode" type="checkbox"><label class="SnSwitch-label" for="themeMode"></label></div>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
        </div>
        <div class="SiteLayout-main">
            <?php echo $content ?>
        </div>
        <div class="SiteLayout-footer">
            <a href="<?= APP_AUTHOR_WEB ?>" target="_blank">Copyright © <?= date('Y') ?> <?= APP_AUTHOR ?></a>
        </div>
    </div>
    <script src="<?= URL_PATH ?>/assets/script/siteLayout.js"></script>
</body>

</html>