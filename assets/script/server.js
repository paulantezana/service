let serverState = {
  modalType: "create",
  modalName: "serverModalForm",
  loading: false,
};
let pValidator;

function serverSetLoading(state) {
  serverState.loading = state;
  let jsServerAction = document.querySelectorAll(".jsServerAction");
  let submitButton = document.getElementById("serverFormSubmit");
  if (serverState.loading) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsServerAction) {
      jsServerAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsServerAction) {
      jsServerAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function serverList(page = 1, limit = 20, search = "") {
  let serverTable = document.getElementById("serverTable");
  if (serverTable) {
    SnFreeze.freeze({ selector: "#serverTable" });
    RequestApi.fetch(
      `/admin/server/table?limit=${limit}&page=${page}&search=${search}`,
      {
        method: "GET",
      }
    )
      .then((res) => {
        if (res.success) {
          serverTable.innerHTML = res.view;
        } else {
          SnModal.error({ title: "Algo salió mal", content: res.message });
        }
      })
      .finally((e) => {
        SnFreeze.unFreeze("#serverTable");
      });
  }
}

function serverClearForm() {
  let currentForm = document.getElementById("serverForm");
  let serverDescripcion = document.getElementById("serverDescripcion");
  if (currentForm && serverDescripcion) {
    currentForm.reset();
    serverDescripcion.focus();
    serverDescripcion.select();
  }
  pValidator.reset();
}

function serverSubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
    return;
  }
  serverSetLoading(true);

  let serverSendData = {};
  serverSendData.description = document.getElementById("serverDescripcion").value;
  serverSendData.address = document.getElementById("serverAddress").value;

  if (serverState.modalType === "update") {
    serverSendData.serverId = document.getElementById("serverId").value || 0;
  }

  RequestApi.fetch('/admin/server/' + serverState.modalType, {
    method: "POST",
    body: serverSendData,
  })
    .then((res) => {
      if (res.success) {
        SnModal.close(serverState.modalName);
        SnMessage.success({ content: res.message });
        serverList();
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      serverSetLoading(false);
    });
}

function serverDelete(serverId, content = "") {
  SnModal.confirm({
    title: "¿Estás seguro de eliminar este registro?",
    content: content,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk() {
      serverSetLoading(true);
      RequestApi.fetch("/admin/server/delete", {
        method: "POST",
        body: {
          serverId: serverId || 0,
        },
      })
        .then((res) => {
          if (res.success) {
            SnMessage.success({ content: res.message });
            serverList();
          } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
          }
        })
        .finally((e) => {
          serverSetLoading(false);
        });
    },
  });
}

function serverShowModalCreate() {
  serverState.modalType = "create";
  serverClearForm();
  SnModal.open(serverState.modalName);
}

function serverShowModalUpdate(serverId) {
  serverState.modalType = "update";
  serverGetById(serverId);
}

function serverGetById(serverId) {
  serverClearForm();
  serverSetLoading(true);

  RequestApi.fetch("/admin/server/id", {
    method: "POST",
    body: {
      serverId: serverId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        document.getElementById('serverDescripcion').value = res.result.description;
        document.getElementById('serverAddress').value = res.result.address;
        document.getElementById('serverId').value = res.result.server_id;

        SnModal.open(serverState.modalName);
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      serverSetLoading(false);
    });
}

function serverToExcel() {
  let dataTable = document.getElementById("serverCurrentTable");
  if (dataTable) {
    TableToExcel(dataTable.outerHTML, 'Server', 'Serveres');
  }
}

function serverToPrint() {
  printArea("serverCurrentTable");
}

document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("serverForm"));

  document.getElementById("searchContent").addEventListener("input", (e) => {
    serverList(1, 10, e.target.value);
  });

  serverList();
});
