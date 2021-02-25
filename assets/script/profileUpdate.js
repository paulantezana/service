function profileUpdateProfile(event) {
  event.preventDefault();
  let userSendData = {};
  userSendData.userId = document.getElementById("userId").value;
  userSendData.email = document.getElementById("userEmail").value;
  userSendData.userName = document.getElementById("userUserName").value;
  userSendData.fullName = document.getElementById("userFullName").value;

  RequestApi.fetch("/admin/user/updateProfile", {
    method: "POST",
    body: userSendData,
  }).then((res) => {
    if (res.success) {
      SnMessage.success({ content: res.message });
    } else {
      SnModal.error({ title: "Algo salió mal", content: res.message });
    }
  });
}

function profileUpdatePassword(event) {
  event.preventDefault();
  let userSendData = {};
  userSendData.userId = document.getElementById("userId").value;
  userSendData.password = document.getElementById("userPassword").value;
  userSendData.passwordConfirm = document.getElementById(
    "userPasswordConfirm"
  ).value;

  RequestApi.fetch("/admin/user/updatePassword", {
    method: "POST",
    body: userSendData,
  }).then((res) => {
    if (res.success) {
      SnMessage.success({ content: res.message });
      document.getElementById("userPassword").value = "";
      document.getElementById("userPasswordConfirm").value = "";
    } else {
      SnModal.error({ title: "Algo salió mal", content: res.message });
    }
  });
}
