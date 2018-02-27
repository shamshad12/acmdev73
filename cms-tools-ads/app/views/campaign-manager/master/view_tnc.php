				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="templates_form active"><a href="#templates_form" data-toggle="tab">TNC & Supported Mobile</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="templates_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>TNC & Supported Mobile</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formtemplates" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<input name="is_uploaded" id="is_uploaded" maxlength="50" type="hidden" class="span6 m-wrap"/>
									
									<div class="control-group" id="btn-confirm">
										<label class="control-label">Terms & Conditions<span class="required">*</span></label>
										<div class="controls">
											<a href="http://54.169.14.129/ads/general/editor.php?data=dG5jLmh0bWw=" class="span1 btn blue" id="btn_page_confirm" target="_blank">Edit</a>
										</div>
									</div>
									<div class="control-group" id="btn-status">
										<label class="control-label">Supported Phone<span class="required">*</span></label>
										<div class="controls">	
											<a href="http://54.169.14.129/ads/general/editor.php?data=c3VwcG9ydC5odG1s" class="span1 btn blue" id="btn_page_status" target="_blank">Edit</a>
										</div>
									</div>
									<!--div class="form-actions">
										<button type="<?php //echo ($accessAdd)?'submit':'button';?>" <?php //echo ($accessAdd)?'':'disabled="disabled"';?> class="btn purple">Save</button>
										<button type="button" class="btn" onClick="clearForm()">Cancel</button>
									</div-->
								</form>
								<!-- END FORM-->
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
						</div>
					</div>
				</div>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="assets/js/metronic/plugins/data-tables/jquery.dataTables.js" type="text/javascript"></script>  
    <script src="assets/js/metronic/plugins/data-tables/DT_bootstrap.js" type="text/javascript"></script>
    <script src="assets/js/metronic/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
	<script src="assets/js/metronic/plugins/jquery-validation/dist/additional-methods.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="assets/js/ckeditor/ckeditor.js" type="text/javascript"></script> 
    <!-- END PAGE LEVEL PLUGINS -->
    
    <script src="assets/js/metronic/app/campaign-manager/master/templates.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadTemplates(1);
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
        
		CKEDITOR.replace( 'page_confirm', {
			fullPage: true,
			allowedContent: true,
			extraPlugins: 'wysiwygarea'
		});
		
		CKEDITOR.replace( 'page_status', {
			fullPage: true,
			allowedContent: true,
			extraPlugins: 'wysiwygarea'
		});
		
		CKEDITOR.replace( 'page_error', {
			fullPage: true,
			allowedContent: true,
			extraPlugins: 'wysiwygarea'
		});
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteTemplates()">Delete</button>
        </div>
    </div>
