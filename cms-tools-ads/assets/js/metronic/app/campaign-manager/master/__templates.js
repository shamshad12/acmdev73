var files;

function loadTemplates(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		media = $('#search_media').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone" width="90">Country</th><th class="hidden-phone" width="90">Media</th><th class="hidden-phone">Name</th><th class="hidden-phone" width="80">Status</th><th class="hidden-phone" title="Created by" width="75">Created By</th><th class="hidden-phone" width="75" title="Updated by">Updated By</th><th class="hidden-phone" width="180">Action</th></tr></thead><tbody id="data-table">';
		
	jQuery.ajax({
			url:  domain+"campaign_manager/templates/loadTemplates",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'media': media
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var result = '';
										var page_confirm = '';
										var splite = '';
										var is_upload = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												page_confirm = data.rows[i]['page_confirm'];
												is_upload = data.rows[i]['is_uploaded'];
												splite = page_confirm.split('/');

												var view='';
												if(is_upload=='1')
												view = '<a href="../../ads/'+page_confirm+'" style="cursor:pointer" target="blank" title="Preview" class="btn green mini"><span class="icon-zoom-in"></a>';
												
												
												var zip='';
												if(is_upload=='1')
													zip = '<a  href="../../ads/assets/campaigns/templates/'+splite[1]+'.zip" style="cursor:pointer" title="Download" class="btn green mini active" data-toggle="modal" ><span class="icon-download"></span></a>';
												

												var edit='';
												if(accessEdit=='1')
													edit = '<a onclick="edit(\''+data.rows[i]['id']+'\')" style="cursor:pointer" title="Edit" class="btn green mini"><span class="icon-edit"></span></a>';
												var del='';
												if(accessDelete=='1')
													del = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" title="Delete" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'"><span class="icon-trash"></span></a>';
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['name_country']+'</td><td>'+data.rows[i]['media_name']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['status']+'</td><td title="'+data.rows[i]['user_enter_time']+'">'+data.rows[i]['user_enter']+' <span id="usr-up-time">'+data.rows[i]['user_enter_time']+'</span></td><td title="'+data.rows[i]['user_updated_time']+'">'+data.rows[i]['user_updated']+' <span id="usr-up-time">'+data.rows[i]['user_updated_time']+'</span></td><td>'+edit+' '+view+' '+zip+' '+del+' </td></tr>';			
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
            var form = $('#formtemplates');
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

var StaticFormValidation = function () {
    return {
        init: function () {
            var form = $('#formtemplates_static');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    static_id_country: {
                        required: true
                    },
                    static_name: {
                        required: true
                    },
                    static_id_template: {
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
					save_static();
                }
            });
        }
    };

}();

var UploadFormValidation = function () {
    return {
        init: function () {
            var form = $('#formtemplates_upload');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    upload_id_country: {
                        required: true
                    },
                    upload_name: {
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
					save_upload();
                }
            });
        }
    };

}();

