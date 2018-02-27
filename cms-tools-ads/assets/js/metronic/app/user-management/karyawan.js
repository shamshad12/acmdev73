var files;

function loadKaryawan(page){	
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">ID</th><th class="hidden-phone">Nama Karyawan</th><th class="hidden-phone">Email</th><th class="hidden-phone">Username</th><th class="hidden-phone">User Type</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	var tipe_user_id = $('#filter_tipe_user_id').val();
		
	jQuery.ajax({
			url:  domain+"user_management/karyawan/loadKaryawan",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'tipe_user_id' : tipe_user_id
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var result = '';
										for(var i=0; i<data.rows.length; i++){
											var duplicate = '';
											if (accessDuplicate == '1')
												duplicate = '<a  style="cursor:pointer;" class="btn blue mini active" data-toggle="modal" href="#duplicate" onclick="duplicateID=\'' + data.rows[i]['id'] + '\'" title="duplicate"><span class="icon-copy"></span></a>';
											var edit='';
											if(accessEdit=='1')
												edit = '<a onclick="edit(\''+data.rows[i]['id']+'\')" style="cursor:pointer" class="btn green mini">Edit</a>';
											var del='';
											if(accessDelete=='1')
												del = '<a style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'" >Delete</a>';
												
											result  += '<tr class="odd gradeX"><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['nama_karyawan']+'</td><td>'+data.rows[i]['email']+'</td><td>'+data.rows[i]['username']+'</td><td>'+data.rows[i]['nama_tipe_user']+'</td><td>'+edit+' '+duplicate+' '+del+'</td></tr>';			
										}
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(html+result+'</tbody></table>'+paging);
									}
		   });	
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formkaryawan');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    nama_karyawan: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                    tempat_lahir: {
                        required: true
                    },
                    tanggal_lahir: {
                        required: true
                    },
                    jenis_kelamin: {
                        required: true
                    },
                    agama: {
                        required: true
                    },
                    alamat: {
                        required: true
                    },
                    telepon: {
                        required: true,
						number: true
                    },
                    email: {
                        required: true,
                        email: true
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

function edit(id){
	jQuery.ajax({
			url:  domain+"user_management/karyawan/getKaryawanData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
				
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#nama_karyawan').val(data.nama_karyawan);
											jQuery('#tempat_lahir').val(data.tempat_lahir);
											jQuery('#tanggal_lahir').val(data.tanggal_lahir);
											jQuery('#jenis_kelamin').val(data.jenis_kelamin);
											jQuery('#agama').val(data.agama);
											jQuery('#alamat').val(data.alamat);
											jQuery('#email').val(data.email);
											jQuery('#telepon').val(data.telepon);
											jQuery('#status').val(data.status);
											jQuery('#avatar_ori').val(data.avatar_ori);
											jQuery('#avatar_thumb').val(data.avatar_thumb);
											jQuery('#img-upload').html('<img src="'+data.avatar_thumb+'" width="50" />');
											
											
											jQuery('#username').val(data.username);
											jQuery('#tipe_user_id').val(data.tipe_user_id);
											jQuery('#id_country').val(data.id_country);
											
											inactiveTab('karyawan_list');
											activeTab('karyawan_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		nama_karyawan = jQuery('#nama_karyawan').val(),
		tempat_lahir = jQuery('#tempat_lahir').val(),
		tanggal_lahir = jQuery('#tanggal_lahir').val(),
		jenis_kelamin = jQuery('#jenis_kelamin').val(),
		agama = jQuery('#agama').val(),
		alamat = jQuery('#alamat').val(),
		email = jQuery('#email').val(),
		telepon = jQuery('#telepon').val(),
		status = jQuery('#status').val(),
		avatar_ori = jQuery('#avatar_ori').val(),
		avatar_thumb = jQuery('#avatar_thumb').val();
		id_country = jQuery('#id_country').val();
	var	username = jQuery('#username').val(),
		userpass = jQuery('#userpass').val(),
		tipe_user_id = jQuery('#tipe_user_id').val();
	
	var form = $('#formkaryawan');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"user_management/karyawan/saveKaryawan",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'nama_karyawan' : nama_karyawan,
					'tempat_lahir' : tempat_lahir,
					'tanggal_lahir' : tanggal_lahir,
					'jenis_kelamin' : jenis_kelamin,
					'agama' : agama,
					'alamat' : alamat,
					'telepon' : telepon,
					'email' : email,
					'avatar_ori' : avatar_ori,
					'avatar_thumb' : avatar_thumb,
					'username' : username,
					'userpass' : userpass,
					'status' : status,
					'tipe_user_id' : tipe_user_id,
					'id_country' : id_country
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadKaryawan();
											clearForm();
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

function deleteKaryawan(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"user_management/karyawan/deleteKaryawan",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadKaryawan(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#nama_karyawan').val('');
	jQuery('#tempat_lahir').val('');
	jQuery('#tanggal_lahir').val('');
	jQuery('#jenis_kelamin').val('');
	jQuery('#agama').val('');
	jQuery('#alamat').val('');
	jQuery('#email').val('');
	jQuery('#telepon').val('');
	jQuery('#status').val('');
	jQuery('#avatar_ori').val('');
	jQuery('#avatar_thumb').val('');
	jQuery('#img-upload').html('');
	jQuery('#file_upload').val('');	
	
	jQuery('#username').val('');
	jQuery('#userpass').val('');
	jQuery('#tipe_user_id').val('');
	
	inactiveTab('karyawan_form');
	activeTab('karyawan_list');
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
	
	var form = $('#formgallery');
	var error = $('.alert-error-upload', form);
    var success = $('.alert-success-upload', form);
	
	$.ajax({
		url: domain+"user_management/karyawan/uploadBackgroundAvatar?files",
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		success: function(data, textStatus, jqXHR)
		{
			if(data.status){
				jQuery('#avatar_ori').val(data.files['ori']);
				jQuery('#avatar_thumb').val(data.files['thumb']);
				jQuery('#img-upload').html('<img src="'+data.files['thumb']+'" width="50" />');
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

function duplicateKaryawan() {
	if (duplicateID != "") {
		jQuery.ajax({
			url : domain + "user_management/karyawan/duplicateKaryawan",
			dataType : "json",
			type : "POST",
			data : {
				'id' : duplicateID
			},
			beforeSend : loading('portlet-list'),
			success : function(data) {
				loadKaryawan(1);
			}
		});
	}
}
