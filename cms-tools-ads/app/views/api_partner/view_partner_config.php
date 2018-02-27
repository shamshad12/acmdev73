				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="partners_services_list active"><a href="#partners_services_list" data-toggle="tab">Partners Configuration  List</a></li>
                        <li class="partners_services_form "><a href="#partners_services_form" data-toggle="tab">Partners Configuration  Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="partners_services_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Partners configuration  List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form"> 
                                  
                                                               
								 <div class="btn-group">								
									<select class="m-wrap" id="search_partner" name="search_partner" style="width:100px;">
										<option value="">- Partner -</option>
										<?php for($i=0; $i<count($partner['rows']); $i++){ ?>
										<option value="<?php echo $partner['rows'][$i]['code'];?>"><?php echo $partner['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_shortcode" name="search_shortcode" style="width:120px;">
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
									<input class="m-wrap" id="search" name="search" type="text"  title="Search by Id,Partner, shortcode or keyword" placeholder="Search by Id,Partner, shortcode or keyword">
									<button class="btn green" type="button" onclick="loadPartner_Config(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="partners_services_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Partners Configuration  Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formpartners_services" class="form-horizontal"> 
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
                                                                        
                                                                        <div class="control-group">
										<label class="control-label">Partner Code<span class="required">*</span></label>
										<div class="controls">
											<select name="partner_code" id="partner_code" class="span6 m-wrap">
												 <option value="">- Partner -</option>
                                                                                                <?php for($i=0; $i<count($partner['rows']); $i++){ ?>
                                                                                                    <option value="<?php echo strtolower($partner['rows'][$i]['code']);?>"><?php echo $partner['rows'][$i]['name'];?></option>
                                                                                                <?php } ?>
											</select>
										</div>
									</div>
                                                                      
									<div class="control-group">
										<label class="control-label">Shortcode<span class="required">*</span></label>
										<div class="controls">
											<select name="shortcode" id="shortcode" class="span6 m-wrap">
												 <option value="">- Shortcode -</option>
                                                                                                    <?php for($i=0; $i<count($shortcode['rows']); $i++){ ?>
                                                                                                    <option value="<?php echo $shortcode['rows'][$i]['code'];?>"><?php echo $shortcode['rows'][$i]['code'];?></option>
                                                                                                    <?php } ?>
											</select>
										</div>
									</div>	
																		
										
									
									<div class="control-group">
										<label class="control-label">Keyword<span class="required">*</span></label>
										<div class="controls">	
											<input name="keyword" id="keyword" maxlengt="5" type="text" class="span3 m-wrap"/>
										</div>
									</div>
                                                                        
                                                                        <div class="control-group">
										<label class="control-label">Product ID<span class="required">*</span></label>
										<div class="controls">	
											<input name="product_id" id="product_id"  type="text" class="span3 m-wrap"/>
										</div>
									</div>
                                                                        
									<div class="control-group">
										<label class="control-label">CPID</label>
										<div class="controls">	
											<input name="cp_id" id="cp_id"  type="text" class="span3 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Customer Care</label>
										<div class="controls">	
											<input name="cc" id="cc" type="text" class="span3 m-wrap"/>
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
    
    <script src="assets/js/metronic/app/api_partner/partners_config.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadPartner_Config(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deletePartner_Config()">Delete</button>
        </div>
    </div>
