var Main = function () {
    return {

        //main function to initiate the module
        init: function () {
	        $("#logout-confirm").dialog({
              dialogClass: 'ui-dialog-blue',
              autoOpen: false,
              resizable: false,
              height: 180,
              modal: true,
              buttons: [
                {
                    'class' : 'btn red',	
                    "text" : "Logout",
                    click: function() {
                        window.location.href = domain+"login/out";
                    }
                },
                {
                    'tabIndex': -1,
                    'class' : 'btn',
                    "text" : "Cancel",
                    click: function() {
                        $(this).dialog( "close" );
                    }
                }
              ]
	        });

            $( "#logout").click(function() {
              $( "#logout-confirm" ).dialog( "open" );
               $('.ui-dialog button').blur();// avoid button autofocus
            });
        },
		tableSorting:function() {
			$('#group-list').dataTable({
											"bPaginate": false,
											"bInfo": false,
											"bFilter": false,
											"bLengthChange": false
										});
		$("#group-list_wrapper div.row-fluid").css("display","none");
		}
    };

}();
