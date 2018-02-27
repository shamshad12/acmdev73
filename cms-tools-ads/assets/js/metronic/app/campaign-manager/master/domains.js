function loadDomains(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Code</th><th class="hidden-phone">Name</th><th class="hidden-phone">Expiry Date</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/domains/loadDomains",
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
												/*var edit='';
												if(accessEdit=='1')
													edit = '<a onclick="edit(\''+data.rows[i]['id']+'\')" style="cursor:pointer" class="btn green mini">Edit</a>';
												var del='';
												if(accessDelete=='1')
													del = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'">Delete</a>';*/
												var edit='';
												if(accessEdit=='1')
													edit = '<a onclick="edit(\''+data.rows[i]['code']+'\')" style="cursor:pointer" class="btn green mini">Edit</a>';
												var del='';
												if(accessDelete=='1')
												{
													var countries_arr = data.rows[i]['co_name'].split(',');
													if(countries_arr.length > 1)
														del = '<a  style="cursor:pointer" class="btn red mini active" href="javascript:void(0);" onclick="alert(\'Please edit and uncheck the desired country.\'); return false;">Delete</a>';
													else
														del = '<a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['code']+'\'">Delete</a>';
												}
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['co_name']+'</td><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formdomains');
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
			$('.custom_error').remove();
			var error_html = '<div class="control-group error custom_error" style="margin-bottom:0;"><span class="help-inline ok">This field is required.</span></div>';
			if(!($('.country').is(":checked")))
			{
				$('#country_div').prepend(error_html);
				return false;
			}
			success.show();
                   	error.hide();
			save();
                }
            });
        }
    };

}();

function edit(code){
	jQuery('.checker').find('.checked').removeClass("checked");
	jQuery('.country').removeAttr("checked");
	jQuery.ajax({
			url:  domain+"campaign_manager/domains/getDomainsData",
			dataType: "json",
			type: "POST",
			data: {
					'code' : code
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#name').val(data.name);
											if(data.id_country!="")
										        {
										    		var countries = data.id_country.split(',');
												for(var i=0;i<countries.length;i++)
												{
													jQuery('#id_country_'+countries[i]).parent().addClass("checked");
													jQuery('#id_country_'+countries[i]).attr("checked","checked");
												}						                                                
										        }
											jQuery('#description').val(data.description);	
											jQuery('#code').val(data.code);	
											jQuery('#status').val(data.status);	
											jQuery('#code').attr('readonly','readonly');
											inactiveTab('domains_list');
											activeTab('domains_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		name = jQuery('#name').val(),
		id_country = jQuery('#id_country').val(),
		code = jQuery('#code').val(),
		status = jQuery('#status').val(),
		description = jQuery('#description').val();

	var id_country = new Array();

	jQuery(".country:checked").each(function() {
       		id_country.push(jQuery(this).val());
	});
	
	var form = $('#formdomains');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/domains/saveDomains",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'id_country' : id_country,
					'code' : code,
					'status' : status,
					'description' : description
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
											loadDomains(1);
											clearForm();
											
											inactiveTab('domains_form');
											activeTab('domains_list');
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

function deleteDomains(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/domains/deleteDomains",
				dataType: "json",
				type: "POST",
				data: {
						'code' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadDomains(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#id_country').val('');
	jQuery('#description').val('');
	jQuery('#code').val('');
	jQuery('#status').val('');
	jQuery('.checker').find('.checked').removeClass("checked");
	jQuery('.country').removeAttr('checked');
	jQuery('#code').removeAttr('readonly');	
	inactiveTab('domains_form');
	activeTab('domains_list');
}

jQuery(document).ready(function() {	
	// Add events
});
