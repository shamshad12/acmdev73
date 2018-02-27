function loadTrigger_List(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		shortcode = $('#search_shortcode').val(),
		operator = $('#search_operator').val(),
                country = $('#search_country').val(), 
                keyword = $('#search_keyword').val(), 
                language = $('#search_language').val(),   
                 
                
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Telco</th><th class="hidden-phone">Shortcode</th><th class="hidden-phone">keyword</th><th class="hidden-phone">Type</th><th class="hidden-phone">Language</th><th class="hidden-phone">Message</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/trigger_list/loadTrigger_List",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'shortcode' : shortcode,
                                        'country' : country,
                                        'keyword' : keyword,
                                        'language' : language,
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
												
                                                                                               var duplicate='';  
												if(accessDuplicate=='1')
													duplicate = '<a  style="cursor:pointer" margin-top:3px !important;" class="btn blue mini active" data-toggle="modal" href="#duplicate" onclick="duplicateID=\''+data.rows[i]['id']+'\',country_id=\''+data.rows[i]['country_id']+'\',type_key=\''+data.rows[i]['type']+'\',shortcode_id=\''+data.rows[i]['short_id']+'\',telco_id=\''+data.rows[i]['telco_id']+'\',language_id=\''+data.rows[i]['language_id']+'\',message=\''+data.rows[i]['message']+'\',keyword=\''+data.rows[i]['keyword']+'\',status=\''+data.rows[i]['status']+'\'">Delete</a>';
                                                                                                var del=''; 
												if(accessDelete=='1')  
										                   if(data.rows[i]['status']==1) 
													{
														del = '<a  style="cursor:pointer"  class="btn green mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\',country_id=\''+data.rows[i]['country_id']+'\',type_key=\''+data.rows[i]['type']+'\',shortcode_id=\''+data.rows[i]['short_id']+'\',telco_id=\''+data.rows[i]['telco_id']+'\',language_id=\''+data.rows[i]['language_id']+'\',message=\''+data.rows[i]['message']+'\',keyword=\''+data.rows[i]['keyword']+'\',status=\''+data.rows[i]['status']+'\'">Active</a>';
													}  
													else
													{
														del = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\',country_id=\''+data.rows[i]['country_id']+'\',type_key=\''+data.rows[i]['type']+'\',shortcode_id=\''+data.rows[i]['short_id']+'\',telco_id=\''+data.rows[i]['telco_id']+'\',language_id=\''+data.rows[i]['language_id']+'\',message=\''+data.rows[i]['message']+'\',keyword=\''+data.rows[i]['keyword']+'\',status=\''+data.rows[i]['status']+'\'">Deactive</a>'; 
													}			
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['country_name']+'</td><td>'+data.rows[i]['operator_name']+'</td><td>'+data.rows[i]['shortcode_id']+'</td><td>'+data.rows[i]['keyword']+'</td><td>'+data.rows[i]['type']+'</td><td>'+data.rows[i]['language']+'</td><td>'+data.rows[i]['message']+'</td><td width="200">'+edit+' '+del+' '+duplicate+'</td></tr>';			
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
                    messagetype: {
                        required: true
                    },
                    country_id: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    language_id: {  
                        required: true
                    },
                    keyword: {
                        required: true
                    },
                    telco_id: {
                        required: true 
                    },
                    shortcode_id: {
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
			url:  domain+"campaign_manager/trigger_list/getTrigger_ListData", 
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
                           
                           
										if(data.count){
                                            loadOperator(data.country_id, data.telco_id); 
                                            loadKeyword(data.shortcode_id,data.keyword);
											jQuery('#id').val(data.id);
											jQuery('#messagetype').val(data.messagetype);  
											jQuery('#country_id').val(data.country_id);
											jQuery('#shortcode_id').val(data.shortcode_id);	
											//jQuery('#keyword').val(data.keyword);	
											jQuery('#type').val(data.type);
											if(data.type=='pin')
											{
												$('.pin_type_div').css('display','block');
												jQuery('#pin_type').val(data.pin_type);
												if(data.pin_type=='remote')
												{
													$('.pin_type_div').css('display','block');
													$('.pin_type_remote').css('display','block');
													jQuery('#generate_url').val(data.generate_url);	
													jQuery('#validate_url').val(data.validate_url);	
												}
												else
												{
													jQuery('#generate_url').val('');	
													jQuery('#validate_url').val('');	
													$('.pin_type_div').css('display','block');
													$('.pin_type_remote').css('display','none');
												}
											}
											else
											{
												jQuery('#pin_type').val('');
												jQuery('#generate_url').val('');	
												jQuery('#validate_url').val('');
												$('.pin_type_div').css('display','none');
												$('.pin_type_div').css('display','none');
												$('.pin_type_remote').css('display','none');
											}
											
											jQuery('#language_id').val(data.language_id);
											jQuery('#status').val(data.status);


											inactiveTab('operators_partners_list');
											activeTab('operators_partners_form');
										} else {
											
										}
									}
		   });	
}

