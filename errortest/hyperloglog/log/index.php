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
                type: 'line',
                zoomType: 'xy'
            },
            title: {
                text: "Absolute Error Against Cardinality"
            },
            xAxis: {
                type: 'logarithmic',
                title: {
                    enabled: true,
                    text: 'Actual'
                },
                startOnTick: true,
                endOnTick: true,
                showLastLabel: true
            },
            yAxis: {
                type: 'logarithmic',
                title: {
                    text: 'Estimated'
                }
            },
            legend: {
                enabled : false
            },
            plotOptions: {
                line: {
                    marker: {
                        radius: 2
                    }
                }
            },
            series: [
                {
                    name: 'Actual/Estimated',
                    data: data
                }
            ]
        })
    });


</script>
</body>
</html>