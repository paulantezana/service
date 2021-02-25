<div class="SnContent">
    <div class="SnGrid col-gap row-gap m-grid-4">
        <div class="m-col-3">
            <form action="" class="SnForm" novalidate id="companyForm" onsubmit="companySubmit(event)">
                <input type="hidden" class="SnForm-control" id="companyId" value="<?= $parameter['company']['company_id'] ?>">
                <div class="SnForm-item required">
                    <label for="companyDocumentNumber" class="SnForm-label">Número documento</label>
                    <div class="SnControlGroup">
                        <div class="SnControl-wrapper SnControlGroup-input">
                            <i class="fas fa-credit-card SnControl-prefix"></i>
                            <input type="text" class="SnForm-control SnControl" id="companyDocumentNumber" value="<?= $parameter['company']['document_number'] ?>" required>
                        </div>
                        <div class="SnControlGroup-append">
                            <div class="SnBtn icon primary" id="companySearchDocument" onclick="CompanySearchDocument()"><i class="fas fa-search"></i></div>
                        </div>
                    </div>
                </div>
                <div class="SnForm-item required">
                    <label for="companySocialReason" class="SnForm-label">Razón social</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="companySocialReason" value="<?= $parameter['company']['social_reason'] ?>" required>
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="companyCommercialReason" class="SnForm-label">Comercial social</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="companyCommercialReason" value="<?= $parameter['company']['commercial_reason'] ?>">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="companyFiscalAddress" class="SnForm-label">Dirección fiscal</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-street-view SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="companyFiscalAddress" value="<?= $parameter['company']['fiscal_address'] ?>">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="companyEmail" class="SnForm-label">Email</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-envelope SnControl-prefix"></i>
                        <input type="email" class="SnForm-control SnControl" id="companyEmail" value="<?= $parameter['company']['email'] ?>">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="companyPhone" class="SnForm-label">Telefono</label>
                    <div class="SnControl-wrapper">
                        <i class="fas fa-phone-volume SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="companyPhone" value="<?= $parameter['company']['phone'] ?>">
                    </div>
                </div>
                <div class="SnForm-item">
                    <label for="companyRepresentative" class="SnForm-label">Reprecentante</label>
                    <div class="SnControl-wrapper">
                        <i class="far fa-user SnControl-prefix"></i>
                        <input type="text" class="SnForm-control SnControl" id="companyRepresentative" value="<?= $parameter['company']['representative'] ?>">
                    </div>
                </div>
                <button type="submit" class="SnBtn lg primary block" id="companyFormSubmit"><i class="fas fa-save SnMr-2"></i>Guardar</button>
            </form>
        </div>
        <div>
            <div class="SnUpload-warapper" id="companyLogoSquareWrapper">
                <?php if ($parameter['company']['logo']): ?>
                    <div class="SnMb-5">
                        <img src="<?php echo URL_PATH . ($parameter['company']['logo'] ?? '') ?>" alt="logo cuadrado" style="width: 100%; display: block;">
                    </div>
                <?php endif; ?>
                <div class="SnForm-item">
                    <label class="SnForm-label" for="businessLogo">Logotipo en formato .JPG de (320px por 320px) menos de 100 KB </label>
                    <input type="file" class="SnForm-control" id="companyLogoSquare"  accept="image/png,image/jpeg,image/jpg">
                </div>
                <button type="button" class="SnBtn primary block" onclick="uploadLogoSquare()">Guardar</button>
            </div>
            <div class="SnUpload-warapper" id="companyLogoLargeWrapper">
                <?php if ($parameter['company']['logo_large']): ?>
                    <div class="SnMb-5">
                        <img src="<?php echo URL_PATH . ($parameter['company']['logo_large'] ?? '') ?>" alt="logo cuadrado" style="width: 100% display: block;">
                    </div>
                <?php endif; ?>
                <div class="SnForm-item">
                    <label class="SnForm-label" for="businessLogo">Logotipo en formato .JPG de (320px por 80px) menos de 100 KB </label>
                    <input type="file" class="SnForm-control" id="companyLogoLarge"  accept="image/png,image/jpeg,image/jpg">
                </div>
                <button type="button" class="SnBtn primary block" onclick="uploadLogoLarge()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_PATH ?>/assets/script/company.js"></script>

<script>
    function SearchDocumentChangeState(state) {
        let companySearchDocument = document.getElementById('companySearchDocument');
        if (companySearchDocument) {
            if (state) {
                companySearchDocument.setAttribute("disabled", "disabled");
            } else {
                companySearchDocument.removeAttribute("disabled");
            }
        }
    }

    function CompanySearchDocument() {
        let searchDocumentNumber = document.getElementById('companyDocumentNumber').value

        SearchDocumentChangeState(true);
        RequestApi.fetch('/admin/customer/queryDocument', {
                method: 'POST',
                body: {
                    documentNumber: searchDocumentNumber,
                    documentType: 6,
                }
            })
            .then(res => {
                if (res.success) {
                    document.getElementById('companySocialReason').value = res.result.social_reason;
                    document.getElementById('companyFiscalAddress').value = res.result.full_address;
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