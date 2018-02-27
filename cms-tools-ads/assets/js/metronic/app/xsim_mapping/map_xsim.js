function getCampaignList()
{
    var client_id=$('#client_id').val();
   // var country_id=$('#id_country').val();
    jQuery.ajax({
			url:  domain+"xsim_mapping/map_client/getCampaignList",
			dataType: "json",
			type: "POST",
			data: {
					'client_id' : client_id
					//,'country_id'  : country_id
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
                            if(data.status==1)
                            {
				        $('#submitbutton').removeAttr('disabled');
                                    }else{
                                        $('#submitbutton').attr('disabled',true);
                                    }
				    $('#campaign_div').html(data.html);
                                    $('#campaign_div1').show();}
		   });
}

function mapCampaign(){
    
    var campaign_array = [];
    var client_id = $('#client_id').val();
    
    $('input.check_campaign:checkbox:checked').each(function () {
        campaign_array.push($(this).val());
    });
    jQuery.ajax({
			url:  domain+"xsim_mapping/map_client/mapCampaign",
			dataType: "json",
			type: "POST",
			data: {
					'data' : campaign_array,
					'client_id'  : client_id
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
                            if(data.status)
                            {
                                alert('Campaign is successfully configured');
                            }else{
                                alert('Campaign is not configured. Please try again.');
                            }
				        }
		   });
    
    
}