let customerState = {
  modalType: "create",
  modalName: "customerModalForm",
  loading: false,
};
let pValidator;

document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("customerForm"));

  document.getElementById("searchContent").addEventListener("input", (e) => {
    customerList(1, 10, e.target.value);
  });

  customerList();
});

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

function customerList(page = 1, limit = 20, search = "") {
  let customerTable = document.getElementById("customerTable");
  if (customerTable) {
    SnFreeze.freeze({ selector: "#customerTable" });
    RequestApi.fetch(
      `/admin/customer/table?limit=${limit}&page=${page}&search=${search}`,
      {
        method: "GET",
      }
    )
      .then((res) => {
        if (res.success) {
          customerTable.innerHTML = res.view;
        } else {
          SnModal.error({ title: "Algo salió mal", content: res.message });
        }
      })
      .finally((e) => {
        SnFreeze.unFreeze("#customerTable");
      });
  }
}

function customerClearForm() {
  let currentForm = document.getElementById("customerForm");
  let customerEmail = document.getElementById("customerEmail");
  if (currentForm && customerEmail) {
    currentForm.reset();
    customerEmail.focus();
  }
  pValidator.reset();
}

function customerSubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
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
        customerList();
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      customerSetLoading(false);
    });
}

function customerDelete(customerId, content = "") {
  SnModal.confirm({
    title: "¿Estás seguro de eliminar este registro?",
    content: content,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk() {
      customerSetLoading(true);
      RequestApi.fetch("/admin/customer/delete", {
        method: "POST",
        body: {
          customerId: customerId || 0,
        },
      })
        .then((res) => {
          if (res.success) {
            SnMessage.success({ content: res.message });
            customerList();
          } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
          }
        })
        .finally((e) => {
          customerSetLoading(false);
        });
    },
  });
}

function customerShowModalCreate() {
  customerState.modalType = "create";
  customerClearForm();
  SnModal.open(customerState.modalName);
}

function customerShowModalUpdate(customerId) {
  customerState.modalType = "update";
  customerGetById(customerId);
}

function customerGetById(customerId) {
  customerClearForm();
  customerSetLoading(true);

  RequestApi.fetch("/admin/customer/id", {
    method: "POST",
    body: {
      customerId: customerId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        document.getElementById('customerIdentityDocumentCode').value = res.result.identity_document_code;
        document.getElementById('customerDocumentNumber').value = res.result.document_number;
        document.getElementById('customerSocialReason').value = res.result.social_reason;
        document.getElementById('customerCommercialReason').value = res.result.commercial_reason;
        document.getElementById('customerFiscalAddress').value = res.result.fiscal_address;
        document.getElementById('customerEmail').value = res.result.email;
        document.getElementById('customerTelephone').value = res.result.telephone;
        document.getElementById('customerId').value = res.result.customer_id;

        SnModal.open(customerState.modalName);
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      customerSetLoading(false);
    });
}

function customerToExcel() {
  let dataTable = document.getElementById("customerCurrentTable");
  if (dataTable) {
    TableToExcel(dataTable.outerHTML, 'Usuario', 'Usuario');
  }
}

function customerToPrint() {
  printArea("customerCurrentTable");
}
