<div class="Result">
    <h1 class="Result-title">500</h1>
    <?php if (isset($parameter['message']) && $parameter['message'] != '') : ?>
        <p class="Result-description"><?= $parameter['message'] ?></p>
    <?php else: ?>
        <p class="Result-description">Lo sentimos, el servidor está equivocado.</p>
    <?php endif; ?>
    <a href="<?= URL_PATH ?>/" class="SnBtn primary">Volver al Inicio</a>
</div>