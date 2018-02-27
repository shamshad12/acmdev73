function loadReporting_Acquisition(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		operator = $('#operator-list').val(),
		partner = $('#partner-list').val(),
		publisher = $('#ads-publisher-list').val(),
		is_publisher_send = $('#is_publisher_send').val(),
		html = '<table class="table table-striped table-bordered" id="group-list" style="word-wrap:break-word;max-width:1062px;"><thead><tr><th class="hidden-phone">Server Time</th><th class="hidden-phone">Charging Time</th><th class="hidden-phone" style="word-wrap:break-word;max-width:80px;">ACM TXN_ID</th><th class="hidden-phone" style="word-wrap:break-word;max-width:100px;">Publisher TXN_ID</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Vendors</th><th class="hidden-phone" style="word-wrap:break-word;max-width:120px;">Campaign</th><th class="hidden-phone">MSISDN</th><th class="hidden-phone">SDC</th><th class="hidden-phone">Keyword</th><th class="hidden-phone">Send?</th><th class="hidden-phone">Detail</th></tr></thead><tbody id="data-table">';
		
		if(search=='' && datefrom=='' && dateto=='' && operator=='' && partner=='' && is_publisher_send=='*' && publisher=='')
		{
			
		jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please key-in your search criteria</p>");
		return false;
		}
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/reporting_acquisition/loadReporting_Acquisition",
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
					'ads-publisher-list': publisher,
					'is_publisher_send': is_publisher_send
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['charging_time']+'</td><td  style="word-wrap:break-word;">'+data.rows[i]['id']+'</td><td style="word-wrap:break-word;max-width:100px;">'+data.rows[i]['pub_trans_id']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_partner']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['msisdn_detection']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['keyword']+'</td><td>'+data.rows[i]['is_publisher_send']+'</td><td><a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#show_detail" onclick="showDetail(\''+data.rows[i]['id']+'\')">View</a></td></tr>';			
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
	var datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val();
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
										var resApptViewLead="";
										/*if(data.pubcode=='A004')
										{
										resApptViewLead= "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>View Post Back URL: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.loading_url+"</p></div><br/><br/>";
										resApptViewLead+= "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Lead Post Back URL: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.updation_url+"</p></div><br/><br/>";
										}*/
										if(data.loading_url !="")
										{
											resApptViewLead= "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>View Post Back URL: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.loading_url+"</p></div><br/><br/>";
										}
										if(data.updation_url !="")
										{
											resApptViewLead+= "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Lead Post Back URL: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.updation_url+"</p></div><br/><br/>";
										}
										
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>ID: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+id+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>MSISDN: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.msisdn_detection+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>Campaign Code: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.campaign_code+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>Operator Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_operator+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue'><b>Partner Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_partner+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Country Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_country+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Shortcode: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.shortcode+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Keyword: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.keyword+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Publisher Name: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.name_ads_publisher+"("+data.pubcode+")</span></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Transaction ID: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.transaction_id_publisher+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Referrer: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.referer+"</p></div><br/><br/>";
										result += "<div class='pull-left' style='width:100%;'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Landing Page Url:  </b></p><p class='pull-left' style='width:70%;word-wrap:break-word;text-align:left;'> http://"+data.http_host+data.request_uri+"</p></div><br/><br/>";
										//result += "<div class='pull-left'><p style='width:20%;text-align:left;color:blue;'><b>Affiliate pixel </b></p>";
										if(data.ads_type=='CPA')
										{
											result += "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Publisher Send: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.is_publisher_send+"</p></div><br/><br/>";
											result +="<p>"+resApptViewLead+"</p>";
											result += "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Pixel Post Back URL: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+data.url_confirm+"</p></div><br/><br/>";
											result += "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Return Response: </b></p><p id='curl-result' class='pull-left' style='width:70%;text-align:left;'>"+data.pixel_response+"</p></div><br/><br/>";
											if(data.is_publisher_send=="Sent")
											{
												result += "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b> </b></p><button class='pull-left btn' style='margin-bottom:20px;' type='button' id='resendPubreq' onclick='resendPubreq(\""+data.url_confirm+"\",\""+datefrom+"\",\""+dateto+"\",\""+id+"\")'>Resend</button><p style='font-size:14px;padding-top:10px;padding-left:10px;' class='pull-left sndngagn'></p></div><br/><br/>";
											}
										}
										else
										{
											result += "<div class='pull-left' style='width:100%'><p class='pull-left' style='width:30%;text-align:left;color:blue;'><b>Campaign Type: </b></p><p class='pull-left' style='width:70%;text-align:left;'>"+"CPC"+"</p></div><br/><br/>";
										}
										
										
										$('#modal-body').html(result);
										
									}
		   });
}
function resendPubreq(url,datefrom,dateto,id)
{
	$(".sndngagn").html('Sending Again. . . .');
	$("#resendPubreq").attr('disabled', true);
	jQuery.ajax({
			url:  domain+"campaign_manager/traffic_reporting/resendPubreq",
			dataType: "json",
			type: "POST",
			data: {
					'url_val' : url,
					'datefrom': datefrom,
					'dateto'  : dateto,
					'id'      : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										//alert(JSON.stringify(data));
										if(data.success)
										{
											$(".sndngagn").html('Successfully Sent');
											$("#curl-result").html(data.curlresult);
											
										}
										else
										{
											$(".sndngagn").html('Sending Failed, Try Again!');
											$("#resendPubreq").removeAttr('disabled');
										}

									}
				});
}