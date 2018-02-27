var files;

function loadPrivilegesMenu(){		
	var privilege  = $('#privilege').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Privilege</th><th class="hidden-phone">Menu</th><th class="hidden-phone">Url</th><th class="hidden-phone">Status</th><th class="hidden-phone" width="190">Access</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"user_management/privileges_menu/loadPrivilegesMenu",
			dataType: "json",
			type: "POST",
			data: {
					'privilege' : privilege
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var result = '',
											resultOpt = '<option value="0">--Parent--</option>',
											tempPriv = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){												
												var edit='disabled="disabled"';
												if(accessEdit=='1')
													edit = 'data-toggle="modal" href="#add" onclick="edit(\''+data.rows[i]['id_menu']+'\')"';
												var del='disabled="disabled"';
												if(accessDelete=='1')
													del = 'data-toggle="modal" href="#delete" onclick="setDeleteID(\''+data.rows[i]['id_menu']+'\')"';
													
												if(data.rows[i]['level'] == ''){
													if(tempPriv=='')
														tempPriv = data.rows[i]['id_privilege'];
														
													if(tempPriv==data.rows[i]['id_privilege']){
														resultOpt += '<option value="'+data.rows[i]['id_menu']+'">'+data.rows[i]['level']+data.rows[i]['name']+'</option>';
														if(data.rows[i]['child'] != 0){
															var childOpt = makeChildMenuOpt(data.child[data.rows[i]['id_privilege']+data.rows[i]['id_menu']], data.rows[i]['id_menu']);
															if(childOpt!='')
																resultOpt += childOpt;
														}
													}
													
													result  += '<tr class="odd gradeX"><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['level']+data.rows[i]['name']+'</td><td>'+data.rows[i]['url']+'</td><td>'+data.rows[i]['status']+'</td><td><a onclick="editAccess(\''+data.rows[i]['id_menu']+'\',\''+data.rows[i]['id_privilege']+'\',1)" style="cursor:pointer" class="btn '+data.rows[i]['access']['view']+' mini">View</a> <a onclick="editAccess(\''+data.rows[i]['id_menu']+'\',\''+data.rows[i]['id_privilege']+'\',2)" style="cursor:pointer" class="btn '+data.rows[i]['access']['save']+' mini">Save</a> <a onclick="editAccess(\''+data.rows[i]['id_menu']+'\',\''+data.rows[i]['id_privilege']+'\',4)" style="cursor:pointer" class="btn '+data.rows[i]['access']['edit']+' mini">Edit</a> <a onclick="editAccess(\''+data.rows[i]['id_menu']+'\',\''+data.rows[i]['id_privilege']+'\',8)" style="cursor:pointer" class="btn '+data.rows[i]['access']['delete']+' mini">Delete</a></td><td width="100"><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';
																													
													if(data.rows[i]['child'] != 0){
														var child = makeChildMenu(data, data.child[data.rows[i]['id_privilege']+data.rows[i]['id_menu']], data.rows[i]['id_menu']);
														if(child!='')
															result += child;
													}
												}
											}
										}
										$('#parent').html(resultOpt);
										$('#table-append').append(html+result+'</tbody></table>');
									}
		   });	
}

