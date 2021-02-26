let planState = {
  modalType: "create",
  modalName: "planModalForm",
  loading: false,
};
let pValidator;

function planSetLoading(state) {
  planState.loading = state;
  let jsPlanAction = document.querySelectorAll(".jsPlanAction");
  let submitButton = document.getElementById("planFormSubmit");
  if (planState.loading) {
    if (submitButton) {
      submitButton.setAttribute("disabled", "disabled");
      submitButton.classList.add("loading");
    }
    if (jsPlanAction) {
      jsPlanAction.forEach((item) => {
        item.setAttribute("disabled", "disabled");
      });
    }
  } else {
    if (submitButton) {
      submitButton.removeAttribute("disabled");
      submitButton.classList.remove("loading");
    }
    if (jsPlanAction) {
      jsPlanAction.forEach((item) => {
        item.removeAttribute("disabled");
      });
    }
  }
}

function planList(page = 1, limit = 20, search = "") {
  let planTable = document.getElementById("planTable");
  if (planTable) {
    SnFreeze.freeze({ selector: "#planTable" });
    RequestApi.fetch(
      `/admin/plan/table?limit=${limit}&page=${page}&search=${search}`,
      {
        method: "GET",
      }
    )
      .then((res) => {
        if (res.success) {
          planTable.innerHTML = res.view;
        } else {
          SnModal.error({ title: "Algo salió mal", content: res.message });
        }
      })
      .finally((e) => {
        SnFreeze.unFreeze("#planTable");
      });
  }
}

function planClearForm() {
  let currentForm = document.getElementById("planForm");
  let planDescripcion = document.getElementById("planDescripcion");
  if (currentForm && planDescripcion) {
    currentForm.reset();
    planDescripcion.focus();
    planDescripcion.select();
  }
  pValidator.reset();
}

function planSubmit(e) {
  e.preventDefault();
  if (!pValidator.validate()) {
    return;
  }
  planSetLoading(true);

  let planSendData = {};
  planSendData.description = document.getElementById("planDescripcion").value;
  planSendData.speed = document.getElementById("planSpeed").value;
  planSendData.price = document.getElementById("planPrice").value;

  if (planState.modalType === "update") {
    planSendData.planId = document.getElementById("planId").value || 0;
  }

  RequestApi.fetch('/admin/plan/' + planState.modalType, {
    method: "POST",
    body: planSendData,
  })
    .then((res) => {
      if (res.success) {
        SnModal.close(planState.modalName);
        SnMessage.success({ content: res.message });
        planList();
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      planSetLoading(false);
    });
}

function planDelete(planId, content = "") {
  SnModal.confirm({
    title: "¿Estás seguro de eliminar este registro?",
    content: content,
    okText: "Si",
    okType: "error",
    cancelText: "No",
    onOk() {
      planSetLoading(true);
      RequestApi.fetch("/admin/plan/delete", {
        method: "POST",
        body: {
          planId: planId || 0,
        },
      })
        .then((res) => {
          if (res.success) {
            SnMessage.success({ content: res.message });
            planList();
          } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
          }
        })
        .finally((e) => {
          planSetLoading(false);
        });
    },
  });
}

function planShowModalCreate() {
  planState.modalType = "create";
  planClearForm();
  SnModal.open(planState.modalName);
}

function planShowModalUpdate(planId) {
  planState.modalType = "update";
  planGetById(planId);
}

function planGetById(planId) {
  planClearForm();
  planSetLoading(true);

  RequestApi.fetch("/admin/plan/id", {
    method: "POST",
    body: {
      planId: planId || 0,
    },
  })
    .then((res) => {
      if (res.success) {
        document.getElementById('planDescripcion').value = res.result.description;
        document.getElementById('planSpeed').value = res.result.speed;
        document.getElementById('planPrice').value = res.result.price;
        document.getElementById('planId').value = res.result.plan_id;

        SnModal.open(planState.modalName);
      } else {
        SnModal.error({ title: "Algo salió mal", content: res.message });
      }
    })
    .finally((e) => {
      planSetLoading(false);
    });
}

function planToExcel() {
  let dataTable = document.getElementById("planCurrentTable");
  if (dataTable) {
    TableToExcel(dataTable.outerHTML, 'Plan', 'Planes');
  }
}

function planToPrint() {
  printArea("planCurrentTable");
}

document.addEventListener("DOMContentLoaded", () => {
  pValidator = new Pristine(document.getElementById("planForm"));

  document.getElementById("searchContent").addEventListener("input", (e) => {
    planList(1, 10, e.target.value);
  });

  planList();
});
