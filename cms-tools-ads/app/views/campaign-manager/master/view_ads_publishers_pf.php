				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="ads_publishers_pf_list active"><a href="#ads_publishers_pf_list" data-toggle="tab">Ads Vendors PF List</a></li>
                        <li class="ads_publishers_pf_form "><a href="#ads_publishers_pf_form" data-toggle="tab">Ads Vendors PF Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="ads_publishers_pf_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Ads Vendors PF List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								  <div class="btn-group">								
									<select class="m-wrap" id="search_operator" name="search_operator" style="width:110px;" >
										<option value="">- Operator -</option>
										<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
										<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_ads_publisher" name="search_ads_publisher" style="width:120px;">
										<option value="">- Ads Vendors -</option>
										<?php for($i=0; $i<count($ads_publisher['rows']); $i++){ ?>
										<option value="<?php echo $ads_publisher['rows'][$i]['id'];?>"><?php echo $ads_publisher['rows'][$i]['name'];?></option>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by id, Pixel fire value, Vendor name Or operator name" title="Search by id, Pixel fire value, Vendor name Or operator name">
									<button class="btn green" type="button" onclick="loadAds_Publishers_PF(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="ads_publishers_pf_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Ads Vendors PF Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formads_publishers_pf" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Operator<span class="required">*</span></label>
										<div class="controls">
											<select name="id_operator" id="id_operator" class="span6 m-wrap">
												<option value="">- Operator -</option>
												<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
												<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Ads Vendors<span class="required">*</span></label>
										<div class="controls">
											<select name="id_ads_publisher" id="id_ads_publisher" class="span6 m-wrap">
												<option value="">- Choose Ads Vendors -</option>
												<?php for($i=0; $i<count($ads_publisher['rows']); $i++){ ?>
												<option value="<?php echo $ads_publisher['rows'][$i]['id'];?>"><?php echo $ads_publisher['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Acquisition Type<span class="required">*</span></label>
										<div class="controls">
											<select name="acquisition_type" id="acquisition_type" class="span6 m-wrap" onchange="setPf(this.value)">
												<option value="">- Choose Acquisition Type -</option>
												<option value="Amount">By Amount</option>
												<option value="Percentage">By Percentage</option>
											</select>
										</div>
									</div>
									<div class="control-group" id="div_pf_value" style="display:none">
										<label class="control-label">Pixel Fire Value<span class="required">*</span></label>
										<div class="controls">
											<input name="pf_value" id="pf_value" type="text" class="span2 m-wrap"/>
										</div>
									</div>
									<div class="control-group" id="div_percentage" style="display:none">
										<label class="control-label">Pixel Fire Percentage<span class="required">*</span></label>
										<div class="controls">
											<select name="percentage" id="percentage" class="span6 m-wrap">
												<option value="">- Choose Percentage -</option>
												<option value="10">10% Send</option>
												<option value="20">20% Send</option>
												<option value="30">30% Send</option>
												<option value="40">40% Send</option>
												<option value="50">50% Send</option>
												<option value="60">60% Send</option>
												<option value="70">70% Send</option>
												<option value="80">80% Send</option>
												<option value="90">90% Send</option>
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
    
    <script src="assets/js/metronic/app/campaign-manager/master/ads_publishers_pf.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadAds_Publishers_PF(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteAds_Publishers_PF()">Delete</button>
        </div>
    </div>
    <div id="urlShow" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-footer" id="modal-body"></div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Close</button>
        </div>
    </div>
