function loadReporting_Profit_Cost(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		operator = $('#operator-list').val(),
		partner = $('#partner-list').val(),
		country = $('#country-list').val(),
		shortcode = $('#shortcode-list').val(),
		keyword = $('#keyword-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Date</th><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Shortcode</th><th class="hidden-phone">Keyword</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">Gross Revenue</th><th class="hidden-phone">Cost</th><th class="hidden-phone">ProfitLost</th></tr></thead><tbody id="data-table">';
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/reporting_profit_cost/loadReporting_Profit_Cost",
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
					'shortcode-list': shortcode,  //SAM
					'keyword-list': keyword,  //SAM
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
												if(i+1 == data.rows.length)
												{
													gt_style = ' gt_style';
													var currency_links = '<div class="currency_links"><a href="javascript:void(0)" onclick="changeCurrency(\'local\',this)" class="active">'+data.rows[i]['currency_code']+'</a><a href="javascript:void(0)" onclick="changeCurrency(\'usd\',this)">USD</a><a href="javascript:void(0)" onclick="changeCurrency(\'sgd\',this)">SGD</a></div>';
												}
											result  += '<tr class="odd gradeX'+gt_style+'"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_partner']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['keyword']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td class="hide_currency local">'+data.rows[i]['gross_revenue']+'</td><td class="hide_currency local">'+data.rows[i]['cost']+'</td><td class="hide_currency local" style="color:'+data.rows[i]['color']+'">'+data.rows[i]['nett_revenue']+'</td><td class="hide_currency usd">'+data.rows[i]['usd_gross_revenue']+'</td><td class="hide_currency usd">'+data.rows[i]['usd_cost']+'</td><td class="hide_currency usd" style="color:'+data.rows[i]['color']+'">'+data.rows[i]['usd_nett_revenue']+'</td><td class="hide_currency sgd">'+data.rows[i]['sgd_gross_revenue']+'</td><td class="hide_currency sgd">'+data.rows[i]['sgd_cost']+'</td><td class="hide_currency sgd" style="color:'+data.rows[i]['color']+'">'+data.rows[i]['sgd_nett_revenue']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
										$('#table-append').prepend(currency_links);
										$('.hide_currency').hide();
										$('.local').show();
									}
		   });	
}

function changeCurrency(code,obj)
{
	$('.hide_currency').hide();
	$('.'+code).show();
	$('.currency_links a').removeClass('active');
	$(obj).addClass('active');
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