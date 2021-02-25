<div class="MainContainer">
    <div class="Login">
        <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
        <p>Ingresa tu correo electrónico para buscar tu cuenta</p>
        <form action="" method="post" class="SnForm">
            <div class="SnForm-item required">
                <label for="email" class="SnForm-label">Email</label>
                <div class="SnControl-wrapper">
                    <i class="far fa-envelope SnControl-prefix"></i>
                    <input type="email" class="SnForm-control SnControl" required id="email" name="email" placeholder="Email">
                </div>
            </div>
            <button type="submit" class="SnBtn block primary SnMb-5" name="commit"><i class="fas fa-search SnMr-2"></i>Buscar</button>
            <p style="text-align: center">
                <a href="<?= URL_PATH ?>/user/login">Iniciar sesión</a>
            </p>
        </form>
    </div>
</div>