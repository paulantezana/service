<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>PAGAR CUOTA</strong>
        </div>
        <div class="SnToolbar-right"></div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar por número de documento o nombre...">
                <span class="SnControl-suffix icon-search4"></span>
            </div>
            <div id="contractMatch"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/helpers/jspdf.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/helpers/moment.js"></script>
<script src="<?= URL_PATH ?>/assets/script/payment.js"></script>
<script src="<?= URL_PATH ?>/assets/script/paymentPay.js"></script>
<script src="<?= URL_PATH ?>/assets/script/paymentPrint.js"></script>

<?php require_once (__DIR__ . '/partials/paymentModalForm.partial.php') ?>
<?php require_once (__DIR__ . '/partials/pdfPrintModal.partial.php') ?>