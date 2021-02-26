

document.addEventListener("DOMContentLoaded", () => {
    let searchContent = document.getElementById('searchContent');

    searchContent.addEventListener('keyup', e => {
        if (e.key === "Enter" && e.target.value.length>3) {
            contractSearch(e.target.value);
        }
    });
    currentView = 'paymentPay';
});

function contractSearch(search) {
    RequestApi.fetch("/admin/contract/searchByCustomer", {
        method: "POST",
        body: {
            search
        },
    })
        .then((res) => {
            if (res.success) {
                document.getElementById('contractMatch').innerHTML = res.view;
            } else {
                SnModal.error({ title: "Algo salió mal", content: res.message });
            }
        })
        .finally((e) => {
            //   customerSetLoading(false);
        });
}

function paymentSearchClear(){
    document.getElementById('contractMatch').innerHTML = '';
    document.getElementById('searchContent').value = '';
}