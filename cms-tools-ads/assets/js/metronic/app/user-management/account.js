var FormValidation = function () {
    return {
        init: function () {
            var form = $('#formAccount');
            var error = $('.alert-error', form);
            var success = $('.alert-success', form);

            form.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-inline', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",
                rules: {
                    username: {
                        required: true
                    },
                    old_userpass: {
                        required: true
                    },
                    userpass: {
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

function save(){
	var id = jQuery('#id').val(),
		username = jQuery('#username').val(),
		old_userpass = jQuery('#old_userpass').val(),
		userpass = jQuery('#userpass').val();
	
	var form = $('#formAccount');
	var error = $('.alert-error-save', form);
    var success = $('.alert-success-save', form);
		
	jQuery.ajax({
			url:  domain+"user_management/account/saveAccount",
			dataType: "json",
			type: "POST",
			data: {
					'id' : id,
					'username' : username,
					'userpass' : userpass,
					'old_userpass' : old_userpass
				  },
			beforeSend: loading('portlet-form'),
			success: function(data) {
										if(data.success){
											success.show();
                    						error.hide();	
											//loadAccount();
											clearForm();
										} else {
											success.hide();
                    						error.show();
										}
									}
		   });	
}

function clearForm(){
	jQuery('#userpass').val('');
	jQuery('#old_userpass').val('');
}
