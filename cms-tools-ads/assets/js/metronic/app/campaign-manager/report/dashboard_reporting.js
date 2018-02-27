function loadDashboard_Reporting(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		//operator = $('#operator-list').val(),
		campaign = $('#campaign-list').val(),
		shortcode = $('#shortcode-list').val(),
		publisher = $('#ads-publisher-list').val(),
		partner = $('#partner-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Server Time</th><th class="hidden-phone">TransactionID</th><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Media</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">MSISDN</th><th class="hidden-phone">SDC</th><th class="hidden-phone">Keyword</th></tr></thead><tbody id="data-table">';
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/dashboard_reporting/loadDashboard_Reporting",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'datefrom': datefrom,
					'dateto': dateto,
					//'operator-list': operator,
					'campaign-list': campaign,
					'shortcode-list': shortcode,
					'ads-publisher-list': publisher,
					'partner-list': partner
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										if(data.count){  
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_partner']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign_media']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['msisdn_detection']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['keyword']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
									}
		   });	
}

function checkDateEntered(){

	var datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val();

	if(datefrom=='' || dateto=='')
	{
		alert("First select dates i.e Date from and Date to, for desired period then export.");
		return false;
	}
	else
	{
		$('#reprt_sbmt').submit();
	}

}