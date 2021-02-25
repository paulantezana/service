<div class="MainContainer">
    <div class="Login">
        <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
        <form action="" method="post" class="SnForm">
            <div class="SnForm-item required">
                <label for="email" class="SnForm-label">Nombre de usuario</label>
                <div class="SnControl-wrapper">
                    <i class="far fa-user SnControl-prefix"></i>
                    <input type="text" class="SnForm-control SnControl" required id="email" name="email" placeholder="Nombre de usuario">
                </div>
            </div>
            <div class="SnForm-item required">
                <label for="password" class="SnForm-label">Contraseña</label>
                <div class="SnControl-wrapper">
                    <i class="fas fa-key SnControl-prefix"></i>
                    <input type="password" class="SnForm-control SnControl" required id="password" name="password" placeholder="Contraseña">
                    <span class="SnControl-suffix far fa-eye togglePassword"></span>
                </div>
            </div>
            <button type="submit" class="SnBtn block primary lg" name="commit"><i class="fas fa-sign-in-alt SnMr-2"></i>Iniciar sesión</button>
            <p style="text-align: center">
                <a href="<?= URL_PATH ?>/user/forgot"> ¿Olvido su contraseña?</a>
            </p>
        </form>
    </div>
</div>