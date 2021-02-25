<div class="SnTable-wrapper">
    <table class="SnTable" id="planCurrentTable">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Velocidad</th>
                <th>Precio</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($parameter['plan']['data']) >= 1) : foreach ($parameter['plan']['data'] as $row) : ?>
                <tr>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['speed'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsPlanOption" title="Eliminar" onclick="planDelete(<?= $row['plan_id'] ?>)">
                                <i class="far fa-trash-alt"></i>
                            </div>
                            <div class="SnBtn icon jsPlanOption" title="Editar" onclick="planShowModalUpdate(<?= $row['plan_id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; else : ?>
                <tr>
                    <td colspan="4">
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
$currentPage = $parameter['plan']['current'];
$totalPage = $parameter['plan']['pages'];
$limitPage = $parameter['plan']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="planList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="planList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="planList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="planList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="planList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>