<div class="SnContent">
    <div class="SnGrid col-gap row-gap m-grid-4">
        <div class="m-col-3">
            <form action="" class="SnForm" novalidate id="appContractForm" onsubmit="appContractSubmit(event)">
                <input type="hidden" class="SnForm-control" id="appContractId" value="<?= $parameter['appContract']['app_contract_id'] ?>">
                <div class="SnForm-item required">
                    <label for="appContractDateOfDue" class="SnForm-label">Fecha vencimiento</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="date" class="SnForm-control SnControl" id="appContractDateOfDue" value="<?= $parameter['appContract']['date_of_due'] ?>" required>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="appContractAppKey" class="SnForm-label">Calve aplicación</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="appContractAppKey" value="<?= $parameter['appContract']['app_key'] ?>" required>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="appContractNoticeDays" class="SnForm-label">Número de dias para notificar</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="number" class="SnForm-control SnControl" id="appContractNoticeDays" value="<?= $parameter['appContract']['notice_days'] ?>" required>
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="appContractFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
        <div></div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/configApp.js"></script>
