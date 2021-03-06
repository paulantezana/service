<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>PAGOS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsPaymentAction" onclick="paymentToPrint()" title="Imprimir">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsPaymentAction" onclick="paymentToExcel()" title="Exportar">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="SnBtn jsPaymentAction" onclick="paymentList()" title="Actualizar">
                <i class="fas fa-sync-alt"></i>
            </div>
        </div>
    </div>
    <?php if($parameter['contractId']>0): ?>
        <div class="SnCard SnMb-2">
            <div class="SnCard-body">
                <div>
                    <span>Contrato: </span><span><?= $parameter['contract']['contract_id'] ?></span>
                </div>
                <div>
                    <span>Plan: </span><span><?= $parameter['contract']['plan_description'] ?></span>
                </div>
                <div>
                    <span>Velocidad: </span><span><?= $parameter['contract']['plan_speed'] ?></span>
                </div>
                <div>
                    <span>Precio: </span><span><?= $parameter['contract']['plan_price'] ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControlGroup">
                <div class="SnControl-wrapper SnControlGroup-input SnMb-5">
                    <i class="SnControl-prefix far fa-calendar-alt"></i>
                    <input type="date" class="SnForm-control SnControl" id="searchStartDate" onchange="paymentList()" value="<?php echo date('Y-m-d', strtotime('-1 year')) ?>">
                </div>
                <div class="SnControl-wrapper SnControlGroup-input SnMb-5">
                    <i class="SnControl-prefix far fa-calendar-alt"></i>
                    <input type="date" class="SnForm-control SnControl" id="searchEndDate" onchange="paymentList()" value="<?php echo date('Y-m-d', strtotime('+1 day')) ?>">
                </div>
                <div class="SnControl-wrapper SnControlGroup-input SnMb-5">
                    <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar por cliente...">
                    <i class="SnControl-suffix fas fa-search"></i>
                </div>
            </div>
            <div id="paymentTable"></div>
        </div>
    </div>
</div>

<script>
    var currentContractId = <?= $parameter['contractId'] ?>;
</script>
<script src="<?= URL_PATH ?>/assets/script/helpers/jspdf.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/helpers/moment-with-locales.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/paymentPrint.js"></script>
<script src="<?= URL_PATH ?>/assets/script/paymentReport.js"></script>
<?php require_once (__DIR__ . '/partials/pdfPrintModal.partial.php') ?>