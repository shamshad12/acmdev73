function loadReport_By_Campaign(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		operator = $('#operator-list').val(),
		partner = $('#partner-list').val(),
		country = $('#country-list').val(),
		/*html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Date</th><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">Gross Revenue</th><th class="hidden-phone">Cost</th><th class="hidden-phone">ProfitLost</th></tr></thead><tbody id="data-table">';*/
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Date</th><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">Revenue</th></tr></thead><tbody id="data-table">';
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/report_by_campaign/loadReport_By_Campaign",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'datefrom': datefrom,
					'dateto': dateto,
					'operator-list': operator,
					'partner-list': partner,
					'country-list': country
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										if(data.count){
											gt_style="";
											for(var i=0; i<data.rows.length; i++){
												/*result  += '<tr class="odd gradeX"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_partner']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['gross_revenue']+'</td><td title="'+data.rows[i]['cost_country_currrency']+'">'+data.rows[i]['cost']+'</td><td style="color:'+data.rows[i]['color']+'">'+data.rows[i]['nett_revenue']+'</td></tr>';*/
												if(i+1 == data.rows.length)
												{
													gt_style = ' gt_style';
												}
												result  += '<tr class="odd gradeX'+gt_style+'"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['gross_revenue']+'</td></tr>';			
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
