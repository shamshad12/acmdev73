    function loadAssignfilter(page){	 
     //alert('page');return false;
	var limit  = $('#limit').val(), 
		search = $('#search').val(), 
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Country</th><th class="hidden-phone">Description</th><th class="hidden-phone">Perday/Perweek Activation</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	     
	jQuery.ajax({
			url:  domain+"campaign_manager/assignfilter/loadAssignfilter",  
                       
			dataType: "json", 
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
                     //  alert(data);return false; 
										jQuery('#table-append').html('');
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												var edit='';
												if(accessEdit=='1') 
													edit = '<a onclick="edit(\''+data.rows[i]['id']+'\')" style="cursor:pointer" class="btn green mini">Edit</a>'; 
												var del='';
												if(accessDelete=='1')
													del = '';
													
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['co_name']+'</td><td>'+data.rows[i]['so_name']+'</td><td>'+data.rows[i]['validdays']+'</td><td width="100">'+edit+' '+del+'</td></tr>';			
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
                    id_listtype: {
                        required: true
                    },
                    validdays: { 
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
			url:  domain+"campaign_manager/assignfilter/getAssignfilterData", 
			dataType: "json",
			type: "POST",
			data: {
					'id' : id
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#id').val(data.id);
											jQuery('#id_listtype').val(data.id_listtype);  
											jQuery('#id_country').val(data.id_country);
                                                                                        jQuery('#validdays').val(data.validdays);
											inactiveTab('partners_list');
											activeTab('partners_form');
										} else {
											
										}
									}
		   });	
}

function save(){
    //alert(id_listtype);return false;
    
	        var id = jQuery('#id').val(),
		id_listtype = jQuery('#id_listtype').val(),
                id_country = jQuery('#id_country').val(),
                 validdays = jQuery('#validdays').val();
		
	
	var form = $('#formpartners');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"campaign_manager/assignfilter/saveAssignfilter",  
			dataType: "json", 
			type: "POST",
                       
			data: {
                            
					'id' : id,
					'id_listtype' : id_listtype,
					'id_country' : id_country,
                                        'validdays' : validdays
					
				  },
                                  
                                   
			beforeSend: loading('portlet-form'),
                        
			success: function(data) {
                           /// alert('data');return false;  
                           if(data.duplicat_data)
										{
											success.hide();
                    						alert(data.errors_message)
										}

										if(data.success){
											success.show();
                    						error.hide();	
											 loadAssignfilter(1);
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

function deleteAssignfilter(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"campaign_manager/assignfilter/deleteAssignfilter",
				dataType: "json",
				type: "POST",
				data: {
						'id' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
                                 
											loadAssignfilter(1);
										}
			   });	
	} 
}

function clearForm(){
  
	jQuery('#id').val('');
        
	jQuery('#list_name').val('');
	jQuery('#id_country').val('');  
        jQuery('#validdays').val('');  
	
	
		
	inactiveTab('partners_form');
	activeTab('partners_list');
}

jQuery(document).ready(function() {	
	// Add events
});
 