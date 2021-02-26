<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>SERVICIOS</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsServerAction" onclick="serverToPrint()" title="Imprimir">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsServerAction" onclick="serverToExcel()" title="Exportar">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="SnBtn jsServerAction" onclick="serverList()" title="Actualizar">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnBtn primary jsServerAction" onclick="serverShowModalCreate()" title="Nuevo">
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
            <div id="serverTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/server.js"></script>

<div class="SnModal-wrapper" data-modal="serverModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="serverModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fas fa-server SnMr-2"></i> Server</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="serverForm" onsubmit="serverSubmit(event)">
                <input type="hidden" class="SnForm-control" id="serverId">
                <div class="SnForm-item required">
                    <label for="serverDescripcion" class="SnForm-label">Descripcion</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-file-code SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="serverDescripcion" required>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="serverAddress" class="SnForm-label">Dirección</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-street-view SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="serverAddress">
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="serverFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
    </div>
</div>