function loadOperator(operatorid,telcoid){
  //alert(operatorid); return false;
 var operatorId = operatorid;
 if(operatorId != ""){
  jQuery.ajax({
    url:  domain+"campaign_manager/trigger_list/loadOperator",
    dataType: "json",
    type: "POST",
    data: {
      'id' : operatorId
       },
    beforeSend: loading('portlet-list'),
    success: function(data) {
     // alert(data);return false;
           jQuery('#select-append').html(data);
           jQuery('#telco_id').val(telcoid);  
          }
      }); 
 }
}

function loadKeyword(operatorid,keywordid){
//alert(operatorid+keywordid);
 var operatorId = operatorid;
 if(operatorId != ""){
  jQuery.ajax({
    url:  domain+"campaign_manager/trigger_list/loadKeyword",
    dataType: "json",
    type: "POST",
    data: {
      'id' : operatorId
       },
    beforeSend: loading('portlet-list'),
    success: function(data) {
           jQuery('#select-keyword').html(data);
           jQuery('#keyword').val(keywordid);    
          }
      });   
 } 
}


function save(){
	var id = jQuery('#id').val(),
		message = jQuery('#messagetype').val(), 
		country_id = jQuery('#country_id').val(),
        shortcode_id = jQuery('#shortcode_id').val(),
        keyword = jQuery('#keyword').val(),
		type 	= jQuery('#type').val(),
		pin_type = jQuery('#pin_type').val(),
		generate_url = jQuery('#generate_url').val(),
		validate_url = jQuery('#validate_url').val(),
		telco_id 	= jQuery('#telco_id').val(),
		language_id = jQuery('#language_id').val(),
        status = jQuery('#status').val(); 
		 
	var form = $('#formoperators_partners');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/trigger_list/saveTrigger_List",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'message' : message,
					'country_id' : country_id,
                    'shortcode_id' : shortcode_id,
                    'keyword' : keyword,
					'type' : type,
					'pin_type' : pin_type,
					'generate_url' : generate_url,
					'validate_url' : validate_url,
					'telco_id' : telco_id,  
					'language_id' : language_id,
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
					loadTrigger_List(1);
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


function duplicateTrigger_List(){
	if(duplicateID != ""){
         
		jQuery.ajax({
				url:  domain+"campaign_manager/trigger_list/duplicateTrigger_List",
				dataType: "json",
				type: "POST",
				data: {
						'id' : duplicateID, 
						'country_id':country_id,
						'shortcode_id':shortcode_id,
						'telco_id':telco_id, 
                                                'message':message,
                                                'type_key':type_key,
						'keyword':keyword,
						'language_id':language_id, 
                                                'status':status 
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadTrigger_List(1); 
										}
			   });	
	}
} 

function deleteTrigger_List(){ 
	if(deleteID != ""){
         
		jQuery.ajax({
				url:  domain+"campaign_manager/trigger_list/deleteTrigger_List",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID, 
						'country_id':country_id,
						'shortcode_id':shortcode_id,
						'telco_id':telco_id, 
                                                'message':message,
                                                'type_key':type_key,
						'keyword':keyword,
						'language_id':language_id, 
                                                'status':status 
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadTrigger_List(1); 
										}
			   });	
	}
}

function selectPinType(type){

	if(type=='pin')
	{
		$('.pin_type_div').css('display','block');
	}
	else if(type=='trigger')
	{
		$('#pin_type').val('');
		$('#generate_url').val('');
		$('#validate_url').val('');
		$('.pin_type_div').css('display','none');
		$('.pin_type_remote').css('display','none');
	}

	if(type=='remote')
	{
		$('.pin_type_div').css('display','block');
		$('.pin_type_remote').css('display','block');
	}
	else if(type=='local')
	{
		$('#generate_url').val('');
		$('#validate_url').val('');
		$('.pin_type_div').css('display','block');
		$('.pin_type_remote').css('display','none');
	}

}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#message').val('');
	jQuery('#campaign_id').val('');
	jQuery('#type').val('');
	jQuery('#telco').val('');
	jQuery('#language').val('');
	jQuery('#status').val('');
		
	inactiveTab('operators_partners_form');
	activeTab('operators_partners_list');
}

jQuery(document).ready(function() {	
	// Add events
});
