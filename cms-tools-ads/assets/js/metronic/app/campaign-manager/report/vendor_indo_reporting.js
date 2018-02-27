function loadvendor_indo_reporting(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		country = $('#country-list').val(),
		operator = $('#operator-list').val(),
		vendor = $('#ads-publisher-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Server Time</th><th class="hidden-phone">Country</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Click</th><th class="hidden-phone">Lead</th><th class="hidden-phone">Convert</th><th class="hidden-phone">ConvR(%)</th></tr></thead><tbody id="data-table">';
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/vendor_indo_reporting/loadvendor_indo_reporting",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'datefrom': datefrom,
					'dateto': dateto,
					'country-list': country,
					'operator-list': operator,
					'ads-publisher-list': vendor
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										var gt_style = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												if(i+1 == data.rows.length)
													gt_style = ' gt_style';
												result += '<tr class="odd gradeX'+gt_style+'"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['clicks']+'</td><td>'+data.rows[i]['leads']+'</td><td>'+data.rows[i]['converts']+'</td><td>'+data.rows[i]['convR']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
									}
		   });	
}
