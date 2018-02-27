function loadKeyword_Groups(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Partner</th><th class="hidden-phone">Code</th><th class="hidden-phone">Name</th><th class="hidden-phone">Description</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/keyword_groups/loadKeyword_Groups",
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['partner_name']+'</td><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formkeyword_groups');
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
                    id_partner: {
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
			url:  domain+"campaign_manager/keyword_groups/getKeyword_GroupsData",
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
											jQuery('#id_partner').val(data.id_partner);
											jQuery('#description').val(data.description);	
											jQuery('#code').val(data.code);	
											jQuery('#status').val(data.status);	
											
											inactiveTab('keyword_groups_list');
											activeTab('keyword_groups_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		name = jQuery('#name').val(),
		id_partner = jQuery('#id_partner').val(),
		code = jQuery('#code').val(),
		status = jQuery('#status').val(),
		description = jQuery('#description').val();
	
	var form = $('#formkeyword_groups');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/keyword_groups/saveKeyword_Groups",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'id_partner' : id_partner,
					'code' : code,
					'status' : status,
					'description' : description
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadKeyword_Groups(1);
											clearForm();
											
											inactiveTab('keyword_groups_form');
											activeTab('keyword_groups_list');
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

function deleteKeyword_Groups(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/keyword_groups/deleteKeyword_Groups",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadKeyword_Groups(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#id_partner').val('');
	jQuery('#description').val('');
	jQuery('#code').val('');
	jQuery('#status').val('');
		
	inactiveTab('keyword_groups_form');
	activeTab('keyword_groups_list');
}

jQuery(document).ready(function() {	
	// Add events
});
