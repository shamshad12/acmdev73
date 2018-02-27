function loadgraphrevenue_vendor(page){		
	var adspublisher = $('#ads-publisher-list').val();
	var partner = $('#partner-list').val();
	jQuery.ajax({
			url:  domain+"campaign_manager/graphrevenue_vendor/loadgraphrevenue_vendor",
			dataType: "json",
			type: "POST",
			data: {
					'ads-publisher-list': adspublisher,
					'partner-list': partner
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										var result = '';
										var arr = $.map(data.revenue, function(el) { return el; });
										var comma, key='', strdata='';
										for(var i = 1; i<=arr.length; i++)
										{
											if(i==arr.length)
												comma = '';
											else
												comma = ',';

											key += i+comma; 
											strdata += arr[i-1]+comma;
										}
										if(data.count){
											$('#container').highcharts({
									            title: {
									                text: 'Monthly Revenue Vendor wise'
									            },
									            subtitle: {
									                text: 'Vendor: '+data.publisher
									            },
									            xAxis: {
									                categories: key.split(","),
									               	title: {
									                    text: 'Months'
									                }	
									            },
									            yAxis: {
									                title: {
									                    text: 'Revenue'
									                }
									            },
									            tooltip: {
									                valueSuffix: data.currency_code
									            },
									           
									            series: [{
									                name: 'Total Revenue in '+data.currency_code,
									                data: strdata.split(',').map(Number) 
									            }]
									        });
										}
									}
			});	
}
