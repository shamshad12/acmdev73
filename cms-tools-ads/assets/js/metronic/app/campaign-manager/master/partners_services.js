function loadPartners_Services(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		partner = $('#search_partner').val(),
		operator = $('#search_operator').val(),
                 country = $('#search_country').val(), 
		shortcode = $('#search_shortcode').val(), 
		price = $('#search_price').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Campaign Media</th><th class="hidden-phone">Shortcode</th><th class="hidden-phone">Price</th><th class="hidden-phone">Service ID</th><th class="hidden-phone">Keyword</th><th class="hidden-phone">Status</th><th class="hidden-phone" title="Created by" width="75">Created By</th><th class="hidden-phone" width="75" title="Updated by">Updated By</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/partners_services/loadPartners_Services",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'partner' : partner,
                                        'country' : country,
					'operator': operator,
					'shortcode': shortcode,
					'price': price
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['country_name']+'</td><td>'+data.rows[i]['operator_name']+'</td><td>'+data.rows[i]['partner_name']+'</td><td>'+data.rows[i]['campaign_media']+'</td><td>'+data.rows[i]['shortcode']+'</td><td>'+data.rows[i]['price']+'</td><td>'+data.rows[i]['sid']+'</td><td>'+data.rows[i]['keyword']+'</td><td>'+data.rows[i]['status']+'</td><td title="'+data.rows[i]['user_enter_time']+'">'+data.rows[i]['user_enter']+' <span id="usr-up-time">'+data.rows[i]['user_enter_time']+'</span></td><td title="'+data.rows[i]['user_updated_time']+'">'+data.rows[i]['user_updated']+' <span id="usr-up-time">'+data.rows[i]['user_updated_time']+'</span></td><td width="125">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formpartners_services');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    id_price: {
                        required: true
                    },
                    sid: {
                        required: true
                    },
                    id_shortcode: {
                        required: true
                    },
                    id_partner: {
                        required: true
                    },
                    id_operator: {
                        required: true
                    },
                    id_campaign_media: {
                    	required: true
                    },
                    id_operator_api: {
                        required: true
                    },
                    id_service_type: {
                        required: true
                    },
                     country_id: {
                        required: true
                    },
                    id_keyword_group: {
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
			url:  domain+"campaign_manager/partners_services/getPartners_ServicesData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },  
			beforeSend: loading('portlet-form'),
			success: function(data) {
                           
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#id_price').val(data.id_price);
//											jQuery('#id_shortcode').val(data.id_shortcode);
											jQuery('#sid').val(data.sid);		
											jQuery('#keyword').val(data.keyword);	
											jQuery('#status').val(data.status);
                                            jQuery('#country_id').val(data.country_id);
											jQuery('#price_code').val(data.price_code);
//											jQuery('#id_operator').val(data.id_operator);
											jQuery('#id_campaign_media').val(data.id_campaign_media);
											jQuery('#id_service_type').val(data.id_service_type);
											jQuery('#id_keyword_group').val(data.id_keyword_group);
											jQuery('#ncontent').val(data.ncontent);				
                                            loadOperator(data.country_id,data.id_operator);
											getPartners(data.id_operator, data.id_partner);
											getshortcode(data.id_partner,data.id_shortcode)
											getOperators_Apis(data.id_operator, data.id_operator_api);
											loadPrice(data.country_id,data.id_price);
											getKeyword_Groups(data.id_partner, data.id_keyword_group);
																						
											inactiveTab('partners_services_list');
											activeTab('partners_services_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		id_price = jQuery('#id_price').val(),
		id_shortcode = jQuery('#id_shortcode').val(),
		id_partner 	= jQuery('#id_partner').val(),
		id_operator	= jQuery('#id_operator').val(),
		price_code	= jQuery('#price_code').val(),
		id_operator_api	= jQuery('#id_operator_api').val(),
		id_campaign_media =  jQuery('#id_campaign_media').val(),
		id_service_type	= jQuery('#id_service_type').val(),
		id_keyword_group	= jQuery('#id_keyword_group').val(),
		ncontent = jQuery('#ncontent').val(),
        country_id	= jQuery('#country_id').val(),
		keyword 	= jQuery('#keyword').val(),
		status 		= jQuery('#status').val(),
		sid = jQuery('#sid').val(),
		sprefix = jQuery('#sprefix').val();	
	var form = $('#formpartners_services');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/partners_services/savePartners_Services",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'id_price' : id_price,
					'id_shortcode' : id_shortcode,
					'id_partner' : id_partner,
					'id_operator' : id_operator,
					'id_campaign_media' : id_campaign_media,
					'id_operator_api' : id_operator_api,
					'id_service_type' : id_service_type,
					'price_code' :price_code,
					'id_keyword_group' : id_keyword_group,
					'keyword' : keyword,
					'ncontent' : ncontent,
                    'country_id' : country_id,
					'status' : status,
					'sprefix' : sprefix,
					'sid' : sid
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
											loadPartners_Services(1);
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

function deletePartners_Services(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/partners_services/deletePartners_Services",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											//loadPartners_Services(1);
											if(data.duplicat_data) 
										    {
											  alert(data.errors_message);
											  return false;
										    }
										    else
										    { 
											  loadPartners_Services(1);
										    }
										}
			   });	
	}
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

