<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <meta name="description" content="<?= APP_DESCRIPTION ?>">
    <link rel="shortcut icon" href="<?= URL_PATH ?>/assets/images/icon/144.png">

    <?php require_once(__DIR__ . '/manifest.partial.php') ?>

    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/nprogress.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/fontawesome.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/slimselect.css">

    <script>var URL_PATH = '<?= URL_PATH ?>';</script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/sedna.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/theme.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/pristine.min.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/nprogress.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/slimselect.min.js"></script>
    <script src="<?= URL_PATH ?>/assets/script/helpers/conmon.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="AdminLayout" id="AdminLayout">
        <div class="AdminLayout-header">
            <header class="Header">
                <div class="Header-left">
                    <div id="AsideMenu-toggle"><i class="fas fa-bars"></i></div>
                </div>
                <div class="Header-right">
                    <ul class="HeaderMenu">
                        <li>
                            <a href="#" class="Header-action">
                                <i class="far fa-bell"></i>
                            </a>
                            <ul>
                                <li class="HeaderMenu-header">
                                    <div>Notification <span>5</span></div>
                                </li>
                                <li class="Notification">
                                    <div class="Notification-avatar SnAvatar">
                                        <img src="images/avatar.svg" alt="avatar">
                                    </div>
                                    <div class="Notification-body">
                                        <p class="SnMb-2">
                                            <strong>User name</strong>
                                            <span> replied to your comment : "Hello world 😍"</span>
                                        </p>
                                        <div class="Notification-time">
                                            <span>💬</span>
                                            <span>Just Now</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="Notification">
                                    <div class="Notification-avatar SnAvatar">
                                        <img src="images/avatar.svg" alt="avatar">
                                    </div>
                                    <div class="Notification-body">
                                        <p class="SnMb-2">
                                            <strong>Current user</strong>
                                            <span>Lorem ipsum, dolor sit amet.</span>
                                        </p>
                                        <div class="Notification-time">
                                            <span>💬</span>
                                            <span>Just Now</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="Notification">
                                    <div class="Notification-avatar SnAvatar">
                                        <img src="images/avatar.svg" alt="avatar">
                                    </div>
                                    <div class="Notification-body">
                                        <p class="SnMb-2">
                                            <strong>Other user</strong>
                                            <span> replied to your comment</span>
                                        </p>
                                        <div class="Notification-time">
                                            <span>💬</span>
                                            <span>Just Now</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="HeaderMenu-footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <li>
                            <div class="HeaderMenu-profile Header-action">
                                <div class="SnAvatar">
                                    <?php if ($_SESSION[SESS_USER]['avatar'] !== '') : ?>
                                        <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $_SESSION[SESS_USER]['avatar'] ?>" alt="avatar">
                                    <?php else : ?>
                                        <div class="SnAvatar-text"><?= substr($_SESSION[SESS_USER]['user_name'], 0, 2); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <ul>
                                <li class="User-item SnMt-2 SnMb-2">
                                    <a href="<?= URL_PATH ?>/admin/user/profile" class="SnAvatar">
                                        <?php if ($_SESSION[SESS_USER]['avatar'] !== '') : ?>
                                            <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $_SESSION[SESS_USER]['avatar'] ?>" alt="avatar">
                                        <?php else : ?>
                                            <div class="SnAvatar-text"><?= substr($_SESSION[SESS_USER]['user_name'], 0, 2); ?></div>
                                        <?php endif; ?>
                                    </a>
                                    <div>
                                        <div class="User-title"><strong id="userTitleInfo"><?= $_SESSION[SESS_USER]['email'] ?></strong></div>
                                        <div class="User-description" id="userDescriptionInfo"><?= $_SESSION[SESS_USER]['user_name'] ?></div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li class="SnMt-2"><a href="<?= URL_PATH ?>/user/update"><i class="fas fa-user SnMr-2"></i>Perfil</a></li>
                                <li class="SnMb-2"><a href="<?= URL_PATH ?>/user/logout"><i class="fas fa-sign-out-alt SnMr-2"></i>Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </header>
        </div>
        <div class="AdminLayout-aside">
            <div id="AsideMenu-wrapper" class="AsideMenu-wrapper">
                <div class="AsideMenu-container">
                    <div class="AsideHeader">
                        <div class="Branding">
                            <a href="<?= URL_PATH ?>" class="Branding-link">
                                <img src="<?= URL_PATH ?>/assets/images/icon/144.png" alt="Logo" class="Branding-img">
                                <span class="Branding-name"><?= APP_NAME ?></span>
                            </a>
                        </div>
                    </div>
                    <ul class="AsideMenu" id="AsideMenu">
                        <?php if (menuIsAuthorized('home')) : ?>
                            <li>
                                <a href="<?= URL_PATH ?>/admin"><i class="fas fa-tachometer-alt AsideMenu-icon"></i><span>Inicio</span> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (menuIsAuthorized('home')) : ?>
                            <li>
                                <a href="<?= URL_PATH ?>/admin/contract"><i class="fas fa-file-contract AsideMenu-icon"></i><span>Contratos</span> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (menuIsAuthorized('home')) : ?>
                            <li>
                                <a href="<?= URL_PATH ?>/admin/plan"><i class="fas fa-network-wired AsideMenu-icon"></i><span>Planes</span> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (menuIsAuthorized('home')) : ?>
                            <li>
                                <a href="<?= URL_PATH ?>/admin/payment"><i class="fab fa-paypal AsideMenu-icon"></i><span>Pagos</span> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (menuIsAuthorized('home')) : ?>
                            <li>
                                <a href="<?= URL_PATH ?>/admin/customer"><i class="far fa-address-book AsideMenu-icon"></i><span>Clientes</span> </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="#"><i class="fas fa-cog AsideMenu-icon"></i><span>Configuración</span></a>
                            <ul>
                                <?php if (menuIsAuthorized('rol')) : ?>
                                    <li>
                                        <a href="<?= URL_PATH ?>/admin/company"><i class="far fa-building AsideMenu-icon"></i><span>Empresa</span></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (menuIsAuthorized('usuario')) : ?>
                                    <li>
                                        <a href="<?= URL_PATH ?>/admin/user"><i class="fas fa-user AsideMenu-icon"></i><span>Usuarios</span></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (menuIsAuthorized('rol')) : ?>
                                    <li>
                                        <a href="<?= URL_PATH ?>/admin/appAuthorization"><i class="fas fa-user-tag AsideMenu-icon"></i><span>Roles</span></a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (menuIsAuthorized('rol')) : ?>
                                    <li>
                                        <a href="<?= URL_PATH ?>/admin/company/backoup"><i class="fas fa-server AsideMenu-icon"></i><span>Backoup</span></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    </ul>
                    <div class="AsideFooter">
                        <div class="SnSwitch" title="Cambiar tema">
                            <input class="SnSwitch-control" type="checkbox" id="themeMode">
                            <label class="SnSwitch-label" for="themeMode"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="AdminLayout-main">
            <?php echo $content ?>
        </div>
    </div>
    <script src="<?= URL_PATH ?>/assets/script/adminLayout.js"></script>
</body>

</html>