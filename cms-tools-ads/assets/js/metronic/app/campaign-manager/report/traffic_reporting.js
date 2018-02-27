function loadTraffic_Reporting(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		operator = $('#operator-list').val(),
		partner = $('#partner-list').val(),
		vendor = $('#ads-publisher-list').val(),
		rejected = $('#filter-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Server Time</th><th class="hidden-phone">TransactionID</th><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Media</th><th class="hidden-phone">Campaign</th><th class="hidden-phone">MSISDN</th><th class="hidden-phone">SDC</th><th class="hidden-phone">Keyword</th><th class="hidden-phone">Status</th><th class="hidden-phone">Detail</th></tr></thead><tbody id="data-table">';
	
		if(search=='' && datefrom=='' && dateto=='' && operator=='' && partner=='' && vendor=='' && rejected=='')
		{
		jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please key-in your search criteria</p>");
		return false;
		}
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/traffic_reporting/loadTraffic_Reporting",
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
					'ads-publisher-list': vendor,
					'filter-list': rejected
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_partner']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign_media']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['msisdn_detection']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['keyword']+'</td><td>'+data.rows[i]['status']+'</td><td><a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#show_detail" onclick="showDetail(\''+data.rows[i]['id']+'\')">View</a></td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
										Main.tableSorting();
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


function showDetail(id)
{
	jQuery('#modal-body').html('');
	jQuery.ajax({
			url:  domain+"campaign_manager/traffic_reporting/getTraficDetail",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
				//alert(data.transaction_id_publisher);
										var result = "";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>ID: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+id+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>MSISDN: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.msisdn_detection+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>Campaign Code: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.campaign_code+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>Operator Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_operator+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>Partner Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_partner+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Country Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_country+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Shortcode: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.shortcode+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Keyword: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.keyword+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Publisher Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_ads_publisher+"</span></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Transaction ID: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.transaction_id_publisher+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Referrer: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.referer+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Landing Page Url:  </b></p><p class='pull-left' style='width:70%;word-wrap:break-word;text-align:left;'> http://"+data.http_host+data.request_uri+"</p></div><br/><br/>";
										
										$('#modal-body').html(result);
										
									}
		   });
}