function edit(id){
	jQuery.ajax({
			url:  domain+"campaign_manager/templates/getTemplatesData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
									if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#is_uploaded').val(data.is_uploaded);
											jQuery('#name').val(data.name);
											jQuery('#id_country').val(data.id_country);
											jQuery('#id_campaign_media').val(data.id_campaign_media);
											jQuery('#description').val(data.description);	
											
											jQuery('#status').val(data.status);	
											
											if(data.is_uploaded == '1'){												
												
												jQuery('#page-confirm').css('display', 'none');												
												jQuery('#page-status').css('display', 'none');
												jQuery('#page-footer').css('display', 'none');															
												jQuery('#page-error').css('display', 'none');
												
												jQuery('#btn-confirm').css('display', 'block');												
												jQuery('#btn-status').css('display', 'block');												
												jQuery('#btn-footer').css('display', 'block');
												jQuery('#btn-error').css('display', 'block');
												jQuery('#btn-css').css('display', 'block');
												
												jQuery('#btn_page_confirm').css('display', 'block');
												jQuery('#btn_page_confirm').attr('href', data.url+'editor.php?data='+data.decode_confirm);
												
												jQuery('#btn_page_status').css('display', 'block');
												jQuery('#btn_page_status').attr('href', data.url+'editor.php?data='+data.decode_status);

												jQuery('#btn_page_footer').css('display', 'block');
												jQuery('#btn_page_footer').attr('href', data.url+'editor.php?data='+data.decode_footer);

												jQuery('#btn_page_error').css('display', 'block');
												jQuery('#btn_page_error').attr('href', data.url+'editor.php?data='+data.decode_error);

												jQuery('#btn_page_css').css('display', 'block');
												//jQuery('#btn_page_css').attr('href', data.url+'editor.php?data='+data.decode_css);
												var res = data.url.split("/");
												jQuery('#btn_page_css').attr('href', 'http://54.169.14.129/ads/sceditor/editcss.php?data='+data.decode_css+'&file='+res[5]);

											} else {
												jQuery('#page-confirm').css('display', 'block');												
												jQuery('#page-status').css('display', 'block');												
												jQuery('#page-footer').css('display', 'block');
												jQuery('#page-error').css('display', 'block');
												
												jQuery('#btn-confirm').css('display', 'none');												
												jQuery('#btn-status').css('display', 'none');												
												jQuery('#btn-footer').css('display', 'none');
												jQuery('#btn-error').css('display', 'none');
												jQuery('#btn-css').css('display', 'none');
																								
												CKEDITOR.instances.page_confirm.setData( data.page_confirm );
												
												CKEDITOR.instances.page_status.setData( data.page_status );
												
												CKEDITOR.instances.page_error.setData( data.page_footer );//
												
												CKEDITOR.instances.page_error.setData( data.page_error );
											}
											
											inactiveTab('templates_list');
											activeTab('templates_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		is_uploaded = jQuery('#is_uploaded').val(),
		name = jQuery('#name').val(),
		id_country = jQuery('#id_country').val(),
		id_campaign_media = jQuery('#id_campaign_media').val(),
		page_confirm = CKEDITOR.instances.page_confirm.getData(),
		page_status = CKEDITOR.instances.page_status.getData(),
		//page_footer = CKEDITOR.instances.page_footer.getData(),
		page_error = CKEDITOR.instances.page_error.getData(),
		status = jQuery('#status').val(),
		description = jQuery('#description').val();
	
	var form = $('#formtemplates');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/templates/saveTemplates",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'is_uploaded' : is_uploaded,
					'name' : name,
					'is_uploaded' : is_uploaded,
					'id_country' : id_country,
					'id_campaign_media' : id_campaign_media,
					'page_confirm' : page_confirm,
					'page_status' : page_status,
					'page_error' : page_error,
					'status' : status,
					'description' : description
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
											loadTemplates(1);
											clearForm();
											
											inactiveTab('templates_form');
											activeTab('templates_list');
										} else {
											success.hide();
                    						error.show();
										}
									}
		   });	
}

