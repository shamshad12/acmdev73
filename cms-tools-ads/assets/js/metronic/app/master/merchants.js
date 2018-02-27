var files;

function loadMerchants(){		
	var html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Name</th><th class="hidden-phone">Code</th><th class="hidden-phone">Allow IP</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"backend/merchants/loadMerchants",
			dataType: "json",
			type: "POST",
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').find('table').parent().remove();
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												var edit='disabled="disabled"';
												if(accessEdit=='1')
													edit = 'onclick="edit(\''+data.rows[i]['id']+'\')"';
												var del='disabled="disabled"';
												if(accessDelete=='1')
													del = 'data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'"';
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['allowIp']+'</td><td>'+data.rows[i]['status']+'</td><td width="100"><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';			
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
            var form = $('#formmerchants');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    code: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                    name: {
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
			url:  domain+"backend/merchants/getMerchantsData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#code').val(data.code);
											jQuery('#status').val(data.status);		
											jQuery('#description').val(data.description);
											jQuery('#name').val(data.name);	
											jQuery('#allowIp').val(data.allowIp);
											
											inactiveTab('merchants_list');
											activeTab('merchants_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		code = jQuery('#code').val(),
		description = jQuery('#description').val(),
		name = jQuery('#name').val(),
		allowIp = jQuery('#allowIp').val(),
		status = jQuery('#status').val();
	
	var form = $('#formmerchants');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"backend/merchants/saveMerchants",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'code' : code,
					'description' : description,
					'name' : name,
					'allowIp' : allowIp,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadMerchants();
											clearForm();
											
											inactiveTab('merchants_form');
											activeTab('merchants_list');
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

function deleteMerchants(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"backend/merchants/deleteMerchants",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadMerchants();
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#code').val('');
	jQuery('#status').val('');
	jQuery('#description').val('');
	jQuery('#name').val('');
	jQuery('#allowIp').val('');
		
	inactiveTab('merchants_form');
	activeTab('merchants_list');
}

jQuery(document).ready(function() {	
	// Add events
});
