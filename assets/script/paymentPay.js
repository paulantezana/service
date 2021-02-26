let currentContractId = 0;
let currentView = 'contract';

let paymentState = {
    modalType: "create",
    modalName: "paymentModalForm",
    loading: false,
};
let paymentPValidator;

document.addEventListener("DOMContentLoaded", () => {
    paymentPValidator = new Pristine(document.getElementById("paymentForm"));
});


function paymentSetLoading(state) {
    paymentState.loading = state;
    let jsPaymentAction = document.querySelectorAll(".jsPaymentAction");
    let submitButton = document.getElementById("paymentFormSubmit");
    if (paymentState.loading) {
        if (submitButton) {
            submitButton.setAttribute("disabled", "disabled");
            submitButton.classList.add("loading");
        }
        if (jsPaymentAction) {
            jsPaymentAction.forEach((item) => {
                item.setAttribute("disabled", "disabled");
            });
        }
    } else {
        if (submitButton) {
            submitButton.removeAttribute("disabled");
            submitButton.classList.remove("loading");
        }
        if (jsPaymentAction) {
            jsPaymentAction.forEach((item) => {
                item.removeAttribute("disabled");
            });
        }
    }
}

function paymentShowModalCreate(contractId) {
    paymentSetLoading(true);
    RequestApi.fetch("/admin/payment/lastPaymentByContractId", {
        method: "POST",
        body: {
            contractId: contractId || 0,
        },
    })
        .then((res) => {
            if (res.success) {
                paymentClearForm();
                SnModal.open(paymentState.modalName);

                let lastPayment = res.result.lastPayment;
                let contract = res.result.contract;
                currentContractId = contract.contract_id;

                let paymentFromDatetime = lastPayment === false ? contract.datetime_of_issue : lastPayment.to_datetime;
                let MfromDateTome = moment(paymentFromDatetime);
                document.getElementById('paymentFromDatetime').value = MfromDateTome.format('YYYY-MM-DD');
                document.getElementById('paymentPrice').value = contract.plan_price;

                let lastPaymentInfo = document.getElementById('lastPaymentInfo');
                if (lastPayment === false) {
                    lastPaymentInfo.innerHTML = 'No existe pagos previos';
                } else {
                    lastPaymentInfo.innerHTML = `<strong>Ultimo pago</strong>
            <div>
                <div><strong>Descripcion:</strong><span> ${lastPayment.description}</span></div>
                <div><strong>Fecha pago:</strong><span> ${moment(lastPayment.datetime_of_issue).format('LLLL')}</span></div>
                <div><strong>Rango:</strong><span style="display: flex;justify-content: space-between;">desde <span class="SnTag success">${moment(lastPayment.from_datetime).format('LL')}</span> hasta <span class="SnTag success">${moment(lastPayment.to_datetime).format('LL')}</span></span></div>
            </div>`;
                }

                document.getElementById('paymentDescription').innerHTML = `${contract.plan_description} - ${contract.plan_speed}`;

                paymentCountChange();
            } else {
                SnModal.error({ title: "Algo salió mal", content: res.message });
            }
        })
        .finally((e) => {
            paymentSetLoading(false);
        });
}

function paymentClearForm() {
    let currentForm = document.getElementById("paymentForm");
    let paymentReference = document.getElementById("paymentReference");
    if (currentForm && paymentReference) {
        currentForm.reset();
        paymentReference.focus();
    }
    paymentPValidator.reset();
}

function paymentSubmit(e) {
    e.preventDefault();
    if (!paymentPValidator.validate()) {
        return;
    }
    paymentSetLoading(true);

    let paymentSendData = {};
    paymentSendData.reference = document.getElementById("paymentReference").value;
    paymentSendData.paymentCount = document.getElementById("paymentCount").value;
    paymentSendData.fromDatetime = document.getElementById("paymentFromDatetime").value;
    paymentSendData.toDatetime = document.getElementById("paymentToDatetime").value;
    paymentSendData.description = document.getElementById("paymentDescription").value;
    paymentSendData.total = document.getElementById("paymentTotal").value;
    paymentSendData.contractId = currentContractId;

    if (paymentState.modalType === "update") {
        paymentSendData.paymentId = document.getElementById("paymentId").value || 0;
    }

    RequestApi.fetch('/admin/payment/' + paymentState.modalType, {
        method: "POST",
        body: paymentSendData,
    })
        .then((res) => {
            if (res.success) {
                SnModal.close(paymentState.modalName);
                SnMessage.success({ content: res.message });
                paymentPrint(res.result);
                if(currentView === 'contract'){
                    contractList(); // Execute function in contract.js
                } else if(currentView === 'paymentPay'){
                    paymentSearchClear(); // Execute funcion in payment.js
                }
            } else {
                SnModal.error({ title: "Algo salió mal", content: res.message });
            }
        })
        .finally((e) => {
            paymentSetLoading(false);
        });
}

function paymentCountChange() {
    let paymentNumberMonth = document.getElementById('paymentCount').value;
    let paymentFromDatetime = document.getElementById('paymentFromDatetime').value;
    let paymentPrice = document.getElementById('paymentPrice').value;

    let Mdate = moment(paymentFromDatetime);
    Mdate.add(paymentNumberMonth, 'months');

    let total = parseFloat(paymentPrice) * parseFloat(paymentNumberMonth);
    document.getElementById('paymentToDatetime').value = Mdate.format('YYYY-MM-DD');
    document.getElementById('paymentTotal').value = total.toFixed(2);
}