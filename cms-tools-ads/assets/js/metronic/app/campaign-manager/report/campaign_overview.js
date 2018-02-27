function loadcampaign_overview(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		dateinternal = $('#dateinternal').val(),
		datepublisher = $('#datepublisher').val(),
		campaign = $('#campaign-list').val(),
		shortcode = $('#shortcode-list').val(),
		publisher = $('#ads-publisher-list').val(),
		operator = $('#operators-list').val(),
		country = $('#country-list').val(),
		keywords = $('#keywords-list').val(),
		partner = $('#partners-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Date</th><th class="hidden-phone">Campaign Name</th><th class="hidden-phone">Country</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Keyword</th><th class="hidden-phone" style="word-wrap:break-word;">Shortcode</th><th class="hidden-phone">Operators</th><th class="hidden-phone">Affliates</th><th class="hidden-phone">View</th><th class="hidden-phone">Web Entry</th><th class="hidden-phone">Sales</th><th class="hidden-phone">Subscriber/Web Entry (%)</th><th class="hidden-phone">Web Entry/Views (%)</th><th class="hidden-phone">Subscriber/Views (%)</th></tr></thead><tbody id="data-table">';
		if(dateinternal=='' && datepublisher=='' && campaign=='' && shortcode=='' && operator=='' && publisher=='' && country=='' && keywords=='' && partner=='')
		{
		jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please key-in your search criteria</p>");
		return false;
		}else if(dateinternal=='' || datepublisher==''){
	   jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please select start and end date</p>");
	   return false;
	  } else if(country==''){
	   jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please select country.</p>");
	   return false;
  }
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/campaign_overview/loadcampaign_overview",
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
					'operators-list': operator,
					'partners-list': partner, //SAM
					'country-list': country,
					'keywords-list': keywords
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										if(data.count){
											gt_style = '';
											for(var i=0; i<data.rows.length; i++){
												var parameters = "'"+data.rows[i]['id_campaign']+"','"+data.rows[i]['name_campaign']+"','"+data.rows[i]['id_country']+"','"+data.rows[i]['id_operator']+"','"+data.rows[i]['id_ads_publisher']+"','"+dateinternal+"','"+datepublisher+"','"+data.rows[i]['name_ads_publisher']+"','"+data.rows[i]['shortcode']+"','"+data.rows[i]['keyword']+"'";
												var campaign_name = '<a style="cursor:pointer" data-toggle="modal" href="#show_detail" onclick="showhourlyDetail('+parameters+')">'+data.rows[i]['name_campaign']+'</a>';
												if(i+1 == data.rows.length)
												{
													gt_style = ' gt_style';
													campaign_name = '<a href="javascript:void(0);">'+data.rows[i]['name_campaign']+'</a>';
												}
												result  += '<tr class="odd gradeX'+gt_style+'"><td style="word-wrap:break-word;">'+data.rows[i]['server_time']+'</td><td style="word-wrap:break-word;">'+campaign_name+'</td><td style="word-wrap:break-word;">'+data.rows[i]['name_country']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['name_partner']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['keyword']+'</td><td style="word-wrap:break-word;">'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['total_landing_views']+'</td><td>'+data.rows[i]['total_web_entry']+'</td><td>'+data.rows[i]['total_subscriber']+'</td><td>'+data.rows[i]['subscriber_webentry']+'</td><td>'+data.rows[i]['webentry_views']+'</td><td>'+data.rows[i]['subscriber_views']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
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

function showhourlyDetail(id_campaign, name_campaign, id_country, id_operator, id_ads_publisher, dateinternal, datepublisher, name_adspublisher, shortcode, keyword){
	
	
	$("#name_details").html('');
	$('#hourly_details').html('');
	var name_dtls = "Campaign: "+name_campaign+"<br/> Publisher: "+name_adspublisher+"<br/> Shortcode: "+shortcode+"<br/> Keyword: "+keyword;
	$("#name_details").html(name_dtls);
	$('#hourly_details').html('<h3 style="text-align: center;"> Loading Hourly Details . . . </h3>');

	html = '<table class="table table-striped table-bordered" id="group-list1"><thead><tr><th class="hidden-phone"></th><th class="hidden-phone">00</th><th class="hidden-phone">01</th><th class="hidden-phone">02</th><th class="hidden-phone">03</th><th class="hidden-phone">04</th><th class="hidden-phone">05</th><th class="hidden-phone">06</th><th class="hidden-phone">07</th><th class="hidden-phone">08</th><th class="hidden-phone">09</th><th class="hidden-phone">10</th><th class="hidden-phone">11</th><th class="hidden-phone">12</th><th class="hidden-phone">13</th><th class="hidden-phone">14</th><th class="hidden-phone">15</th><th class="hidden-phone">16</th><th class="hidden-phone">17</th><th class="hidden-phone">18</th><th class="hidden-phone">19</th><th class="hidden-phone">20</th><th class="hidden-phone">21</th><th class="hidden-phone">22</th><th class="hidden-phone">23</th></tr></thead><tbody id="data-table">';

	jQuery.ajax({
			url: domain+"campaign_manager/campaign_overview/getcampaign_hourlydetail",
			dataType: "JSON",
			type: "POST",
			data: {
				'id_campaign': id_campaign,
				'id_country': id_country,
				'id_operator': id_operator,
				'id_ads_publisher': id_ads_publisher,
				'dateinternal': dateinternal,
				'datepublisher': datepublisher,
				'shortcode': shortcode,
				'keyword': keyword
			},
			success: function(data) { 
										var result = '';
										if(data.count){
												var date_range = data.sdate;
												if(data.edate != "")
													date_range += " To "+data.edate;
												$('#name_details').append("<br/> Date: "+date_range);
												result  += '<tr class="odd gradeX"><td>Web Entry</td>';
												var abc = ['00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23'];
												for(var i=0; i<abc.length; i++){
													for(var j=0; j< data.time.length; j++)
													{
														if(data.time[j]['time']==abc[i])
														{
															if(data.total_web_entry[j]['total_web_entry']==0)
																data.total_web_entry[j]['total_web_entry'] = '--';
															result += '<td>'+data.total_web_entry[j]['total_web_entry']+'</td>';
															var temp = abc[i];
															break;
														}
													}
													if(abc[i] != temp)
														result += '<td> -- </td>';
												}

												result += '</tr><tr class="odd gradeX"><td>Subscriber</td>';
												for(var i=0; i<abc.length; i++){
													for(var j=0; j< data.time.length; j++)
													{
														if(data.time[j]['time']==abc[i])
														{
															if(data.total_subscriber[j]['total_subscriber']==0)
																data.total_subscriber[j]['total_subscriber'] = '--';
															result += '<td>'+data.total_subscriber[j]['total_subscriber']+'</td>';
															var temp = abc[i];
															break;
														}
													}
													if(abc[i] != temp)
														result += '<td> -- </td>';
												}
												result += '</tr>';
											$('#hourly_details').html(html+result+'</tbody></table>');	
											//$("#show_detail").width($("#group-list1").width()+100);
										}
									}
	})
}