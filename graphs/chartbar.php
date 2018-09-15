<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Highcharts Example</title>
 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
    $.getJSON("arraydatabar.php", function(json) {
        options.series = json;
        chart = new Highcharts.Chart(options);
    });
        var options = {
        chart: {
            renderTo: 'container',
            type: 'bar',
            marginRight: 130,
            marginBottom: 80
        },
        title: {
            text: ''
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: ${point.y}<br/>Total: ${point.stackTotal}'
        },
        xAxis: {
            categories: ['Total Invested','Current Value']
        },
        yAxis: {
           labels: {
                formatter: function () {
                    return '$' + this.axis.defaultLabelFormatter.call(this);
                }            
            }
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                formatter: function () {
                    return this.series.name + " $" + this.y;
                }
                }
            },
        },
        series: []
        }
});
});
 
</script>
</head>
<body>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
 
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
 
</body>
</html>