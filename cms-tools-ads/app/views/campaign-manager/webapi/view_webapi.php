				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="domains_list active"><a href="#domains_list" data-toggle="tab">WebAPI List</a></li>
                        <li class="domains_form "><a href="#domains_form" data-toggle="tab">WebAPI Form</a></li>
                         <li class="domains_test "><a href="#domains_test" data-toggle="tab">WebAPI Test</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="domains_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>WebAPI List</div>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Id, Campaign code, Affiliate name, API Url doain or Parameters"  title="Search by Id, Campaign code, Affiliate name, API Url doain or Parameters" >
									<button class="btn green" type="button" onclick="loadWebAPI(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="domains_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      		<br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>WebAPI Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formdomains" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Campaign Code<span class="required">*</span></label>
										<div class="controls">
											<input name="campaign_code" id="campaign_code" type="text" class="span2 m-wrap"/>
										</div>
									</div>									
									<div class="control-group">
										<label class="control-label">Affiliate Name<span class="required">*</span></label>
										<div class="controls">
											<input name="affiliate_name" id="affiliate_name" type="text" class="span2 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">API URL<span class="required">*</span></label>
										<div class="controls">
											<input name="api_url" id="api_url" type="text" class="span6 m-wrap" placeholder="http://54.169.14.129/ads/webapi"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Params<span class="required">*</span></label>
										<div class="controls">	
											<input name="param" id="param" type="text" class="span6 m-wrap" placeholder="authkey={CAMPAIGNCODE}&msisdn={MSISDN}&referer={REFERRAL-URL}"/>
										</div>
									</div>	
									<div class="control-group">
										<label class="control-label">Allowed IP Address<span class="required">*</span></label>
										<div class="controls">	
											<input name="domain" id="domain" type="text" class="span6 m-wrap" placeholder="for mutiple ips, please add comma sepearted ips eg: 54.169.14.129,202.169.20.130,123.155.67.118"/>
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
                        <div class="tab-pane " id="domains_test">
                           <!-- BEGIN DASHBOARD STATS -->
                      		<br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>WebAPI Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formdomains_test" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<div class="control-group">
										<label class="control-label">Campaign Code<span class="required">*</span></label>
										<div class="controls">
											<select name="campaign_code_test" id="campaign_code_test" class="span6 m-wrap">
												<option value="">- Choose Campaign Code -</option>
												<?php for($i=0; $i<count($code_affiliate['rows']); $i++){ ?>
												<option value="<?php echo $code_affiliate['rows'][$i]['campaign_code'].'_'.$code_affiliate['rows'][$i]['api_url'];?>"><?php echo $code_affiliate['rows'][$i]['affiliate_name'].'_'.$code_affiliate['rows'][$i]['campaign_code'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>									
									<div class="control-group">
										<label class="control-label">MSISDN<span class="required">*</span></label>
										<div class="controls">
											<input name="msisdn_test" id="msisdn_test" type="text" class="span2 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Refferal<span class="required">*</span></label>
										<div class="controls">
											<input name="refferal_test" id="refferal_test" type="text" class="span6 m-wrap" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Allowed IP Address<span class="required">*</span></label>
										<div class="controls">	
											<input name="domain_test" id="domain_test" type="text" class="span6 m-wrap" placeholder=""/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Visitor IP<span class="required">*</span></label>
										<div class="controls">	
											<input name="visitorip" id="visitorip" type="text" class="span6 m-wrap" placeholder=""/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">PIN<span class="required">*</span></label>
										<div class="controls">	
											<input name="pin" id="pin" type="text" class="span6 m-wrap" placeholder=""/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Test Result<span class="required">*</span></label>
										<div class="controls">	
											<textarea name="test_result" id="test_result" type="text" class="span6 m-wrap"></textarea>
										</div>
									</div>
									<div class="form-actions">
										<button type="<?php echo ($accessAdd)?'submit':'button';?>" <?php echo ($accessAdd)?'':'disabled="disabled"';?> class="btn purple">Test</button>
										<button type="button" class="btn" onClick="clearFormTest()">Cancel</button>
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
    
    <script src="assets/js/metronic/app/campaign-manager/webapi/webapi.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadWebAPI(1);
			FormValidation.init();
			TestFormValidation.init();
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteWebAPI()">Delete</button>
        </div>
    </div>
