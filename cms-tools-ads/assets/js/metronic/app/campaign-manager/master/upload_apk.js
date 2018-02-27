var files;

function loadTemplates(page) {
	var limit = $('#limit').val(), search = $('#search').val(), user = $('#search_user').val(), html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone" width="90">Country</th><th class="hidden-phone">Name</th><th class="hidden-phone">URL</th><th class="hidden-phone" width="80">Status</th><th class="hidden-phone" title="Created by" width="75">Created By</th><th class="hidden-phone" width="75" title="Updated by">Updated By</th><th class="hidden-phone" width="236">Action</th></tr></thead><tbody id="data-table">';

	jQuery
			.ajax({
				url : domain + "campaign_manager/upload_apk/loadApk",
				dataType : "json",
				type : "POST",
				data : {
					'limit' : limit,
					'page' : page,
					'search' : search,
					'search_user' : user
				},
				// beforeSend: progress_jenis_user_list,
				success : function(data) {
					jQuery('#table-append').html('');
					var result = ''
					if (data.count) {
						for ( var i = 0; i < data.rows.length; i++) {
							var isLock = data.rows[i]['edit_type'];
							var fontcolor = (isLock == 1 & (data.rows[i]['login_user'] != data.rows[i]['edit_user'])) ? 'red'
									: '';
							var apk = '';
								apk = '<a  href="'+data.rows[i]['apkurl']+'" style="cursor:pointer" title="Download" class="btn green mini active" target="_blank" data-toggle="modal"><span class="icon-download"></span></a>';

							var edit = '';
							if (accessEdit == '1')
								edit = '<a onclick="edit(\''
										+ data.rows[i]['id']
										+ '\')" style="cursor:pointer" title="Edit" class="btn green mini"><span class="icon-edit"></span></a>';
							var del = '';
							if (accessDelete == '1') {
								del = '<a href="#delete" style="cursor:pointer;" class="btn red mini active" data-toggle="modal" onclick="deleteID=\''
											+ data.rows[i]['id']
											+ '\'" title="delete"><span class="icon-trash"></span></a>';
							}

							result += '<tr class="odd gradeX"><td>'
									+ data.rows[i]['name_country']
									+ '</td><td class=' + fontcolor + '>'
									+ data.rows[i]['name'] + '</td><td>'
									+ data.rows[i]['apkurl']
									+ '</td><td>'
									+ data.rows[i]['status']
									+ '</td><td title="'
									+ data.rows[i]['user_enter_time'] + '">'
									+ data.rows[i]['user_enter']
									+ ' <span id="usr-up-time">'
									+ data.rows[i]['user_enter_time']
									+ '</span></td><td title="'
									+ data.rows[i]['user_updated_time'] + '">'
									+ data.rows[i]['user_updated']
									+ ' <span id="usr-up-time">'
									+ data.rows[i]['user_updated_time']
									+ '</span></td><td>' + edit + '' + apk + '' + del
									+ ' </td></tr>';
						}
					}
					var paging = '<div id="paging">' + data.pagination
							+ '</div>';
					$('#table-append').append(
							html + result + '</tbody></table>' + paging);
				}
			});
}

var UploadFormValidation = function() {
	return {
		init : function() {
			var form = $('#formtemplates_upload');
			var error = $('.alert-error', form);
			var success = $('.alert-success', form);

			form.validate({
				errorElement : 'span', // default input error message container
				errorClass : 'help-inline', // default input error message class
				focusInvalid : false, // do not focus the last invalid input
				ignore : "",
				rules : {
					upload_id_country : {
						required : true
					},
					upload_name : {
						required : true
					}
				},

				invalidHandler : function(event, validator) { // display error
					// alert on form
					// submit
					success.hide();
					error.show();
					App.scrollTo(error, -200);
				},

				highlight : function(element) { // hightlight error inputs
					$(element).closest('.help-inline').removeClass('ok'); // display
					// OK
					// icon
					$(element).closest('.control-group').removeClass('success')
							.addClass('error'); // set error class to the
					// control group
				},

				unhighlight : function(element) { // revert the change dony by
					// hightlight
					$(element).closest('.control-group').removeClass('error'); // set
					// error
					// class
					// to
					// the
					// control
					// group
				},

				success : function(label) {
					label.addClass('valid').addClass('help-inline ok') // mark
					// the
					// current
					// input
					// as
					// valid
					// and
					// display
					// OK
					// icon
					.closest('.control-group').removeClass('error').addClass(
							'success'); // set success class to the control
					// group
				},

				submitHandler : function(form) {
					success.show();
					error.hide();
					save_upload();
				}
			});
		}
	};

}();

