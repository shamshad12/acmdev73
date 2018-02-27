$(function(){
	//$('.date-range').daterangepicker();
	jQuery('.portlet .tools a.remove').click(function () {
		var removable = jQuery(this).parents(".portlet");
		if (removable.next().hasClass('portlet') || removable.prev().hasClass('portlet')) {
			jQuery(this).parents(".portlet").remove();
		} else {
			jQuery(this).parents(".portlet").parent().remove();
		}
	});

	jQuery('.portlet .tools .collapse, .portlet .tools .expand').click(function () {
		var el = jQuery(this).parents(".portlet").children(".portlet-body");
		if (jQuery(this).hasClass("collapse")) {
			jQuery(this).removeClass("collapse").addClass("expand");
			el.slideUp(200);
		} else {
			jQuery(this).removeClass("expand").addClass("collapse");
			el.slideDown(200);
		}
	});
	
	
});

function loading(id){
	var el = jQuery("."+id).parents(".tabbable");
	App.blockUI(el);
	window.setTimeout(function () {
		App.unblockUI(el);
	}, 1000);
}

function remove_toggle(id){
	jQuery('.'+id).toggle('slow');
}

function close_dialog(id){
	$('#'+id).dialog("close");
}

function activeTab(id){
	jQuery('.'+id).addClass('active');
	jQuery('#'+id).addClass('active');
} 

function inactiveTab(id){
	jQuery('.'+id).removeClass('active');
	jQuery('#'+id).removeClass('active');
}

function handleTables(){
	//Data table
	if (!jQuery().dataTable) {
		return;
	}

	// begin first table
	$('#group-list').dataTable({
		"aLengthMenu": [
			[10, 25, 50, -1],
			[5, 15, 20, "All"]
		],
		// set the initial value
		"iDisplayLength": 5,
		"sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page",
			"oPaginate": {
				"sPrevious": "Prev",
				"sNext": "Next"
			}
		},
		"aoColumnDefs": [{
			'bSortable': false,
			'aTargets': [0]
		}]
	});

	jQuery('#group-list .group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).attr("checked", true);
			} else {
				$(this).attr("checked", false);
			}
		});
		jQuery.uniform.update(set);
	});

	jQuery('#group-list_wrapper .dataTables_filter input').addClass("m-wrap medium"); // modify table search input
	jQuery('#group-list_wrapper .dataTables_length select').addClass("m-wrap xsmall"); // modify table per page dropdown
	//End of Data Table	
}
