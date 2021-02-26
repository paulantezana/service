function paymentSetLoading(state) {
  let jsPaymentAction = document.querySelectorAll(".jsPaymentAction");
  let submitButton = document.getElementById("paymentFormSubmit");
  if (state) {
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

function paymentList(page = 1, limit = 20, search = "") {
  let paymentTable = document.getElementById("paymentTable");
  if (paymentTable) {
    SnFreeze.freeze({ selector: "#paymentTable" });
    RequestApi.fetch(
      `/admin/payment/table?limit=${limit}&page=${page}&search=${search}&contractId=${currentContractId}`,
      {
        method: "GET",
      }
    )
      .then((res) => {
        if (res.success) {
          paymentTable.innerHTML = res.view;
        } else {
          SnModal.error({ title: "Algo salió mal", content: res.message });
        }
      })
      .finally((e) => {
        SnFreeze.unFreeze("#paymentTable");
      });
  }
}

function paymentCanceled(paymentId, content = "") {
  SnModal.confirm({
    title: "¿Estás seguro de anular este registro?",
    content: content,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk() {
      paymentSetLoading(true);
      RequestApi.fetch("/admin/payment/canceled", {
        method: "POST",
        body: {
          paymentId: paymentId || 0,
        },
      })
        .then((res) => {
          if (res.success) {
            SnMessage.success({ content: res.message });
            paymentList();
          } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
          }
        })
        .finally((e) => {
          paymentSetLoading(false);
        });
    },
  });
}


function paymentToExcel() {
  let dataTable = document.getElementById("paymentCurrentTable");
  if (dataTable) {
    TableToExcel(dataTable.outerHTML, 'Payment', 'Paymentes');
  }
}

function paymentToPrint() {
  printArea("paymentCurrentTable");
}

document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("searchContent").addEventListener("input", (e) => {
    paymentList(1, 10, e.target.value);
  });

  paymentList();
});
