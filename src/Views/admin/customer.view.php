<div class="SnContent">
    <div class="SnToolbar">
        <div class="SnToolbar-left">
            <i class=" fas fa-list-ul SnMr-2"></i> <strong>CLIENTES</strong>
        </div>
        <div class="SnToolbar-right">
            <div class="SnBtn jsCustomerAction" onclick="customerToPrint()" title="Imprimir">
                <i class="fas fa-print"></i>
            </div>
            <div class="SnBtn jsCustomerAction" onclick="customerToExcel()" title="Exportar">
                <i class="fas fa-file-excel"></i>
            </div>
            <div class="SnBtn jsCustomerAction" onclick="customerList()" title="Actualizar">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="SnBtn primary jsCustomerAction" onclick="customerShowModalCreate()" title="Nuevo">
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
            <div id="customerTable"></div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/customer.js"></script>

<?php require_once (__DIR__ . '/partials/customerModalForm.partial.php') ?>