function save_static(){
	var id = jQuery('#static_id').val(),
		name = jQuery('#static_name').val(),
		id_country = jQuery('#static_id_country').val(),
		id_campaign_media = jQuery('#static_id_campaign_media').val(),
		id_template = jQuery('#static_id_template').val(),
		static_description = jQuery('#static_description').val(),
		static_conf_header_text = jQuery('#static_conf_header_text').val(),
		static_conf_id_banner = jQuery('#static_conf_id_banner').val(),
		static_conf_text = jQuery('#static_conf_text').val(),
		static_conf_msisdn_prefix = jQuery('#static_conf_msisdn_prefix').val(),
		static_conf_button_text = jQuery('#static_conf_button_text').val(),
		static_conf_tc_description = jQuery('#static_conf_tc_description').val(),
		static_conf_footer_text = jQuery('#static_conf_footer_text').val(),
		static_thanks_header_text = jQuery('#static_thanks_header_text').val(),
		static_thanks_id_banner = jQuery('#static_thanks_id_banner').val(),
		static_thanks_text = jQuery('#static_thanks_text').val(),
		static_thanks_sms_keyword = jQuery('#static_thanks_sms_keyword').val(),
		static_thanks_sms_shortcode = jQuery('#static_thanks_sms_shortcode').val(),
		static_thanks_button_text = jQuery('#static_thanks_button_text').val(),
		static_thanks_tc_description = jQuery('#static_thanks_tc_description').val(),
		static_thanks_footer_text = jQuery('#static_thanks_footer_text').val(),		
		status = jQuery('#static_status').val();
	
	var form = $('#formtemplates_static');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/templates/saveTemplatesStatic",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'id_country' : id_country,
					'id_campaign_media' : id_campaign_media,
					'id_template' : id_template,
					'static_description' : static_description,
					'static_conf_header_text' : static_conf_header_text,
					'static_conf_id_banner' : static_conf_id_banner,
					'static_conf_text' : static_conf_text,
					'static_conf_msisdn_prefix' : static_conf_msisdn_prefix,
					'static_conf_button_text' : static_conf_button_text,
					'static_conf_tc_description' : static_conf_tc_description,
					'static_conf_footer_text' : static_conf_footer_text,
					'static_thanks_header_text' : static_thanks_header_text,
					'static_thanks_id_banner' : static_thanks_id_banner,
					'static_thanks_text' : static_thanks_text,
					'static_thanks_sms_keyword' : static_thanks_sms_keyword,
					'static_thanks_sms_shortcode' : static_thanks_sms_shortcode,
					'static_thanks_button_text' : static_thanks_button_text,
					'static_thanks_tc_description' : static_thanks_tc_description,
					'static_thanks_footer_text' : static_thanks_footer_text,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadTemplates(1);
											clearFormStatic();
											
											inactiveTab('templates_static_form');
											activeTab('templates_list');
										} else {
											success.hide();
                    						error.show();
										}
									}
		   });	
}

function save_upload(){
	var id = jQuery('#upload_id').val(),
		name = jQuery('#upload_name').val(),
		id_country = jQuery('#upload_id_country').val(),
		id_campaign_media = jQuery('#upload_id_campaign_media').val(),
		id_template = jQuery('#upload_id_template').val(),
		description = jQuery('#upload_description').val(),
		page_confirm = jQuery('#upload_page_confirm').val(),
		page_status = jQuery('#upload_page_status').val(),
		page_pin = jQuery('#upload_page_pin').val(),
		status = jQuery('#upload_status').val();
	
	var form = $('#formtemplates_static');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/templates/saveTemplatesUpload",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'id_country' : id_country,
					'id_campaign_media' : id_campaign_media,
					'id_template' : id_template,
					'description' : description,
					'page_confirm' : page_confirm,
					'page_status' : page_status,
					'page_pin' : page_pin,
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
											loadTemplates(1);
											clearFormUpload();
											
											$("#upload_file").val('');
											$("#upload_description").val('');
											$("#upload_name").val('');

											inactiveTab('templates_upload_form');
											$(".alert").css('display','none');
											activeTab('templates_list');
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

function deleteTemplates(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/templates/deleteTemplates",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											//loadTemplates(1);
											if(data.duplicat_data) 
										    {
											  alert(data.errors_message);
											  return false;
										    }
										    else
										    { 
											  loadTemplates(1);
										    }
										}
			   });	
	}
}

function getPrices(id_country){
	$('#id_price').val('');
	$('#id_price').attr('disabled', 'disabled');
	if(id_country != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/prices/loadPricesSelect",
				dataType: "json",
				type: "POST",
				data: {
						'id_country' : id_country
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											var result = '<option value="">- Choose Price -</option>';											
											if(data.count){	
												for(var i=0; i<data.rows.length; i++){
													result += "<option value='"+data.rows[i]['id']+"'>"+data.rows[i]['cu_code']+" "+data.rows[i]['value']+"</option>";
												}
												$('#id_price').html(result);
												$('#id_price').removeAttr('disabled');
											}
										}
			   });	
	}
}

