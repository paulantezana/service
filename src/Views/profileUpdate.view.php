<div class="MainContainer">
    <input type="hidden" id="userId" value="<?= $parameter['user']['user_id'] ?>">
    <div class="SnGrid col-gap m-grid-2 SnMb-5">
        <div>
            <strong>Foto de perfil</strong>
            <p>Solo se permite el formato .JPG, .PNG de (320px por 320px) menos de 100 KB</p>
            <div class="SnAvatar" style="width: 80px; height: 80px;">
                <?php if ($parameter['user']['avatar'] !== '') : ?>
                    <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $parameter['user']['avatar'] ?>" alt="avatar">
                <?php else : ?>
                    <div class="SnAvatar-text"><?= substr($parameter['user']['user_name'], 0, 2); ?></div>
                <?php endif; ?>
            </div>
        </div>
        <div id="userProfileAvatarWrapper">
            <div class="SnForm-item">
                <label class="SnForm-label" for="businessLogo"> </label>
                <input type="file" class="SnForm-control" id="userProfileAvatar" accept="image/png,image/jpeg,image/jpg">
            </div>
            <button type="button" class="SnBtn primary block" onclick="updateProfileAvatar()"><i class="fas fa-cloud-upload-alt SnMr-2"></i>Guardar</button>
        </div>
    </div>

    <div class="SnDivider"></div>

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
    <div class="SnDivider"></div>
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
<script src="<?= URL_PATH ?>/assets/script/profileUpdate.js"></script>