<div class="SnTable-wrapper">
    <table class="SnTable" id="paymentCurrentTable">
        <thead>
            <tr>
                <th>Cod</th>
                <th style="width: 50px">P</th>
                <th>CN</th>
                <th>Cliente</th>
                <th>Fecha pago</th>
                <th>Folio</th>
                <th>Descripción</th>
                <th>Desde</th>
                <th>Hasta</th>
                <th>N° Meses</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Usuario</th>
                <th style="width: 50px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($parameter['payment']['data']) >= 1) : $paymentTotal = 0;
                foreach ($parameter['payment']['data'] as $row) : $paymentTotal += ($row['canceled'] == 0 ? $row['total'] : 0 ); ?>
                    <tr class="<?= $row['canceled'] == 1 ? 'disabled' : '' ?>">
                        <td><?= $row['payment_id'] ?></td>
                        <td>
                            <button class="SnBtn icon jsPaymentOption" onclick="paymentPrint(<?= $row['payment_id'] ?>)">
                                <i class="fas fa-print"></i>
                            </button>
                        </td>
                        <td><?= $row['contract_id'] ?></td>
                        <td><?= $row['customer_social_reason'] ?></td>
                        <td><?= $row['datetime_of_issue'] ?></td>
                        <td><?= $row['reference'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['from_datetime'] ?></td>
                        <td><?= $row['to_datetime'] ?></td>
                        <td><?= $row['payment_count'] ?></td>
                        <td><?= $row['total'] ?></td>
                        <td title="<?= $row['canceled_message'] ?>"><span class="SnTag <?= $row['canceled'] == 0 ? 'success' : 'error' ?>"><?= $row['canceled'] == 0 ? 'activo' : 'anulado' ?></span></td>
                        <td><?= $row['user_name'] ?></td>
                        <td>
                            <div class="SnTable-action">
                                <button class="SnBtn icon jsPaymentOption" title="Anular" onclick="paymentCanceled(<?= $row['payment_id'] ?>)" <?= $row['canceled'] == 1 ? 'disabled' : '' ?>>
                                    <i class="fas fa-ban"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="10" style="text-align: right;">Total</td>
                    <td><?= $paymentTotal ?></td>
                    <td colspan="3"></td>
                </tr>
            <?php else : ?>
                <tr>
                    <td colspan="14">
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
$currentPage = $parameter['payment']['current'];
$totalPage = $parameter['payment']['pages'];
$limitPage = $parameter['payment']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="paymentList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="paymentList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="paymentList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="paymentList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="paymentList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>