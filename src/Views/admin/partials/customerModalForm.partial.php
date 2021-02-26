<div class="SnModal-wrapper" data-modal="customerModalForm">
    <div class="SnModal">
        <div class="SnModal-close" data-modalclose="customerModalForm">
            <i class="fas fa-times"></i>
        </div>
        <div class="SnModal-header"><i class="far fa-address-book SnMr-2"></i> Cliente</div>
        <div class="SnModal-body">
            <form action="" class="SnForm" novalidate id="customerForm" onsubmit="customerSubmit(event)">
                <input type="hidden" class="SnForm-control" id="customerId">
                <div class="SnForm-item required">
                    <label for="customerIdentityDocumentCode" class="SnForm-label">Tipo de documetno</label>
                    <select id="customerIdentityDocumentCode" class="SnForm-control" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($parameter['identityDocumentType'] ?? [] as $row) : ?>
                            <option value="<?= $row['code'] ?>"><?= $row['description'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="SnForm-item required">
                    <label for="customerDocumentNumber" class="SnForm-label">Número documento</label>
                    <div class="SnControlGroup">
                        <div class="SnControl-wrapper SnControlGroup-input">
                            <i class="fas fa-credit-card SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" id="customerDocumentNumber" required>
                        </div>
                        <div class="SnControlGroup-append">
                            <div class="SnBtn icon primary" id="customerSearchDocument" onclick="CustomerSearchDocument()"><i class="fas fa-search"></i></div>
                        </div>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="customerSocialReason" class="SnForm-label">Razón social / Nombres</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="customerSocialReason" required>
                    </div>
                </div>
                <div class="SnForm-item" style="display: none;">
                    <label for="customerCommercialReason" class="SnForm-label">Nombre comercial</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="customerCommercialReason">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerFiscalAddress" class="SnForm-label">Dirección fiscal</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-street-view SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="customerFiscalAddress">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerEmail" class="SnForm-label">Email</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-envelope SnControl-prefix"></i>
                        <input type="email" class="SnForm-control SnControl" id="customerEmail">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="customerTelephone" class="SnForm-label">Telefono</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-phone-volume SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="customerTelephone">
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="customerFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
    </div>
</div>

<script>
    function SearchDocumentChangeState(state){
        let customerSearchDocument = document.getElementById('customerSearchDocument');
        if(customerSearchDocument){
            if (state) {
                customerSearchDocument.setAttribute("disabled", "disabled");
            } else {
                customerSearchDocument.removeAttribute("disabled");
            }
        }
    }

    function CustomerSearchDocument() {
        let searchIdentityDocumentCode = document.getElementById('customerIdentityDocumentCode').value;
        let searchDocumentNumber = document.getElementById('customerDocumentNumber').value

        SearchDocumentChangeState(true);
        RequestApi.fetch('/admin/customer/queryDocument', {
            method: 'POST',
            body: {
                documentNumber: searchDocumentNumber,
                documentType: searchIdentityDocumentCode,
            }
        })
            .then(res => {
                if (res.success) {
                    document.getElementById('customerSocialReason').value = res.result.social_reason;
                    document.getElementById('customerFiscalAddress').value = res.result.full_address;
                } else {
                    SnModal.error({
                        title: "Algo salió mal",
                        content: res.message
                    });
                }
            })
            .finally(e => {
                SearchDocumentChangeState(false);
            });
    }
</script>