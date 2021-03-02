<div class="SnContent">
    <div class="SnGrid m-grid-3 l-grid-3 col-gap">
        <div class="SnCard DashCard blue">
            <div class="SnCard-body DashCard-body">
                <div class="DashCard-icon"><i class="far fa-address-book"></i></div>
                <div class="DashCard-right">
                    <div class="DashCard-title">Clientes</div>
                    <div class="DashCard-number"><?= $parameter['customerCount'] ?></div>
                </div>
            </div>
        </div>
        <div class="SnCard DashCard green">
            <div class="SnCard-body DashCard-body">
                <div class="DashCard-icon"><i class="fas fa-file-contract"></i></div>
                <div class="DashCard-right">
                    <div class="DashCard-title">Contratos</div>
                    <div class="DashCard-number"><?= $parameter['contractCount']['total'] ?> - <?= $parameter['contractCount']['total_canceled'] ?> </div>
                </div>
            </div>
        </div>
        <div class="SnCard DashCard purple">
            <div class="SnCard-body DashCard-body">
                <div class="DashCard-icon"><i class="fas fa-network-wired"></i></div>
                <div class="DashCard-right">
                    <div class="DashCard-title">Servicios</div>
                    <div class="DashCard-number"><?= $parameter['planCount'] ?> </div>
                </div>
            </div>
        </div>
    </div>

    <div class="SnCard SnMb-3" id="filterWrapper">
        <div class="SnCard-body">
            <div class="SnGrid s-grid-2 col-gap">
                <div class="SnForm-item">
                    <label for="chartStartDate" class="SnForm-label">Desde</label>
                    <input type="date" id="chartStartDate" class="SnForm-control" value="<?php echo date('Y-m-d', strtotime('-1 year')) ?>">
                </div>
                <div class="SnForm-item">
                    <label for="chartEndDate" class="SnForm-label">Hasta</label>
                    <input type="date" id="chartEndDate" class="SnForm-control" value="<?php echo date('Y-m-d', strtotime('+1 day')) ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="SnGrid m-grid-2 l-grid-2 col-gap">
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnCard-title">Contratos</div>
                <div style="height: 320px">
                    <canvas id="contractsChart" width="320" height="320"></canvas>
                </div>
            </div>
        </div>
        <div class="SnCard">
            <div class="SnCard-body">
                <div class="SnCard-title">Pagos</div>
                <div style="height: 320px">
                    <canvas id="paymentChart" width="320" height="320"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/helpers/moment-with-locales.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/helpers/chart.min.js"></script>
<script src="<?= URL_PATH ?>/assets/script/dashboard.js"></script>