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
  let searchStartDate = document.getElementById("searchStartDate");
  let searchEndDate = document.getElementById("searchEndDate");
  let urlParams = {
    limit,
    page,
    contractId: currentContractId,
    search,
    searchStartDate: searchStartDate.value,
    searchEndDate: searchEndDate.value,
  };
  let urlString = Object.keys(urlParams).map(key => key + '=' + urlParams[key]).join('&');

  if (paymentTable) {
    SnFreeze.freeze({ selector: "#paymentTable" });
    RequestApi.fetch(
      `/admin/payment/table?${urlString}`,
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
    title: `¿Estás seguro de anular este registro con código: ${paymentId}?`,
    content: 'Ingrese el motivo de la anulación',
    input: true,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk(message) {
      paymentSetLoading(true);
      RequestApi.fetch("/admin/payment/canceled", {
        method: "POST",
        body: {
          paymentId: paymentId || 0,
          message,
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
  document.getElementById("searchContent").addEventListener("keyup", (e) => {
    if (e.key === "Enter" && e.target.value.length > 3) {
      paymentList(1, 10, e.target.value);
    }
  });

  paymentList();
});