function makeChildMenu(data, data_child, parent){
	var result = '';
	for(var i=0; i<data_child.rows.length; i++){
		var edit='disabled="disabled"';
		if(accessEdit=='1')
			edit = 'data-toggle="modal" href="#add" onclick="edit(\''+data_child.rows[i]['id_menu']+'\')"';
		var del='disabled="disabled"';
		if(accessDelete=='1')
			del = 'data-toggle="modal" href="#delete" onclick="setDeleteID(\''+data_child.rows[i]['id_menu']+'\')"';
		result  += '<tr class="odd gradeX"><td>'+data_child.rows[i]['description']+'</td><td>'+data_child.rows[i]['level']+data_child.rows[i]['name']+'</td><td>'+data_child.rows[i]['url']+'</td><td>'+data_child.rows[i]['status']+'</td><td><a onclick="editAccess(\''+data_child.rows[i]['id_menu']+'\',\''+data_child.rows[i]['id_privilege']+'\',1)" style="cursor:pointer" class="btn '+data_child.rows[i]['access']['view']+' mini">View</a> <a onclick="editAccess(\''+data_child.rows[i]['id_menu']+'\',\''+data_child.rows[i]['id_privilege']+'\',2)" style="cursor:pointer" class="btn '+data_child.rows[i]['access']['save']+' mini">Save</a> <a onclick="editAccess(\''+data_child.rows[i]['id_menu']+'\',\''+data_child.rows[i]['id_privilege']+'\',4)" style="cursor:pointer" class="btn '+data_child.rows[i]['access']['edit']+' mini">Edit</a> <a onclick="editAccess(\''+data_child.rows[i]['id_menu']+'\',\''+data_child.rows[i]['id_privilege']+'\',8)" style="cursor:pointer" class="btn '+data_child.rows[i]['access']['delete']+' mini">Delete</a></td><td width="100"><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';
		if(data_child.rows[i]['child'] != 0 && data_child.child[data_child.rows[i]['id_privilege']+data_child.rows[i]['id_menu']]){
			result += makeChildMenu(data, data_child.child[data_child.rows[i]['id_privilege']+data_child.rows[i]['id_menu']], data_child.rows[i]['id_menu'])
		}
		
	}
	return result;
}

function makeChildMenuOpt(data, parent){
	var result = '';
	for(var i=0; i<data.rows.length; i++){
		result  += '<option value="'+data.rows[i]['id_menu']+'">'+data.rows[i]['level']+data.rows[i]['name']+'</option>';
	}
	return result;
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formprivileges_menu');
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
                    description: {
                        required: true
                    },
                    status: {
                        required: true
                    },
                    url: {
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

function editAccess(menu, privilege, access){
	jQuery.ajax({
			url:  domain+"user_management/privileges_menu/setAccess",
			dataType: "json",
			type: "POST",
			data: {
					'menu' : menu,
					'privilege' : privilege,
					'access' : access
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data){
											loadPrivilegesMenu();
										} else {
											alert('Failed');
										}
									}
		   });	
}

function edit(id){
	jQuery.ajax({
			url:  domain+"user_management/privileges_menu/getPrivilegesMenuData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#url').val(data.url);
											jQuery('#name').val(data.name);
											jQuery('#status').val(data.status);		
											jQuery('#description').val(data.description);
											jQuery('#icon').val(data.icon);	
											jQuery('#parent').val(data.parent);	
										} else {
											
										}
									}
		   });	
}

function save(){
	var id 		= jQuery('#id').val(),
		name 	= jQuery('#name').val(),
		description = jQuery('#description').val(),
		url 	= jQuery('#url').val(),
		icon 	= jQuery('#icon').val(),
		parent 	= jQuery('#parent').val(),
		status 	= jQuery('#status').val();
	
	var form = $('#formprivileges_menu');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"user_management/privileges_menu/savePrivilegesMenu",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'description' : description,
					'url' : url,
					'icon' : icon,
					'parent' : parent,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadPrivilegesMenu();
											//clearForm();
											$(location).attr('href','user_management/privileges_menu');
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

function deletePrivilegesMenu(){
	jQuery.ajax({
			url:  domain+"user_management/privileges_menu/deletePrivilegesMenu",
			dataType: "json",
			type: "POST",
			data: {
					'id_menu' : deleteID
				  },
			beforeSend: loading('portlet-list'),
			success: function(data) {
										loadPrivilegesMenu();
									}
		   });	
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#url').val('');
	jQuery('#name').val('');
	jQuery('#status').val('');
	jQuery('#description').val('');
	jQuery('#icon').val('');
	jQuery('#parent').val('');
}
