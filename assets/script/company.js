let pValidator;
document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("companyForm"));;
});

function companySetLoading(state) {
  let jsCompanyAction = document.querySelectorAll(".jsCompanyAction");
  let submitButton = document.getElementById("companyFormSubmit");
  if (state) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsCompanyAction) {
      jsCompanyAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsCompanyAction) {
      jsCompanyAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function companySubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
    return;
  }
  companySetLoading(true);

  let companySendData = {};
  companySendData.documentNumber = document.getElementById("companyDocumentNumber").value;
  companySendData.socialReason = document.getElementById("companySocialReason").value;
  companySendData.commercialReason = document.getElementById("companyCommercialReason").value;
  companySendData.fiscalAddress = document.getElementById("companyFiscalAddress").value;
  companySendData.email = document.getElementById("companyEmail").value;
  companySendData.phone = document.getElementById("companyPhone").value;
  companySendData.representative = document.getElementById("companyRepresentative").value;
  companySendData.companyId = document.getElementById("companyId").value || 0;

  RequestApi.fetch('/admin/company/update', {
    method: "POST",
    body: companySendData,
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
      companySetLoading(false);
    });
}

function uploadLogoSquare() {
  let element = document.getElementById('companyLogoSquare');
  if (element == null) {
    return;
  }

  if (element.files === undefined) {
    SnModal.error({ title: "Error de usuario", content: 'Elije almenos un archivo' });
    return;
  }

  let archivo = element.files[0];

  if (archivo == undefined || archivo == null) {
    SnModal.error({ title: "Error de usuario", content: 'Elije almenos un archivo' });
    return;
  }

  if (validateFile(archivo, ['image/png', 'image/jpeg', 'image/jpg'], 100)) {
    SnModal.confirm({
      title: "¿Estás seguro de subir el logo?",
      content: 'Logo cuadrada de la empresa',
      okText: "Si",
      okType: "error",
      cancelText: "No",
      onOk() {
        let data = new FormData();
        data.append('logo', archivo);
        data.append('companyId', document.getElementById("companyId").value);

        SnFreeze.freeze({ selector: '#companyLogoSquareWrapper'});
        RequestApi.fetch("/admin/company/uploadLogoSquare", {
          method: "POST",
          body: data,
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
            SnFreeze.unFreeze('#companyLogoSquareWrapper');
          });
      },
    });
  } else {
    SnModal.error({ title: "Error de usuario", content: 'El archivo tiene formato o tamaño incorrecto, solo se aceptan archivos con extension [image/png,image/jpeg,image/jpg]. y un tamaño maximo de 100Kb.' });
  }
}

function uploadLogoLarge() {
  let element = document.getElementById('companyLogoLarge');
  if (element == null) {
    return;
  }

  if (element.files === undefined) {
    SnModal.error({ title: "Error de usuario", content: 'Elije almenos un archivo' });
    return;
  }

  let archivo = element.files[0];

  if (archivo == undefined || archivo == null) {
    SnModal.error({ title: "Error de usuario", content: 'Elije almenos un archivo' });
    return;
  }

  if (validateFile(archivo, ['image/png', 'image/jpeg', 'image/jpg'], 100)) {
    SnModal.confirm({
      title: "¿Estás seguro de subir el logo?",
      content: 'Logo largo de la empresa',
      okText: "Si",
      okType: "error",
      cancelText: "No",
      onOk() {
        let data = new FormData();
        data.append('logo', archivo);
        data.append('companyId', document.getElementById("companyId").value);

        SnFreeze.freeze({ selector: '#companyLogoLargeWrapper'});
        RequestApi.fetch("/admin/company/uploadLogoLarge", {
          method: "POST",
          body: data,
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
            SnFreeze.unFreeze('#companyLogoLargeWrapper');
          });
      },
    });
  } else {
    SnModal.error({ title: "Error de usuario", content: 'El archivo tiene formato o tamaño incorrecto, solo se aceptan archivos con extension [image/png,image/jpeg,image/jpg]. y un tamaño maximo de 100Kb.' });
  }
}