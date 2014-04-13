<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Ploter</title>
</head>
<body>
<?php include __DIR__ . '/../menu.php'; ?>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    function reloadPlot(dataset) {
        $.getJSON('plot.php?dataset=' + dataset, function (data) {
            $('#container').highcharts({
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: "Absolute Error Against Cardinality ("+dataset+")"
                },
                xAxis: {
                    type: 'logarithmic',
                    title: {
                        text: 'Actual Cardinality'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Absolute Error'
                    }
                },
                legend: {
                    enabled: false
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
    }

</script>
</body>
</html>