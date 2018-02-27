function loadPublisher_Traffic_Reporting(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		operator = $('#operator-list').val(),
		partner = $('#partner-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Server Time</th><th class="hidden-phone">Pub. Time</th><th class="hidden-phone">Pub. Transaction ID</th><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Media</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">Status Send</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/publisher_traffic_reporting/loadPublisher_Traffic_Reporting",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'datefrom': datefrom,
					'dateto': dateto,
					'operator-list': operator,
					'partner-list': partner
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['ads_publisher_time']+'</td><td>'+data.rows[i]['transaction_id_publisher']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_partner']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign_media']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['is_send']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(html+result+'</tbody></table>'+paging);
									}
		   });	
}
