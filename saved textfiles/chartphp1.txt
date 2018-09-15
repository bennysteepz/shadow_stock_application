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
$.getJSON("arraydata.php", function(json) {
 
    chart = new Highcharts.Chart({
        chart: {
        renderTo: 'container',
        type: 'line',
        marginRight: 130,
        marginBottom: 25
        },
        title: {
        text: 'Guru1',
        x: -20 //center
        },
        subtitle: {
        text: '',
        x: -20
        },
        xAxis: {
        type: 'datetime',
        },
        yAxis: {
        title: {
        text: 'PL'
        },
        plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
        }]
        },
        tooltip: {
        formatter: function() {
        return '<b>'+ this.series.name +'</b><br/>'+
        this.x +': '+ this.y;
        }
        },
        legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -10,
        y: 100,
        borderWidth: 0
        },
        series: json
    });
});
 
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