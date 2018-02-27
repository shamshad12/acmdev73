function load_log_list(page){

	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
                log_type = $('#log_type').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">User Name</th><th class="hidden-phone">Log Information</th><th class="hidden-phone">Date Time</th></tr></thead><tbody id="data-table">';

	jQuery.ajax({
			url:  domain+"campaign_manager/log_list/loadLogList",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'datefrom': datefrom,
					'dateto': dateto,
                                        'log_type':log_type
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {

										jQuery('#table-append').html('');
										var result = '';
										if(data.count){

											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['user_name']+' ['+data.rows[i]['user_info']+'] </td><td>'+data.rows[i]['action_type']+'</td><td>'+data.rows[i]['create_ts']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(html+result+'</tbody></table>'+paging);
									}
		   });	
}



