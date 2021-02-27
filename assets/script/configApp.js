let pValidator;
document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("appContractForm"));;
});

function appContractSetLoading(state) {
  let jsappContractAction = document.querySelectorAll(".jsappContractAction");
  let submitButton = document.getElementById("appContractFormSubmit");
  if (state) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsappContractAction) {
      jsappContractAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsappContractAction) {
      jsappContractAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function appContractSubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
    return;
  }
  appContractSetLoading(true);

  let appContractSendData = {};
  appContractSendData.dateOfDue = document.getElementById("appContractDateOfDue").value;
  appContractSendData.appKey = document.getElementById("appContractAppKey").value;
  appContractSendData.noticeDays = document.getElementById("appContractNoticeDays").value;
  appContractSendData.appContractId = document.getElementById("appContractId").value;

  RequestApi.fetch('/admin/config/updateApp', {
    method: "POST",
    body: appContractSendData,
  })
    .then((res) => {
      if (res.success) {
        SnMessage.success({ content: res.message });
        location.reload();
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      appContractSetLoading(false);
    });
}