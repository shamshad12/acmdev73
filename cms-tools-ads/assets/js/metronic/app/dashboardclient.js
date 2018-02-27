var Dashboard = function () {
    return {
        //main function to initiate the module
        init: function () {
            App.addResponsiveHandler(function () {
            });
        },

        getData: function (start, end)
        {
            $.ajax({
                    type: "POST",
                    url: "dashboard/_loadDataClient",
                    data: { s: start.toString('yyyy-MM-d'), e: end.toString('yyyy-MM-d') },
                    async: false,
                    dataType: "json",
                    success: function (result) {
                        if (result.status == true)
                            Dashboard.loadStatAndChart (result.data, start, end);
                        else
                            Dashboard.loadStatAndChart (null, start, end);
                    }, 
            });
        },

        loadStatAndChart: function (data, dateStart, dateEnd)
        {
            var emptyData = (data == null);
            var curData= [], prevData= [], allData=[];
            var revenueData= [], transactData= [], performanceData= [];
            var xTickLabel = [], tickLabel = [], tickLabelPrev = [];
            var revenueColor = ["#d12610", "#37b7f3", "#52e136"],
                transactColor = ["#d12610", "#37b7f3", "#52e136"];
            var isDateRange = (dateStart.toString('yyyy-MM-d') !== dateEnd.toString('yyyy-MM-d'));

            loadMetroStat ();

            $('#btn_revenue').button('reset');
            $('#btn_transaction').button('reset');
            
            var _div = (data.size / 10).toFixed(); 
            $.each(data.label.current, function (i,v) { if (i % _div == 0) xTickLabel.push([i,v]); });
            $.each(data.label.current, function (i,v) { tickLabel.push([i,v]); });
            $.each(data.label.previous, function (i,v) { tickLabelPrev.push([i,v]); });
                        
            if (!emptyData)
            {   
                $.each(data.revenue_stat.all.current, function (i,v) { curData.push([i,v]); });
                $.each(data.revenue_stat.all.previous, function (i,v) { prevData.push([i,v]); });
            }
            revenueData = [{data: curData, label: data.legend.current},{data: prevData, label: data.legend.previous}];
            loadLineChart('revenue', revenueData, revenueColor);

            curData= [];
            prevData= [];
            if (!emptyData)
            {    
                $.each(data.transaction_stat.all.current, function (i,v) { curData.push([i,v]); });
                $.each(data.transaction_stat.all.previous, function (i,v) { prevData.push([i,v]); });
            }                
            transactData = [{data: curData, label: data.legend.current},{data: prevData, label: data.legend.previous}];
            loadLineChart('transaction', transactData, transactColor);

            curData= [];
            prevData= [];

            $('#btn_revenue_all').click(function (){
                loadLineChart('revenue', revenueData, revenueColor);
            }); 

            $('#btn_transaction_all').click(function (){
                loadLineChart('transaction', transactData, transactColor);
            });

            function tickFormat(val, axis) {
                if (val >= 1000000)
                    return (val / 1000000).toFixed(axis.tickDecimals) + " M";
                else if (val >= 1000)
                    return (val / 1000).toFixed(axis.tickDecimals) + " K";
                else
                    return val.toFixed(axis.tickDecimals) + "";
            }
            
            function loadMetroStat ()
            {
                $('#d_total_revenue').html(emptyData ? 0 : data.total_revenue);
                $('#d_new_purchase').html(emptyData ? 0 : data.new_purchase);
            }

            function loadLineChart (chartName, chartData, chartColor)
            {
                if ($('#'+chartName+'_chart').size() != 0) {

                    $('#'+chartName+'_chart_loading').hide();
                    $('#'+chartName+'_chart_content').show();

                    var chartPlot = $.plot($('#'+chartName+'_chart'), chartData, 
                        {
                        series: {
                            lines: {
                                show: true,
                                lineWidth: 1,
                                fill: true,
                                fillColor: {
                                    colors: [{
                                            opacity: 0.05
                                        }, {
                                            opacity: 0.01
                                        }
                                    ]
                                }
                            },
                            points: {
                                show: true
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderWidth: 0
                        },
                        colors: chartColor,
                        xaxis: {
                            ticks: xTickLabel
                        },
                        yaxis: {
                            ticks: 5,
                            tickFormatter: tickFormat
                        },
                        legend: {
                            container: $('#legend_'+chartName),
                            noColumns: 3
                        }
                    });

                    var previousPoint = null;
                    var previousSeries = null;
                    $('#'+chartName+'_chart').unbind("plothover");
                    $('#'+chartName+'_chart').bind("plothover", function (event, pos, item) {
                        if (item) {
                            if (previousPoint != item.dataIndex || previousSeries != item.seriesIndex) {
                                previousPoint = item.dataIndex;
                                previousSeries = item.seriesIndex;
                        
                                $("#chart-tooltip").remove();
                                var x = item.datapoint[0].toFixed(0),
                                    y = item.datapoint[1].toFixed(2);

                                var theDate = new Date(parseInt(x));
                                var labelName = "";
                                var dataValue = parseInt(y).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                                
                                dataValue = addValueSign (dataValue, chartName);
                                var dateValue = ((item.seriesIndex == 0) ? tickLabel[item.dataIndex] : tickLabelPrev[item.dataIndex]);
                                //console.log(item.seriesIndex, item.dataIndex, dateValue);
                                if (typeof(dateValue) != 'undefined')
                                {
                                    if (isDateRange) 
                                    {
                                        labelName = '<b>'+ dateValue[1] + '</b>';
                                    }
                                    else
                                    {
                                        labelName = '<b>'+item.series.label+' - '+dateValue[1]+ '</b>';
                                    }
                                    //console.log(labelName, item.series.label, dateValue, item.dataIndex, item.seriesIndex, x, y, dataValue);
                                    showTooltip(labelName, item.pageX, item.pageY, dataValue);
                                }
                            }
                        } else {
                            $("#chart-tooltip").remove();
                            previousPoint = null;
                            previousSeries = null;
                        }
                    });

                }               
            }

            function loadCombineChart (chartName, chartData, chartColor)
            {
                if ($('#'+chartName+'_chart').size() != 0) {

                    $('#'+chartName+'_chart_loading').hide();
                    $('#'+chartName+'_chart_content').show();

                    var chartPlot = $.plot($('#'+chartName+'_chart'), chartData, 
                        {
                        series: {
                            shadowSize: 1,
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#eee",
                            borderWidth: 0
                        },
                        colors: chartColor,
                        xaxis: {
                            ticks: xTickLabel,
                        },
                        yaxis: {
                            ticks: 5,
                            tickFormatter: tickFormat
                        },
                        legend: {
                            container: $('#legend_'+chartName),
                            noColumns: 3
                        }
                    });

                    var previousPoint = null;
                    var previousSeries = null;
                    $('#'+chartName+'_chart').unbind("plothover");
                    $('#'+chartName+'_chart').bind("plothover", function (event, pos, item) {
                        if (item) {
                            if (previousPoint != item.dataIndex || previousSeries != item.seriesIndex) {
                                previousPoint = item.dataIndex;
                                previousSeries = item.seriesIndex;
                        
                                $("#chart-tooltip").remove();
                                var x = item.datapoint[0].toFixed(0),
                                    y = item.datapoint[1].toFixed(2);

                                var theDate = new Date(parseInt(x));
                                var labelName = "";
                                var dataValue = parseInt(y).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

                                dataValue = addValueSign (dataValue, chartName);
                                var dateValue = tickLabel[item.dataIndex];    
                                //console.log(item.seriesIndex, item.dataIndex, dateValue, dateStart, dateStart.toString("d MMM"));
                                if (typeof(dateValue) != 'undefined')
                                {
                                    if (isDateRange) 
                                    {
                                        labelName = '<b>'+ item.series.label + '</br>' +dateValue[1]+  '</b>';
                                    }
                                    else
                                    {
                                        labelName = '<b>'+ item.series.label + '</br>' + dateStart.toString("d MMM")+' - '+dateValue[1]+ '</b>';
                                    }
                                    showTooltip(labelName, item.pageX, item.pageY, dataValue);
                                }
                            }
                        } else {
                            $("#chart-tooltip").remove();
                            previousPoint = null;
                            previousSeries = null;
                        }
                    });
                }              
            }

            function addValueSign (value, type)
            {
                if (type == 'revenue') 
                    return 'Rp.'+value;
                else if (type == 'performance')     
                    return value+'%';
                else 
                    return value;                    
            }

            function showTooltip (title, x, y, contents) {
                $('<div id="chart-tooltip">' + title + '<\/br>' +contents + '<\/div>').css({
                    top: y - 70,
                    left: x - 40
                }).appendTo("body").fadeIn(100);
            }

        },

        initCharts: function () {
            if (!jQuery.plot) {
                return;
            }
            Dashboard.getData(Date.today(),Date.today());
        },

        initDashboardDaterange: function () {

            $('#dashboard-report-range').daterangepicker({
                ranges: {
                    'Today': ['today', 'today'],
                    'Yesterday': ['yesterday', 'yesterday'],
                    'Last 7 Days': [Date.today().add({
                            days: -6
                        }), 'today'],
                    'Last 30 Days': [Date.today().add({
                            days: -29
                        }), 'today']
                },
                opens: (App.isRTL() ? 'right' : 'left'),
                format: 'MM/dd/yyyy',
                separator: ' to ',
                startDate: Date.today(),
                endDate: Date.today(),
                minDate: '01/01/2012',
                maxDate: '12/31/2014',
                customRange: false,
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                },
                showWeekNumbers: true,
                buttonClasses: ['btn maroon']
            },

            function (start, end) {
                Dashboard.getData (start, end);
                /*
                App.blockUI(jQuery("#dashboard"));
                setTimeout(function () {
                    App.unblockUI(jQuery("#dashboard"));
                    $.gritter.add({
                        title: 'Dashboard',
                        text: 'Dashboard date range updated.'
                    });
                    App.scrollTo();
                }, 1000);
                */
                $('#dashboard-report-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));

            });

            $('#dashboard-report-range').show();

            $('#dashboard-report-range span').html(Date.today().toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
        }
    };

}();
