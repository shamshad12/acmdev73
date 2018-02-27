				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="telcos_list active"><a href="#telcos_list" data-toggle="tab">Telcos List</a></li>
                        <li class="telcos_form "><a href="#telcos_form" data-toggle="tab">Telcos Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="telcos_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Telcos List</div>
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
									<input class="m-wrap" id="search" name="search"  placeholder="Search by Id, Telco name, Telco code, MSISDN prefix and status" title="Search by Id, Telco name, Telco code, MSISDN prefix and status" type="text">
									<button class="btn green" type="button" onclick="loadTelcos(1)">Search!</button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="telcos_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Telcos Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formtelcos" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="country_id" id="country_id" class="span6 m-wrap">		
												<option value="">--Country--</option>
												<?php 
												if($country['count']){ 
													for($i=0; $i<count($country['rows']); $i++){
												?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php 
													}
												} ?>
											</select>
											<span class="help-block"></span>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Telco Code<span class="required">*</span></label>
										<div class="controls">
											<input name="code" id="code" maxlength="25" type="text" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Telco Name<span class="required">*</span></label>
										<div class="controls">	
											<input name="name" id="name" maxlength="50" type="text" class="span6 m-wrap"/>
											<span class="help-block" id='img-upload'></span>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Description<span class="required">*</span></label>
										<div class="controls">
											<input name="description" id="description" maxlength="255" type="text" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Prefix Number<span class="required">*</span></label>
										<div class="controls">
											<input name="prefix_list" id="prefix_list" maxlength="25" type="text" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">
											<select name="status" id="status" class="span6 m-wrap">		
												<option value="">--Status--</option>										
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
											<span class="help-block"></span>
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
    
    <script src="assets/js/metronic/app/zing-wallet/master/telcos.js" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadTelcos(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteTelcos()">Delete</button>
        </div>
    </div>
