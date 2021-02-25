<div class="SnModal-wrapper" data-modal="paymentModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="paymentModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="fab fa-paypal SnMr-2"></i> Pago</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="paymentForm" onsubmit="paymentSubmit(event)">
                <input type="hidden" class="SnForm-control" id="paymentId">
                <input type="hidden" class="SnForm-control" id="paymentPrice">
                <div style="margin-bottom: 1rem;" id="lastPaymentInfo"></div>
                <div class="SnForm-item required">
                    <label for="paymentReference" class="SnForm-label">Folio</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-file-alt SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="paymentReference" required>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="paymentCount" class="SnForm-label">Número de meses</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-calendar-alt SnControl-prefix"></i>
                        <select class="SnForm-control SnControl" id="paymentCount" onchange="paymentCountChange()">
                            <?php for ($i=1; $i <= 24; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="paymentFromDatetime" class="SnForm-label">Desde - Hasta</label>
                    <div class="SnControlGroup">
                        <div class="SnControl-wrapper SnControlGroup-input">
                            <i class="far fa-calendar-alt SnControl-prefix"></i>
                            <input type="date" class="SnForm-control SnControl" id="paymentFromDatetime" required disabled>
                        </div>
                        <div class="SnControl-wrapper SnControlGroup-input">
                            <i class="far fa-calendar-alt SnControl-prefix"></i>
                            <input type="date" class="SnForm-control SnControl" id="paymentToDatetime" required disabled>
                        </div>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="paymentTotal" class="SnForm-label">Total</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-coins SnControl-prefix"></i>
                        <input type="number" min="0" class="SnForm-control SnControl" id="paymentTotal">
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="paymentFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
    </div>
</div>