var files; 
function loadCampaigns(page, template_id, campaign_media, temp_country_id) {
	if (template_id) {
		inactiveTab('campaigns_list');
		activeTab('campaigns_form');
		jQuery('#id_campaign_media').val(campaign_media);
		jQuery('#id_country').val(temp_country_id);
		getPartners_Services(temp_country_id, '', '');
		getDomainsList(temp_country_id, '');
		getAds_Publishers('', '', temp_country_id);
		getTemplates(campaign_media, template_id);
	}

	var limit = $('#limit').val(), search = $('#search').val(), media = $(
			'#search_media').val(), category = $('#search_category').val(), country = $(
			'#search_country').val(), user = $('#search_user').val(), html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone" width="70">Country</th><th class="hidden-phone">Name</th><th class="hidden-phone">Category</th><th class="hidden-phone" width="90">Media</th><th class="hidden-phone" width="100">Banner</th><th class="hidden-phone">Shortcodes</th><th class="hidden-phone">Keywords</th><th class="hidden-phone" width="70">Language</th><th class="hidden-phone" width="190">Conf. | Status | Url 1 | Url 2</th><th class="hidden-phone" title="Created by" width="75">Created By</th><th class="hidden-phone" width="75" title="Updated by">Updated By</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';

	jQuery
			.ajax({
				url : domain + "partner_campaign_manager/campaigns/loadCampaigns",
				dataType : "json",
				type : "POST",
				data : {
					'limit' : limit,
					'page' : page,
					'search' : search,
					'media' : media,
					'category' : category,
					'country' : country
				},
				// beforeSend: progress_jenis_user_list,
				success : function(data) {
					// alert(JSON.stringify(data));return false;
					jQuery('#table-append').html('');
					var result = '';
					if (data.count) {
						for ( var i = 0; i < data.rows.length; i++) {
							var duplicate = '';
							var isLock = data.rows[i]['edit_type'];
							var fontcolor = (isLock == 1 & (data.rows[i]['login_user'] != data.rows[i]['edit_user'])) ? 'red':'';
							if (accessDuplicate == '1')
								duplicate = '<a  style="cursor:pointer;" class="btn blue mini active" data-toggle="modal" href="#duplicate" onclick="duplicateID=\''
										+ data.rows[i]['id']
										+ '\'" title="duplicate"><span class="icon-copy"></span></a>';
							var edit = '';
							if (accessEdit == '1')
								edit = '<a onclick="edit(\''
										+ data.rows[i]['id']
										+ '\')" style="cursor:pointer;" class="btn green mini" title="edit"><span class="icon-edit"></span></a>';
							var updateTemp = '';
							if (data.rows[i]['template'])
								updateTemp = '<a href="partner_campaign_manager/templates?template_id='+data.rows[i]['template']+'" style="cursor:pointer;" class="btn green mini" title="update template" target="_blank"><span class="icon-upload"></span></a>';
							var del = '';
							if (accessDelete == '1') {
								if (data.rows[i]['edit_type'] == 1
										&& (data.rows[i]['login_user'] != data.rows[i]['edit_user'])) {
									del = '<a  style="cursor:pointer;margin-left:-10px;" class="btn red mini active" data-toggle="modal" onclick="deleteCampaigns(\''
											+ data.rows[i]['id']
											+ '\')" title="delete"><span class="icon-trash"></span></a>';
								} else if (data.rows[i]['edit_type'] == 1
										&& (data.rows[i]['session_user_typeid'] == 2)) {
									del = '<a  style="cursor:pointer;margin-left:-10px;" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''
											+ data.rows[i]['id']
											+ '\'" title="delete"><span class="icon-trash"></span></a>';
								} else {
									del = '<a  style="cursor:pointer;margin-left:-10px;" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''
											+ data.rows[i]['id']
											+ '\'" title="delete"><span class="icon-trash"></span></a>';
								}

							}
							var status;
							// if(data.rows[i]['edit_type']==0 &&
							// (data.rows[i]['session_user_id']==2 ||
							// data.rows[i]['session_user_id']==6))
							//												
							// status = '<a style="cursor:pointer" class="btn
							// red mini active" data-toggle="modal"
							// href="#changestatus"
							// onclick="edit_type=\''+1+'\',sid=\''+data.rows[i]['id']+'\'">Lock</a>';
							//													
							//													
							if (data.rows[i]['edit_type'] == 1
									&& (data.rows[i]['session_user_typeid'] == 2))

								status = '<a  style="cursor:pointer" class="btn green mini active" data-toggle="modal" href="#changestatus" onclick="edit_type=\''
										+ 0
										+ '\',sid=\''
										+ data.rows[i]['id']
										+ '\'" title="Unlock"><span class="icon-lock"></span></a>';
							else
								status = '';
							result += '<tr class="odd gradeX"><td class='
								+ fontcolor
								+ '>'
									+ data.rows[i]['country_name']
									+ '</td><td>'
									+ data.rows[i]['name']
									+ '</td><td>'
									+ data.rows[i]['categories_name']
									+ '</td><td>'
									+ data.rows[i]['media_name']
									+ '</td><td><img src="'
									+ data.host
									+ data.rows[i]['url_thumb']
									+ '" width="100"/></td><td>'
									+ data.rows[i]['shortcode']
									+ '</td><td>'
									+ data.rows[i]['keyword']
									+ '</td><td>'
									+ data.rows[i]['language']
									+ '</td><td>'
									+ data.rows[i]['use_confirmation']
									+ ' | '
									+ data.rows[i]['status']
									//+ ' | <a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#urlShow" onclick="loadUrl(\''
									//+ data.rows[i]['id']
									//+ '\')" title="Url without parameters">Url 1</a> | <a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#urlShow" onclick="loadUrlWithValues(\''
									+ ' | <a  style="cursor:pointer" class="btn blue mini active" data-toggle="modal" href="#urlShow" onclick="loadUrlWithValues(\''
									+ data.rows[i]['id']
									+ '\')" title="Url with parameters">Landing page URL</a></td><td title="'
									+ data.rows[i]['user_enter_time'] + '">'
									+ data.rows[i]['user_enter']
									+ ' <span id="usr-up-time">'
									+ data.rows[i]['user_enter_time']
									+ '</span></td><td title="'
									+ data.rows[i]['user_updated_time'] + '">'
									+ data.rows[i]['user_updated']
									+ ' <span id="usr-up-time">'
									+ data.rows[i]['user_updated_time']
									+ '</span></td><td width="135">' + edit
									+ ' ' + del + ' ' + duplicate + ' '
									+ status + ' ' + updateTemp + '</td></tr>';
						}
					}
					var paging = '<div id="paging">' + data.pagination
							+ '</div>';
					$('#table-append').append(
							html + result + '</tbody></table>' + paging);
				}
			});
}

