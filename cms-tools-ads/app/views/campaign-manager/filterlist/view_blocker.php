				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="blocker_list active"><a href="#blocker_list" data-toggle="tab">Blocker List</a></li>
                        <li class="blocker_form "><a href="#blocker_form" data-toggle="tab">Blocker Form</a></li>
                        <li class="blocker_bulk_form "><a href="#blocker_bulk_form" data-toggle="tab">Blocker Bulk Upload</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="blocker_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Country List</div>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Service, Country name, Operator name or Shortcode" title="Search by Service, Country name, Operator name or Shortcode">
									<button class="btn green" type="button" onclick="loadBlocker(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                         <div class="tab-pane " id="blocker_bulk_form">
                           <!-- BEGIN DASHBOARD STATS  UPLOAD BLOCK-->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Blocker Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="campaign_manager/blocker/blockerBulkUpload" method="post" id="blocker_bulkform" class="form-horizontal" enctype="multipart/form-data">
									<?php $this->load->view('layout/_errorhandling');?>
									<div class="control-group">
										<label class="control-label">Select XLS file<span class="required">*</span></label>
										<div class="controls">
											<input type="file" name="upload_blocker" id="upload_blocker" class="span6 m-wrap">
										</div>
										<div class="controls">
											To Download "xlsx" Format <a href="assets/template_blocker.xlsx">Click here</a>
										</div>
									</div>
									<div class="form-actions">
										<button type="<?php echo ($accessAdd)?'submit':'button';?>" <?php echo ($accessAdd)?'':'disabled="disabled"';?> class="btn purple">Upload</button>
										<button type="button" class="btn" onClick="clearFormBulk()">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
                        <div class="tab-pane " id="blocker_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Blocker Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formblocker" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="country_id" id="country_id" class="span6 m-wrap" onchange="fetchOperators()">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
										
									<div class="control-group">
										<label class="control-label">Shortcode<span class="required">*</span></label>
										<div class="controls">
											<select name="shortcode" id="shortcode" class="span6 m-wrap" onchange="fetchServices()">
												<!--<option value="" selected>- Choose Shortcode</option> -->
												<option value="All">All</option>
												<?php for($i=0; $i<count($shortcode['rows']); $i++)
													{ 
												?>   
													<option value="<?php echo $shortcode['rows'][$i]['id'].'__'.$shortcode['rows'][$i]['code'];?>"><?php echo $shortcode['rows'][$i]['code'];?></option>
												<?php 
													} 
												?>
												
											</select>
										</div>
									
									</div>
									<div class="control-group">
										<label class="control-label">Kewword<span class="required">*</span></label>
										<div class="controls">
											<select name="service" id="service" class="span6 m-wrap" >
												<!--<option value="">- Choose Keyword</option>-->
												<option value="All">All</option>
												<?php 

													/*for($i=0; $i<count($keyword); $i++)
													{
														echo "<option value='".$keyword[$i]."'>".$keyword[$i]."</option>";
													}*/

												?>
											</select>
										</div>
									
									</div>
									<div class="control-group">
										<label class="control-label">Operators<span class="required">*</span></label>
										<div class="controls">
											<select name="operator_id" id="operator_id" class="span6 m-wrap">
												<option value="">- Choose Operators -</option>
												
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
    
    <script src="assets/js/metronic/app/campaign-manager/filterlist/blocker.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadBlocker(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteBlocker()">Delete</button>
        </div>
    </div>
    <div id="chngeblockestatus" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to change status?</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="changeStatus()">Change</button>
        </div>
    </div>
