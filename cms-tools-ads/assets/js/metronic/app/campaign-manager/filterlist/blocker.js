function loadBlocker(page){	
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Operators</th><th class="hidden-phone">Shortcode</th><th class="hidden-phone">Keywords</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/blocker/loadBlocker",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) { 
										//alert(JSON.stringify(data));
										//return false
										jQuery('#table-append').html('');
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												var del='';
												if(accessDelete=='1')
													del = '<a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'">Delete</a>';
												var status;
												if(data.rows[i]['status']=='Active')	
													status = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#chngeblockestatus" onclick="new_status=\''+0+'\',sid=\''+data.rows[i]['id']+'\'">Deactivate</a>';
												if(data.rows[i]['status']=='Inactive')
													status = '<a  style="cursor:pointer" class="btn green mini active" data-toggle="modal" href="#chngeblockestatus" onclick="new_status=\''+1+'\',sid=\''+data.rows[i]['id']+'\'">Activate</a>';
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['country']+'</td><td>'+data.rows[i]['telco']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['service']+'</td><td>'+status+' '+del+'</td></tr>';			
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
            var form = $('#formblocker');

            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    country_id: {
                        required: true
                    },
                    operator_id: {
                        required: true
                    },
                    shortcode: {
                        required: true
                    },
                     service: {
                        required: true
                    },
                     status: {
                        required: true
                    },
                    upload_blocker: {
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
            var form = $('#blocker_bulkform');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    upload_blocker: {
                        required: true,
                    
					    extension: 'xlsx'
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
                }
            });
        }
    };

}();

function edit(id){
	jQuery.ajax({
			url:  domain+"campaign_manager/country/getCountryData",
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
											jQuery('#code').val(data.code);
											jQuery('#description').val(data.description);	
											jQuery('#prefix').val(data.prefix);	
											jQuery('#status').val(data.status);	
											
											inactiveTab('country_list');
											activeTab('country_form');
										} else {
											
										}
									}
		   });	
}
function fetchOperators()
{
	
	var country_id = jQuery('#country_id').val();
	jQuery.ajax({
			url:  domain+"campaign_manager/blocker/fetchOperators",
			dataType: "json",
			type: "POST",
			data: {
					'country_id' : country_id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										//alert(JSON.stringify(data));
										if(data.count)
										{
											
											var result='';

											for(var i=0; i<data.rows.length; i++)
											{
												result += '<option id=\'operator_option\' value=\"' + data.rows[i]['id'] +'\">'+data.rows[i]['name'] + '</option>';
											}
										}	
											$("#operator_id").empty();
											$('#operator_id').append(result);								
			}
				
		});
}

function fetchServices()
{

	var shortcode_id = jQuery('#shortcode').val();
	jQuery.ajax({
			url:  domain+"campaign_manager/blocker/fetchServices",
			dataType: "json",
			type: "POST",
			data: {
					'shortcode_id' : shortcode_id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										//alert(JSON.stringify(data));
										//return false
										if(data.count)
										{
											
											var result='';

											for(var i=0; i<data.rows.length; i++)
											{
												result += '<option id=\'service_option\' value=\"' + data.rows[i] +'\">'+data.rows[i] + '</option>';
											}
										}
										else
										{
											result = '<option id =\'service_option\' value=\'\'>No Service</option>';
										}
										if(shortcode_id=='All')
										{
											result = '<option id =\'service_option\' value=\'All\'>All</option>';
										}	
											$("#service").empty();
											$('#service').append(result);								
			}
				
		});



}
function save(){
	var country_id = jQuery('#country_id').val(),  
		operator_id = jQuery('#operator_id').val(),
		shortcode = jQuery('#shortcode').val(),
		service = jQuery('#service').val(),
		status = jQuery('#status').val();
		
	
	var form = $('#blocker_form');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/blocker/saveBlocker",
			dataType: "json",
			type: "POST",
			data: {
					'country_id' : country_id,
					'operator_id' : operator_id,
					'shortcode' : shortcode,
					'service' : service,
					'status' : status
					
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										//alert(JSON.stringify(data));
										//return false

										if(data.duplicat_data)
										{
											success.hide();
                    						alert(data.errors_message)
										}

										if(data.success){
											success.show();
                    						error.hide();	
											loadBlocker(1);
											clearForm();
											
											inactiveTab('blocker_form');
											activeTab('blocker_list');
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

function deleteBlocker(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/blocker/deleteBlocker",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadBlocker(1);
										}
			   });	
	}
}
function changeStatus()
{

		jQuery.ajax({
					url: domain+"campaign_manager/blocker/changeStatus",
					dataType: "json",
					type: "POST",
					data: 
					{
						'id' : sid,
						'new_status' : new_status
				  	},
				 beforeSend: loading('portlet-list'),
				success: function(data) {
											loadBlocker(1);
										}

		

		});

	}

	

function clearForm(){
	jQuery('#country_id').val('');
	jQuery('#operator_id').val('');
	jQuery('#shortcode').val('');
	jQuery('#service').val('');
	jQuery('#status').val('');
	
		
	inactiveTab('blocker_form');
	inactiveTab('blocker_bulk_form');
	activeTab('blocker_list');
}
clearFormBulk()
{
	jQuery('#upload_blocker').val('');
	inactiveTab('blocker_form');
	inactiveTab('blocker_bulk_form');
	activeTab('blocker_list');
}

jQuery(document).ready(function() {	
	// Add events
});
