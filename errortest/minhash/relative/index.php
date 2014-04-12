<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ploter</title>
</head>
<body>
<?php include __DIR__ . '/../../menu.php'; ?>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    $.getJSON('plot.php',function (data) {
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: "Absolute Error Against Cardinality"
            },
            xAxis: {
                type: 'logarithmic',
                title: {
                    text: 'Actual'
                }
            },
            yAxis: {
                title: {
                    text: 'Relative Error (%)'
                }
            },
            legend: {
                enabled:false
            },
            plotOptions: {
                scatter: {
                    marker: {
                        radius: 2
                    }
                }
            },
            series: [
                {
                    type: 'scatter',
                    color: 'rgba(223, 83, 83, .5)',
                    data: data[0]
                },
                {
                    type: 'line',
                    data: data[1]
                }
            ]
        })
    });


</script>
</body>
</html>