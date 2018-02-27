				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="tipeuser_list active"><a href="#tipeuser_list" data-toggle="tab">TipeUser List</a></li>
                        <li class="tipeuser_form "><a href="#tipeuser_form" data-toggle="tab">TipeUser Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="tipeuser_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>TipeUser List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="tipeuser_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>TipeUser Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formTipeUser" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<!--<div class="control-group">
										<label class="control-label">ID<span class="required">*</span></label>
										<div class="controls">
											<input type="hidden" name="id" id="id" readonly class="span6 m-wrap"/>
										</div>
									</div>-->
									<input type="hidden" name="id" id="id" readonly class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Nama TipeUser<span class="required">*</span></label>
										<div class="controls">
											<input name="nama_tipe_user" id="nama_tipe_user" maxlength="50" type="text" data-required="1" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="form-actions">
										<button type="<?php echo ($accessAdd)?'submit':'button';?>" <?php echo ($accessAdd)?'':'disabled="disabled"';?> class="btn purple">Save</button>
										<button type="button" class="btn" onclick="clearForm()">Cancel</button>
									</div>
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
    
    <script src="assets/js/metronic/app/parameter/tipeuser.js" type="text/javascript"></script>

	<script type="text/javascript">
		var deleteID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadTipeUser();
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onclick="deleteTipeUser()">Delete</button>
        </div>
    </div>
