var barChartData1 = {
    labels: dept_codes_with_at_least_one_requirement,
    datasets: [{
            label: "Partially Complied",
            backgroundColor: "#2671B4",
            data: partially_complied_ones,
        },
        {
            label: "Not Complied",
            backgroundColor: "#FC4C2B",
            data: not_complied_ones,
        },
        {
            label: "Complied",
            backgroundColor: "#58D68D",
            data: complied_ones,
        },
    ]
};
var barChartData2 = {
    labels: depcode,
    datasets: [{
        label: 'Under Review',
        backgroundColor: "#032553",
        data: under_review_collect
    }]
};
var barChartData3 = {
    labels: depcode,
    datasets: [{
        label: "Due Compliance Evaluation",
        backgroundColor: "#af1e1e",
        data: due_collect,
    }],
};

var barChartData4 = {
    labels: depcode,
    datasets: [{
        label: "Awaiting Compliance Evaluation",
        backgroundColor: "#032553",
        data: awaiting_compliance_collect,
    }],
};



function drawChart(ctx, theData) {
    window.myBar = new Chart(ctx, {
        type: 'bar',
        data: theData,
        options: {
            elements: {
                rectangle: {
                    borderWidth: 0,
                    borderColor: '#c1c1c1',
                    borderSkipped: 'bottom',
                }
            },
            legend: {
                position: 'bottom'
            },
            responsive: true,
            title: {
                display: true,
                text: ''
            }
        }
    });
}
window.onload = function() {
    var ctx = document.getElementById("canvas").getContext("2d");
    drawChart(ctx, barChartData1);
    var ctx1 = document.getElementById("canvas1").getContext("2d");
    drawChart(ctx1, barChartData2);
    var ctx2 = document.getElementById("canvas2").getContext("2d");
    drawChart(ctx2, barChartData3);
    var ctx3 = document.getElementById("canvas3").getContext("2d");
    drawChart(ctx3, barChartData4);
};