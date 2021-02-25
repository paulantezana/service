<?php if (isset($parameter['message']) ? $parameter['message'] : '') : ?>
    <div class="SnAlert <?= isset($parameter['messageType']) ? $parameter['messageType'] : '' ?> SnMb-5"><i class="SnIcon-<?= isset($parameter['messageType']) ? $parameter['messageType'] : '' ?> SnMr-2"></i><?php echo $parameter['message'] ?></div>
<?php endif; ?>