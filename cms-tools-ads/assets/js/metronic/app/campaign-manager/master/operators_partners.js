function loadOperators_Partners(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		partner = $('#search_partner').val(),
		operator = $('#search_operator').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Rev. Share Operator</th><th class="hidden-phone">Rev. Share Partner</th><th class="hidden-phone">VAT</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/operators_partners/loadOperators_Partners",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'partner' : partner,
					'operator': operator
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['operator_name']+'</td><td>'+data.rows[i]['partner_name']+'</td><td>'+data.rows[i]['share_operator']+'</td><td>'+data.rows[i]['share_partner']+'</td><td>'+data.rows[i]['vat']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formoperators_partners');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    share_operator: {
                        required: true
                    },
                    share_partner: {
                        required: true
                    },
                    id_operator: {
                        required: true
                    },
                    id_partner: {
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
			url:  domain+"campaign_manager/operators_partners/getOperators_PartnersData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#share_operator').val(data.share_operator);
											jQuery('#id_operator').val(data.id_operator);
											jQuery('#share_partner').val(data.share_partner);	
											jQuery('#id_partner').val(data.id_partner);	
											jQuery('#vat').val(data.vat);	
											jQuery('#status').val(data.status);	
											
											inactiveTab('operators_partners_list');
											activeTab('operators_partners_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		share_operator = jQuery('#share_operator').val(),
		id_operator = jQuery('#id_operator').val(),
		id_partner 	= jQuery('#id_partner').val(),
		vat 	= jQuery('#vat').val(),
		status 		= jQuery('#status').val(),
		share_partner = jQuery('#share_partner').val();
	
	var form = $('#formoperators_partners');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/operators_partners/saveOperators_Partners",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'share_operator' : share_operator,
					'id_operator' : id_operator,
					'id_partner' : id_partner,
					'vat' : vat,
					'status' : status,
					'share_partner' : share_partner
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
                            if(data.duplicat_data) { 
											success.hide();
											alert(data.errors_message)
										}   
                            
										if(data.success){
											success.show();
                    						error.hide();	
											loadOperators_Partners(1);
											clearForm();
											
											inactiveTab('operators_partners_form');
											activeTab('operators_partners_list');
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

function deleteOperators_Partners(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/operators_partners/deleteOperators_Partners",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadOperators_Partners(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#share_operator').val('');
	jQuery('#id_operator').val('');
	jQuery('#share_partner').val('');
	jQuery('#id_partner').val('');
	jQuery('#vat').val('');
	jQuery('#status').val('');
		
	inactiveTab('operators_partners_form');
	activeTab('operators_partners_list');
}

jQuery(document).ready(function() {	
	// Add events
});
