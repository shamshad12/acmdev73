				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="campaigns_auto_redirect_list active"><a href="#campaigns_auto_redirect_list" data-toggle="tab">Campaigns Auto Redirect List</a></li>
                        <li class="campaigns_auto_redirect_form "><a href="#campaigns_auto_redirect_form" data-toggle="tab">Campaigns Auto Redirect Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="campaigns_auto_redirect_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Campaigns Auto Redirect List</div>
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
									<select class="m-wrap" id="search_ads_publisher" name="search_ads_publisher" style="width:150px;">
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Id, Operator, Campaign or Vendor name" title="Search by Id, Operator, Campaign or Vendor name">
									<button class="btn green" type="button" onclick="loadCampaigns_Auto_Redirect(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="campaigns_auto_redirect_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Campaigns Auto Redirect Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formcampaigns_auto_redirect" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Operator<span class="required">*</span></label>
										<div class="controls">
											<select name="id_operator" id="id_operator" class="span6 m-wrap" onchange="getCampaigns('', '', '')">
												<option value="">- Choose Operator -</option>
												<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
												<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Ads Vendors<span class="required">*</span></label>
										<div class="controls">
											<select name="id_ads_publisher" id="id_ads_publisher" class="span6 m-wrap" onchange="getCampaigns('', '', '')">
												<option value="">- Choose Ads Publisher -</option>
												<?php for($i=0; $i<count($ads_publisher['rows']); $i++){ ?>
												<option value="<?php echo $ads_publisher['rows'][$i]['id'];?>"><?php echo $ads_publisher['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Campaign<span class="required">*</span></label>
										<div class="controls">
											<select name="id_campaign" id="id_campaign" class="span6 m-wrap" disabled="disabled">
												<option value="">- Choose Campaign -</option>
												<?php for($i=0; $i<count($campaign['rows']); $i++){ ?>
												<option value="<?php echo $campaign['rows'][$i]['id'];?>"><?php echo $campaign['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">From<span class="required">*</span></label>
										<div class="controls">
											<select name="day_from" id="day_from" class="span6 m-wrap">
												<option value="">- Choose Day -</option>
												<?php foreach($day as $key => $val){ ?>
												<option value="<?php echo $key;?>"><?php echo $val;?></option>
												<?php } ?>
											</select>
											<select name="hour_from" id="hour_from" class="span6 m-wrap">
												<option value="">- Choose Time -</option>
												<?php foreach($time as $key => $val){ ?>
												<option value="<?php echo $key;?>"><?php echo $val;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">To<span class="required">*</span></label>
										<div class="controls">
											<select name="day_to" id="day_to" class="span6 m-wrap">
												<option value="">- Choose Day -</option>
												<?php foreach($day as $key => $val){ ?>
												<option value="<?php echo $key;?>"><?php echo $val;?></option>
												<?php } ?>
											</select>
											<select name="hour_to" id="hour_to" class="span6 m-wrap">
												<option value="">- Choose Time -</option>
												<?php foreach($time as $key => $val){ ?>
												<option value="<?php echo $key;?>"><?php echo $val;?></option>
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
    
    <script src="assets/js/metronic/app/campaign-manager/campaigns/campaigns_auto_redirect.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadCampaigns_Auto_Redirect(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteCampaigns_Auto_Redirect()">Delete</button>
        </div>
    </div>
    <div id="urlShow" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-footer" id="modal-body"></div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Close</button>
        </div>
    </div>
