var files;

function loadGateway(page){		
	
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		country=$('#search_country').val(),
		operator=$('#search_operator').val(),
		shortcode=$('#search_shortcode').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Operator</th><th class="hidden-phone">Name</th><th class="hidden-phone">API</th><th class="hidden-phone">Shortcode</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/sms_gateway/loadGateway",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'country':country,
					'operator':operator,
					'shortcode':shortcode
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
							del = 'data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\',country_id=\''+data.rows[i]['country_id']+'\',telco_id=\''+data.rows[i]['telco_id']+'\',shortcode_id=\''+data.rows[i]['shortcode_id']+'\'"';
							result  += '<tr class="odd gradeX"><td>'+data.rows[i]['country_name']+'</td><td>'+data.rows[i]['operator_name']+'</td></td><td>'+data.rows[i]['g_name']+'</td><td>'+data.rows[i]['api_url']+'</td><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['status']+'</td><td width="105"><a '+edit+' style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" '+del+'>Delete</a></td></tr>';			
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
            var form = $('#sms_gateway');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    api_url: {
                        required: true
                    },
                    operator_id: {
                        required: true
                    },
                    country_id: {
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
			url:  domain+"campaign_manager/sms_gateway/getGatewayData",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {

										if(data.count){
											loadOperator(data.country_id,data.telco_id);
											jQuery('#id').val(data.id);
											jQuery('#g_name').val(data.g_name);
											jQuery('#country_id').val(data.country_id);
											//jQuery('#operator_id').val(data.telco_id);
											jQuery('#api_url').val(data.api_url);	
											//jQuery('#params').val(data.params);
											jQuery('#status').val(data.status);
											jQuery('#shortcode_id').val(data.shortcode_id);
											inactiveTab('sms_gateway_list');
											activeTab('sms_gateway_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
	g_name = jQuery('#g_name').val(),
	country_id = jQuery('#country_id').val(),
	telco_id = jQuery('#operator_id').val(),
	api_url = jQuery('#api_url').val(),
	status = jQuery('#status').val(),
	shortcode_id = jQuery('#shortcode_id').val();
	

	var form = $('#sms_gateway');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/sms_gateway/saveGateway",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'g_name' : g_name,
					'country_id' : country_id,
					'telco_id' : telco_id,
					'api_url' : api_url,
					'status' : status,
					'shortcode_id':shortcode_id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
					if(data.success){
						success.show();
						error.hide();	
						loadGateway(1);
						clearForm();
						
						inactiveTab('sms_gateway_form');
						activeTab('sms_gateway_list');
					} else {
						$(".alert-error-save").append('');
						success.hide();
						error.show();
						//alert(success.message);
						if(data.message.trim()!='')
						{
							$(".alert-error-save").html('');
							$(".alert-error-save").append(data.message);
						}
					}
				}
		   });	
}

function setDeleteID(id,country_id,telco_id,shortcode_id){
//alert(id);
	deleteID = 	id;
	country_id=country_id;
	telco_id=telco_id;
	shortcode_id=shortcode_id;
}

function deleteGateway(){
	
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/sms_gateway/deleteGateway",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID,
						'country_id':country_id,
						'telco_id':telco_id,
						'shortcode_id':shortcode_id
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
					
											loadGateway(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#country_id').val('');
	jQuery('#operator_id').val('');
	jQuery('#api_url').val('');	
	jQuery('#status').val('');
	jQuery('#shortcode_id').val('');	
	inactiveTab('sms_gateway_form');
	activeTab('sms_gateway_list');
}

jQuery(document).ready(function() {	
	// Add events
});

function loadOperator(operatorid,telco_id){
	
 if(operatorid != ""){
  jQuery.ajax({
    url:  domain+"campaign_manager/sms_gateway/loadOperator",
    dataType: "json",
    type: "POST",
    data: {
      'id' : operatorid
       },
    beforeSend: loading('portlet-list'),
    success: function(data) {

     // alert(data);return false;
           jQuery('#select-append').html(data);
           jQuery('#operator_id').val(telco_id);
           
          }
      }); 
 }
}
