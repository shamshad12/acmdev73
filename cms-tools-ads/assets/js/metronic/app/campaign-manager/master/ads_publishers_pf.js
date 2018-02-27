function loadAds_Publishers_PF(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		operator = $('#search_operator').val(),
		ads_publisher = $('#search_ads_publisher').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Operator</th><th class="hidden-phone">Ads Vendors</th><th class="hidden-phone" width="120">Acquisition Type</th><th class="hidden-phone">Value</th><th class="hidden-phone" width="130">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
		
	jQuery.ajax({
			url:  domain+"campaign_manager/ads_publishers_pf/loadAds_Publishers_PF",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'operator': operator,
					'ads_publisher': ads_publisher
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['name_operator']+'</td><td>'+data.rows[i]['name_ads_publisher']+'</td><td>'+data.rows[i]['acquisition_type']+'</td><td>'+data.rows[i]['pf_value']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formads_publishers_pf');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    id_operator: {
                        required: true
                    },
                    id_ads_publisher: {
                        required: true
                    },
                    acquisition_type: {
                        required: true
                    },
                    status: {
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
			url:  domain+"campaign_manager/ads_publishers_pf/getAds_Publishers_PFData",
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
											jQuery('#acquisition_type').val(data.acquisition_type);
											if(data.acquisition_type == 'Amount'){
												jQuery('#pf_value').val(data.pf_value);
												jQuery('#div_pf_value').css('display', 'block');
												jQuery('#div_percentage').css('display', 'none');
											} else {
												jQuery('#percentage').val(data.pf_value);
												jQuery('#div_pf_value').css('display', 'none');
												jQuery('#div_percentage').css('display', 'block');
											}
											jQuery('#status').val(data.status);	
																					
											inactiveTab('ads_publishers_pf_list');
											activeTab('ads_publishers_pf_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		id_operator = jQuery('#id_operator').val(),
		id_ads_publisher = jQuery('#id_ads_publisher').val(),
		acquisition_type = jQuery('#acquisition_type').val(),
		status = jQuery('#status').val();
		
		if(acquisition_type == 'Amount'){
			var pf_value = jQuery('#pf_value').val();
		} else {
			var pf_value = jQuery('#percentage').val();
		}
	
	var form = $('#formads_publishers_pf');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/ads_publishers_pf/saveAds_Publishers_PF",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'id_operator' : id_operator,
					'id_ads_publisher' : id_ads_publisher,
					'acquisition_type' : acquisition_type,
					'pf_value' : pf_value,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {

										if(data.duplicat_data) 
										{
											success.hide();
											alert(data.errors_message)
										}
										
										if(data.success){
											success.show();
                    						error.hide();	
											loadAds_Publishers_PF(1);
											clearForm();
											
											inactiveTab('ads_publishers_pf_form');
											activeTab('ads_publishers_pf_list');
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

function deleteAds_Publishers_PF(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/ads_publishers_pf/deleteAds_Publishers_PF",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadAds_Publishers_PF(1);
										}
			   });	
	}
}

function setPf(type){
	if(type == 'Amount'){
		jQuery('#div_pf_value').css('display', 'block');
		jQuery('#div_percentage').css('display', 'none');
	} else {
		jQuery('#div_pf_value').css('display', 'none');
		jQuery('#div_percentage').css('display', 'block');
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#pf_value').val('');
	jQuery('#percentage').val('');
	jQuery('#id_operator').val('');
	jQuery('#id_ads_publisher').val('');
	jQuery('#acquisition_type').val('');
	jQuery('#status').val('');
		
	inactiveTab('ads_publishers_pf_form');
	activeTab('ads_publishers_pf_list');
}

jQuery(document).ready(function() {	
	// Add events
});
