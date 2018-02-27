var files;

function loadContent(page){		
	var limit  = $('#limit').val(),
		search = $('#search').val(),
		language = $('#language-list').val(),
		html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Code</th><th class="hidden-phone">Title</th><th class="hidden-phone">Short Description</th><th class="hidden-phone">Status</th><th class="hidden-phone">Action</th></tr></thead><tbody id="data-table">';
	
	jQuery.ajax({
			url:  domain+"backend/content/loadContent",
			dataType: "json",
			type: "POST",
			data: {
					'limit' : limit,
					'page'  : page,
					'search': search,
					'language': language
				  },
			//beforeSend: progress_jenis_user_list,
			success: function(data) {
										jQuery('#table-append').html('');
										var result = '';
										if(data.count){
											for(var i=0; i<data.rows.length; i++){
												result  += '<tr class="odd gradeX"><td>'+data.rows[i]['code']+'</td><td>'+data.rows[i]['title']+'</td><td>'+data.rows[i]['short_description']+'</td><td>'+data.rows[i]['status']+'</td><td width="100"><a onclick="edit(\''+data.rows[i]['code']+'\')" style="cursor:pointer" class="btn green mini">Edit</a> <a  style="cursor:pointer" class="btn red mini active" data-toggle="modal" href="#delete" onclick="deleteID=\''+data.rows[i]['code']+'\'">Delete</a></td></tr>';			
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
            var form = $('#formcontent');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    code: {
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

function edit(code){
	jQuery.ajax({
			url:  domain+"backend/content/getContentData",
			dataType: "json",
			type: "POST",
			data: {
					'code' : code
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.count){
											jQuery('#code').val(data.code);
											
											var lang = language.split(',');
											for(var i=0;i<lang.length;i++){
												jQuery('#title-'+lang[i]).val(data.title[lang[i]]);
												jQuery('#description-'+lang[i]).val(data.description[lang[i]]);		
												jQuery('#short_description-'+lang[i]).val(data.short_description[lang[i]]);
											}
											
											jQuery('#description').val(data.description);
											jQuery('#status').val(data.status);	
											
											inactiveTab('content_list');
											activeTab('content_form');
										} else {
											
										}
									}
		   });	
}

function save(){
	var code = jQuery('#code').val(),
		status = jQuery('#status').val(),
		lang = language.split(','),
		title = '',
		short_description = '',
		description = '';
		
	for(var i=0;i<lang.length;i++){
		var separator = (i!=0)?'##':'';
		title += separator+jQuery('#title-'+lang[i]).val();
		short_description += separator+jQuery('#short_description-'+lang[i]).val();
		description += separator+jQuery('#description-'+lang[i]).val();
	};
	
	var form = $('#formcontent');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"backend/content/saveContent",
			dataType: "json",
			type: "POST",
			data: {
					'language' : language,
					'title' : title,
					'short_description' : short_description,
					'description' : description,
					'code' : code,
					'status' : status
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											loadContent(1);
											clearForm();
											
											inactiveTab('content_form');
											activeTab('content_list');
										} else {
											success.hide();
                    						error.show();
										}
									}
		   });	
}

function setDeleteID(code){
	deleteID = 	code;
}

function deleteContent(){
	if(deleteID != ""){
		jQuery.ajax({
				url:  domain+"backend/content/deleteContent",
				dataType: "json",
				type: "POST",
				data: {
						'code' : deleteID
					  },
				beforeSend: loading('portlet-list'),
				success: function(data) {
											loadContent(1);
										}
			   });	
	}
}

function clearForm(){
	jQuery('#status').val('');
	jQuery('#code').val('');
	
	var lang = language.split(',');
	for(var i=0;i<lang.length;i++){
		jQuery('#title-'+lang[i]).val('');
		jQuery('#short_description-'+lang[i]).val('');
		jQuery('#description-'+lang[i]).val('');
	};
		
	inactiveTab('content_form');
	activeTab('content_list');
}

function prepareUpload(event)
{
	files = event.target.files;
	
	uploadFiles(event);
}

jQuery(document).ready(function() {	
	// Add events
});
