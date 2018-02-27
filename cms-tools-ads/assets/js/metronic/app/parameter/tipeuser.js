function loadTipeUser(){		
	$("#table-append").html('');
	var html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Nama TipeUser</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"parameter/tipeuser/loadTipeUser",
			dataType: "json",
			type: "POST",
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').find('table').parent().remove();
										var result = '';
										for(var i=0; i<data.rows.length; i++){
											var edit='disabled="disabled"';
											if(accessEdit=='1')
												edit = 'onclick="edit(\''+data.rows[i]['id']+'\')"';
											var del='disabled="disabled"';
											if(accessDelete=='1')
												del = 'data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'"';
												
											result  += '<tr class="odd gradeX"><td>'+data.rows[i]['nama_tipe_user']+'</td><td><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';			
										}
										$('#table-append').append(html+result+'</tbody></table>');
										handleTables();
									}
		   });	
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formTipeUser');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    nama_tipe_user: {
                        required: true
                    },
                    alamat: {
                        required: true
                    },
                    telepon: {
                        required: true
                    },
                    fax: {
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
			url:  domain+"parameter/tipeuser/getTipeUserData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#nama_tipe_user').val(data.nama_tipe_user);
											
											inactiveTab('tipeuser_list');
											activeTab('tipeuser_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		nama_tipe_user = jQuery('#nama_tipe_user').val();
	
	var form = $('#formTipeUser');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"parameter/tipeuser/saveTipeUser",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'nama_tipe_user' : nama_tipe_user
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadTipeUser();
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

function deleteTipeUser(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"parameter/tipeuser/deleteTipeUser",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadTipeUser();
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#nama_tipe_user').val('');
	inactiveTab('tipeuser_form');
	activeTab('tipeuser_list');
}
