function loadoperator_overview(page){		
	var day = $('#day-list').val();
	var partner = $('#partner-list').val();
	var shortcode = $('#shortcode-list').val();
	
	var html_tdy='<h3 style="text-align:center;font-size:20px;background:#ddd">Today</h3><table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">operator</th><th class="hidden-phone">Total Landing Views</th><th class="hidden-phone">Total Web Entry</th><th class="hidden-phone">Total Subscriber</th><th class="hidden-phone">Subscriber/Web Entry (%)</th><th class="hidden-phone">Web Entry/Views (%)</th><th class="hidden-phone">Subscriber/Views (%)</th></tr></thead><tbody id="data-table">';
	var result_tdy='';
	var html_ystrdy='<h3 style="text-align:center;font-size:20px;background:#ddd">Yesterday</h3><table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">operator</th><th class="hidden-phone">Total Landing Views</th><th class="hidden-phone">Total Web Entry</th><th class="hidden-phone">Total Subscriber</th><th class="hidden-phone">Subscriber/Web Entry (%)</th><th class="hidden-phone">Web Entry/Views (%)</th><th class="hidden-phone">Subscriber/Views (%)</th></tr></thead><tbody id="data-table">';
	var result_ystrdy='';
	jQuery.ajax({
			url:  domain+"campaign_manager/operator_overview/loadoperator_overview",
			dataType: "json",
			type: "POST",
			data: {
					'page'  : page,
					'partner-list': partner,
					'shortcode-list': shortcode,
					'day-list': day
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append-today').html('');
										jQuery('#table-append-yesterday').html('');
										var result = '';
										var gt_style = '';
										if(data.count){
											if(data.today.length){
												for(var i=0; i<data.today.length; i++){
													if(i+1 == data.today.length)
														gt_style = ' gt_style';
													result_tdy  += '<tr class="odd gradeX'+gt_style+'"><td>'+data.today[i]['name_operator']+'</td><td>'+data.today[i]['total_landing_views']+'</td><td>'+data.today[i]['total_web_entry']+'</td><td>'+data.today[i]['total_subscriber']+'</td><td>'+data.today[i]['subscriber_webentry']+'</td><td>'+data.today[i]['webentry_views']+'</td><td>'+data.today[i]['subscriber_views']+'</td></tr>';			
												}
											}
											if(data.yesterday.length){
												gt_style = '';
												for(var i=0; i<data.yesterday.length; i++){
													if(i+1 == data.yesterday.length)
														gt_style = ' gt_style';
													result_ystrdy  += '<tr class="odd gradeX'+gt_style+'"><td>'+data.yesterday[i]['name_operator']+'</td><td>'+data.yesterday[i]['total_landing_views']+'</td><td>'+data.yesterday[i]['total_web_entry']+'</td><td>'+data.yesterday[i]['total_subscriber']+'</td><td>'+data.yesterday[i]['subscriber_webentry']+'</td><td>'+data.yesterday[i]['webentry_views']+'</td><td>'+data.yesterday[i]['subscriber_views']+'</td></tr>';			
												}
											}
										}
										$('#table-append-today').append(html_tdy+result_tdy+'</tbody></table>');
										$('#table-append-yesterday').append(html_ystrdy+result_ystrdy+'</tbody></table>');
									}
		   });	
}

function formsbmt(){
    $("#operator_overview_form").submit();
}