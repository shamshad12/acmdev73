var files;

function loadBanners(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Name</th><th class="hidden-phone">Url & Path</th><th class="hidden-phone" width="100">Banner</th><th class="hidden-phone" width="70">Status</th><th class="hidden-phone" title="Created by" width="75">Created By</th><th class="hidden-phone" width="75" title="Updated by">Updated By</th><th class="hidden-phone" width="100">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/banners/loadBanners",
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['name']+'</td><td>Url Thumb : <br>'+data.host+data.rows[i]['url_thumb']+'<br>Url Ori : <br>'+data.host+data.rows[i]['url_ori']+'</td><td><img src="'+data.host+data.rows[i]['url_thumb']+'" width="105"/></td><td>'+data.rows[i]['status']+'</td><td title="'+data.rows[i]['user_enter_time']+'">'+data.rows[i]['user_enter']+'</td><td title="'+data.rows[i]['user_updated_time']+'">'+data.rows[i]['user_updated']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formbanners');
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
                    url_thumb: {
                        required: true
                    },
                    path_thumb: {
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
			url:  domain+"campaign_manager/banners/getBannersData",
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
											jQuery('#description').val(data.description);	
											jQuery('#path_thumb').val(data.path_thumb);	
											jQuery('#path_ori').val(data.path_ori);	
											jQuery('#url_thumb').val(data.url_thumb);
											jQuery('#url_ori').val(data.url_ori);
											jQuery('#status').val(data.status);	
											
											inactiveTab('banners_list');
											activeTab('banners_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		name = jQuery('#name').val(),
		url_thumb = jQuery('#url_thumb').val(),
		url_ori = jQuery('#url_ori').val(),
		path_thumb = jQuery('#path_thumb').val(),
		status = jQuery('#status').val(),
		path_ori = jQuery('#path_ori').val(),
		description = jQuery('#description').val();
	
	var form = $('#formbanners');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/banners/saveBanners",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'url_thumb' : url_thumb,
					'url_ori' : url_ori,
					'path_thumb' : path_thumb,
					'status' : status,
					'path_ori' : path_ori,
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
											loadBanners(1);
											clearForm();
											
											inactiveTab('banners_form');
											activeTab('banners_list');
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

function deleteBanners(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/banners/deleteBanners",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadBanners(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#url_thumb').val('');
	jQuery('#url_ori').val('');
	jQuery('#description').val('');
	jQuery('#path_thumb').val('');
	jQuery('#path_ori').val('');
	jQuery('#status').val('');
		
	inactiveTab('banners_form');
	activeTab('banners_list');
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
	
	var form = $('#formbanners');
	var error = $('.alert-error-upload', form);
    var success = $('.alert-success-upload', form);
	
	$.ajax({
		url: domain+"campaign_manager/banners/uploadBackgroundGallery?files",
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		success: function(data, textStatus, jqXHR)
		{
			if(data.status){
				jQuery('#path_thumb').val(data.path['thumb']);
				jQuery('#path_ori').val(data.path['ori']);
				jQuery('#url_ori').val(data.url['ori']);
				jQuery('#url_thumb').val(data.url['thumb']);
				jQuery('#img-upload').html('<img src="'+data.host+data.url['thumb']+'" width="100" />');
				success.show();
				error.hide();
			} else {
				success.hide();
                		error.show();
				$('.alert-error-upload').append('<p>'+data.error+'</p>');
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
