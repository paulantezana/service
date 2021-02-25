<ul class="SnList">
    <?php foreach ($parameter['userRole'] ?? [] as $row) : ?>
        <li id="roleRow_<?= $row['user_role_id'] ?>" class="<?= $row['state'] == 0 ? 'disabled' : '' ?>">
            <span><?= $row['description'] ?></span>
            <div class="SnTable-action">
                <div class="SnBtn icon jsUserRoleOption" title="Configurar permisos" onclick="userRoleLoadAuthorities(<?= $row['user_role_id'] ?>,'<?= $row['description'] ?>')" <?= $row['state'] == 0 ? 'disabled' : '' ?>>
                    <i class="fas fa-cog"></i>
                </div>
                <div class="SnBtn icon jsUserRoleOption" title="Editar" onclick="userRoleShowModalUpdate(<?= $row['user_role_id'] ?>,'<?= $row['description'] ?>')">
                    <i class="fas fa-edit"></i>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>