function getAds_Publishers(id_country){
	$('#id_ads_publisher').val('');
	$('#id_ads_publisher').attr('disabled', 'disabled');
	if(id_country != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/ads_publishers/loadAds_PublishersSelect",
				dataType: "json",
				type: "POST",
				data: {
						'id_country' : id_country
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											var result = '<option value="">- Choose Ads Publisher -</option>';											
											if(data.count){	
												for(var i=0; i<data.rows.length; i++){
													result += "<option value='"+data.rows[i]['id']+"'>"+data.rows[i]['name']+"</option>";
												}
												$('#id_ads_publisher').html(result);
												$('#id_ads_publisher').removeAttr('disabled');
											}
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#is_uploaded').val('');
	jQuery('#name').val('');
	jQuery('#id_country').val('');
	jQuery('#id_campaign_media').val('');
	jQuery('#description').val('');
	CKEDITOR.instances.page_confirm.setData('');
	CKEDITOR.instances.page_status.setData('');
	//CKEDITOR.instances.page_footer.setData('');
	CKEDITOR.instances.page_error.setData('');
	jQuery('#status').val('');
	
	jQuery('#page-confirm').css('display', 'block');												
	jQuery('#page-status').css('display', 'block');												
	jQuery('#page-error').css('display', 'block');
	
	jQuery('#btn-confirm').css('display', 'none');												
	jQuery('#btn-status').css('display', 'none');												
	jQuery('#btn-error').css('display', 'none');
		
	inactiveTab('templates_form');
	activeTab('templates_list');
}

function clearFormStatic(){
	jQuery('#static_id').val('');
	jQuery('#static_name').val('');
	jQuery('#static_id_country').val('');
	jQuery('#static_id_campaign_media').val('');
	jQuery('#static_id_template').val('');
	jQuery('#static_description').val(''),
	jQuery('#static_conf_header_text').val(''),
	jQuery('#static_conf_id_banner').val(''),
	jQuery('#static_conf_text').val(''),
	jQuery('#static_conf_msisdn_prefix').val(''),
	jQuery('#static_conf_button_text').val(''),
	jQuery('#static_conf_tc_description').val(''),
	jQuery('#static_conf_footer_text').val(''),
	jQuery('#static_thanks_header_text').val(''),
	jQuery('#static_thanks_id_banner').val(''),
	jQuery('#static_thanks_text').val(''),
	jQuery('#static_thanks_sms_keyword').val(''),
	jQuery('#static_thanks_sms_shortcode').val(''),
	jQuery('#static_thanks_button_text').val(''),
	jQuery('#static_thanks_tc_description').val(''),
	jQuery('#static_thanks_footer_text').val(''),
	jQuery('#status').val('');
		
	inactiveTab('templates_static_form');
	activeTab('templates_list');
}

function clearFormUpload(){
	jQuery('#upload_id').val('');
	jQuery('#upload_name').val('');
	jQuery('#upload_id_country').val('');
	jQuery('#upload_id_campaign_media').val('');
	jQuery('#upload_id_template').val('');
	jQuery('#upload_page_confirm').val(''),
	jQuery('#upload_page_status').val(''),
	jQuery('#status').val('');
		
	inactiveTab('templates_upload_form');
	activeTab('templates_list');
}

function prepareUpload(event)
{
	files = event.target.files;
	
	uploadFiles(event);
}

function uploadFiles(event)
{
	event.stopPropagation(); // Stop stuff happening
	event.preventDefault(); // Totally stop stuff happening

	// START A LOADING SPINNER HERE

	// Create a formdata object and add the files
	var data = new FormData();
	$.each(files, function(key, value)
	{
		data.append(key, value);
	});
	
	var form = $('#formtemplates_upload');
	var error = $('.alert-error-upload-problem', form);
    var success = $('.alert-success-upload', form);

    jQuery('#upload_web_temp').html('Uploading Template...');
	
	$.ajax({
		url: domain+"campaign_manager/templates/uploadBackground?files",
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		success: function(data, textStatus, jqXHR)
		{
			if(data.status){

				jQuery('#upload_page_confirm').val(data.confirm);
				jQuery('#upload_page_status').val(data.status);
				jQuery('#upload_page_pin').val(data.pin);
				jQuery('#upload_web_temp').removeAttr('disabled');
				jQuery('#upload_web_temp').html('Save');
				success.show();
				error.hide();
			} else {				
				jQuery('#upload-problem').html(data.error);
				jQuery('#upload_web_temp').html('Upload Failed');
				success.hide();
                error.show();
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log('ERRORS: ' + textStatus);
			// STOP LOADING SPINNER
		}
	});
}

jQuery(document).ready(function() {	
	// Add events
	$('input[type=file]').on('change', prepareUpload);
	//$('form').on('submit', uploadFiles);		
});
