function loadWebAPI(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Campaign Code</th><th class="hidden-phone">Affiliate Name</th><th class="hidden-phone">API URL</th><th class="hidden-phone">Params</th><th class="hidden-phone">Domain</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/webapi/loadWebAPI",
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['campaign_code']+'</td><td>'+data.rows[i]['affiliate_name']+'</td><td>'+data.rows[i]['api_url']+'</td><td>'+data.rows[i]['param']+'</td><td>'+data.rows[i]['domain']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formdomains');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    campaign_code: {
                        required: true
                    },
                    affiliate_name: {
                        required: true
                    },
                    api_url: {
                        required: true
                    },
                    param: {
                        required: true
                    },
                    domain: {
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

var TestFormValidation = function () {
    return {
        init: function () {
            var form = $('#formdomains_test');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    campaign_code_test: {
                        required: true
                    },
                    msisdn_test: {
                        required: true
                    },
                    refferal_test: {
                        required: true
                    },
                    domain_test: {
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
					saveTestwebAPI();
                }
            });
        }
    };

}();

function edit(id){
	jQuery.ajax({
			url:  domain+"campaign_manager/webapi/getWebAPIData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#campaign_code').val(data.campaign_code);
											jQuery('#affiliate_name').val(data.affiliate_name);
											jQuery('#api_url').val(data.api_url);	
											jQuery('#param').val(data.param);
											jQuery('#domain').val(data.domain);	
											jQuery('#status').val(data.status);	
											
											inactiveTab('domains_list');
											activeTab('domains_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		campaign_code = jQuery('#campaign_code').val(),
		affiliate_name = jQuery('#affiliate_name').val(),
		api_url = jQuery('#api_url').val(),
		status = jQuery('#status').val(),
		param = jQuery('#param').val(),
		domain_name = jQuery('#domain').val();
	
	var form = $('#formdomains');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/webapi/saveWebAPI",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'campaign_code' : campaign_code,
					'affiliate_name' : affiliate_name,
					'api_url' : api_url,
					'status' : status,
					'param' : param,
					'domain' : domain_name
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {

										if(data.duplicat_data) 
										{
											success.hide();
											alert(data.errors_message);
										}

										if(data.success){
											success.show();
                    						error.hide();	
											loadWebAPI(1);
											clearForm();
											
											inactiveTab('domains_form');
											activeTab('domains_list');
										} else {
											success.hide();
                    						error.show();
										}
									}
		   });	
}


function saveTestwebAPI(){
	var campaign_code_test = jQuery('#campaign_code_test').val(),
		msisdn_test = jQuery('#msisdn_test').val(),
		refferal_test = jQuery('#refferal_test').val(),
		domain_test = jQuery('#domain_test').val();
		visitorip = jQuery('#visitorip').val();
		pin = jQuery('#pin').val();
		test_result = jQuery('#test_result').val();
	
	var form = $('#formdomains_test');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/webapi/saveTestwebAPI",
			dataType: "json",
			type: "POST",
			data: {
					'campaign_code_test' : campaign_code_test,
					'msisdn_test' : msisdn_test,
					'refferal_test' : refferal_test,
					'domain_test' : domain_test,
					'visitorip' : visitorip,
					'pin' : pin,
					'test_result' : test_result
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#campaign_code_test').val(data.campaign_code_test);
											jQuery('#msisdn_test').val(data.msisdn_test);
											jQuery('#refferal_test').val(data.refferal_test);	
											jQuery('#domain_test').val(data.domain_test);
											jQuery('#visitorip').val(data.visitorip);
											if(typeof data.test_result.txn_id === 'undefined')
											{
												var txn_id = '';
											}
											else
											{
												var txn_id = ', txn id: '+data.test_result.txn_id;
											}
											if((typeof data.test_result.sms_status === 'undefined') || (data.test_result.sms_status === null) || (jQuery.isEmptyObject(data.test_result.sms_status)) || (data.test_result.sms_status === 0) || (typeof data.test_result.sms_status.pin === 'undefined') || (data.test_result.sms_status.pin === null) || (jQuery.isEmptyObject(data.test_result.sms_status.pin)) || (data.test_result.sms_status.pin === 0)) 
											{
												//alert('hi');return false;
												var sms_status = '';
												var	validate_url = '';
											}
											else
											{
												//alert('byeee');return false;
												if(data.test_result.sms_status.pin!=null || data.test_result.sms_status.pin)
												{
													jQuery('#pin').val(data.test_result.sms_status.pin);
												}
												var sms_status = ', sms status: '+data.test_result.sms_status.msg;
												var validate_url = '\n validate url: http://54.169.14.129/webapi/validatepin?pin={pin}';

											}
											if(data.test_result.error==false)
											{
												jQuery('#test_result').val('message: '+data.test_result.msg+txn_id+sms_status+'\nurl: '+data.test_result_url+validate_url);
											}
											else
											{
												jQuery('#test_result').val('Status: Failed, message: '+data.test_result.msg);
											}
										}
									}
		   });	
}

function setDeleteID(id){
	deleteID = 	id;
}

function deleteWebAPI(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/webapi/deleteWebAPI",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadWebAPI(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#campaign_code').val('');
	jQuery('#affiliate_name').val('');
	jQuery('#api_url').val('');
	jQuery('#param').val('');
	jQuery('#domain').val('');
	jQuery('#status').val('');
		
	inactiveTab('domains_form');
	activeTab('domains_list');
}

function clearFormTest(){
	jQuery('#id').val('');
	jQuery('#campaign_code_test').val('');
	jQuery('#msisdn_test').val('');
	jQuery('#refferal_test').val('');
	jQuery('#domain_test').val('');
		
	inactiveTab('domains_test');
	activeTab('domains_list');
}

jQuery(document).ready(function() {	
	// Add events
});
