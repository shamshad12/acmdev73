function loadFilterlist(page){		 
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		country = $('#search_country').val(),
		operator = $('#search_operator').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Telco Name</th><th class="hidden-phone">Filter Type</th><th class="hidden-phone">MSISDN</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/filterlist/loadFilterlist",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'country' : country,
					'operator': operator
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
                           // alert(data);
										jQuery('#table-append').html('');
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												var del='';
												if(accessEdit=='1')  
													del = '<a  style="cursor:pointer" class="btn  blue mini active" data-toggle="modal" href="#deletenew" onclick="deletenewID=\''+data.rows[i]['id']+'\',id_country=\''+data.rows[i]['id_country']+'\',msisdn=\''+data.rows[i]['msisdn']+'\',id_telco=\''+data.rows[i]['id_telco']+'\',filter_type=\''+data.rows[i]['filter_type']+'\',status=\''+data.rows[i]['status']+'\'">Delete</a>';
												var activet_deacti='';
												if(accessDelete=='1') 
													if(data.rows[i]['status']==1)
													{
														activet_deacti = '<a  style="cursor:pointer"  class="btn green mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\',id_country=\''+data.rows[i]['id_country']+'\',msisdn=\''+data.rows[i]['msisdn']+'\',id_telco=\''+data.rows[i]['id_telco']+'\',filter_type=\''+data.rows[i]['filter_type']+'\',status=\''+data.rows[i]['status']+'\'">Active</a>';
													}
													else
													{
														activet_deacti = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\',id_country=\''+data.rows[i]['id_country']+'\',msisdn=\''+data.rows[i]['msisdn']+'\',id_telco=\''+data.rows[i]['id_telco']+'\',filter_type=\''+data.rows[i]['filter_type']+'\',status=\''+data.rows[i]['status']+'\'">Deactive</a>'; 
													}					
														result  += '<tr class="odd gradeX"><td>'+data.rows[i]['co_name']+'</td><td>'+data.rows[i]['so_name']+'</td><td>'+data.rows[i]['filter_type']+'</td><td>'+data.rows[i]['msisdn']+'</td><td>'+activet_deacti+' '+del+'</td></tr>'; 			
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
            var form = $('#formpartners');
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
                    id_country: {
                        required: true
                    },
                    id_telco: {
                        required: true
                    }, 
                    msisdn: {
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

var TestFormValidation = function () {
    return {
        init: function () {
            var form = $('#bulkupload_filters_form');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    upload_filter: {
                        required: true,
                    
					    extension: 'xlsx'
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
                }
            });
        }
    };

}();

function edit(id){
	jQuery.ajax({
			url:  domain+"campaign_manager/filterlist/getFilterlistData", 
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){  
											jQuery('#id').val(data.id);
											jQuery('#filter_type').val(data.filter_type);
											jQuery('#id_country').val(data.id_country);
                                            jQuery('#id_telco').val(data.id_telco);
											jQuery('#msisdn').val(data.msisdn);	
											
											
											inactiveTab('partners_list');
											activeTab('partners_form');
										} else {
											
										}
									}
		   });	 
} 


function loadOperator(operatorid){
 //alert(operatorid); return false;
 var operatorId = operatorid;
 if(operatorId != ""){
  jQuery.ajax({
    url:  domain+"campaign_manager/filterlist/loadOperator",
    dataType: "json",
    type: "POST",
    data: {
      'id' : operatorId
       },
    beforeSend: loading('portlet-list'),
    success: function(data) {
     // alert(data);return false;
           jQuery('#select-append').html(data);
           
          }
      }); 
 }
}




function save(){
    
	var id = jQuery('#id').val(),
		filter_type = jQuery('#filter_type').val(),
               id_country = jQuery('#id_country').val(), 
                 id_telco = jQuery('#id_telco').val(), 
		msisdn = jQuery('#msisdn').val();
		
	
	var form = $('#formpartners');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/filterlist/saveFilterlist",
			dataType: "json",
			type: "POST",
			data: {
                            
					'id' : id,
					'filter_type' : filter_type,
					'id_country' : id_country,
                                        'id_telco' : id_telco,
					'msisdn' : msisdn
					
				  },
                                  
                                   
			beforeSend: loading('portlet-form'),
			success: function(data) {

				if(data.duplicat_data)
										{
											success.hide();
                    						alert(data.errors_message)
										}
 
                           /// alert('data');return false;  
										if(data.success){
											success.show();
                    						error.hide();	
											loadFilterlist(1);
											clearForm();
											
											inactiveTab('partners_form');
											activeTab('partners_list');
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
function deleteFilterlistNew()
{
	if(deletenewID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/filterlist/deleteFilterlistNew",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deletenewID, 
						'id_country':id_country,
						'filter_type':filter_type,
						'id_telco':id_telco, 
                        'msisdn':msisdn,  
						'status':status
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadFilterlist(1);
										}
			   });	
	}

}

function deleteFilterlist(){

	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/filterlist/deleteFilterlist",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID, 
						'id_country':id_country,
						'filter_type':filter_type,
						'id_telco':id_telco, 
                        'msisdn':msisdn,  
						'status':status
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadFilterlist(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#filter_type').val('');
	jQuery('#id_country').val('');
        jQuery('#id_telco').val('');
	jQuery('#msisdn').val('');
	
		
	inactiveTab('partners_form');
	inactiveTab('bulkupload_filters');
	activeTab('partners_list');
}clearFormBulk
function clearFormBulk(){
	jQuery('#id').val('');
	jQuery('#filter_type').val('');
	jQuery('#id_country').val('');
        jQuery('#id_telco').val('');
	jQuery('#msisdn').val('');
	
		
	inactiveTab('partners_form');
	inactiveTab('bulkupload_filters');
	activeTab('partners_list');
}

jQuery(document).ready(function() {	
	// Add events
});
