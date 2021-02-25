<div class="SnTable-wrapper">
    <table class="SnTable" id="contractCurrentTable">
        <thead>
            <tr>
                <th>Cod</th>
                <th>Cliente</th>
                <th>Plan</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>Estado</th>
                <th>Observacion</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($parameter['contract']['data']) >= 1): foreach ($parameter['contract']['data'] as $row): ?>
                <tr class="<?= $row['canceled'] == 1 ? 'disabled' : '' ?>">
                    <td><?= $row['contract_id'] ?></td>
                    <td><?= $row['customer_social_reason'] ?></td>
                    <td>
                        <span><?= $row['plan_description'] ?></span>
                        <div>
                            <small><?= $row['plan_speed'] ?> - <?= $row['plan_price'] ?></small>
                        </div>
                    </td>
                    <td><?= $row['datetime_of_issue'] ?></td>
                    <td><?= $row['datetime_of_due'] ?></td>
                    <td>
                        <?php if($row['payment_count'] > 0): ?>
                            <?php
                                $months = stringDateDiffMonth($row['datetime_of_issue'], date('Y-m-d'));
                                if($months < $row['payment_count']){
                                    echo '<div class="SnTag success">activo - '.$row['payment_count'].'</div>';
                                } else {
                                    echo '<div class="SnTag error">deuda - '.$row['payment_count'].'</div>';
                                }
                            ?>
                        <?php else: if($row['canceled'] == 0):?>
                            <div class="SnTag error">Sin pago</div>
                        <?php endif; endif; ?>
                    </td>
                    <td><?= $row['observation'] ?></td>
                    <td>
                        <div class="SnTable-action">
                            <button class="SnBtn icon jsContractOption" title="Pagos" onclick="paymentShowModalCreate(<?= $row['contract_id'] ?>)" <?= $row['canceled'] == 1 ? 'disabled' : '' ?>>
                                <i class="fab fa-paypal"></i>
                            </button>
                            <button class="SnBtn icon jsContractOption" title="Anular" onclick="contractCanceled(<?= $row['contract_id'] ?>, <?= $row['contract_id'] ?>)" <?= $row['canceled'] == 1 ? 'disabled' : '' ?>>
                                <i class="fas fa-ban"></i>
                            </button>
                            <!-- <button class="SnBtn icon jsContractOption" title="Editar" onclick="contractShowModalUpdate(<?= $row['contract_id'] ?>)" <?= $row['canceled'] == 1 ? 'disabled' : '' ?>>
                                <i class="fas fa-edit"></i>
                            </button> -->
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="8">
                        <div class="SnEmpty">
                            <img src="<?= URL_PATH . '/assets/images/empty.svg' ?>" alt="">
                            <div>No hay datos</div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$currentPage = $parameter['contract']['current'];
$totalPage = $parameter['contract']['pages'];
$limitPage = $parameter['contract']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="contractList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="contractList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="contractList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="contractList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="contractList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>