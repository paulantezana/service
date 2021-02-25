<div class="SnTable-wrapper">
    <table class="SnTable" id="userCurrentTable">
        <thead>
            <tr>
                <th style="width: 40px">Avatar</th>
                <th>Usuario</th>
                <th>Nombre completo</th>
                <th>Email</th>
                <th>Perfil</th>
                <th style="width: 97px">Estado</th>
                <th style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($parameter['user']['data']) >= 1): foreach ($parameter['user']['data'] as $row) : ?>
                <tr>
                    <td>
                        <div class="SnAvatar">
                            <?php if($row['avatar'] !== ''): ?>
                                <img class="SnAvatar-img" src="<?= URL_PATH ?><?= $row['avatar'] ?>" alt="avatar">
                            <?php else: ?>
                                <div class="SnAvatar-text"><?= substr($row['user_name'], 0, 2); ?></div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= $row['user_name'] ?></td>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <span class="SnBadge <?= $row['user_role_state'] == 1 ? 'success' : 'error' ?>"></span>
                        <?= $row['user_roles'] ?>
                    </td>
                    <td>
                        <span class="SnTag <?= $row['state'] == 1 ? 'success' : 'error' ?>"><?= $row['state'] == 1 ? 'activo' : 'desactivado' ?></span>
                    </td>
                    <td>
                        <div class="SnTable-action">
                            <div class="SnBtn icon jsUserOption" title="Cambiar contraseña" onclick="userShowModalUpdatePassword(<?= $row['user_id'] ?>)">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="SnBtn icon jsUserOption" title="Editar" onclick="userShowModalUpdate(<?= $row['user_id'] ?>)">
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
$currentPage = $parameter['user']['current'];
$totalPage = $parameter['user']['pages'];
$limitPage = $parameter['user']['limit'];
$additionalQuery = '';
$linksQuantity = 3;

if ($totalPage > 1) {
    $lastPage       = $totalPage;
    $startPage      = (($currentPage - $linksQuantity) > 0) ? $currentPage - $linksQuantity : 1;
    $endPage        = (($currentPage + $linksQuantity) < $lastPage) ? $currentPage + $linksQuantity : $lastPage;

    $htmlPaginate       = '<nav aria-label="..."><ul class="SnPagination">';

    $class      = ($currentPage == 1) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="userList(\'' . ($currentPage - 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Anterior</a></li>';

    if ($startPage > 1) {
        $htmlPaginate   .= '<li class="SnPagination-item"><a href="#" onclick="userList(\'1\',\'' . $limitPage . '\')" class="SnPagination-link">1</a></li>';
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        $class  = ($currentPage == $i) ? "active" : "";
        $htmlPaginate   .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="userList(\'' . $i . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $i . '</a></li>';
    }

    if ($endPage < $lastPage) {
        $htmlPaginate   .= '<li class="SnPagination-item disabled"><span class="SnPagination-link">...</span></li>';
        $htmlPaginate   .= '<li><a href="#" onclick="userList(\'' . $lastPage . '\',\'' . $limitPage . '\')" class="SnPagination-link">' . $lastPage . '</a></li>';
    }

    $class      = ($currentPage == $lastPage || $totalPage == 0) ? "disabled" : "";
    $htmlPaginate       .= '<li class="SnPagination-item ' . $class . '"><a href="#" onclick="userList(\'' . ($currentPage + 1) . '\',\'' . $limitPage . '\')" class="SnPagination-link">Siguiente</a></li>';

    $htmlPaginate       .= '</ul></nav>';

    echo  $htmlPaginate;
}
?>