				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="domains_list active"><a href="#domains_list" data-toggle="tab">Domains List</a></li>
                        <li class="domains_form "><a href="#domains_form" data-toggle="tab">Domains Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="domains_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Domains List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								 <div class="btn-group">								
									<select class="m-wrap" id="limit" name="limit" style="width:60px;">
										<option value='15'>15</option>
										<option value='25'>25</option>
										<option value='50'>50</option>
										<option value='100'>100</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by id, Domain name or country name" title="Search by id, Domain name or country name">
									<button class="btn green" type="button" onclick="loadDomains(1)" title="Search"><i class="icon-search"></i></button>
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
                                 <div class="caption"><i class="icon-reorder"></i>Domains Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formdomains" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls" id="country_div">
											<?php /* 
											<select name="id_country" id="id_country" class="span6 m-wrap">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
											*/ ?>
											<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<input class="country" type="checkbox" value="<?php echo $country['rows'][$i]['id'];?>" name="id_country" id="id_country_<?php echo $country['rows'][$i]['id'];?>" /><?php echo $country['rows'][$i]['name'];?>
											<?php } ?>
										</div>
									</div>									
									<div class="control-group">
										<label class="control-label">Domains Code<span class="required">*</span></label>
										<div class="controls">
											<input name="code" id="code" maxlength="10" type="text" class="span2 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Domains Name<span class="required">*</span></label>
										<div class="controls">
											<input name="name" id="name" maxlength="100" type="text" class="span6 m-wrap" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Domain Expiry DATE:<span class="required">*</span></label>
										<div class="controls">	
											<input name="description" id="description" type="text" class="span6 m-wrap small"/>
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
	<script src="assets/js/jquery.datetimepicker.js" type="text/javascript"></script>
	<link href="assets/css/metronic/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    
    <script src="assets/js/metronic/app/campaign-manager/master/domains.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
		loadDomains(1);
		FormValidation.init();
		
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
		
		jQuery('#description').datetimepicker({
		timepicker:false,
		format:'Y/m/d'
		});
		
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteDomains()">Delete</button>
        </div>
    </div>
