function loadUser(page){	
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">ID</th><th class="hidden-phone">Karyawan</th><th class="hidden-phone">Tipe User</th><th class="hidden-phone">Username</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
		
	jQuery.ajax({
			url:  domain+"user_management/user/loadUser",
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
												var edit='disabled="disabled"';
												if(accessEdit=='1')
													edit = 'onclick="edit(\''+data.rows[i]['id']+'\')"';
												var del='disabled="disabled"';
												if(accessDelete=='1')
													del = 'data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'"';
												
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['nama_karyawan']+'</td><td>'+data.rows[i]['nama_tipe_user']+'</td><td>'+data.rows[i]['username']+'</td><td>'+data.rows[i]['status']+'</td><td><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';			
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
            var form = $('#formUser');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    karyawan_id: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    tipe_user_id: {
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
			url:  domain+"user_management/user/getUserData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#karyawan_id').val(data.karyawan_id);
											jQuery('#username').val(data.username);
											jQuery('#userpass').val(data.userpass);
											jQuery('#tipe_user_id').val(data.tipe_user_id);
											jQuery('#status').val(data.status);
											
											inactiveTab('user_list');
											activeTab('user_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		karyawan_id = jQuery('#karyawan_id').val(),
		username = jQuery('#username').val(),
		userpass = jQuery('#userpass').val(),
		tipe_user_id = jQuery('#tipe_user_id').val(),
		status = jQuery('#status').val();
	
	var form = $('#formUser');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"user_management/user/saveUser",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'karyawan_id' : karyawan_id,
					'username' : username,
					'userpass' : userpass,
					'tipe_user_id' : tipe_user_id,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadUser(1);
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

function deleteUser(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"user_management/user/deleteUser",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadUser(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#karyawan_id').val('');
	jQuery('#username').val('');
	jQuery('#userpass').val('');
	jQuery('#tipe_user_id').val('');
	jQuery('#status').val('');
	
	inactiveTab('user_form');
	activeTab('user_list');
}
