function loadClient(){		
	$("#table-append").html('');
	var html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">ID</th><th class="hidden-phone">Nama Client</th><th class="hidden-phone">Alamat</th><th class="hidden-phone">Telepon</th><th class="hidden-phone">Fax</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"user-management/client/loadClient",
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
											result  += '<tr class="odd gradeX"><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['nama_client']+'</td><td>'+data.rows[i]['alamat']+'</td><td>'+data.rows[i]['telepon']+'</td><td>'+data.rows[i]['fax']+'</td><td><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';			
										}
										$('#table-append').append(html+result+'</tbody></table>');
										handleTables();
									}
		   });	
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formClient');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    nama_client: {
                        required: true
                    },
                    alamat: {
                        required: true
                    },
                    telepon: {
                        required: true,
						number: true
                    },
                    fax: {
                        required: true,
						number: true
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
			url:  domain+"user-management/client/getClientData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#alamat').val(data.alamat);
											jQuery('#telepon').val(data.telepon);
											jQuery('#nama_client').val(data.nama_client);
											jQuery('#fax').val(data.fax);
											
											inactiveTab('client_list');
											activeTab('client_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		alamat = jQuery('#alamat').val(),
		telepon = jQuery('#telepon').val(),
		nama_client = jQuery('#nama_client').val(),
		fax = jQuery('#fax').val();
	
	var form = $('#formClient');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"user-management/client/saveClient",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'alamat' : alamat,
					'telepon' : telepon,
					'nama_client' : nama_client ,
					'fax' : fax 
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadClient();
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

function deleteClient(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"user-management/client/deleteClient",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadClient();
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#alamat').val('');
	jQuery('#telepon').val('');
	jQuery('#nama_client').val('');
	jQuery('#fax').val('');
	inactiveTab('client_form');
	activeTab('client_list');
}
