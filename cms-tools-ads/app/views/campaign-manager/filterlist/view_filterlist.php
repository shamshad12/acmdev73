				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="partners_list active"><a href="#partners_list" data-toggle="tab">Filter List</a></li>
                        <li class="partners_form "><a href="#partners_form" data-toggle="tab">Filter Form</a></li>
                        <li class="bulkupload_filters "><a href="#bulkupload_filters" data-toggle="tab">Filter Bulk Upload</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="partners_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Filter List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	 <div class="btn-group">								
									<select class="m-wrap" id="search_country" name="search_country"  style="width:110px;">
										<option value="">- Country -</option>
										<?php for($i=0; $i<count($country['rows']); $i++){ ?>
										<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_operator" name="search_operator" style="width:120px;">
										<option value="">- Operator -</option>
										<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
										<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search By Msisdn Or Filter Type" title="Search By Msisdn Or Filter Type">
									<button class="btn green" type="button" onclick="loadFilterlist(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                         <div class="tab-pane " id="bulkupload_filters">
                           <!-- BEGIN DASHBOARD STATS  Bulk Upload filters-->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Bulk Upload Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="campaign_manager/filterlist/filterBulkUpload" method="post" id="bulkupload_filters_form" class="form-horizontal" enctype="multipart/form-data">
									<?php $this->load->view('layout/_errorhandling');?>
									<div class="control-group">
										<label class="control-label">Select XLS file<span class="required">*</span></label>
										<div class="controls">
											<input type="file" name="upload_filter" id="upload_filter"  class="span6 m-wrap">
										</div>

										<div class="controls">
											To Download "xlsx" Format <a href="assets/template_filter.xlsx">Click here</a>
											
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
                        <div class="tab-pane " id="partners_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Filter Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formpartners" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="id_country" id="id_country" onchange="loadOperator(this.value)" class="span6 m-wrap">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
                                    <div class="control-group">
                                        <label class="control-label">Operator<span class="required">*</span></label>
                                        <div class="controls" id="select-append">
                                            <select name="id_telco" id="id_telco" class="span6 m-wrap">
                                             <option value="">- Choose Operator -</option>
                                            </select>
                                        </div>
                                    </div> 
                                                                        
									<div class="control-group">
										<label class="control-label">Filter Type<span class="required">*</span></label>
										<div class="controls">
											<select name="filter_type" id="filter_type" class="span6 m-wrap">
												<option value="">- Choose Filter Type-</option>
												
												 <option value="Blacklist">Blacklist Msisdn</option>
                                                                                                 <option value="Whitelist">Whitelist Msisdn</option>
											</select>
										</div>
									</div>								
									<div class="control-group">
										<label class="control-label">MSISDN<span class="required"></span></label>
										<div class="controls">
											<input name="msisdn" id="msisdn"  type="text" class="span2 m-wrap"/>
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
    
    <script src="assets/js/metronic/app/campaign-manager/filterlist/filterlist.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
                         
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>"; 
		
		jQuery(document).ready(function() {		
			loadFilterlist(1);
			FormValidation.init();
			TestFormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="deletenew" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteFilterlistNew()">Ok</button>
        </div>
    </div>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to change this status</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteFilterlist()">Ok</button>
        </div>
    </div>
