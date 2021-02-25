<div class="MainContainer">
    <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
    <div class="SnTab">
        <input type="hidden" id="userId" value="<?php echo $_SESSION[SESS_KEY] ?? 0 ?>">
        <div class="SnTab-header">
            <div class="SnTab-title is-active">Perfil</div>
            <div class="SnTab-title">Seguridad</div>
        </div>
        <div class="SnTab-content">
            <div class="SnGrid col-gap m-grid-2 SnMb-5">
                <div>
                    <strong>Perfil</strong>
                    <p>Su dirección de correo electrónico es su identidad en <?= APP_NAME ?> y se utiliza para iniciar sesión.</p>
                </div>
                <form action="" class="SnForm" method="post" onsubmit="profileUpdateProfile(event)">
                    <div class="SnForm-item required">
                        <label for="userEmail" class="SnForm-label">Email</label>
                        <div class="SnControl-wrapper">
                            <i class="far fa-envelope SnControl-prefix"></i>
                            <input type="email" class="SnForm-control SnControl" required id="userEmail" placeholder="Email" value="<?= $parameter['user']['email'] ?>">
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="userUserName" class="SnForm-label">Nombre de usuario</label>
                        <div class="SnControl-wrapper">
                            <i class="far fa-user SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" required id="userUserName" placeholder="Nombre de usuario" value="<?= $parameter['user']['user_name'] ?>">
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="userFullName" class="SnForm-label">Nombre completo</label>
                        <div class="SnControl-wrapper">
                            <i class="far fa-user SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" required id="userFullName" placeholder="Nombre completo" value="<?= $parameter['user']['full_name'] ?>">
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" name="commitUser">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="SnTab-content">
            <div class="SnGrid col-gap m-grid-2 SnMb-5">
                <div>
                    <strong>Password</strong>
                    <p>Cambiar su contraseña también restablecerá su clave</p>
                </div>
                <form action="" class="SnForm" method="post" onsubmit="profileUpdatePassword(event)">
                    <div class="SnForm-item required">
                        <label for="userPassword" class="SnForm-label">Contraseña</label>
                        <div class="SnControl-wrapper">
                            <i class="fas fa-key SnControl-prefix"></i>
                            <input type="password" class="SnForm-control SnControl" id="userPassword" placeholder="Contraseña">
                            <span class="SnControl-suffix far fa-eye togglePassword"></span>
                        </div>
                    </div>
                    <div class="SnForm-item required">
                        <label for="userPasswordConfirm" class="SnForm-label">Confirmar contraseña</label>
                        <div class="SnControl-wrapper">
                            <i class="fas fa-key SnControl-prefix"></i>
                            <input type="password" class="SnForm-control SnControl" id="userPasswordConfirm" placeholder="Confirmar contraseña">
                            <span class="SnControl-suffix far fa-eye togglePassword"></span>
                        </div>
                    </div>
                    <div class="SnForm-item">
                        <button type="submit" class="SnBtn primary block" name="commitChangePassword">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= URL_PATH ?>/assets/script/profileUpdate.js"></script>