function loadAds_Publishers(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Code</th><th class="hidden-phone">Name</th><th class="hidden-phone">Description</th><th class="hidden-phone">Ads Type</th><th class="hidden-phone">UTC Timezone</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/ads_publishers/loadAds_Publishers",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['co_name']+'</td><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['ads_type']+'</td><td>'+data.rows[i]['utc_timezone']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formads_publishers');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    name: {
                        required: true
                    },
                    id_country: {
                        required: true
                    },
                    code: {
                        required: true
                    },
                    utc_timezone: {
                        required: true
                    },
                    url_confirm: {
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
			url:  domain+"campaign_manager/ads_publishers/getAds_PublishersData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#name').val(data.name);
											jQuery('#id_country').val(data.id_country);
											jQuery('#utc_timezone').val(data.utc_timezone);
											jQuery('#description').val(data.description);	
											jQuery('#code').val(data.code);	
											jQuery('#ads_type').val(data.ads_type);	
											jQuery('#affiliate_params').val(data.affiliate_params);	
											jQuery('#affiliate_values').val(data.affiliate_values);	
											jQuery('#trans_id_params').val(data.trans_id_params);
											jQuery('#url_confirm').val(data.url_confirm);
											jQuery('#status').val(data.status);	
											
											inactiveTab('ads_publishers_list');
											activeTab('ads_publishers_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		name = jQuery('#name').val(),
		id_country = jQuery('#id_country').val(),
		utc_timezone = jQuery('#utc_timezone').val(),
		code = jQuery('#code').val(),
		status = jQuery('#status').val(),
		ads_type = jQuery('#ads_type').val(),
		trans_id_params = jQuery('#trans_id_params').val(),
		affiliate_params = jQuery('#affiliate_params').val(),
		affiliate_values = jQuery('#affiliate_values').val(),
		url_confirm = jQuery('#url_confirm').val(),
		description = jQuery('#description').val();
	
	var form = $('#formads_publishers');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/ads_publishers/saveAds_Publishers",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'id_country' : id_country,
					'utc_timezone' : utc_timezone,
					'code' : code,
					'status' : status,
					'ads_type' : ads_type,
					'trans_id_params' : trans_id_params,
					'affiliate_params' : affiliate_params,
					'affiliate_values' : affiliate_values,
					'url_confirm' : url_confirm,
					'description' : description
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadAds_Publishers(1);
											clearForm();
											
											inactiveTab('ads_publishers_form');
											activeTab('ads_publishers_list');
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

function deleteAds_Publishers(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/ads_publishers/deleteAds_Publishers",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadAds_Publishers(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#id_country').val('');
	jQuery('#utc_timezone').val('');
	jQuery('#description').val('');
	jQuery('#code').val('');
	jQuery('#ads_type').val('');
	jQuery('#trans_id_params').val('');
	jQuery('#affiliate_params').val('');
	jQuery('#affiliate_values').val('');
	jQuery('#url_confirm').val('');
	jQuery('#status').val('');
		
	inactiveTab('ads_publishers_form');
	activeTab('ads_publishers_list');
}

jQuery(document).ready(function() {	
	// Add events
});
