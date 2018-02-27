function loadreport2_reporting(page,days){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		country = $('#country-list').val(),
		media = $('#search_media').val(),
		operator = $('#operator-list').val(),
		vendor = $('#ads-publisher-list').val(),
		shortcode = $('#shortcode-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Server Time</th><th class="hidden-phone">Country</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Shortcodes</th><th class="hidden-phone">Campaign Price</th><th class="hidden-phone">Click</th><th class="hidden-phone">Lead</th><th class="hidden-phone">Convert</th><th class="hidden-phone">ConvR(%)</th><th class="hidden-phone">Gross Rev</th></tr></thead><tbody id="data-table">';
	
	if(search=='' && datefrom=='' && dateto=='' && operator=='' && country=='' && vendor=='' && shortcode=='' && media=='')
		{
			jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please key-in your search criteria</p>");
			return false;
		}
	
	if(days)
		{
			$('#datefrom').val('');
			$('#dateto').val('');
			datefrom = '';
			dateto = '';
			$('#days').val(days);
		}
		else
		{
			$('#days').val('');
		}
		var daysval = $('#days').val();
		var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/report2_reporting/loadreport2_reporting",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'days'	: daysval,
					'datefrom': datefrom,
					'dateto': dateto,
					'country-list': country,
					'media': media,
					'operator-list': operator,
					'ads-publisher-list': vendor,
					'shortcode-list': shortcode
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										var gt_style = '';
										if(data.count){
											result += '<tr class="odd gradeX gt_style"><td>'+data.rows[data.rows.length-1]['server_time']+'</td><td>'+data.rows[data.rows.length-1]['name_country']+'</td><td>'+data.rows[data.rows.length-1]['name_campaign']+'</td><td>'+data.rows[data.rows.length-1]['name_operator']+'</td><td>'+data.rows[data.rows.length-1]['name_ads_publisher']+'</td><td>'+data.rows[data.rows.length-1]['shortcode']+'</td><td>'+data.rows[data.rows.length-1]['campaign_price']+'</td><td>'+data.rows[data.rows.length-1]['clicks']+'</td><td>'+data.rows[data.rows.length-1]['leads']+'</td><td>'+data.rows[data.rows.length-1]['converts']+'</td><td>'+data.rows[data.rows.length-1]['convR']+'</td><td>'+data.rows[data.rows.length-1]['revenue']+'</td></tr>';
											for(var i=0; i<data.rows.length; i++){
												if(i+1 == data.rows.length)
													gt_style = ' gt_style';
												result += '<tr class="odd gradeX'+gt_style+'"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['campaign_price']+'</td><td>'+data.rows[i]['clicks']+'</td><td>'+data.rows[i]['leads']+'</td><td>'+data.rows[i]['converts']+'</td><td>'+data.rows[i]['convR']+'</td><td>'+data.rows[i]['revenue']+'</td></tr>';			
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
