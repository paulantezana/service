<div class="MainContainer">
    <div class="Login">
        <?php require_once __DIR__ . '/partials/alertMessage.php' ?>
        <?php if (($parameter['contentType'] === 'validateToken' && $parameter['messageType'] === 'success') || ($parameter['contentType'] === 'changePassword'  && $parameter['messageType'] === 'error')) :  ?>
            <form action="<?= URL_PATH ?>/publicCompany/forgotValidate" method="POST" class="SnForm">
                <input type="hidden" name="userId" id="userId" value="<?php echo $parameter['user']['user_id'] ?? 0; ?>">
                <input type="hidden" name="userForgotId" id="userForgotId" value="<?php echo $parameter['user']['user_forgot_id'] ?? 0; ?>">
    
                <div class="SnForm-item required">
                    <label for="password" class="SnForm-label">Contraseña</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-key SnControl-prefix"></i>
                        <input type="password" class="SnForm-control SnControl" required id="password" name="password" placeholder="Contraseña">
                        <span class="SnControl-suffix fas fa-eye togglePassword"></span>
                    </div>
                </div>
    
                <div class="SnForm-item required">
                    <label for="confirmPassword" class="SnForm-label">Confirmar Contraseña</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-key SnControl-prefix"></i>
                        <input type="password" class="SnForm-control SnControl" required id="confirmPassword" name="confirmPassword" placeholder="Contraseña">
                        <span class="SnControl-suffix fas fa-eye togglePassword"></span>
                    </div>
                </div>
                
                <button type="submit" class="SnBtn block primary SnMb-5" name="commit"><i class="fas fa-key SnMr-2"></i>Cambiar contraseña</button>
            </form>
        <?php endif; ?>
        <p style="text-align: center">
            <a href="<?= URL_PATH ?>/user/login">Iniciar sesión</a>
        </p>
    </div>
</div>