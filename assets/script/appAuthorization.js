let userRoleState = {
    modalType: 'create',
    modalName: 'userRoleModalForm',
    loading: false,
    currentUserRoleId: 0,
};
let pValidator;

function userRoleList() {
    let userRoleTable = document.getElementById('userRoleTable');
    if (userRoleTable) {
        SnFreeze.freeze({ selector: '#userRoleTable' });
        RequestApi.fetch('/admin/userRole/list').then(res => {
            if (res.success) {
                userRoleTable.innerHTML = res.view;
            } else {
                SnModal.error({ confirm: false, title: 'Algo salió mal', content: res.message })
            }
        }).finally(e => {
            SnFreeze.unFreeze('#userRoleTable');
        })
    }
}

function userRoleLoadAuthorities(userRoleId, content) {
    userRoleState.currentUserRoleId = userRoleId;
    userRoleSetLoading(true);
    RequestApi.fetch('/admin/appAuthorization/byUserRoleId', {
        method: 'POST',
        body: {
            userRoleId: userRoleId || 0,
        }
    }).then(res => {
        if (res.success) {
            let rows = document.querySelectorAll('#userRoleAuthList [id*="autState"]');
            rows.forEach(item => {
                item.checked = false;
            });

            [...res.result].forEach(item => {
                let autState = document.querySelector(`#userRoleAuthList #autState${item.app_authorization_id}`);
                if (autState) {
                    autState.checked = true;
                }
            });

            [...document.querySelectorAll('[id*="roleRow_"]')].forEach(item=>{
                item.classList.remove('active');
            });
            document.getElementById(`roleRow_${userRoleId}`).classList.add('active');

            document.getElementById('userRoleAuthSave').classList.remove('hidden');
            document.getElementById('userRoleAuthTitle').textContent = content;
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message });
        }
    }).finally(e => {
        userRoleSetLoading(false);
    });
}

function userRoleSaveAuthorization() {
    if (!(userRoleState.currentUserRoleId >= 1)) {
        SnModal.error({ title: 'Algo salió mal', content: 'No se indico el rol' });
        return;
    }

    let rows = document.querySelectorAll('#userRoleAuthList tbody tr');

    let enableAuth = [];
    rows.forEach(item => {
        let authId = item.dataset.id;
        let authState = item.querySelector(`#autState${authId}`);
        if (authState.checked) {
            enableAuth.push(parseInt(authId));
        }
    });

    userRoleSetLoading(true);
    RequestApi.fetch('/admin/appAuthorization/save', {
        method: 'POST',
        body: {
            authIds: enableAuth || [],
            userRoleId: userRoleState.currentUserRoleId || 0,
        }
    }).then(res => {
        if (res.success) {
            SnMessage.success({ content: res.message });
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        userRoleSetLoading(false);
    })
}

function userRoleSetLoading(state) {
    userRoleState.loading = state;
    let jsUserRoleOption = document.querySelectorAll('.jsUserRoleOption');
    let userRoleFormSubmit = document.getElementById('userRoleFormSubmit');
    if (userRoleState.loading) {
        if (userRoleFormSubmit) {
            userRoleFormSubmit.setAttribute('disabled', 'disabled');
            userRoleFormSubmit.classList.add('loading');
            if (jsUserRoleOption) {
                jsUserRoleOption.forEach(item => {
                    item.setAttribute('disabled', 'disabled');
                });
            }
        }
    } else {
        if (userRoleFormSubmit) {
            userRoleFormSubmit.removeAttribute('disabled');
            userRoleFormSubmit.classList.remove('loading');
            if (jsUserRoleOption) {
                jsUserRoleOption.forEach(item => {
                    item.removeAttribute('disabled');
                });
            }
        }
    }
}

function userRoleClearForm() {
    let currentForm = document.getElementById('userRoleForm');
    let userRoleDescription = document.getElementById('userRoleDescription');
    if (currentForm && userRoleDescription) {
        currentForm.reset();
        userRoleDescription.focus();
    }
    pValidator.reset();
}

function userRoleSubmit() {
    event.preventDefault();
    if (!pValidator.validate()) {
        return;
    }
    userRoleSetLoading(true);

    let userRole = {};
    userRole.description = document.getElementById('userRoleDescription').value,
    userRole.userRoleId = document.getElementById('userRoleFormId').value;
    userRole.state = document.getElementById('userRoleState').checked || false;

    RequestApi.fetch('/admin/userRole/' + userRoleState.modalType, {
        method: 'POST',
        body: userRole
    }).then(res => {
        if (res.success) {
            userRoleList();
            SnModal.close(userRoleState.modalName);
            SnMessage.success({ content: res.message });
        } else {
            SnModal.error({ confirm: false, title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        userRoleSetLoading(false);
    })
}

function userRoleDelete(userRoleId, content = '') {
    SnModal.confirm({
        title: '¿Estás seguro de eliminar este registro?',
        content: content,
        okText: 'Si',
        okType: 'error',
        cancelText: 'No',
        onOk() {
            userRoleSetLoading(true);
            RequestApi.fetch('/admin/userRole/delete', {
                method: 'POST',
                body: {
                    userRoleId: userRoleId || 0
                }
            }).then(res => {
                if (res.success) {
                    userRoleList();
                    SnMessage.success({ content: res.message });
                } else {
                    SnModal.error({ title: 'Algo salió mal', content: res.message })
                }
            }).finally(e => {
                userRoleSetLoading(false);
            })
        }
    });
}

function userRoleShowModalCreate() {
    SnModal.open(userRoleState.modalName);
    userRoleClearForm();
    userRoleState.modalType = 'create';
    document.getElementById('userRoleState').checked = true;
}

function userRoleShowModalUpdate(userRoleId, content) {
    userRoleState.modalType = 'update';
    userRoleSetLoading(true);

    RequestApi.fetch('/admin/userRole/id', {
        method: 'POST',
        body: {
            userRoleId: userRoleId || 0
        }
    }).then(res => {
        if (res.success) {
            document.getElementById('userRoleDescription').value = res.result.description;
            document.getElementById('userRoleFormId').value = res.result.user_role_id;
            document.getElementById('userRoleState').checked = res.result.state == '1';
            SnModal.open(userRoleState.modalName);
        } else {
            SnModal.error({ title: 'Algo salió mal', content: res.message })
        }
    }).finally(e => {
        userRoleSetLoading(false);
    })
}


document.addEventListener('DOMContentLoaded', () => {
    pValidator = new Pristine(document.getElementById('userRoleForm'));
    userRoleList();
});