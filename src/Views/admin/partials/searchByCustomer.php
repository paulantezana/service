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
            <?php if (count($parameter['contract']) >= 1) : foreach ($parameter['contract'] as $row) : ?>
                    <tr class="<?= $row['canceled'] == 1 ? 'disabled' : '' ?>">
                        <td><?= $row['contract_id'] ?></td>
                        <td><?= $row['customer_social_reason'] ?></td>
                        <td>
                            <span><?= $row['plan_description'] ?></span>
                            <div>
                                <small><?= $row['plan_speed'] ?> - S/. <?= $row['plan_price'] ?></small>
                            </div>
                        </td>
                        <td><?= $row['datetime_of_issue'] ?></td>
                        <td><?= $row['datetime_of_due'] ?></td>
                        <td>
                            <?php if ($row['payment_count'] > 0) : ?>
                                <?php
                                $months = stringDateDiffMonth($row['datetime_of_issue'], date('Y-m-d'));
                                if ($months < $row['payment_count']) {
                                    echo '<div class="SnTag success">adelanto - ' . ($row['payment_count'] - $months) . ' meses</div>';
                                } else {
                                    echo '<div class="SnTag error">deuda - ' . ($months - $row['payment_count']) . ' meses</div>';
                                }
                                ?>
                                <?php else : if ($row['canceled'] == 0) : ?>
                                    <div class="SnTag error">Sin pago</div>
                            <?php endif;
                            endif; ?>
                        </td>
                        <td><?= $row['observation'] ?></td>
                        <td>
                            <div class="SnTable-action">
                                <button class="SnBtn icon jsContractOption" title="Pagos" onclick="paymentShowModalCreate(<?= $row['contract_id'] ?>)" <?= $row['canceled'] == 1 ? 'disabled' : '' ?>>
                                    <i class="fab fa-paypal"></i>
                                </button>
                                <a class="SnBtn icon jsContractOption" title="Detalles" href="<?= URL_PATH ?>/admin/payment/report?contractId=<?= $row['contract_id'] ?>"><i class="far fa-list-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;
            else : ?>
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