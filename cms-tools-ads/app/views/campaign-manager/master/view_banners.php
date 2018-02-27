				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="banners_list active"><a href="#banners_list" data-toggle="tab">Banners List</a></li>
                        <li class="banners_form "><a href="#banners_form" data-toggle="tab">Banners Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="banners_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Banners List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								 <div class="btn-group">								
									<select class="m-wrap" id="limit" name="limit" style="width:60px;">
										<option value='5'>5</option>
										<option value='10'>10</option>
										<option value='25'>25</option>
										<option value='50'>50</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Id, Name, URL or path" title="Search by Id, Name, URL or path">
									<button class="btn green" type="button" onclick="loadBanners(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="banners_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Banners Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formbanners" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<input name="path_thumb" id="path_thumb" type="hidden" class="span2 m-wrap"/>
									<input name="path_ori" id="path_ori" type="hidden" class="span2 m-wrap"/>
									<input name="url_thumb" id="url_thumb" type="hidden" class="span6 m-wrap"/>
									<input name="url_ori" id="url_ori" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Banners Name<span class="required">*</span></label>
										<div class="controls">
											<input name="name" id="name" maxlength="25" type="text" class="span6 m-wrap" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Description<span class="required">*</span></label>
										<div class="controls">	
											<input name="description" id="description" type="text" class="span6 m-wrap"/>
										</div>
									</div>								
									<div class="control-group">
										<label class="control-label">Banner<span class="required">*</span></label>
										<div class="controls">
											<input type="file" name="file_upload" id="file_upload" multiple  class="span3 m-wrap">
											<span class="help-block" id='img-upload'></span>
											
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">	
											<select name="status" id="status" class="span6 m-wrap">
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
										</div>
									</div>	
									<div class="form-actions">
										<button type="<?php echo ($accessAdd)?'submit':'button';?>" <?php echo ($accessAdd)?'':'disabled="disabled"';?> class="btn purple">Save</button>
										<button type="button" class="btn" onClick="clearForm()">Cancel</button>
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
    
    <script src="assets/js/metronic/app/campaign-manager/master/banners.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadBanners(1);
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteBanners()">Delete</button>
        </div>
    </div>
