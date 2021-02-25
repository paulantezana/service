<div class="SnTable-wrapper">
    <table class="SnTable" id="customerCurrentTable">
        <thead>
            <tr>
                <th>N. Documento</th>
                <th>Razón social</th>
                <th>Razón comercial</th>
                <th>Dirección</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($parameter['customer']['data']) >= 1): foreach ($parameter['customer']['data'] as $row) : ?>
                <tr>
                    <td><?= $row['identity_document_description'] ?>: <?= $row['document_number'] ?></td>
                    <td><?= $row['social_reason'] ?></td>
                    <td><?= $row['commercial_reason'] ?></td>
                    <td><?= $row['fiscal_address'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['telephone'] ?></td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsCustomerOption" title="Eliminar" onclick="customerDelete(<?= $row['customer_id'] ?>)">
                                <i class="far fa-trash-alt"></i>
                            </div>
                            <div class="SnBtn icon jsCustomerOption" title="Editar" onclick="customerShowModalUpdate(<?= $row['customer_id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="7">
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
$currentPage = $parameter['customer']['current'];
$totalPage = $parameter['customer']['pages'];
$limitPage = $parameter['customer']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="customerList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="customerList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="customerList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="customerList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="customerList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>