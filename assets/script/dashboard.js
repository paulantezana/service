document.addEventListener("DOMContentLoaded", () => {
    contractsChart();
    paymentChart();
    document.getElementById("chartStartDate").addEventListener("change", () => {
        contractsChart();
        paymentChart();
    });
    document.getElementById("chartEndDate").addEventListener("change", () => {
        contractsChart();
        paymentChart();
    });
});

let chartColors = {
    red: "rgb(255, 99, 132)",
    orange: "rgb(255, 159, 64)",
    yellow: "rgb(255, 205, 86)",
    green: "rgb(75, 192, 192)",
    blue: "rgb(54, 162, 235)",
    purple: "rgb(153, 102, 255)",
    grey: "rgb(201, 203, 207)",
};

function contractsChart() {
    let startDate = document.getElementById("chartStartDate").value;
    let endDate = document.getElementById("chartEndDate").value;

    SnFreeze.freeze({ selector: '#filterWrapper' });
    RequestApi.fetch("/admin/report/contractChart", {
        method: "POST",
        body: {
            startDate,
            endDate,
        },
    }).then((res) => {
        if (res.success) {
            buildContractsChart(res.result);
        } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
        }
    })
        .finally((e) => {
            SnFreeze.unFreeze();
        });
}

function paymentChart() {
    let startDate = document.getElementById("chartStartDate").value;
    let endDate = document.getElementById("chartEndDate").value;

    SnFreeze.freeze({ selector: '#filterWrapper' });
    RequestApi.fetch("/admin/report/paymentChart", {
        method: "POST",
        body: {
            startDate,
            endDate,
        },
    }).then((res) => {
        if (res.success) {
            buildPaymentChart(res.result);
        } else {
            SnModal.error({ title: "Algo salió mal", content: res.message });
        }
    })
        .finally((e) => {
            SnFreeze.unFreeze();
        });
}

function buildContractsChart(result) {
    let ctx = document.getElementById("contractsChart");
    new Chart(ctx, {
        type: "line",
        data: {
            datasets: [
                {
                    label: "Contratos",
                    backgroundColor: Color(chartColors.blue).alpha(0.5).rgbString(),
                    bcontractColor: chartColors.blue,
                    data: [...result].map((item) => ({
                        x: item.created_at_query,
                        y: item.count,
                    })),
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                xAxes: [
                    {
                        type: "time",
                        time: {
                            parser: "YYYY-MM-DD",
                            round: "day",
                            tooltipFormat: "ll",
                        },
                    },
                ],
            },
        },
    });
}

function buildPaymentChart(result) {
    let ctx = document.getElementById('paymentChart');

    new Chart(ctx, {
        type: "line",
        data: {
            datasets: [
                {
                    label: "Pagos",
                    backgroundColor: Color(chartColors.blue).alpha(0.5).rgbString(),
                    bcontractColor: chartColors.blue,
                    data: [...result].map((item) => ({
                        x: item.created_at_query,
                        y: item.count,
                    })),
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                xAxes: [
                    {
                        type: "time",
                        time: {
                            parser: "YYYY-MM-DD",
                            round: "day",
                            tooltipFormat: "ll",
                        },
                    },
                ],
            },
        },
    });
}