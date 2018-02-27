var files;

function loadPartners(){		
	var html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">ID</th><th class="hidden-phone">Type</th><th class="hidden-phone">Title</th><th class="hidden-phone">Description</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"backend/partners/loadPartners",
			dataType: "json",
			type: "POST",
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').find('table').parent().remove();
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['type']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['status']+'</td><td width="100"><a onclick="edit(\''+data.rows[i]['id']+'\')" style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'">Delete</a></td></tr>';			
											}
										}
										$('#table-append').append(html+result+'</tbody></table>');
										handleTables('group-list');
									}
		   });	
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formpartners');
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
			url:  domain+"backend/partners/getPartnersData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#type').val(data.type);
											jQuery('#name').val(data.name);
											jQuery('#status').val(data.status);		
											jQuery('#description').val(data.description);
											jQuery('#image').val(data.image);
											jQuery('#img-upload').html('<img src="'+data.image+'" width="100" />');
											
											inactiveTab('partners_list');
											activeTab('partners_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		type = jQuery('#type').val(),
		name = jQuery('#name').val(),
		description = jQuery('#description').val(),
		image = jQuery('#image').val(),
		status = jQuery('#status').val();
	
	var form = $('#formpartners');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"backend/partners/savePartners",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'type' : type,
					'name' : name,
					'description' : description,
					'image' : image,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadPartners();
											clearForm();
											
											inactiveTab('partners_form');
											activeTab('partners_list');
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

function deletePartners(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"backend/partners/deletePartners",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadPartners();
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#type').val('');
	jQuery('#name').val('');
	jQuery('#status').val('');
	jQuery('#description').val('');
	jQuery('#image').val('');
	jQuery('#img-upload').html('');
	jQuery('#file_upload').val('');
		
	inactiveTab('partners_form');
	activeTab('partners_list');
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
	
	var form = $('#formpartners');
	var error = $('.alert-error-upload', form);
    var success = $('.alert-success-upload', form);
	
	$.ajax({
		url: domain+"backend/partners/uploadBackgroundPartners?files",
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		success: function(data, textStatus, jqXHR)
		{
			if(data.status){
				jQuery('#image_ori').val(data.files['ori']);
				jQuery('#img-upload').html('<img src="'+data.files['ori']+'" width="100" />');
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
