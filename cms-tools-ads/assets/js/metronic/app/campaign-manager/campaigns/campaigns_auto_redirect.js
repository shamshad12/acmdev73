function loadCampaigns_Auto_Redirect(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		ads_publisher = $('#search_ads_publisher').val(),
		operator = $('#search_operator').val(),
		country = $('#search_country').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone" width="70">Operator</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone">Campaign</th><th class="hidden-phone" width="90">From</th><th class="hidden-phone">To</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
		
	jQuery.ajax({
			url:  domain+"campaign_manager/campaigns_auto_redirect/loadCampaigns_Auto_Redirect",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'ads_publisher': ads_publisher,
					'operator': operator
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												var edit='';
												if(accessEdit=='1')
													edit = '<a onclick="edit(\''+data.rows[i]['id']+'\')" style="cursor:pointer" class="btn green mini">Edit</a>';
												var del='';
												if(accessDelete=='1')
													del = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'">Delete</a>';
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['name_campaign']+'</td><td>'+data.rows[i]['hour_from']+'.00 '+data.rows[i]['day_from']+'</td><td>'+data.rows[i]['hour_to']+'.00 '+data.rows[i]['day_to']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
											}
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(html+result+'</tbody></table>'+paging);
									}
		   });	
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formcampaigns_auto_redirect');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    hour_from: {
                        required: true
                    },
                    hour_to: {
                        required: true
                    },
                    id_operator: {
                        required: true
                    },
                    id_campaign: {
                        required: true
                    },
                    id_ads_publisher: {
                        required: true
                    },
                    day_from: {
                        required: true
                    },
                    day_to: {
                        required: true
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success.hide();
                    error.show();
                    App.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.help-inline').removeClass('ok'); // display OK icon
                    $(element)
                        .closest('.control-group').removeClass('success').addClass('error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change dony by hightlight
                    $(element)
                        .closest('.control-group').removeClass('error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .addClass('valid').addClass('help-inline ok') // mark the current input as valid and display OK icon
                    .closest('.control-group').removeClass('error').addClass('success'); // set success class to the control group
                },

                submitHandler: function (form) {
					success.show();
                    error.hide();
					save();
                }
            });
        }
    };

}();

function edit(id){
	jQuery.ajax({
			url:  domain+"campaign_manager/campaigns_auto_redirect/getCampaigns_Auto_RedirectData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#id_operator').val(data.id_operator);
											jQuery('#id_ads_publisher').val(data.id_ads_publisher);
											jQuery('#id_campaign').val(data.id_campaign);
											jQuery('#day_from').val(data.day_from);	
											jQuery('#hour_from').val(data.hour_from);
											jQuery('#day_to').val(data.day_to);	
											jQuery('#hour_to').val(data.hour_to);	
											jQuery('#status').val(data.status);	
											
											getCampaigns(data.id_operator, data.id_ads_publisher, data.id_campaign);
											
											inactiveTab('campaigns_auto_redirect_list');
											activeTab('campaigns_auto_redirect_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		id_operator = jQuery('#id_operator').val(),
		id_ads_publisher = jQuery('#id_ads_publisher').val(),
		id_campaign = jQuery('#id_campaign').val(),
		day_from = jQuery('#day_from').val(),
		hour_from = jQuery('#hour_from').val(),
		day_to = jQuery('#day_to').val(),
		hour_to = jQuery('#hour_to').val(),
		status = jQuery('#status').val();
			
	var form = $('#formcampaigns_auto_redirect');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/campaigns_auto_redirect/saveCampaigns_Auto_Redirect",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'id_operator' : id_operator,
					'id_ads_publisher' : id_ads_publisher,
					'id_campaign' : id_campaign,
					'day_from' : day_from,
					'hour_from' : hour_from,
					'day_to' : day_to,
					'hour_to' : hour_to,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadCampaigns_Auto_Redirect(1);
											clearForm();
											
											inactiveTab('campaigns_auto_redirect_form');
											activeTab('campaigns_auto_redirect_list');
										} else {
											success.hide();
                    						error.show();
										}
									}
		   });
}

function setDeleteID(id){
	deleteID = 	id;
}

function deleteCampaigns_Auto_Redirect(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/campaigns_auto_redirect/deleteCampaigns_Auto_Redirect",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadCampaigns_Auto_Redirect(1);
										}
			   });	
	}
}

function getCampaigns(id_operator, id_ads_publisher, id_campaign){
	if(id_operator == ''){
		id_operator = $('#id_operator').val();
	}
	
	if(id_ads_publisher == ''){
		id_ads_publisher = $('#id_ads_publisher').val();
	}
	
	$('#id_campaign').val('');
	$('#id_campaign').attr('disabled', 'disabled');
	if(id_operator != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/campaigns/loadCampaignsSelect",
				dataType: "json",
				type: "POST",
				data: {
						'id_operator' : id_operator,
						'id_ads_publisher' : id_ads_publisher
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											var result = '<option value="">- Choose Campaign -</option>';											
											if(data.count){	
												for(var i=0; i<data.rows.length; i++){
													result += "<option value='"+data.rows[i]['id']+"'>"+data.rows[i]['name']+"</option>";
												}
												$('#id_campaign').html(result);
												$('#id_campaign').removeAttr('disabled');
												
												$('#id_campaign').val(id_campaign);
											}
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#id_operator').val('');
	jQuery('#id_ads_publisher').val('');
	jQuery('#id_campaign').val('');
	jQuery('#day_from').val('');
	jQuery('#hour_from').val('');
	jQuery('#day_to').val('');
	jQuery('#hour_to').val('');
	jQuery('#status').val('');
	
	$('#id_campaign').attr('disabled', 'disabled');
		
	inactiveTab('campaigns_auto_redirect_form');
	activeTab('campaigns_auto_redirect_list');
}

jQuery(document).ready(function() {	
	// Add events
});
