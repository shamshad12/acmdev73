var Dashboard = function () {


    return {

        //main function to initiate the module
        init: function () {
            App.addResponsiveHandler(function () {
            });

        },

        getData: function ()
        {
            $.ajax({
                    type: "POST",
                    url: "dashboard/loadData",
                    async: false,
                    dataType: "json",
                    success: function (result) {
                        if (result.success){
                            Dashboard.loadStatAndChart (result);
                        } else {
                            Dashboard.loadStatAndChart (null);
                        }
                    }, 
            });
        },

        loadStatAndChart: function (data)
        {
            var emptyData = (data == null);
            var curData= [], prevData= [], allData=[];
            var revenueData= [], pengeluaranData= [], projectData= [];
            var xTickLabel = [], tickLabel = [], tickLabelPrev = [];
            var revenueColor = ["#d12610", "#37b7f3", "#52e136"],
                pengeluaranColor = ["#d12610", "#37b7f3", "#52e136"],
                projectColor = ["#d12610", "#37b7f3", "#52e136"];

            loadMetroStat ();
            
            $.each(data.revenue['label_current_revenue'], function (i,v) { xTickLabel.push([i,v]); });
            $.each(data.revenue['label_prev_revenue'], function (i,v) { tickLabelPrev.push([i,v]); });
           
			curData= [];
            prevData= [];
            if (!emptyData)
            {    
                $.each(data.revenue['total_current_revenue'], function (i,v) { curData.push([i,v]); });
                $.each(data.revenue['total_prev_revenue'], function (i,v) { prevData.push([i,v]); });
            }
            revenueData = [{data: curData, label: 'Current'},{data: prevData, label: 'Previous'}];
            loadLineChart('revenue', revenueData, revenueColor, data.revenue['label_current_revenue']);

            curData= [];
            prevData= [];
            if (!emptyData)
            {    
                $.each(data.project['total_current_project'], function (i,v) { curData.push([i,v]); });
                $.each(data.project['total_prev_project'], function (i,v) { prevData.push([i,v]); });
            }
            projectData = [{data: curData, label: 'Current'},{data: prevData, label: 'Previous'}];
            loadLineChart('project', projectData, projectColor, data.project['label_current_project']);
            
            curData= [];
            prevData= [];
            if (!emptyData)
            {    
                $.each(data.pengeluaran['total_current_pengeluaran'], function (i,v) { curData.push([i,v]); });
                $.each(data.pengeluaran['total_prev_pengeluaran'], function (i,v) { prevData.push([i,v]); });
            }
            pengeluaranData = [{data: curData, label: 'Current'},{data: prevData, label: 'Previous'}];
            loadLineChart('pengeluaran', pengeluaranData, pengeluaranColor, data.pengeluaran['label_current_pengeluaran']);


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
                $('#d_total_revenue').html(emptyData ? 0 : data.revenue['total_revenue']);
                $('#d_total_pengeluaran').html(emptyData ? 0 : data.pengeluaran['total_pengeluaran']);
                $('#d_total_project').html(emptyData ? 0 : data.project['total_project']);
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
                else if (type == 'project')     
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
                $('#dashboard-report-range span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));

            });

            $('#dashboard-report-range').show();

            $('#dashboard-report-range span').html(Date.today().toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));
        }


    };

}();
