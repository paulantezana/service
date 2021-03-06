<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>CONTRATOS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsContractAction" onclick="contractToPrint()" title="Imprimir">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsContractAction" onclick="contractToExcel()" title="Exportar">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="SnBtn jsContractAction" onclick="contractList()" title="Actualizar">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnBtn primary jsContractAction" onclick="contractShowModalCreate()" title="Nuevo">
                <i class="fas fa-plus SnMr-2"></i> Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                <i class="SnControl-suffix fas fa-search"></i>
            </div>
            <div id="contractTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/helpers/jspdf.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/helpers/moment-with-locales.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/paymentPay.js"></script>
<script src="<?= URL_PATH ?>/assets/script/paymentPrint.js"></script>
<script src="<?= URL_PATH ?>/assets/script/contract.js"></script>

<div class="SnModal-wrapper" data-modal="contractModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="contractModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-file-contract SnMr-2"></i> Contract</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="contractForm" onsubmit="contractSubmit(event)">
                <input type="hidden" class="SnForm-control" id="contractId">
                <div class="SnForm-item required">
                    <label for="contractCustomerId" class="SnForm-label">Cliente</label>
                    <div class="SnControlGroup">
                        <div class="SnControlGroup-input">
                            <select id="contractCustomerId" required>
                                <option value="">Seleccionar</option>
                            </select>
                        </div>
                        <div class="SnControlGroup-append">
                            <div class="SnBtn icon primary" onclick="customerShowModalCreate()"><i class="fas fa-plus"></i></div>
                        </div>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="contractPlanId" class="SnForm-label">Plan</label>
                    <select id="contractPlanId" class="SnForm-control" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($parameter['plan'] ?? [] as $row) : ?>
                            <option value="<?= $row['plan_id'] ?>"><?= $row['description'] ?> - <?= $row['speed'] ?> - <?= $row['price'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item required">
                    <label for="contractServerId" class="SnForm-label">Servidor</label>
                    <select id="contractServerId" class="SnForm-control" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($parameter['server'] ?? [] as $row) : ?>
                            <option value="<?= $row['server_id'] ?>"><?= $row['description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item required">
                    <label for="contractDateTimeStart" class="SnForm-label">Fecha inicio</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-calendar-alt SnControl-prefix"></i>
                        <input type="date" class="SnForm-control SnControl" id="contractDateTimeStart" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="SnGrid s-grid-5 col-gap" style="display: none;">
                    <div class="SnForm-item s-col-4">
                        <label for="contractDateTimeEnd" class="SnForm-label">Fecha fin</label>
                        <div class="SnControl-wrapper">
                            <i class="far fa-calendar-alt SnControl-prefix"></i>
                            <input type="date" class="SnForm-control SnControl" id="contractDateTimeEnd" disabled>
                        </div>
                    </div>
                    <div>
                        <div class="SnSwitch" title="Havilitar fecha fin" style="margin-top: 2.3rem;">
                            <input class="SnSwitch-control" type="checkbox" id="contractDateTimeEndEnable">
                            <label class="SnSwitch-label" for="contractDateTimeEndEnable"></label>
                        </div>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="contractObservation" class="SnForm-label">Observación</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-file-contract SnControl-prefix"></i>
                        <textarea id="contractObservation" cols="30" rows="2" class="SnForm-control SnControl"></textarea>
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="contractFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
    </div>
</div>

<?php require_once (__DIR__ . '/partials/customerModalForm.partial.php') ?>
<?php require_once (__DIR__ . '/partials/paymentModalForm.partial.php') ?>
<?php require_once (__DIR__ . '/partials/pdfPrintModal.partial.php') ?>