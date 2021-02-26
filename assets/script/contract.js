let contractState = {
  modalType: "create",
  modalName: "contractModalForm",
  loading: false,
  slimCustomerId: null,
};
let pValidator;

let customerState = {
  modalType: "create",
  modalName: "customerModalForm",
  loading: false,
};
let customerPValidator;

document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("contractForm"));
  customerPValidator = new Pristine(document.getElementById("customerForm"));
  paymentPValidator = new Pristine(document.getElementById("paymentForm"));

  document.getElementById("searchContent").addEventListener("input", (e) => {
    contractList(1, 10, e.target.value);
  });

  contractList();

  contractState.slimCustomerId = new SlimSelect({
    select: '#contractCustomerId',
    searchingText: 'Buscando...',
    // addToBody: true,
    ajax: function (search, callback) {
      if (search.length < 2) {
        callback('Escriba almenos 2 caracteres');
        return
      }
      RequestApi.fetch('/admin/customer/searchBySocialReason', {
        method: 'POST',
        body: { search }
      }).then(res => {
        if (res.success) {
          let data = res.result.map(item => ({ text: item.social_reason, value: item.customer_id }));
          callback(data);
        } else {
          callback(false);
        }
      }).catch(err => {
        callback(false);
      })
    }
  });
});

function contractSetLoading(state) {
  contractState.loading = state;
  let jsContractAction = document.querySelectorAll(".jsContractAction");
  let submitButton = document.getElementById("contractFormSubmit");
  if (contractState.loading) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsContractAction) {
      jsContractAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsContractAction) {
      jsContractAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function contractList(page = 1, limit = 20, search = "") {
  let contractTable = document.getElementById("contractTable");
  if (contractTable) {
    SnFreeze.freeze({ selector: "#contractTable" });
    RequestApi.fetch(
      `/admin/contract/table?limit=${limit}&page=${page}&search=${search}`,
      {
        method: "GET",
      }
    )
      .then((res) => {
        if (res.success) {
          contractTable.innerHTML = res.view;
        } else {
          SnModal.error({ title: "Algo salió mal", content: res.message });
        }
      })
      .finally((e) => {
        SnFreeze.unFreeze("#contractTable");
      });
  }
}

function contractClearForm() {
  let currentForm = document.getElementById("contractForm");
  let contractPlanId = document.getElementById("contractPlanId");
  if (currentForm && contractPlanId) {
    currentForm.reset();
    contractPlanId.focus();
    // contractPlanId.select();
  }
  pValidator.reset();
}

function contractSubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
    return;
  }
  contractSetLoading(true);

  let contractSendData = {};
  contractSendData.customerId = document.getElementById("contractCustomerId").value;
  contractSendData.planId = document.getElementById("contractPlanId").value;
  contractSendData.serverId = document.getElementById("contractServerId").value;
  contractSendData.datetimeOfIssue = document.getElementById("contractDateTimeStart").value;
  contractSendData.datetimeOfDue = document.getElementById("contractDateTimeEnd").value;
  contractSendData.datetimeOfDueEnable = document.getElementById("contractDateTimeEndEnable").value;
  contractSendData.observation = document.getElementById("contractObservation").value;

  if (contractState.modalType === "update") {
    contractSendData.contractId = document.getElementById("contractId").value || 0;
  }

  RequestApi.fetch('/admin/contract/' + contractState.modalType, {
    method: "POST",
    body: contractSendData,
  })
    .then((res) => {
      if (res.success) {
        SnModal.close(contractState.modalName);
        SnMessage.success({ content: res.message });
        contractList();
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      contractSetLoading(false);
    });
}

function contractCanceled(contractId, content = "") {
  SnModal.confirm({
    title: "¿Estás seguro de anular este registro?",
    content: `Código: ${content}`,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk() {
      contractSetLoading(true);
      RequestApi.fetch("/admin/contract/canceled", {
        method: "POST",
        body: {
          contractId: contractId || 0,
        },
      })
        .then((res) => {
          if (res.success) {
            SnMessage.success({ content: res.message });
            contractList();
          } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
          }
        })
        .finally((e) => {
          contractSetLoading(false);
        });
    },
  });
}

function contractShowModalCreate() {
  contractState.modalType = "create";
  contractClearForm();
  SnModal.open(contractState.modalName);
}

function contractShowModalUpdate(contractId) {
  contractState.modalType = "update";
  contractGetById(contractId);
}

function contractGetById(contractId) {
  contractClearForm();
  contractSetLoading(true);

  RequestApi.fetch("/admin/contract/id", {
    method: "POST",
    body: {
      contractId: contractId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        document.getElementById('contractCustomerId').value = res.result.customer_id;
        document.getElementById('contractPlanId').value = res.result.plan_id;
        document.getElementById('contractServerId').value = res.result.server_id;
        document.getElementById('contractDateTimeStart').value = res.result.datetime_of_issue;
        document.getElementById('contractDateTimeEnd').value = res.result.datetime_of_due;
        document.getElementById('contractDateTimeEndEnable').value = res.result.datetime_of_due_enable;
        document.getElementById('contractObservation').value = res.result.observation;
        document.getElementById('contractId').value = res.result.contract_id;

        SnModal.open(contractState.modalName);
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      contractSetLoading(false);
    });
}

function contractToExcel() {
  let dataTable = document.getElementById("contractCurrentTable");
  if (dataTable) {
    TableToExcel(dataTable.outerHTML, 'Contract', 'Contractes');
  }
}

function contractToPrint() {
  printArea("contractCurrentTable");
}


// CUSTOMER
function customerSetLoading(state) {
  customerState.loading = state;
  let jsCustomerAction = document.querySelectorAll(".jsCustomerAction");
  let submitButton = document.getElementById("customerFormSubmit");
  if (customerState.loading) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsCustomerAction) {
      jsCustomerAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsCustomerAction) {
      jsCustomerAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function customerShowModalCreate() {
  customerState.modalType = "create";
  customerClearForm();
  SnModal.open(customerState.modalName);
}

function customerClearForm() {
  let currentForm = document.getElementById("customerForm");
  let customerEmail = document.getElementById("customerEmail");
  if (currentForm && customerEmail) {
    currentForm.reset();
    customerEmail.focus();
  }
  customerPValidator.reset();
}

function customerSubmit(e) {
  e.preventDefault();
  if (!customerPValidator.validate()) {
    return;
  }
  customerSetLoading(true);

  let customerSendData = {};
  customerSendData.identityDocumentCode = document.getElementById("customerIdentityDocumentCode").value;
  customerSendData.documentNumber = document.getElementById("customerDocumentNumber").value;
  customerSendData.socialReason = document.getElementById("customerSocialReason").value;
  customerSendData.commercialReason = document.getElementById("customerCommercialReason").value;
  customerSendData.fiscalAddress = document.getElementById("customerFiscalAddress").value;
  customerSendData.email = document.getElementById("customerEmail").value;
  customerSendData.telephone = document.getElementById("customerTelephone").value;

  if (customerState.modalType === "update") {
    customerSendData.customerId = document.getElementById("customerId").value || 0;
  }

  RequestApi.fetch('/admin/customer/' + customerState.modalType, {
    method: "POST",
    body: customerSendData,
  })
    .then((res) => {
      if (res.success) {
        SnModal.close(customerState.modalName);
        SnMessage.success({ content: res.message });
        contractState.slimCustomerId.setData([
          {
            text: customerSendData.socialReason,
            value: res.result,
          },
        ]);
        contractState.slimCustomerId.set(res.result);
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      customerSetLoading(false);
    });
}