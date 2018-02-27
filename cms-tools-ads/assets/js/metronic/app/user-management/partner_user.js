function loadUser(page){
    
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">ID</th><th class="hidden-phone">Karyawan</th><th class="hidden-phone">Username</th><th class="hidden-phone">Partner</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
		
	jQuery.ajax({
			url:  domain+"user_management/partner_user/loadUser",
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
                                    var edit='disabled="disabled"';
                                    if(accessEdit=='1')
                                            edit = 'onclick="configure(\''+data.rows[i]['id']+'\')"';
                                    var del='disabled="disabled"';
                                   // if(accessDelete=='1')
                                     //       del = 'data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['id']+'\'"';

                                    result  += '<tr class="odd gradeX"><td>'+data.rows[i]['id']+'</td><td>'+data.rows[i]['nama_karyawan']+'</td><td>'+data.rows[i]['username']+'</td><td>'+data.rows[i]['partner_name']+'</td><td>'+data.rows[i]['status']+'</td><td><a '+edit+' style="cursor:pointer" class="btn green mini">Configure Partner</a></td></tr>';			
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
            var form = $('#formUser');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    partner_id: {
                        required: true
                    }/*,
                    shortcode: {
                        required: true
                    },
                    keyword: {
                        required: true
                    }*/
                },

                invalidHandler: function (event, validator) { //display error alert on form submit  
		    //alert(JSON.stringify(event)+'----'+JSON.stringify(validator));            
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
			//false;
			$('.custom_error').remove();
			var error_html = '<div class="control-group error custom_error" style="margin-bottom:0;"><span class="help-inline ok">This field is required.</span></div>';
			if(!($('.shortcode_name').is(":checked")))
			{
				$('#shortcode_div').prepend(error_html);
				return false;
			}
			if(!($('.keyword').is(":checked")))
			{
				$('#keyword_div').prepend(error_html);
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

function configure(id){
    //jQuery(".partner_data").each(function() {
        jQuery('.checker').find('.checked').removeClass("checked");
        //});
        
    jQuery.ajax({
            url:  domain+"user_management/partner_user/getUserData",
            dataType: "json",
            type: "POST",
                        async:false,
            data: {
                    'id' : id
                  },
            beforeSend: loading('portlet-form'),
            success: function(data) {
                                   // alert(data.partner_access_level.country.length);
                                    if(data.count){
                                            jQuery('#id').val(data.id);
                                            jQuery('#employee_name').val(data.username);
                                            if(data.partner_access_level!=null)
                                            {
                                            //partner_ids
                                            if(data.partner_access_level.partner_ids!=null)
                                            {
                                                for(var i=0;i<data.partner_access_level.partner_ids.length;i++)
                                                {
                                                    var temp_id=data.partner_access_level.partner_ids[i]+'_'+data.partner_access_level.country[i];
                                                    jQuery('#partner_'+temp_id).parent().addClass("checked");
                                                    jQuery('#partner_'+temp_id).attr("checked","checked");
                                                }
                                                getshortcode();                                                
                                            }
                                            //shortcode
                                            if(data.partner_access_level.shortcode!=null)
                                            {
                                                for(var i=0;i<data.partner_access_level.shortcode_ids.length;i++)
                                                {
                                                    jQuery('#shortcode_'+data.partner_access_level.shortcode_ids[i]).attr("checked","checked");
                                                }
                                            }
                                            //keyword
                                            getKeywordsList();
                                            if(data.partner_access_level.keyword!=null)
                                            {
                                                for(var i=0;i<data.partner_access_level.keyword.length;i++)
                                                {
                                                   // jQuery('#shortcode_'+data.partner_access_level.shortcode[i]).attr("checked","checked");
                                                    jQuery('#keyword_'+data.partner_access_level.keyword[i].replace('*','')).attr("checked","checked");
                                                }
                                            }
                                            //domain
                                            getdomain();
                                            if(data.partner_access_level.domain!=null)
                                            {
                                                for(var i=0;i<data.partner_access_level.domain.length;i++)
                                                { 
                                                    var dom_id = data.partner_access_level.domain[i];
                                                    jQuery('#domain_'+dom_id).parent().addClass("checked");
                                                    jQuery('#domain_'+dom_id).attr("checked","checked");
                                                }
                                            }
                                            //ads Publisher
                                            getadspublisher();
                                            if(data.partner_access_level.ads_publisher!=null)
                                            {
                                                for(var i=0;i<data.partner_access_level.ads_publisher.length;i++)
                                                { 
                                                    var ads_id = data.partner_access_level.ads_publisher[i];
                                                    jQuery('#ads_publisher_'+ads_id).parent().addClass("checked");
                                                    jQuery('#ads_publisher_'+ads_id).attr("checked","checked");
                                                }
                                            }
                                            
                                            /*end*/
                                        }
                                        inactiveTab('user_list');
                                        activeTab('user_form');
                                    } else {
                                        jQuery('#id').val(data.id);
                                        inactiveTab('user_list');
                                        activeTab('user_form');
                                    }
                }
           });  
}
function save(){
            var id = jQuery('#id').val();
            var shortcode = new Array();
            var shortcode_ids = new Array();
            var partner_ids = new Array();
            var partner_name = new Array();
            var country = new Array();
            var keyword = new Array();
            var domain_data = new Array();
            var ads_publisher_data = new Array();
                
            jQuery(".shortcode_name:checked").each(function() {
                shortcode_data = jQuery(this).val().split('_');
                shortcode.push(shortcode_data[0]);
                shortcode_ids.push(shortcode_data[1]);
            });
    
            jQuery(".partner_data:checked").each(function() {
                var partner_country_data = jQuery(this).val().split('_');
                    partner_ids.push(partner_country_data[0]);
                    country.push(partner_country_data[1]);
                    partner_name.push(partner_country_data[2]);
            });

        jQuery(".keyword:checked").each(function() {
                keyword.push($(this).val());
        });
        jQuery(".domain_data:checked").each(function() {
                domain_data.push($(this).val());
        }); 
        jQuery(".ads_publisher_data:checked").each(function() {
                ads_publisher_data.push($(this).val());
        }); 
        //console.log("========>"+ads_publisher_data);
        var form = $('#formUser');
        var error = $('.alert-error-save', form);
        var success = $('.alert-success-save', form);
        
        jQuery.ajax({
            url:  domain+"user_management/partner_user/saveUser",
            dataType: "json",
            type: "POST",
            data: {
                    'id' : id,
                    'country':country,
                    'shortcode':shortcode,
                    'keyword':keyword,
                    'partner_ids':partner_ids,
                    'partner_name':partner_name,
                    'shortcode_ids':shortcode_ids,
                    'domain':domain_data,
                    'ads_publisher':ads_publisher_data
                  },
            beforeSend: loading('portlet-form'),
            success: function(data) {
                        if(data.success){
                                success.show();
                                error.hide();   
                                loadUser(1);
                                clearForm();
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

function deleteUser(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"user_management/partner_user/deleteUser",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadUser(1);
										}
			   });	
	}
}


//get domain
function getdomain(){
    var id_country = Array();

    jQuery(".partner_data:checked").each(function() {
        data=$(this).val().split('_');
            id_country.push(data[1]);
    });
    jQuery.ajax({
        url:  domain+"user_management/partner_user/loadDomain",
        dataType: "json", 
        type: "POST",
        async:false,
        data: {
          'id_country' : id_country
           },
        beforeSend: loading('portlet-list'), 
        success: function(data) { 
        //alert(data);
        jQuery('#domain_div').html(data);
        selected_ads_domain();
       }
      }); 
}

//get ads_publisher
function getadspublisher(){
    var id_country = Array();
    jQuery(".partner_data:checked").each(function() {
        data=$(this).val().split('_');
            id_country.push(data[1]);
    });
    jQuery.ajax({
        url:  domain+"user_management/partner_user/loadAdsPublisher",
        dataType: "json", 
        type: "POST",
        async:false,
        data: {
          'id_country' : id_country
           },
        beforeSend: loading('portlet-list'), 
        success: function(data) { 
        //alert(data);
        jQuery('#ads_publisher_div').html(data);
        selected_ads_domain();
       }
      }); 
}


function clearForm(){
	jQuery('#id').val('');
	jQuery('#karyawan_id').val('');
	jQuery('#username').val('');
	jQuery('#userpass').val('');
	jQuery('#tipe_user_id').val('');
	jQuery('#status').val('');
	
	inactiveTab('user_form');
	activeTab('user_list');
}

function getshortcode(){
	var partner_ids = Array();
	var shortcode = Array();
	//var keyword = Array();

	jQuery(".partner_data:checked").each(function() {
	    	data=$(this).val().split('_');
            	partner_ids.push(data[0]);
        });

	jQuery(".shortcode_name:checked").each(function() {
	    	data=$(this).val().split('_');
            	shortcode.push(data[1]);
        });
 
  	jQuery.ajax({
	    url:  domain+"user_management/partner_user/loadShortcode",
	    dataType: "json", 
	    type: "POST",
	    async:false,
	    data: {
	      'partner_ids' : partner_ids
	       },
	    beforeSend: loading('portlet-list'), 
	    success: function(data) { 
		//alert(data);
		jQuery('#shortcode_div').html(data);
		//jQuery('#id_shortcode').val(id_shortcode);
		for(var j=0;j<shortcode.length;j++)
		{
			jQuery('#shortcode_'+shortcode[j]).attr("checked","checked");
		} 
		
		getKeywordsList();
	   }
      }); 
}


function selected_ads_domain(){
    jQuery.ajax({
            url:  domain+"user_management/partner_user/getUserData",
            dataType: "json",
            type: "POST",
                        async:false,
            data: {
                    'id' : jQuery('#id').val()
                  },
            beforeSend: loading('portlet-form'),
            success: function(data) {
                                   // alert(data.partner_access_level.country.length);
                    if(data.count){
                            if(data.partner_access_level!=null)
                            {                                            
                            //domain
                            //getdomain();
                            if(data.partner_access_level.domain!=null)
                            {
                                for(var i=0;i<data.partner_access_level.domain.length;i++)
                                { 
                                    var dom_id = data.partner_access_level.domain[i];
                                    jQuery('#domain_'+dom_id).parent().addClass("checked");
                                    jQuery('#domain_'+dom_id).attr("checked","checked");
                                }
                            }
                            //ads Publisher
                            //getadspublisher();
                            if(data.partner_access_level.ads_publisher!=null)
                            {
                                for(var i=0;i<data.partner_access_level.ads_publisher.length;i++)
                                { 
                                    var ads_id = data.partner_access_level.ads_publisher[i];
                                    jQuery('#ads_publisher_'+ads_id).parent().addClass("checked");
                                    jQuery('#ads_publisher_'+ads_id).attr("checked","checked");
                                }
                            }
                            
                            /*end*/
                        }
                        //inactiveTab('user_list');
                        //activeTab('user_form');
                    } else {
                        // jQuery('#id').val(data.id);
                        // inactiveTab('user_list');
                        // activeTab('user_form');
                    }
                }
           });
}


function getKeywordsList()
{
    	var keyword = new Array();
	var shortcode = new Array();

	jQuery(".keyword:checked").each(function() {
        	keyword.push($(this).val());
	});
    	
        jQuery(".shortcode_name:checked").each(function() {
            data=$(this).val().split('_');
            shortcode.push(data[1]);
        });

	jQuery.ajax({
                url:  domain+"user_management/partner_user/getKeywordList",
                dataType: "json",
                type: "POST",
                async:false,
                data: {
			'shortcode' : shortcode
                          },
                beforeSend: loading('portlet-list'),
                success: function(data) {
                   if(data.count == true)
		   {
		            var new_html='';
		            for(var i=0;i<data.row.length;i++)
		            {
		                new_html+='<div style="width:20%;float:left"><input type="checkbox" name="keyword" class="keyword" id="keyword_'+data.row[i]['keyword'].replace('*','')+'" value="'+data.row[i]['keyword']+'">'+data.row[i]['keyword']+'</div>';       
		            }
		            
		            jQuery("#keyword_div").html(new_html);
		            for(var j=0;j<keyword.length;j++)
		            {
		                jQuery('#keyword_'+keyword[j]).attr("checked","checked");
		            }
		   }
		   else
			jQuery("#keyword_div").html(''); 
                }
                                     
           });
}


