function loadPartner_Config(page){
    
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		partner = $('#search_partner').val(), 
		shortcode = $('#search_shortcode').val(), 
		price = $('#search_price').val(),
		html = '<table class="table table-striped table-bordered" id="group-list">\n\
    <thead><tr><th class="hidden-phone">Partner Code</th><th class="hidden-phone">Shortcode</th><th class="hidden-phone">Keyword</th><th class="hidden-phone">Product Id </th><th class="hidden-phone">CPID</th><th class="hidden-phone">CC</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"api_partner/api_config/loadPartner_config",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'partner' : partner,
					'shortcode': shortcode
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
													del = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\',partner_code=\''+data.rows[i]['partner_code']+'\',shortcode=\''+data.rows[i]['shortcode']+'\',keyword=\''+data.rows[i]['keyword']+'\'">Delete</a>';
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['partner_code']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['keyword']+'</td><td>'+data.rows[i]['product_id']+'</td><td>'+data.rows[i]['cp_id']+'</td><td>'+data.rows[i]['cc']+'</td><td>'+data.rows[i]['status']+'</span></td><td width="125">'+edit+' '+del+'</td></tr>';			
											}
										
										var paging = '<div id="paging">'+data.pagination+'</div>';
										$('#table-append').append(html+result+'</tbody></table>'+paging);
                                                                                }else {
                $('#table-append').append(html + '<tr><td colspan="8" style=text-align:center !important">No data Found</td><tr>' + '</tbody></table>');
            }
									}
		   });	
}

var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formpartners_services');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    partner_code: {
                        required: true
                    },
                    shortcode: {
                        required: true
                    },
                    keyword: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    cp_id: {
                        required: true
                    },
                    cc: {
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
   // alert(id);return false;
	jQuery.ajax({
			url:  domain+"api_partner/api_config/getPartner_ConfigData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },  
			beforeSend: loading('portlet-form'),
			success: function(data) {
                           
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#partner_code').val(data.partner_code);
//											jQuery('#id_shortcode').val(data.id_shortcode);
											jQuery('#shortcode').val(data.shortcode);		
											jQuery('#keyword').val(data.keyword);
											jQuery('#product_id').val(data.product_id);
											jQuery('#cp_id').val(data.cp_id);	
											jQuery('#cc').val(data.cc);
                                                                                        jQuery('#status').val(data.status);						
											inactiveTab('partners_services_list');
											activeTab('partners_services_form');
										} else {
											
										}
									}
		   });	
}

function save(){
               
	var id = jQuery('#id').val(),
		partner_code = jQuery('#partner_code').val(),
		shortcode = jQuery('#shortcode').val(),
		keyword 	= jQuery('#keyword').val(),
		product_id	= jQuery('#product_id').val(),
		cp_id	= jQuery('#cp_id').val(),
		cc =  jQuery('#cc').val(),
		status	= jQuery('#status').val();
	var form = $('#formpartners_services');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"api_partner/api_config/savePartner_Config",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'partner_code' : partner_code,
					'shortcode' : shortcode,
					'keyword' : keyword,
					'product_id' : product_id,
					'cp_id' : cp_id,
					'cc' : cc,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {

										if(data.duplicat_data) 
										{
											success.hide();
											alert(data.errors_message)
										}
										
										if(data.success){
											success.show();
                    						error.hide();	
											loadPartner_Config(1);
											clearForm();
											
											inactiveTab('partners_services_form');
											activeTab('partners_services_list');
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

function deletePartner_Config(){
   
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"api_partner/api_config/deletePartner_Config",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID,
                                                'partner_code':partner_code,
                                                'shortcode':shortcode,
                                                'keyword':keyword
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											//loadPartner_Config(1);
											if(data.duplicat_data) 
										    {
											  alert(data.errors_message);
											  return false;
										    }
										    else
										    { 
											  loadPartner_Config(1);
										    }
										}
			   });	
	}11
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#id_price').val('');
	jQuery('#id_shortcode').val('');
	jQuery('#sid').val('');
	jQuery('#id_partner').val('');
	jQuery('#id_operator').val('');
	jQuery('#id_operator_api').val('');
	jQuery('#id_service_type').val('');
	jQuery('#id_keyword_group').val('');
	jQuery('#keyword').val('');
	jQuery('#status').val('');
        jQuery('#country_id').val(''); 
		
	$('#id_operator_api').attr('disabled', 'disabled');
	$('#id_partner').attr('disabled', 'disabled');
		
	inactiveTab('partners_services_form');
	activeTab('partners_services_list');
}

 






jQuery(document).ready(function() {	
	// Add events
});
