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

  RequestApi.fetch("/admin/user/updateProfilePassword", {
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

function updateProfileAvatar() {
  let element = document.getElementById('userProfileAvatar');
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
      title: "¿Estás seguro de subir el foto de perfil?",
      content: 'foto de perfil',
      okText: "Si",
      okType: "error",
      cancelText: "No",
      onOk() {
        let data = new FormData();
        data.append('avatar', archivo);
        data.append('userId', document.getElementById("userId").value);

        SnFreeze.freeze({ selector: '#userProfileAvatarWrapper' });
        RequestApi.fetch("/admin/user/updateProfileAvatar", {
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
            SnFreeze.unFreeze('#userProfileAvatarWrapper');
          });
      },
    });
  } else {
    SnModal.error({ title: "Error de usuario", content: 'El archivo tiene formato o tamaño incorrecto, solo se aceptan archivos con extension [image/png,image/jpeg,image/jpg]. y un tamaño maximo de 100Kb.' });
  }
}