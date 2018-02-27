				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="sms_gateway_list active"><a href="#sms_gateway_list" data-toggle="tab">Bulk Connectivity List</a></li>
                        <li class="sms_gateway_form"><a href="#sms_gateway_form" data-toggle="tab">Bulk Connectivity Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="sms_gateway_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Bulk Connectivity List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	<div class="btn-group">								
									<select class="m-wrap" id="search_country" name="search_country" style="width:110px;">
										<option value="">- Country -</option>
										<?php for($i=0; $i<count($country['rows']); $i++){ ?>
										<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_operator" name="search_operator" style="width:110px;">
										<option value="">- Operator -</option>
										<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
										<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>

								  <div class="btn-group">								
									<select class="m-wrap" id="search_shortcode" name="search_shortcode" style="width:100px;">
										<option value="">- Shortcode -</option>
										<?php for($i=0; $i<count($shortcode['rows']); $i++){ ?>   
										<option value="<?php echo $shortcode['rows'][$i]['code'];?>"><?php echo $shortcode['rows'][$i]['code'];?></option>
										<?php } ?>
									</select>
								 </div> 
								 
								 <div class="btn-group">								
									<select class="m-wrap" id="limit" name="limit" style="width:60px;">
										<option value='5'>5</option>
										<option value='10'>10</option>
										<option value='25'>25</option>
										<option value='50'>50</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Id, API name, Operator name or Country name" title="Search by Id, API name, Operator name or Country name">
									<button class="btn green" type="button" onclick="loadGateway(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="sms_gateway_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Bulk Connectivity Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="sms_gateway" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="country_id" id="country_id" class="span6 m-wrap" onchange="loadOperator(this.value)">
												<option value="">- Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
                                        <label class="control-label">Operator<span class="required">*</span></label>
                                        <div class="controls" id="select-append">
                                            <select name="operator_id" id="operator_id" class="span6 m-wrap">
                                             <option value="">- Choose Operator -</option>
                                            </select>
                                        </div>
                                    </div> 
                                     <div class="control-group">
										<label class="control-label">Shortcode<span class="required">*</span></label>
										<div class="controls">
											<select name="shortcode_id" id="shortcode_id" class="span6 m-wrap" onchange="loadKeyword(this.value, '')">
												<option value="">- Choose Shortcode -</option>
												<?php for($i=0; $i<count($shortcode['rows']); $i++){ ?> 
												<option value="<?php echo $shortcode['rows'][$i]['code'];?>"><?php echo $shortcode['rows'][$i]['code'];?></option>
												   <?php } ?>
											</select>
										</div>
									</div>

									

									<div class="control-group">
										<label class="control-label">Name<span class="required">*</span></label>
										<div class="controls">
											<input name="g_name" id="g_name"  type="text" class="span3 m-wrap" style="width:205px;">
										</div>
									</div>

									

									<div class="control-group">
										<label class="control-label">API<span class="required">*</span></label>
										<div class="controls">
											<select class="m-wrap" id="api_url" name="api_url" style="width:220px;">
												<option value="">- API -</option>
												<?php for($i=0; $i<count($gateway_urls); $i++){ ?>
												<option value="<?php echo preg_replace('/\.[^.]+$/','',$gateway_urls[$i]);?>"><?php echo preg_replace('/\.[^.]+$/','',$gateway_urls[$i]);?></option>
												<?php } ?>
											</select>

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
    
    <script src="assets/js/metronic/app/campaign-manager/master/sms_gateway.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadGateway(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteGateway()">Delete</button>
        </div>
    </div>
    <div id="urlShow" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-footer" id="modal-body"></div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Close</button>
        </div>
    </div>
