				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="operators_partners_list active"><a href="#operators_partners_list" data-toggle="tab">Mo Api List</a></li>
                        <li class="operators_partners_form "><a href="#operators_partners_form" data-toggle="tab">Mo Api Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="operators_partners_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Mo Api List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								 <!-- <div class="btn-group">								
									<select class="m-wrap" id="search_country" name="search_country" style="width:120px;">
										<option value="">- Country -</option>
										<?php for($i=0; $i<count($country['rows']); $i++){ ?>
										<option value="<?php echo strtolower($country['rows'][$i]['code']);?>"><?php echo $country['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div> 
								 <div class="btn-group">								
									<select class="m-wrap" id="search_operator" name="search_operator" style="width:100px;">
										<option value="">- Operator -</option>
										<?php 
											for($i=0; $i<count($operator['rows']); $i++)
											{ 
												$name = explode('-', $operator['rows'][$i]['name']);
										?>
										<option value="<?php echo strtolower($name[1]);?>"><?php echo $operator['rows'][$i]['name'];?></option>
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
									<select class="m-wrap" id="search_keyword" name="search_keyword" style="width:100px;">
										<option value="">-Keyword -</option>
										<?php for($i=0; $i<count($keyword['rows']); $i++){ ?> 
										<option value="<?php echo $keyword['rows'][$i]['keyword'];?>"><?php echo $keyword['rows'][$i]['keyword'];?></option>
										<?php } ?> 
									</select>
								 </div>  -->
                                                  
								 <div class="btn-group">								
									<select class="m-wrap" id="limit"  name="limit" style="width:60px;">
										<option value='5'>5</option>
										<option value='10'>10</option>
										<option value='25'>25</option>
										<option value='50'>50</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search"  placeholder="Search By Type or message" title="Search By Type or message" type="text">
									<button class="btn green" type="button" onclick="loadMo_Api(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="operators_partners_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Operators Partners Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formoperators_partners" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									
                                                                        
									<div class="control-group"> 
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="country_id" id="country_id" class="span6 m-wrap" onchange="loadOperator(this.value, '')">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'].'__'.strtolower($country['rows'][$i]['code']);?>"><?php echo $country['rows'][$i]['name'];?></option>
												   <?php } ?>
											</select>
										</div>
									</div>
                                                                        
                                                                       
                                                                        
                                    <div class="control-group">
										<label class="control-label">Shortcode<span class="required">*</span></label>
										<div class="controls">
											<select name="shortcode_id" id="shortcode_id" class="span6 m-wrap" onchange="loadKeyword(this.value, '')">
												<option value="">- Choose Shortcode -</option>
												<?php for($i=0; $i<count($shortcode['rows']); $i++){ ?> 
												<option value="<?php echo $shortcode['rows'][$i]['id'].'__'.$shortcode['rows'][$i]['code'];?>"><?php echo $shortcode['rows'][$i]['code'];?></option>
												   <?php } ?>
											</select>
										</div>
									</div>
                                                                        
                                    <div class="control-group">
										<label class="control-label">Keyword<span class="required">*</span></label>
										<div class="controls" id="select-keyword"> 
											<select name="keyword" id="keyword" class="span6 m-wrap">
												<option value="">- Choose Keyword -</option>
												
											</select>
										</div>
									</div>
                         
                                    <div class="control-group">
                                       <label class="control-label">Telco<span class="required">*</span></label>
                                       <div class="controls" id="select-append">
                                        <select name="telco_id" id="telco_id" class="span6 m-wrap">
                                      		<option value="">- Choose telco -</option>
                                       	
                                       	</select>
                                      	</div>
                                    </div> 
									                                 
                                    <div class="control-group"> 
										<label class="control-label">Campaign Code<span class="required">*</span></label>
										<div class="controls">
											 <input name="messagetype" id="messagetype" placeholder="" type="text" class="span6 m-wrap" /> 
										</div>
									</div>
                                                 
									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">	
											<select name="status" id="status" class="span6 m-wrap">
												<option value="0">Active</option>
												<option value="1">Inactive</option>
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
    
    <script src="assets/js/metronic/app/campaign-manager/campaigns/mo_api.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
                var duplicateID = "",
			duplicateLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		var accessDuplicate = 1; 
		jQuery(document).ready(function() {		
			loadMo_Api(1);
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to change the Status?</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">NO</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deletemo_api()">Yes</button>
        </div>
    </div>
    
     <div id="duplicate" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p> 
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">NO</button>   
            <button type="button" data-dismiss="modal" class="btn green" onClick="duplicatemo_api()">Yes</button>
        </div>
    </div> 
