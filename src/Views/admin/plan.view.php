<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>SERVICIOS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsPlanAction" onclick="planToPrint()" title="Imprimir">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsPlanAction" onclick="planToExcel()" title="Exportar">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="SnBtn jsPlanAction" onclick="planList()" title="Actualizar">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnBtn primary jsPlanAction" onclick="planShowModalCreate()" title="Nuevo">
                <i class="fas fa-plus SnMr-2"></i> Nuevo
            </div>
        </div>
    </div>
    <div class="SnCard">
        <div class="SnCard-body">
            <div class="SnControl-wrapper SnMb-5">
                <input type="text" class="SnForm-control SnControl" id="searchContent" placeholder="Buscar...">
                <span class="SnControl-suffix icon-search4"></span>
            </div>
            <div id="planTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/plan.js"></script>

<div class="SnModal-wrapper" data-modal="planModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="planModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-network-wired SnMr-2"></i> Plan</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="planForm" onsubmit="planSubmit(event)">
                <input type="hidden" class="SnForm-control" id="planId">
                <div class="SnForm-item required">
                    <label for="planDescripcion" class="SnForm-label">Descripcion</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-file-code SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="planDescripcion" required>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="planSpeed" class="SnForm-label">Velocidad</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-tachometer-alt SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="planSpeed" required>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="planPrice" class="SnForm-label">Precio</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-coins SnControl-prefix"></i>
                        <input type="number" min="0" class="SnForm-control SnControl" id="planPrice" required>
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="planFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
    </div>
</div>