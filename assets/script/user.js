let userState = {
  modalType: "create",
  modalName: "userModalForm",
  loading: false,
};
let pValidator;

function userSetLoading(state) {
  userState.loading = state;
  let jsUserAction = document.querySelectorAll(".jsUserAction");
  let submitButton = document.getElementById("userFormSubmit");
  if (userState.loading) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsUserAction) {
      jsUserAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsUserAction) {
      jsUserAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function userList(page = 1, limit = 10, search = "") {
  let userTable = document.getElementById("userTable");
  if (userTable) {
    SnFreeze.freeze({ selector: "#userTable" });
    RequestApi.fetch(
      `/admin/user/table?limit=${limit}&page=${page}&search=${search}`,
      {
        method: "GET",
      }
    )
      .then((res) => {
        if (res.success) {
          userTable.innerHTML = res.view;
        } else {
          SnModal.error({ title: "Algo salió mal", content: res.message });
        }
      })
      .finally((e) => {
        SnFreeze.unFreeze("#userTable");
      });
  }
}

function userClearForm() {
  let currentForm = document.getElementById("userForm");
  let userEmail = document.getElementById("userEmail");
  if (currentForm && userEmail) {
    currentForm.reset();
    userEmail.focus();
  }
  pValidator.reset();
}

function userSubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
    return;
  }
  userSetLoading(true);

  let url = "";
  let userSendData = {};
  userSendData.password = document.getElementById("userPassword").value;
  userSendData.passwordConfirm = document.getElementById(
    "userPasswordConfirm"
  ).value;
  userSendData.email = document.getElementById("userEmail").value;
  userSendData.userName = document.getElementById("userUserName").value;
  userSendData.fullName = document.getElementById("userFullName").value;
  userSendData.state = document.getElementById("userState").checked || false;
  userSendData.userRoleId = document.getElementById("userUserRoleId").value;

  if (userState.modalType === "update") {
    userSendData.userId = document.getElementById("userId").value || 0;
  }
  if (userState.modalType === "updatePassword") {
    userSendData = {
      password: document.getElementById("userPassword").value,
      passwordConfirm: document.getElementById("userPasswordConfirm").value,
      userId: document.getElementById("userId").value || 0,
    };
  }

  RequestApi.fetch('/admin/user/' + userState.modalType, {
    method: "POST",
    body: userSendData,
  })
    .then((res) => {
      if (res.success) {
        SnModal.close(userState.modalName);
        SnMessage.success({ content: res.message });
        userList();
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      userSetLoading(false);
    });
}

function userDelete(userId, content = "") {
  SnModal.confirm({
    title: "¿Estás seguro de eliminar este registro?",
    content: content,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk() {
      userSetLoading(true);
      RequestApi.fetch("/admin/user/delete", {
        method: "POST",
        body: {
          userId: userId || 0,
        },
      })
        .then((res) => {
          if (res.success) {
            SnMessage.success({ content: res.message });
            userList();
          } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
          }
        })
        .finally((e) => {
          userSetLoading(false);
        });
    },
  });
}

function userShowModalCreate() {
  userState.modalType = "create";
  userClearForm();
  prepareModalUser(userState.modalType);
  SnModal.open(userState.modalName);
}

function userShowModalUpdatePassword(userId) {
  userState.modalType = "updatePassword";
  prepareModalUser(userState.modalType);
  userGetById(userId);
}

function userShowModalUpdate(userId) {
  userState.modalType = "update";
  prepareModalUser(userState.modalType);
  userGetById(userId);
}

function prepareModalUser(mode = "") {
  pValidator.destroy();

  document
    .getElementById("userEmail")
    .parentElement.parentElement.classList.remove("hidden");
  document
    .getElementById("userUserName")
    .parentElement.parentElement.classList.remove("hidden");
  document
    .getElementById("userFullName")
    .parentElement.parentElement.classList.remove("hidden");
  document
    .getElementById("userState")
    .parentElement.parentElement.classList.remove("hidden");
  document
    .getElementById("userUserRoleId")
    .parentElement.classList.remove("hidden");
  document
    .getElementById("userPassword")
    .parentElement.parentElement.classList.remove("hidden");
  document
    .getElementById("userPasswordConfirm")
    .parentElement.parentElement.classList.remove("hidden");

  document.getElementById("userEmail").removeAttribute("required");
  document.getElementById("userUserName").removeAttribute("required");
  document.getElementById("userFullName").removeAttribute("required");
  document.getElementById("userState").removeAttribute("required");
  document.getElementById("userUserRoleId").removeAttribute("required");
  document.getElementById("userPassword").removeAttribute("required");
  document.getElementById("userPasswordConfirm").removeAttribute("required");

  if (mode === "update") {
    document
      .getElementById("userPassword")
      .parentElement.parentElement.classList.add("hidden");
    document
      .getElementById("userPasswordConfirm")
      .parentElement.parentElement.classList.add("hidden");

    document.getElementById("userEmail").setAttribute("required", true);
    document.getElementById("userUserName").setAttribute("required", true);
    document.getElementById("userFullName").setAttribute("required", true);
    document.getElementById("userUserRoleId").setAttribute("required", true);
  } else if (mode === "updatePassword") {
    document
      .getElementById("userEmail")
      .parentElement.parentElement.classList.add("hidden");
    document
      .getElementById("userUserName")
      .parentElement.parentElement.classList.add("hidden");
    document
      .getElementById("userFullName")
      .parentElement.parentElement.classList.add("hidden");
    document
      .getElementById("userState")
      .parentElement.parentElement.classList.add("hidden");
    document
      .getElementById("userUserRoleId")
      .parentElement.classList.add("hidden");

    document.getElementById("userPassword").setAttribute("required", true);
    document
      .getElementById("userPasswordConfirm")
      .setAttribute("required", true);
  } else if (mode === "create") {
    document.getElementById("userState").checked = true;
  }

  pValidator = new Pristine(document.getElementById("userForm"));
}

function userGetById(userId) {
  userClearForm();
  userSetLoading(true);

  RequestApi.fetch("/admin/user/id", {
    method: "POST",
    body: {
      userId: userId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        document.getElementById("userEmail").value = res.result.email;
        document.getElementById("userUserName").value = res.result.user_name;
        document.getElementById("userFullName").value = res.result.full_name;
        document.getElementById("userState").checked =
          res.result.state == "0" ? false : true;
        document.getElementById("userUserRoleId").value =
          res.result.user_role_id;
        document.getElementById("userId").value = res.result.user_id;
        SnModal.open(userState.modalName);
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      userSetLoading(false);
    });
}

function userToExcel() {
  let dataTable = document.getElementById("userCurrentTable");
  if (dataTable) {
    TableToExcel(dataTable.outerHTML, 'Usuario', 'Usuario');
  }
}

function userToPrint() {
  printArea("userCurrentTable");
}

document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("userForm"));

  document.getElementById("searchContent").addEventListener("input", (e) => {
    userList(1, 10, e.target.value);
  });

  userList();
});