function edit(id) {
	jQuery
			.ajax({
				url : domain + "campaign_manager/upload_apk/getApkData",
				dataType : "json",
				type : "POST",
				data : {
					'id' : id
				},
				beforeSend : loading('portlet-form'),
				success : function(data) {
					// alert(JSON.stringify(data));
					if (data.count) {
						jQuery('#upload_id').val(data.id);
						jQuery('#apkurl').val(data.apkurl);
						jQuery('#upload_name').val(data.name);
						jQuery('#upload_id_country').val(data.id_country);
						jQuery('#upload_description').val(data.description);
						jQuery('#upload_status').val(data.status);
						jQuery('#upload_web_temp').removeAttr('disabled');
						inactiveTab('templates_list');
						activeTab('templates_upload_form');
					} else {

					}
				}
			});
}

function save_upload() {
	var id = jQuery('#upload_id').val(), name = jQuery('#upload_name').val(), id_country = jQuery(
			'#upload_id_country').val(), description = jQuery(
			'#upload_description').val(), apkurl = jQuery(
			'#apkurl').val(), status = jQuery('#upload_status').val();

	var form = $('#formtemplates_static');
	var error = $('.alert-error-save', form);
	var success = $('.alert-success-save', form);

	jQuery.ajax({
		url : domain + "campaign_manager/upload_apk/saveUploadApk",
		dataType : "json",
		type : "POST",
		data : {
			'id' : id,
			'name' : name,
			'id_country' : id_country,
			'description' : description,
			'apkurl' : apkurl,
			'status' : status
		},
		beforeSend : loading('portlet-form'),
		success : function(data) {
			if (data.duplicat_data) {
				success.hide();
				alert(data.errors_message)
			}

			if (data.success) {
				success.show();
				error.hide();
				loadTemplates(1);
				clearFormUpload();

				$("#upload_file").val('');
				$("#upload_description").val('');
				$("#upload_name").val('');

				inactiveTab('templates_upload_form');
				$(".alert").css('display', 'none');
				activeTab('templates_list');
			} else {
				success.hide();
				error.show();
			}
		}
	});
}

function setDeleteID(id) {
	deleteID = id;
}

function changeStatus() {
	// alert(sid);return false;
	jQuery.ajax({
		url : domain + "campaign_manager/templates/Status",
		dataType : "json",
		type : "POST",
		data : {
			'id' : sid,
			'edit_type' : edit_type
		},
		beforeSend : loading('portlet-list'),
		success : function(data) {
			// alert(data);return false;
			loadTemplates(1);
		}

	});

}

function deleteApk(id) {
	if (id != "") {
		deleteID = id;
	}
	if (deleteID != "") {
		jQuery.ajax({
			url : domain + "campaign_manager/upload_apk/deleteApk",
			dataType : "json",
			type : "POST",
			data : {
				'id' : deleteID
			},
			beforeSend : loading('portlet-list'),
			success : function(data) {
				// loadTemplates(1);
				if (data.duplicateedit_data) {
					alert(data.edit_message);
					return false;
				}
				if (data.duplicat_data) {
					alert(data.errors_message);
					return false;
				} else {
					loadTemplates(1);
				}
			}
		});
	}
}

function clearFormUpload() {
	jQuery('#id').val('');
	jQuery('#upload_id').val('');
	jQuery('#upload_name').val('');
	jQuery('#upload_id_country').val('');
	// jQuery('#upload_file').replaceWith(jQuery("#upload_file").clone());
	jQuery('#apkurl').val('');
	jQuery('#status').val('');
    jQuery('#upload_description').val('');
	inactiveTab('templates_upload_form');
	activeTab('templates_list');
}

function prepareUpload(event) {
	files = event.target.files;
	var r = confirm('Are you sure you want to upload?');
	if (r == true) {
	    uploadFiles(event);
	}
	// uploadFiles(event);
}

function uploadFiles(event) {
	
	var data = new FormData();
	$.each(files, function(key, value) {
		data.append(key, value);
	});
	
	var form = $('#formtemplates_upload');
	var error = $('.alert-error-upload-problem', form);
	var success = $('.alert-success-upload', form);

	jQuery('#upload_web_temp').html('Uploading Template...');

	$.ajax({
		url : domain + "campaign_manager/upload_apk/uploadBackground?files",
		type : 'POST',
		data : data,
		cache : false,
		dataType : 'json',
		processData : false, // Don't process the files
		contentType : false, // Set content type to false as jQuery will tell
		// the server its a query string request
		success : function(data, textStatus, jqXHR) {
			if (data.status) {

				jQuery('#apkurl').val(data.url);
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
		error : function(jqXHR, textStatus, errorThrown) {
			console.log('ERRORS: ' + textStatus);
			// STOP LOADING SPINNER
		}
	});
}


jQuery(document).ready(function() {
	// Add events
	$('input[type=file]').on('change', prepareUpload);
	// $('form').on('submit', uploadFiles);
});
