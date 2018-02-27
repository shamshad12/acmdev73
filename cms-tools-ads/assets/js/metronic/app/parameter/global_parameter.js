function loadGlobal_Parameter(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Code</th><th class="hidden-phone">Value</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"parameter/global_parameter/loadGlobal_Parameter",
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['value']+'</td><td width="105"><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';			
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
            var form = $('#formglobal_parameter');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    value: {
                        required: true
                    },
                    code: {
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
			url:  domain+"parameter/global_parameter/getGlobal_ParameterData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#value').val(data.value);
											
											inactiveTab('global_parameter_list');
											activeTab('global_parameter_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		value = jQuery('#value').val();
	
	var form = $('#formglobal_parameter');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"parameter/global_parameter/saveGlobal_Parameter",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'value' : value
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadGlobal_Parameter(1);
											clearForm();
											
											inactiveTab('global_parameter_form');
											activeTab('global_parameter_list');
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

function deleteGlobal_Parameter(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"parameter/global_parameter/deleteGlobal_Parameter",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadGlobal_Parameter(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#value').val('');
		
	inactiveTab('global_parameter_form');
	activeTab('global_parameter_list');
}

jQuery(document).ready(function() {	
	// Add events
});
