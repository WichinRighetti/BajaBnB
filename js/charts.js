function chart(){
    showChart('Reservations', 'Subtitle');
}

function getData(){
    //create request
    var x = new XMLHttpRequest();
    //prepare request
    x.open('GET', 'http://localhost/BajaBnB/controllers/PropertyController.php?id='+sessionStorage.siteId, true);
    //send request
    x.send();
    //handle ready state change event
    x.onreadystatechange = function(){
        //check status
        if(x.status == 200 && x.readyState == 4){
            var jsonData = JSON.parse(x.responseText);
            if(jsonData.status ==0){
                prepareChart(jsonData); }
        }
    }
}

function prepareChart(data){
    //data array
    var xAxisCategories = [];
    var seriesData = [];
    var records = data.property.reservations;

    //read data
    records.forEach(function(item){
        xAxisCategories.push(item.startDate);
        seriesData.push(item.qty);
    });

    showChart(data.property.propertyName, data.property.propertyDescription, xAxisCategories, "Quantity", seriesData);
}

function showChart(chartTitle, chartSubtitle, xAxisCategories, seriesName, seriesData){
     // Create the chart    
     Highcharts.getJSON('https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/samples/data/usdeur.json',        
     function(){
        Highcharts.chart('chart',{
            chart: {
                zoomType: "x"
            },
            title: {
                text : chartTitle
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                chartSubtitle : "Pinch the chart to zoom in"
            },
            xAxis: {
                reversed: false,
                categories: xAxisCategories
            },
            yAxis: {
                title:{
                    text: seriesName
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 1,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[1]],
                            [1, Highcharts.color(Highcharts.getOptions().colors[1]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 2
                        }
                    },
                    threshold: null
                }
            },
            series: [{
                type: 'column',
                name: "Date",
                data: seriesData
            }]
        });
    }
    );
    //refresh data
    //setInterval('getData()',4000);
}