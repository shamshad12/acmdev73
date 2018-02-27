function loadpixelfire_report(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		datefrom = $('#datefrom').val(),
		dateto = $('#dateto').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Time</th><th class="hidden-phone">App ID</th><th class="hidden-phone">Ad Network</th><th class="hidden-phone">Transaction ID</th><th class="hidden-phone">Fire Pixel Click ID</th><th class="hidden-phone">Response</th></tr></thead><tbody id="data-table">';
		var selectdate = $("#select-date").val();
		var adnetwork = $("#ad-network").val();
	
		if(adnetwork == 0)
		{
			jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select AdNetwork</p>");
			return false;
		}
		if(selectdate == 0)
		{
			jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select Day Type</p>");
			return false;
		}
		else if(selectdate == 2 && datefrom=='' && dateto=='')
		{
			jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select Date Range</p>");
			return false;
		}
	var base_url = window.location.origin;
	jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="'+base_url+'/cms-tools-ads/assets/images/loader.gif"/></div>');
	jQuery.ajax({
			url:  domain+"campaign_manager/pixelfire_report/loadpixelfire_report",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'datefrom': datefrom,
					'dateto': dateto,
					'selectdate': selectdate,
					'adnetwork': adnetwork
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var totalres = '<div id="info">Total <span id="red">'+data.total+'</span> records found.</div>';
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['server_time']+'</td><td>'+data.rows[i]['appid']+'</td><td>'+data.rows[i]['adnetwork']+'</td><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['firepixelclckid']+'</td><td>'+data.rows[i]['reponse']+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(totalres+html+result+'</tbody></table>'+paging);
										Main.tableSorting();
									}
		   });	
}

function showhidedates(opt){

	if(opt=='1')
	{
		$(".daterange1").addClass('hidden');
		$(".daterange2").addClass('hidden');
		$(".daterange3").addClass('hidden');
		$(".daterange4").addClass('hidden');
		$(".daterange6").addClass('hidden');
		$("#ad-network").val('');
	}
	else if(opt=='2')
	{
		$(".daterange1").removeClass('hidden');
		$(".daterange2").removeClass('hidden');
		$(".daterange3").removeClass('hidden');
		$(".daterange4").removeClass('hidden');
		$(".daterange6").removeClass('hidden');
	}
	else
	{
		$(".daterange1").addClass('hidden');
		$(".daterange2").addClass('hidden');
		$(".daterange3").addClass('hidden');
		$(".daterange4").addClass('hidden');
		$(".daterange6").addClass('hidden');
		$("#ad-network").val('');
	}

}

function checkDateEntered(){

	var selectdate = $("#select-date").val();
	var adnetwork = $("#ad-network").val();
	
	if(adnetwork == 0)
	{
		alert("Please Select Ad Nework.");
		return false;
	}
	else if(selectdate == 0)
	{
		alert("Please Select Day Type.");
		return false;
	}
	else if(selectdate == 2)
	{
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
	else
	{
		$('#reprt_sbmt').submit();
	}

}