function getPartners(id_operator, id_partner){
	$('#id_partner').val('');
	$('#id_partner').attr('disabled', 'disabled');
	if(id_operator != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/operators_partners/loadOperators_PartnersSelect",
				dataType: "json",
				type: "POST",
				data: {
						'id_operator' : id_operator
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											var result = '<option value="">- Choose Partner -</option>';											
											if(data.count){	
												for(var i=0; i<data.rows.length; i++){
													result += "<option value='"+data.rows[i]['partner_id']+"'>"+data.rows[i]['partner_name']+"</option>";
												}
												$('#id_partner').html(result);
												$('#id_partner').removeAttr('disabled');
												
												jQuery('#id_partner').val(id_partner);
											}
										}
			   });	
	}		
}

function getOperators_Apis(id_operator, id_operator_api){
	$('#id_operator_api').val('');
	$('#id_operator_api').attr('disabled', 'disabled');
	if(id_operator != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/operators_apis/loadOperators_ApisSelect",
				dataType: "json",
				type: "POST",
				data: {
						'id_operator' : id_operator
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											var result = '<option value="">- Choose Operator APIs -</option>';											
											if(data.count){	
												for(var i=0; i<data.rows.length; i++){
													result += "<option value='"+data.rows[i]['id']+"'>"+data.rows[i]['name']+"</option>";
												}
												$('#id_operator_api').html(result);
												$('#id_operator_api').removeAttr('disabled');
												
												jQuery('#id_operator_api').val(id_operator_api);
											}
										}
			   });	
	}		
}

function getKeyword_Groups(id_partner, id_keyword_group){
	$('#id_keyword_group').val('');
	$('#id_keyword_group').attr('disabled', 'disabled');
	if(id_partner != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/keyword_groups/loadKeyword_GroupsSelect",
				dataType: "json",
				type: "POST",
				data: {
						'id_partner' : id_partner
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											var result = '<option value="">- Choose Keyword Group -</option>';											
											if(data.count){	
												for(var i=0; i<data.rows.length; i++){
													result += "<option value='"+data.rows[i]['id']+"'>"+data.rows[i]['name']+"</option>";
												}
												$('#id_keyword_group').html(result);
												$('#id_keyword_group').removeAttr('disabled');
												
												jQuery('#id_keyword_group').val(id_keyword_group);
											}
										}
			   });	
	}		
}

function loadOperator(operatorid,id_operator){ 
  //alert(operatorid); return false;
 var operatorId = operatorid;
 if(operatorId != ""){
  jQuery.ajax({
    url:  domain+"campaign_manager/partners_services/loadOperator",
    dataType: "json",
    type: "POST",
    data: {
      'id' : operatorId
       },
    beforeSend: loading('portlet-list'), 
    success: function(data) {
   //alert(data);return false; 
           jQuery('#id_operator').html(data);
           jQuery('#id_operator').val(id_operator);  
          }
      }); 
 }
} 

function getshortcode(id_partner,id_shortcode){ 
  //alert(id_partner);  
 var id_partner = id_partner;
 if(id_partner != ""){ 
  jQuery.ajax({
    url:  domain+"campaign_manager/partners_services/loadShortcode",
    dataType: "json", 
    type: "POST",
    data: {
      'id' : id_partner
       },
    beforeSend: loading('portlet-list'), 
    success: function(data) {
   //alert(data);return false; 
           jQuery('#id_shortcode').html(data);
           jQuery('#id_shortcode').val(id_shortcode);  
          }
      }); 
 }
}

function getserviceVal(id_shortcode)
{
	if(id_shortcode != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/partners_services/getserviceVal",
				dataType: "json",
				type: "POST",
				data: {
						'id_shortcode' : id_shortcode
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {	
											if(data.count){	
												jQuery('#sid').val(data.code);
											}
										}
			   });	
	}	
}

//
function loadPrice(country_id,id_price){ 
  //alert(country_id); return false; 
 if(country_id != ""){
  jQuery.ajax({
    url:  domain+"campaign_manager/partners_services/loadPrice",
    dataType: "json",
    type: "POST", 
    data: {
      'id' : country_id
       },
    beforeSend: loading('portlet-list'), 
    success: function(data) {
  
           jQuery('#id_price').html(data); 
           jQuery('#id_price').val(id_price);   
          }
      }); 
 }
} 

jQuery(document).ready(function() {	
	// Add events
});
