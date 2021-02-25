function queryRucSubmit() {
    let query = {};
    query.ruc = document.getElementById("ruc").value;
    query.googleKey = document.getElementById("googleKey").value;

    RequestApi.fetch("/page/rucQuery/", {
        method: "POST",
        body: query,
    })
        .then((res) => {
            document.getElementById("queryRucResult").innerHTML = "";
            if (res.success) {
                SnMessage.success({ content: res.message });
                document.getElementById("queryRucResult").innerHTML = res.view;
                document.getElementById("queryRuc").style.display = 'none';
            } else {
                SnModal.error({
                    title: "Algo salió mal",
                    content: res.message,
                });
            }
        })
        .finally((e) => {
            // userSetLoading(false);
        });
}


function queryRucSubmitNewQuery(){
  location.reload();
}