var files;

function loadPrivileges(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">User Type</th><th class="hidden-phone">Description</th><th class="hidden-phone">Default menu</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"user_management/privileges/loadPrivileges",
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
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['tipe_user_id']+'</td><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['default_menu']+'</td><td>'+data.rows[i]['status']+'</td><td width="100">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formprivileges');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    tipe_user_id: {
                        required: true
                    },
                    description: {
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
			url:  domain+"user_management/privileges/getPrivilegesData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#tipe_user_id').val(data.tipe_user_id);
											jQuery('#status').val(data.status);		
											jQuery('#description').val(data.description);
											jQuery('#default_menu').val(data.default_menu);	
											jQuery('#image_thumb').val(data.image_thumb);	
											jQuery('#img-upload').html('<img src="'+data.default_menu+'" width="100" />');
											
											inactiveTab('privileges_list');
											activeTab('privileges_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		tipe_user_id = jQuery('#tipe_user_id').val(),
		description = jQuery('#description').val(),
		default_menu = jQuery('#default_menu').val(),
		image_thumb = jQuery('#image_thumb').val(),
		status = jQuery('#status').val();
	
	var form = $('#formprivileges');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"user_management/privileges/savePrivileges",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'tipe_user_id' : tipe_user_id,
					'description' : description,
					'default_menu' : default_menu,
					'image_thumb' : image_thumb,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadPrivileges(1);
											clearForm();
											
											inactiveTab('privileges_form');
											activeTab('privileges_list');
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

function deletePrivileges(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"user_management/privileges/deletePrivileges",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadPrivileges(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#tipe_user_id').val('');
	jQuery('#status').val('');
	jQuery('#description').val('');
	jQuery('#default_menu').val('');
	jQuery('#image_thumb').val('');
	jQuery('#img-upload').html('');
	jQuery('#file_upload').val('');
		
	inactiveTab('privileges_form');
	activeTab('privileges_list');
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
	
	var form = $('#formprivileges');
	var error = $('.alert-error-upload', form);
    var success = $('.alert-success-upload', form);
	
	$.ajax({
		url: domain+"user_management/privileges/uploadBackgroundPrivileges?files",
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		success: function(data, textStatus, jqXHR)
		{
			if(data.status){
				jQuery('#default_menu').val(data.files['thumb']);
				jQuery('#img-upload').html('<img src="'+data.files['thumb']+'" width="100" />');
				success.show();
				error.hide();
			} else {
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
