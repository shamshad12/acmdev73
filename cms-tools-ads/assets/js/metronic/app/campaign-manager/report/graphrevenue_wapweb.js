function loadgraphrevenue_wapweb(page){		
	var campaign_media = $('#campaign_media').val();
	var country = $('#country').val();
	var select_month = $('#select_month').val();
	$('#container').html('Loading...');
	jQuery.ajax({
			url:  domain+"campaign_manager/graphrevenue_wapweb/loadgraphrevenue_wapweb",
			dataType: "json",
			type: "POST",
			data: {
					'campaign_media': campaign_media,
					'country': country,
					'select_month' : select_month
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										if(data.count)
										{
											$('#container').html('');
											var result = '';
											var arr = $.map(data.revenue, function(el) { return el; });
											var arr_pub = $.map(data.publisher_name, function(el) { return el; });
											var comma, key='', strdata='';
											for(var i = 1; i<=arr.length; i++)
											{
												if(i==arr.length)
													comma = '';
												else
													comma = ',';

												key += arr_pub[i-1]+comma; 
												strdata += arr[i-1]+comma;
											}
											$('#container').highcharts({
									            title: {
									                text: 'Monthly Revenue Media type wise'
									            },
									            subtitle: {
									                text: 'Country: '+data.name_country+', Media Type: '+data.name_campaign_media
									            },
									            xAxis: {
									                categories: key.split(","),
									               	title: {
									                    text: 'Vendors'
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
										else
										{
											$('#container').html('No data found');
										}
									}
			});	
}
