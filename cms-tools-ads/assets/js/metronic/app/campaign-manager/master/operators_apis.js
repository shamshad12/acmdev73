function loadOperators_Apis(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		operator = $('#search_operator').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone" width="90">Operator</th><th class="hidden-phone" width="100">Type | Method</th><th class="hidden-phone">Name</th><th class="hidden-phone">Url API</th><th class="hidden-phone" width="60">Status</th><th class="hidden-phone" width="100">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/operators_apis/loadOperators_Apis",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['operator_name']+'</td><td>'+data.rows[i]['type']+' | '+data.rows[i]['method']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['url_api']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formoperators_apis');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    method: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    url_api: {
                        required: true
                    },
                    id_operator: {
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
			url:  domain+"campaign_manager/operators_apis/getOperators_ApisData",
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
											jQuery('#url_api').val(data.url_api);
											jQuery('#method').val(data.method);
											jQuery('#type').val(data.type);
											jQuery('#params_request').val(data.params_request);		
											jQuery('#params_response').val(data.params_response);	
											jQuery('#status').val(data.status);	
											jQuery('#id_operator').val(data.id_operator);
																						
											inactiveTab('operators_apis_list');
											activeTab('operators_apis_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id 			= jQuery('#id').val(),
		name 		= jQuery('#name').val(),
		method 		= jQuery('#method').val(),
		type 		= jQuery('#type').val(),
		url_api 	= jQuery('#url_api').val(),
		id_operator	= jQuery('#id_operator').val(),
		params_response 	= jQuery('#params_response').val(),
		params_request = jQuery('#params_request').val(),
		status 		= jQuery('#status').val();
	
	var form = $('#formoperators_apis');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/operators_apis/saveOperators_Apis",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'method' : method,
					'type' : type,
					'url_api' : url_api,
					'id_operator' : id_operator,
					'params_request' : params_request,
					'params_response' : params_response,
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
											loadOperators_Apis(1);
											clearForm();
											
											inactiveTab('operators_apis_form');
											activeTab('operators_apis_list');
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

function deleteOperators_Apis(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/operators_apis/deleteOperators_Apis",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadOperators_Apis(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#method').val('');
	jQuery('#type').val('');
	jQuery('#params_request').val('');
	jQuery('#url_api').val('');
	jQuery('#id_operator').val('');
	jQuery('#params_response').val('');
	jQuery('#status').val('');
		
	inactiveTab('operators_apis_form');
	activeTab('operators_apis_list');
}

jQuery(document).ready(function() {	
	// Add events
});
