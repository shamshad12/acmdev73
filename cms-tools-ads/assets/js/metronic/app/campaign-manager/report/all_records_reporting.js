function loadAllRecords_Reporting(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		dateinternal = $('#dateinternal').val(),
		datepublisher = $('#datepublisher').val(),
		campaign = $('#campaign-list').val(),
		shortcode = $('#shortcode-list').val(),
		publisher = $('#ads-publisher-list').val(),
		partner = $('#partner-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list" style="table-layout: fixed;"><thead><tr><th class="hidden-phone">Operator</th><th class="hidden-phone">MSISDN</th><th class="hidden-phone" style="word-wrap:break-word;">Camp Title</th><th class="hidden-phone" style="word-wrap:break-word;width:200px;">Referer</th><th class="hidden-phone" style="word-wrap:break-word;">Transaction ID</th><th class="hidden-phone" style="word-wrap:break-word;">Ads Vendors</th><th class="hidden-phone" style="word-wrap:break-word;">Shortcode</th><th class="hidden-phone" style="word-wrap:break-word;">Keyword</th><th class="hidden-phone" style="word-wrap:break-word;">Country</th><th class="hidden-phone" style="word-wrap:break-word;">Internal Datetime</th><th class="hidden-phone" style="word-wrap:break-word;">Pub Datetime</th><th class="hidden-phone">Detail</th></tr></thead><tbody id="data-table">';
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/all_records_reporting/loadAllRecords_Reporting",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'dateinternal': dateinternal,
					'datepublisher': datepublisher,
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
												result  += '<tr class="odd gradeX"><td style="word-wrap:break-word;">'+data.rows[i]['name_operator']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['msisdn_detection']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['name_campaign']+'</td><td style="word-wrap:break-word;width:200px;">'+data.rows[i]['referer']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['id']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['name_ads_publisher']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['shortcode']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['keyword']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['name_country']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['server_time']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['ads_publisher_time']+'</td><td><a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#show_detail" onclick="showDetail(\''+data.rows[i]['id']+'\')">View</a></td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
										Main.tableSorting();
									}
		   });	
}

function checkDateEntered(){

	var datefrom = $('#dateinternal').val(),
		dateto = $('#datepublisher').val();

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