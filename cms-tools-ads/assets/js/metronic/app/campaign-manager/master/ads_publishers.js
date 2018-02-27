function loadAds_Publishers(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Code</th><th class="hidden-phone">Name</th><th class="hidden-phone">Description</th><th class="hidden-phone">Ads Type</th><th class="hidden-phone hidden">UTC Timezone</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"campaign_manager/ads_publishers/loadAds_Publishers",
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
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['co_name']+'</td><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['name']+'</td><td>'+data.rows[i]['description']+'</td><td>'+data.rows[i]['ads_type']+'</td><td class="hidden">'+data.rows[i]['utc_timezone']+'</td><td>'+data.rows[i]['status']+'</td><td width="105">'+edit+' '+del+'</td></tr>';			
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
            var form = $('#formads_publishers');
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
                    },
                    utc_timezone: {
                        required: true
                    },
                    url_confirm: {
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
			url:  domain+"campaign_manager/ads_publishers/getAds_PublishersData",
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
											jQuery('#utc_timezone').val(data.utc_timezone);
											jQuery('#description').val(data.description);	
											jQuery('#code').val(data.code);	
											jQuery('#ads_type').val(data.ads_type);
											data.ads_type=='CPC'?jQuery('#cpc-disabled').hide():jQuery('#cpc-disabled').show();	
											jQuery('#affiliate_params').val(data.affiliate_params);	
											jQuery('#affiliate_values').val(data.affiliate_values);	
											jQuery('#trans_id_params').val(data.trans_id_params);
											jQuery('#url_confirm').val(data.url_confirm);
											jQuery('#loading_url').val(data.loading_url);
											jQuery('#updation_url').val(data.updation_url);
											jQuery('#status').val(data.status);	
											//alert(jQuery('#loading_url').val().trim());
											
											if(jQuery('#loading_url').val().trim()!='' && jQuery('#updation_url').val().trim()!='' )
											{
												//debugger;
												$(".hide_show_lead").show();
												jQuery("#isthirdparty").parent().addClass("checked");
												jQuery(".hide_show").show();
												//jQuery('#isthirdparty').prop('checked', true);\
												//document.getElementById("isthirdparty").checked = true;
											}
											else if(jQuery('#updation_url').val().trim()!='' )
											{
												$('#update_label').html("Third Party Url");
												$(".hide_show_lead").show();
												jQuery("#isthirdparty_1").parent().addClass("checked")
												//jQuery(".hide_show").show();
												
											}
											else
											{
												jQuery(".hide_show").hide();	
												$(".hide_show_lead").hide();
											}
											jQuery('#code').attr('readonly','readonly');
											inactiveTab('ads_publishers_list');
											activeTab('ads_publishers_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var id = jQuery('#id').val(),
		name = jQuery('#name').val(),
		id_country = jQuery('#id_country').val(),
		utc_timezone = jQuery('#utc_timezone').val(),
		code = jQuery('#code').val(),
		status = jQuery('#status').val(),
		ads_type = jQuery('#ads_type').val(),
		trans_id_params = jQuery('#trans_id_params').val(),
		affiliate_params = jQuery('#affiliate_params').val(),
		affiliate_values = jQuery('#affiliate_values').val(),
		url_confirm = jQuery('#url_confirm').val(),
		description = jQuery('#description').val();
	
	var id_country = new Array();

	jQuery(".country:checked").each(function() {
       		id_country.push(jQuery(this).val());
	});

	var loading_url=$('#loading_url').val();
	var updation_url=$('#updation_url').val();	
	var form = $('#formads_publishers');
	var error = $('.alert-error-save', form);
    	var success = $('.alert-success-save', form);

	jQuery.ajax({
			url:  domain+"campaign_manager/ads_publishers/saveAds_Publishers",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'name' : name,
					'id_country' : id_country,
					'utc_timezone' : utc_timezone,
					'code' : code,
					'status' : status,
					'ads_type' : ads_type,
					'trans_id_params' : trans_id_params,
					'affiliate_params' : affiliate_params,
					'affiliate_values' : affiliate_values,
					'url_confirm' : url_confirm,
					'loading_url':loading_url,
					'updation_url':updation_url,
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
											loadAds_Publishers(1);
											clearForm();
											
											inactiveTab('ads_publishers_form');
											activeTab('ads_publishers_list');
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

function deleteAds_Publishers(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/ads_publishers/deleteAds_Publishers",
				dataType: "json",
				type: "POST",
				data: {
						'code' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadAds_Publishers(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#id_country').val('');
	jQuery('#utc_timezone').val('');
	jQuery('#description').val('');
	jQuery('#code').val('');
	jQuery('#ads_type').val('');
	jQuery('#trans_id_params').val('');
	jQuery('#affiliate_params').val('');
	jQuery('#affiliate_values').val('');
	jQuery('#url_confirm').val('');
	jQuery('#status').val('');
	jQuery('.checker').find('.checked').removeClass("checked");
	jQuery('.country').removeAttr('checked');
	jQuery('#code').removeAttr('readonly');	
	inactiveTab('ads_publishers_form');
	activeTab('ads_publishers_list');
}

jQuery(document).ready(function() {	
	// Add events
});

//Function written by rakesh joshi for hide and show functionality for checkbox check uncheck
function showHideContent(state)
{
if(state==1)
{
	$('#update_label').html("Lead Confirmation Url");
	if($('#isthirdparty').is(":checked")) {
    $(".hide_show").show();
    $(".hide_show_lead").show();
	} else {
		//alert('hi');
	    $(".hide_show").hide();
	    $(".hide_show_lead").hide();
	}
	$("#isthirdparty_1").parent().removeClass("checked");
	$("#isthirdparty_1").attr("checked",false);
}
else if(state==2)
{
	$('#update_label').html("Third Party Url");
	$(".hide_show").hide();
	if($('#isthirdparty_1').is(":checked")) {
   
    $(".hide_show_lead").show();
	} else {
		//alert('hi');
	   	
	    $(".hide_show_lead").hide();
	}
	$("#isthirdparty").parent().removeClass("checked");
	$("#isthirdparty").attr("checked",false);
}

}