var FormValidation = function() {
	return {
		init : function() {
			var form = $('#formcampaigns');
			var error = $('.alert-error', form);
			var success = $('.alert-success', form);

			form.validate({
				errorElement : 'span', // default input error message container
				errorClass : 'help-inline', // default input error message class
				focusInvalid : false, // do not focus the last invalid input
				ignore : "",
				rules : {
					name : {
						required : true
					},
					id_country : {
						required : true
					},
					id_campaign_media : {
						required : true
					},
					id_template : {
						required : true
					},
					id_campaign_category : {
						required : true
					},
					content : {
						required : true
					}
				},

				invalidHandler : function(event, validator) { // display error
																// alert on form
																// submit
					success.hide();
					error.show();
					App.scrollTo(error, -200);
				},

				highlight : function(element) { // hightlight error inputs
					$(element).closest('.help-inline').removeClass('ok'); // display
																			// OK
																			// icon
					$(element).closest('.control-group').removeClass('success')
							.addClass('error'); // set error class to the
												// control group
				},

				unhighlight : function(element) { // revert the change dony by
													// hightlight
					$(element).closest('.control-group').removeClass('error'); // set
																				// error
																				// class
																				// to
																				// the
																				// control
																				// group
				},

				success : function(label) {
					label.addClass('valid').addClass('help-inline ok') // mark
																		// the
																		// current
																		// input
																		// as
																		// valid
																		// and
																		// display
																		// OK
																		// icon
					.closest('.control-group').removeClass('error').addClass(
							'success'); // set success class to the control
										// group
				},

				submitHandler : function(form) {
					$('.custom_error').remove();
					var error_html = '<div class="control-group error custom_error" style="margin-bottom:0;"><span class="help-inline ok">This field is required.</span></div>';
					if(!($('.ads_publisher').is(":checked")))
					{
						$('#ads_publishers_list').prepend(error_html);
						return false;
					}
					if(!($('.partner_service').is(":checked")))
					{
						$('#operator_partner_list').prepend(error_html);
						return false;
					}
					if(!($('.domain_list').is(":checked")))
					{
						$('#domain_list').prepend(error_html);
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

function edit(id) {
	jQuery.ajax({
		url : domain + "partner_campaign_manager/campaigns/getCampaignsData",
		dataType : "json",
		type : "POST",
		data : {
			'id' : id
		},
		beforeSend : loading('portlet-form'),
		success : function(data) {
			if (data.duplicateedit_data) {

				alert(data.edit_message);
				return false;
			}
			if (data.count) {
				jQuery('#id').val(data.id);
				jQuery('#name').val(data.name);
				jQuery('#id_country').val(data.id_country);

				jQuery('#id_campaign_media').val(data.id_campaign_media);

				if (data.id_campaign_media == 2) { //web
					$('#hide_show_confirmation_page').hide();
					$('#hide_show_thnk_msg').hide();
					$('#hide_show_alrt_msg').hide();
					$('.alch_cont_id').addClass('hide');
					// $('#content').val('--');
				}
				else if(data.id_campaign_media == 5 || data.id_campaign_media == 7) //ivr
				{
					$('.alch_cont_id').addClass('hide');
					$('#hide_show_confirmation_page').show();
					$('#hide_show_thnk_msg').show();
					$('#thnk_msg').val(data.thnk_msg);					
					}
				else {
					$('#hide_show_confirmation_page').show();
					$('#hide_show_thnk_msg').show();
					$('#thnk_msg').val(data.thnk_msg);
					$('#hide_show_alrt_msg').show();
					$('#alrt_msg').val(data.alrt_msg);
					$('.alch_cont_id').removeClass('hide');
					// $('#content').val(data.content);
				}

				jQuery('#id_campaign_category').val(data.id_campaign_category);
				jQuery('#id_banner').val(data.id_banner);
				jQuery('#id_template').val(data.id_template);
				jQuery('#id_language').val(data.id_language);
				// jQuery('#acm_code').val(data.acm_code);
				jQuery('#description').val(data.description);
				jQuery('#content').val(data.content);
				jQuery('#use_confirmation').val(data.use_confirmation);
				jQuery('#status').val(data.status);
				jQuery('#expire_date_camp').val(data.expire_date_camp);
				jQuery('#publish_start').val(data.publish_start);
				jQuery('#publish_end').val(data.publish_end);

				if (data.status == '2')
					jQuery('#expire_date').css('display', 'block');

				getAds_Publishers(data.ads_publishers, data.cost,
						data.id_country);

				getPartners_Services(data.id_country, data.partners_services,
						data.id_campaign_media);
				getTemplates(data.id_campaign_media, data.id_template);
				getDomainsList(data.id_country, data.domain_list)

				inactiveTab('campaigns_list');
				activeTab('campaigns_form');
			} else {

			}
		}
	});
}

function loadUrl(id) {
	jQuery('#modal-body').html('');
	jQuery.ajax({
		url : domain + "partner_campaign_manager/campaigns/getUrlData",
		dataType : "json",
		type : "POST",
		data : {
			'id' : id
		},
		beforeSend : loading('portlet-form'),
		success : function(data) {
			var result = "";
			if (data.count) {
				for ( var i = 0; i < data.rows.length; i++) {
					var campaign_url = data.rows[i]['url'];
					//if(data.rows[i]['ivr_url'] != '')
						//campaign_url = data.rows[i]['ivr_url'];
					 result += "<p style='text-align:left'>"
							+ data.rows[i]['slot']
							+ " :<br><a  target='_blank' href='"
							+ campaign_url + "'>" + campaign_url
							+ "</a></p><br>";
				}
			} else {

			}
			jQuery('#modal-body').html(result);
		}
	});
}

function loadUrlWithValues(id) {
	jQuery('#modal-body').html('');
	jQuery.ajax({
		url : domain + "partner_campaign_manager/campaigns/getUrlDataWithValues",
		dataType : "json",
		type : "POST",
		data : {
			'id' : id
		},
		beforeSend : loading('portlet-form'),
		success : function(data) {
			var result = "";
			if (data.count) {
				var use_confirmation_btn = '';
				for ( var i = 0; i < data.rows.length; i++) {
					    if(data.rows[i]['banner_flag'])
                                                {
                                            var bannerText = '<span><a class="btn blue mini active" target="_blank" href="'+domain + "campaign_manager/campaigns/generateBannerCode/"+data.rows[i]['campaign_code']+'/'+data.rows[i]['url_ecode']+'/">Generate Code</a></span>';
                                                }else{
                                                    var bannerText ='';
                                                }
					var campaign_url = data.rows[i]['url'];
					if(data.rows[i]['id_campaign_media'] == 1)
					{
						var camp_data = {};
						camp_data['id_campaign'] = data.rows[i]['id_campaign'];
						camp_data['id_domain'] = data.rows[i]['id_domain'];
						camp_data['id_partner_service'] = data.rows[i]['id_partner_service'];
						camp_data['id_ads_publisher'] = data.rows[i]['id_ads_publisher'];
						camp_data['use_confirmation'] = data.rows[i]['use_confirmation'];
						camp_data['curr_domain_id'] = data.rows[i]['curr_domain_id'];
						camp_data['md5_domain'] = data.rows[i]['md5_domain'];
						camp_data = JSON.stringify(camp_data);
						if(data.rows[i]['sub_use_confirmation'] == 1)
							use_confirmation_btn = "<a href='javascript:void(0)' class='btn blue mini active' title='Disable Use Confirmation Page' onclick='setUseCofirmation(this,"+camp_data+",0)'>Disable</a>";
						else
							use_confirmation_btn = "<a href='javascript:void(0)' class='btn blue mini active' title='Enable Use Confirmation Page' onclick='setUseCofirmation(this,"+camp_data+",1)'>Enable</a>";
					}

					//if(data.rows[i]['ivr_url'] != '')
						//campaign_url = data.rows[i]['ivr_url'];
					
					result += "<p style='text-align:left'>"
							+ data.rows[i]['slot']
							+ " :<span class='btn_wrap'>"+use_confirmation_btn+ "<img class='ajax_loading' src='"+domain+"assets/img/metronic/ajax-loading.gif' style='display:none;'/></span><br><a target='_blank' href='"
							+ campaign_url + "'>" + campaign_url
							+ "</a></p>";
                      result+="<div id='banner_div_"+data.rows[i]['campaign_code']+"' style='width:100%;border:1px solid#FFF;padding:5px;text-align:left'><span>Upload Banners: <input type='file' class='banner-code' style='font-size:12px;'  id='upload_files_"+data.rows[i]['campaign_code']+"' name='upload_files'  onchange='uploadFiles(\""+data.rows[i]['campaign_code']+"\")'></span>"+bannerText+"</div><br/>";
                                            
				}
			} else {

			}
			jQuery('#modal-body').html(result);
		}
	});
}

function save() {
	var id = jQuery('#id').val(), name = jQuery('#name').val(), id_country = jQuery(
			'#id_country').val(), id_campaign_media = jQuery(
			'#id_campaign_media').val(), id_campaign_category = jQuery(
			'#id_campaign_category').val(), id_banner = jQuery('#id_banner')
			.val(), id_template = jQuery('#id_template').val(), id_language = jQuery(
			'#id_language').val(), thnk_msg = jQuery('#thnk_msg').val(), alrt_msg = jQuery('#alrt_msg').val(),
	// acm_code = jQuery('#acm_code').val(),
	content = jQuery('#content').val(), status = jQuery('#status').val(), expire_date_camp = jQuery(
			'#expire_date_camp').val(), use_confirmation = jQuery(
			'#use_confirmation').val(), description = jQuery('#description')
			.val(), publish_start = jQuery('#publish_start').val(), publish_end = jQuery('#publish_end').val();

	if (id_campaign_media == 2 || id_campaign_media==5 || id_campaign_media == 7) {
		content = '--';
		$('.wap input').removeAttr('checked');
	} else {
		$('.web input').removeAttr('checked');
	}

	if (id_language.trim() == '')
		id_language = 1;

	var partner_service = new Array();
	var operator_data = new Array();
	var n = jQuery("#partner-service:checked").length;
	if (n > 0) {
		jQuery("#partner-service:checked").each(function() {
			partner_service.push($(this).val());
			operator_data.push($(this).attr('operator_data'));
		});
	}

	var domain_list = new Array();
	var n = jQuery("#domain-list:checked").length;
	if (n > 0) {
		jQuery("#domain-list:checked").each(function() {
			domain_list.push($(this).val());
		});
	}

	var ads_publisher = new Array(), cost = new Array();
	var n = jQuery("#ads-publisher:checked").length;
	if (n > 0) {
		jQuery("#ads-publisher:checked").each(function() {
			ads_publisher.push($(this).val());
			var costAds = $(this).val();
			cost.push($('#cost-' + costAds).val());
		});
	}

	var form = $('#formcampaigns');
	var error = $('.alert-error-save', form);
	var success = $('.alert-success-save', form);

	jQuery.ajax({
		url : domain + "partner_campaign_manager/campaigns/saveCampaigns",
		dataType : "json",
		type : "POST",
		data : {
			'id' : id,
			'name' : name,
			'id_country' : id_country,
			'id_campaign_media' : id_campaign_media,
			'id_campaign_category' : id_campaign_category,
			'id_banner' : id_banner,
			'id_template' : id_template,
			'id_language' : id_language,
			// 'acm_code' : acm_code,
			'operator_data' : operator_data,
			'partner_service' : partner_service,
			'domain_list' : domain_list,
			'ads_publisher' : ads_publisher,
			'cost' : cost,
			'content' : content,
			'status' : status,
			'expire_date_camp' : expire_date_camp,
			'use_confirmation' : use_confirmation,
			'description' : description,
			'publish_start' : publish_start,
			'publish_end' : publish_end,
			'thnk_msg' : thnk_msg,
			'alrt_msg' : alrt_msg
		},
		beforeSend : loading('portlet-form'),
		success : function(data) {

			if (data.duplicat_data) {
				success.hide();
				alert(data.errors_message)
			}

			if (data.success) {
				success.show();
				error.hide();
				loadCampaigns(1);
				clearForm();
				jQuery('#showLesspub').addClass('hidden');
				jQuery('#showMorepub').removeClass('hidden');
				jQuery('#showLessapi').addClass('hidden');
				jQuery('#showMoreapi').removeClass('hidden');
				inactiveTab('campaigns_form');
				activeTab('campaigns_list');
			} else {
				success.hide();
				error.show();
			}
		}
	});
}

function setDeleteID(id) {
	deleteID = id;
}

function deleteCampaigns(id) {
	// alert(id);return false;
	if (id != "") {
		deleteID = id;
	}
	if (deleteID != "") {
		jQuery.ajax({
			url : domain + "partner_campaign_manager/campaigns/deleteCampaigns",
			dataType : "json",
			type : "POST",
			data : {
				'id' : deleteID
			},
			beforeSend : loading('portlet-list'),
			success : function(data) {

				if (data.duplicateedit_data) {

					alert(data.edit_message);
					return false;
				}

				loadCampaigns(1);
			}
		});
	}
}

function duplicateCampaigns() {
	if (duplicateID != "") {
		jQuery.ajax({
			url : domain + "partner_campaign_manager/campaigns/duplicateCampaigns",
			dataType : "json",
			type : "POST",
			data : {
				'id' : duplicateID
			},
			beforeSend : loading('portlet-list'),
			success : function(data) {
				loadCampaigns(1);
			}
		});
	}
}

function getPrices(id_country, id_price) {
	$('#id_price').val('');
	$('#id_price').attr('disabled', 'disabled');
	if (id_country != "") {
		jQuery.ajax({
			url : domain + "campaign_manager/prices/loadPricesSelect",
			dataType : "json",
			type : "POST",
			data : {
				'id_country' : id_country
			},
			beforeSend : loading('portlet-list'),
			success : function(data) {
				var result = '<option value="">- Choose Price -</option>';
				if (data.count) {
					for ( var i = 0; i < data.rows.length; i++) {
						result += "<option value='" + data.rows[i]['id'] + "'>"
								+ data.rows[i]['cu_code'] + " "
								+ data.rows[i]['value'] + "</option>";
					}
					$('#id_price').html(result);
					$('#id_price').removeAttr('disabled');

					$('#id_price').val(id_price);
				}
			}
		});
	}
}

function getTemplates(id_campaign_media, id_template) {
	if (id_campaign_media == 2) {
		$('#hide_show_confirmation_page').hide();
		$('#hide_show_thnk_msg').hide();
		$('#hide_show_alrt_msg').hide();
		$('.alch_cont_id').addClass('hide');
		$('#content').val('--');
		$('.wap').hide();
		$('.web').show();
	}
	else if(id_campaign_media == 5)
				{
					$('.alch_cont_id').addClass('hide');
					$('#hide_show_confirmation_page').show();
					$('#hide_show_thnk_msg').show();	
					$('#content').val('--');
				}
	else if(id_campaign_media == 7)
		{
			$('#hide_show_confirmation_page').show();
			$('#hide_show_thnk_msg').show();
			$('.alch_cont_id').addClass('hide');	
			$('#content').val('--');
			id_campaign_media = 1;
		 }				
		else {
		$('#hide_show_confirmation_page').show();
		$('#hide_show_thnk_msg').show();
		$('#hide_show_alrt_msg').show();
		$('.alch_cont_id').removeClass('hide');
		// $('#content').val('');
		$('.wap').show();
		$('.web').hide();
	}
	$('#id_template').val('');
	$('#id_template').attr('disabled', 'disabled');
	if (id_country != "") {
		jQuery.ajax({
			url : domain + "partner_campaign_manager/templates/loadTemplatesSelect",
			dataType : "json",
			type : "POST",
			data : {
				'campaign_media' : id_campaign_media
			},
			beforeSend : loading('portlet-list'),
			success : function(data) {
				var result = '<option value="">- Choose Template -</option>';
				if (data.count) {
					for ( var i = 0; i < data.rows.length; i++) {
						result += "<option value='" + data.rows[i]['id'] + "'>"
								+ data.rows[i]['name'] + "</option>";
					}
					$('#id_template').html(result);
					$('#id_template').removeAttr('disabled');

					$('#id_template').val(id_template);
				}
			}
		});
	}
}

function getPartners_Services(id_country, partner_service, camp_media) {
	var id_campaign_media = $('#id_campaign_media').val();
	var country_id = $('#id_country').val();
	if (id_campaign_media)
		camp_media = id_campaign_media;
	if (country_id)
		id_country = country_id;
	$('#showmrlsapi').html('');
	// alert(id_campaign_media);
	$('#operator_partner_list').html('');
	if (id_country != "") {
		jQuery
				.ajax({
					url : domain
							+ "campaign_manager/partners_services/loadPartners_ServicesSelect",
					dataType : "json",
					type : "POST",
					data : {
						'id_country' : id_country,
						'id_campaign_media' : camp_media
					},
					beforeSend : loading('portlet-list'),
					success : function(data) {
						// var result = '[ Operator ] [ Partner ] [ Price ] [
						// Shortcode ] [ SID ] [ Keyword ] [ Campaign Media ] ';
						var result = '[ Operator ] [ Partner ] [ Price ] [ Shortcode ] [ Keyword ] [ Campaign Media ] ';
						var shwbtn = '';
						if (data.count) {
							var j = 0, k = 0, array1 = [], array2 = [];
							for ( var i = 0; i < data.rows.length; i++) {
								if (partner_service[data.rows[i]['id']]) {
									// alert(JSON.stringify(data.rows[i]));return
									// false;
									array1[j] = data.rows[i];
									j++;
								} else {
									array2[k] = data.rows[i];
									k++;
								}
							}
							data['rows'] = $.merge(array1, array2);
							for ( var i = 0; i < data.rows.length; i++) {
								var checked = "";
								var apcls = "";
								if (partner_service[data.rows[i]['id']])
									checked = "checked";
								if (i > 9) {
									apcls = "aftertenapi hidden";
								} else {
									apcls = "firsttenapi";
								}
								result += "<div class='"
										+ apcls
										+ "'><label class="
										+ data.rows[i]['campaign_media']
										+ "><input type='checkbox' id='partner-service' class='partner_service' value='"
										+ data.rows[i]['id'] + "' " + checked
										+ " operator_data='"+data.rows[i]['operator_id']+"__||__"+data.rows[i]['operator_name']+"'"
										+ "> [ "
										+ data.rows[i]['operator_name']
										+ " ] [ "
										+ data.rows[i]['partner_name']
										+ " ] [ " + data.rows[i]['price']
										+ " ] [ " + data.rows[i]['shortcode']
										+ " ] [ " + data.rows[i]['keyword']
										+ " ] [ "
										+ data.rows[i]['campaign_media']
										+ " ] </label></div>";
							}
							$('#operator_partner_list').html(result);
							if (camp_media == 2 || id_campaign_media == 2) {
								$('.wap').hide();
								$('.wap input').removeAttr('checked');
								$('.web').show();
							} else if (camp_media == 1
									|| id_campaign_media == 1) {
								$('.wap').show();
								$('.web').hide();
								$('.web input').removeAttr('checked');
							}

							if (data.rows.length > 10) {
								shwbtn = "<button type='button' class='btn purple' id='showMoreapi' onClick='moreapishow()'>Show More</button>";
								shwbtn += "<button type='button' class='btn purple hidden' id='showLessapi' onClick='lessapishow()'>Show Less</button>";

								$('#showmrlsapi').html(shwbtn);
							}
						}
					}
				});
	}
}

function getAds_Publishers(ads_publishers, ads_cost, id_country) {
	$('#ads_publishers_list').html('');
	$('#showmrls').html('');
	jQuery
			.ajax({
				url : domain
						+ "campaign_manager/ads_publishers/loadAds_PublishersSelect",
				dataType : "json",
				type : "POST",
				data : {
					'id_country' : id_country
				},
				beforeSend : loading('portlet-list'),
				success : function(data) {
					var result = '';
					var shwbtn = '';
					if (data.count) {
						var j = 0, k = 0, array1 = [], array2 = [];
						for ( var i = 0; i < data.rows.length; i++) {
							if (ads_publishers[data.rows[i]['id']]) {
								// alert(JSON.stringify(data.rows[i]));return
								// false;
								array1[j] = data.rows[i];
								j++;
							} else {
								array2[k] = data.rows[i];
								k++;
							}
						}
						data['rows'] = $.merge(array1, array2);
						for ( var i = 0; i < data.rows.length; i++) {
							var checked = "";
							var cost = "";
							var apcls = "";
							if (ads_publishers[data.rows[i]['id']]) {
								checked = "checked";
								cost = ads_cost[data.rows[i]['id']];
							}
							if (i > 9) {
								apcls = "afterten hidden";
							} else {
								apcls = "firstten";
							}
							result += "<div class='"
									+ apcls
									+ "'><label class='span6 m-wrap'><input type='checkbox' id='ads-publisher' class='ads_publisher' value='"
									+ data.rows[i]['id']
									+ "' "
									+ checked
									+ "> "
									+ data.rows[i]['name']
									+ "<span style='margin-right:150px; float:right;'>Cost/Ads : USD<input type='text' id='cost-"
									+ data.rows[i]['id']
									+ "' value='"
									+ cost
									+ "' style='width:100px' title='Cost Ads per Click/Acquisition' /></span></label></div>";
						}
						$('#ads_publishers_list').html(result);

						if (data.rows.length > 10) {
							shwbtn = "<button type='button' class='btn purple' id='showMorepub' onClick='morepubshow()'>Show More</button>";
							shwbtn += "<button type='button' class='btn purple hidden' id='showLesspub' onClick='lesspubshow()'>Show Less</button>";

							$('#showmrls').html(shwbtn);
						}
					}
				}
			});
}

function getCrossell(id_country, partner_service) {
	$('#operator_partner_list').html('');
	if (id_country != "") {
		jQuery
				.ajax({
					url : domain
							+ "campaign_manager/partners_services/loadPartners_ServicesSelect",
					dataType : "json",
					type : "POST",
					data : {
						'id_country' : id_country
					},
					beforeSend : loading('portlet-list'),
					success : function(data) {
						var result = '[ Operator ] [ Partner ] [ Price ] [ Shortcode ] [ SID ] [ Keyword ] ';
						if (data.count) {
							for ( var i = 0; i < data.rows.length; i++) {
								var checked = "";
								if (partner_service[data.rows[i]['id']])
									checked = "checked";
								result += "<label><input type='checkbox' id='partner-service' value='"
										+ data.rows[i]['id']
										+ "' "
										+ checked
										+ "> [ "
										+ data.rows[i]['operator_name']
										+ " ] [ "
										+ data.rows[i]['partner_name']
										+ " ] [ "
										+ data.rows[i]['price']
										+ " ] [ "
										+ data.rows[i]['shortcode']
										+ " ] [ "
										+ data.rows[i]['sid']
										+ " ] [ "
										+ data.rows[i]['keyword']
										+ " ] </label>";
							}
							$('#operator_partner_list').html(result);

						}
					}
				});
	}
}

function setPf(type) {
	if (type == '2') {
		jQuery('#expire_date').css('display', 'block');
	} else {
		jQuery('#expire_date_camp').val('');
		jQuery('#expire_date').css('display', 'none');
	}
}

function getDomainsList(id_country, domain_list) {

	// alert(id_country);
	$('#domain_list').html('');
	if (id_country != "") {
		jQuery
				.ajax({
					url : domain + "campaign_manager/domains/loadDomainsSelect",
					dataType : "json",
					type : "POST",
					data : {
						'id_country' : id_country
					},
					beforeSend : loading('portlet-list'),
					success : function(data) {
						/*
						 * alert(JSON.stringify(data, null, 4)); return false;
						 */
						var result = '';
						if (data.count) {
							for ( var i = 0; i < data.rows.length; i++) {
								var checked = "";
								if (domain_list[data.rows[i]['id']])
									checked = "checked";
								result += "<label><input type='checkbox' id='domain-list' class='domain_list' value='"
										+ data.rows[i]['id']
										+ "' "
										+ checked
										+ "> [ "
										+ data.rows[i]['name']
										+ " ] </label>";
							}
							$('#domain_list').html(result);

						}
					}
				});
	}

}

function morepubshow() {
	jQuery('.afterten').removeClass('hidden');
	jQuery('#showLesspub').removeClass('hidden');
	jQuery('#showMorepub').addClass('hidden');
}
function lesspubshow() {
	jQuery('.afterten').addClass('hidden');
	jQuery('#showLesspub').addClass('hidden');
	jQuery('#showMorepub').removeClass('hidden');
}

function moreapishow() {
	jQuery('.aftertenapi').removeClass('hidden');
	jQuery('#showLessapi').removeClass('hidden');
	jQuery('#showMoreapi').addClass('hidden');
}
function lessapishow() {
	jQuery('.aftertenapi').addClass('hidden');
	jQuery('#showLesspubapi').addClass('hidden');
	jQuery('#showMorepubapi').removeClass('hidden');
}

function changeStatus() {
	// alert(sid);return false;
	jQuery.ajax({
		url : domain + "partner_campaign_manager/campaigns/Status",
		dataType : "json",
		type : "POST",
		data : {
			'id' : sid,
			'edit_type' : edit_type
		},
		beforeSend : loading('portlet-list'),
		success : function(data) {
			// alert(data);return false;
			loadCampaigns(1);
		}

	});

}

function clearForm() {
	jQuery('#id').val('');
	jQuery('#name').val('');
	jQuery('#id_country').val('');
	jQuery('#id_campaign_media').val('');
	jQuery('#id_campaign_category').val('');
	jQuery('#id_banner').val('');
	jQuery('#id_template').val('');
	// jQuery('#acm_code').val('');
	jQuery('#description').val('');
	jQuery('#content').val('');
	jQuery('#use_confirmation').val('');
	jQuery('#status').val('');

	getAds_Publishers('', '', '');

	jQuery('#operator_partner_list').html('');

	inactiveTab('campaigns_form');
	activeTab('campaigns_list');
}

$(document).ready(function() {
	// Add events
	if($('#file_upload').length>0)
	$('#file_upload').on('change', prepareUpload);
	$('#alchemy_content_link').click(function(){
		    $('#alchemy_content').on('show', function () {

			$('iframe').attr("src",domain+'custom_files/alchemy_content.php');
		      
			});
		    $('#alchemy_content').modal({show:true});
		});
	
	$("#addbtn").click(function(e){
	if($('#iframe_content').contents().find(".content_ids:checked").length>0)
	{
	var r = confirm("Have you selected correct content id?");
	if(r==true) 
	{
	var content_ids = "";
	$('#iframe_content').contents().find(".content_ids:checked").each(function() {
        	content_ids += $(this).val()+',';
    	});
	content_ids = content_ids.replace(/^,|,$/g,'');
	$('#content').val(content_ids);
	$('#alchemy_content').modal('hide');	
	}
	else {	return; }
	
	}
	else {alert("Please click on search button and select content id(s)!"); return ;}
	});
	
	
	
	
	});


function saveBanner(){
	var id = jQuery('#banner_id').val(),
	name = jQuery('#banner_name').val(),
	url_thumb = jQuery('#url_thumb').val(),
	url_ori = jQuery('#url_ori').val(),
	path_thumb = jQuery('#path_thumb').val(),
	status = jQuery('#status').val(),
	path_ori = jQuery('#path_ori').val(),
	description = jQuery('#banner_name').val();

	if(name == '')
	{
		alert('Please enter banner name.');
		return false;
	}
	else if(url_thumb == '')
	{
		alert('Please upload banner.');
		return false;
	}
		
	jQuery.ajax({
		url:  domain+"campaign_manager/banners/saveBanners",
		dataType: "json",
		type: "POST",
		data: {
				'id' : id,
				'name' : name,
				'url_thumb' : url_thumb,
				'url_ori' : url_ori,
				'path_thumb' : path_thumb,
				'status' : status,
				'path_ori' : path_ori,
				'description' : description
			  },
		beforeSend: loading('portlet-form'),
		success: function(data) {
			console.log(JSON.stringify(data));
			if(data.duplicat_data) 
			{
				success.hide();
				alert(data.errors_message);
			}
			if(data.success){
				jQuery('#id_banner').append('<option value="'+data.id+'" selected="selected">'+name+'</option>');
				jQuery('#add_banner').modal('hide');
			} else {
				alert('Banner not added successfully.');
			}
		}
	 });
}

function prepareUpload(event)
{
	files = event.target.files;
	
	uploadFiles(event);
}

function uploadFiles(event)
{
	event.stopPropagation(); // Stop stuff happening
	event.preventDefault(); // Totally stop stuff happening

	// START A LOADING SPINNER HERE

	// Create a formdata object and add the files
	var data = new FormData();
	$.each(files, function(key, value)
	{
		data.append(key, value);
	});
	
	var form = $('#formbanners');
	var error = $('.alert-error-upload', form);
    	var success = $('.alert-success-upload', form);
	
	$.ajax({
		url: domain+"campaign_manager/banners/uploadBackgroundGallery?files",
		type: 'POST',
		data: data,
		cache: false,
		dataType: 'json',
		processData: false, // Don't process the files
		contentType: false, // Set content type to false as jQuery will tell the server its a query string request
		success: function(data, textStatus, jqXHR)
		{
			if(data.status){
				jQuery('#path_thumb').val(data.path['thumb']);
				jQuery('#path_ori').val(data.path['ori']);
				jQuery('#url_ori').val(data.url['ori']);
				jQuery('#url_thumb').val(data.url['thumb']);
				jQuery('#img-upload').html('<img src="'+data.host+data.url['thumb']+'" width="100" />');
				success.show();
				error.hide();
			} else {
				success.hide();
                		error.show();
				alert(data.error);
			}
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log('ERRORS: ' + textStatus);
			// STOP LOADING SPINNER
		}
	});
}
function setUseCofirmation(obj,data,status)
{
	if(data.use_confirmation == 0)
		alert('Please Enable Use Confirmation page for the Campaign first.');
	else
	{
		jQuery(obj).hide();
		jQuery(obj).parent().find('.ajax_loading').show();
		var camp_data = JSON.stringify(data);
		jQuery.ajax({
			url:  domain+"campaign_manager/campaigns/setUseConfirmation",
			dataType: "json",
			type: "POST",
			data: {
					'data' : data,
					'status' : status
				  },
			success: function(data) {
				if(data.success){
					if(status == 1)
					{
						alert('Use Confirmation page enabled successfully.');
						$(obj).parent().html("<a href='javascript:void(0)' class='btn blue mini active' title='Disable Use Confirmation Page' onclick='setUseCofirmation(this,"+camp_data+",0)'>Disable</a><img class='ajax_loading' src='"+domain+"assets/img/metronic/ajax-loading.gif' style='display:none;'/>");
						jQuery(obj).find('.ajax_loading').hide();
						jQuery(obj).show();
					}
					else
					{
						alert('Use Confirmation page disabled successfully.');
						$(obj).parent().html("<a href='javascript:void(0)' class='btn blue mini active' title='Enable Use Confirmation Page' onclick='setUseCofirmation(this,"+camp_data+",1)'>Enable</a><img class='ajax_loading' src='"+domain+"assets/img/metronic/ajax-loading.gif' style='display:none;'/>");
						jQuery(obj).find('.ajax_loading').hide();
						jQuery(obj).show();
					}
				} else {
					if(status == 1)
						alert('Use Confirmation page not enabled successfully.');
					else
						alert('Use Confirmation page not disabled successfully.');
					jQuery(obj).find('.ajax_loading').hide();
					jQuery(obj).show();
				}
			}
		});
	}
}
