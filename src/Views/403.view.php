<div class="Result">
    <h1 class="Result-title">403</h1>
    <?php if (isset($parameter['message']) && $parameter['message'] != '') : ?>
        <p class="Result-description"><?= $parameter['message'] ?></p>
    <?php else: ?>
        <p class="Result-description">Lo sentimos, no estás autorizado para acceder a esta página.</p>
    <?php endif; ?>
    <a href="<?= URL_PATH ?>/" class="SnBtn primary">Volver al Inicio</a>
</div>