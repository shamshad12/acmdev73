				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="partners_services_list active"><a href="#partners_services_list" data-toggle="tab">Partners Services List</a></li>
                        <li class="partners_services_form "><a href="#partners_services_form" data-toggle="tab">Partners Services Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="partners_services_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Partners Services List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form"> 
                                  
                                                               <div class="btn-group">								
									<select class="m-wrap" id="search_country" name="search_country" style="width:120px;">
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
									<select class="m-wrap" id="search_partner" name="search_partner" style="width:100px;">
										<option value="">- Partner -</option>
										<?php for($i=0; $i<count($partner['rows']); $i++){ ?>
										<option value="<?php echo $partner['rows'][$i]['id'];?>"><?php echo $partner['rows'][$i]['name'];?></option>
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
									<select class="m-wrap" id="search_price" name="search_price" style="width:120px;">
										<option value="">- Price -</option>
										<?php for($i=0; $i<count($price['rows']); $i++){ ?>
										<option value="<?php echo $price['rows'][$i]['id'];?>"><?php echo $price['rows'][$i]['cu_code'].' '.$price['rows'][$i]['value'];?></option>
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
									<input class="m-wrap" id="search" name="search" type="text"  title="Search by Id, SID, Keyword, Campaign media code or Operator name" placeholder="Search by Id, SID, Keyword, Campaign media code or Operator name">
									<button class="btn green" type="button" onclick="loadPartners_Services(1)" title="Search"><i class="icon-search"></i></button>
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
                                 <div class="caption"><i class="icon-reorder"></i>Partners Services Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formpartners_services" class="form-horizontal"> 
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
                                                                        
                                                                        <div class="control-group"> 
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="country_id" id="country_id" class="span6 m-wrap" onchange="loadOperator(this.value,''),loadPrice(this.value,'')">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												   <?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Operator<span class="required">*</span></label>
										<div class="controls"> 
											<select name="id_operator" id="id_operator" class="span6 m-wrap" onchange="getPartners(this.value, ''), getOperators_Apis(this.value, '')">
												<option value="">- Choose Operator -</option>
												 
											</select>
										</div>
									</div>    
									<div class="control-group">
										<label class="control-label">Campaign Media<span class="required">*</span></label>
										<div class="controls">
											<select name="id_campaign_media" id="id_campaign_media" class="span6 m-wrap">
												<option value="">- Choose Media -</option>
												<?php for($i=0; $i<count($campaign_media['rows']); $i++){ ?>
												<option value="<?php echo $campaign_media['rows'][$i]['id'];?>"><?php echo $campaign_media['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Operator APIs<span class="required">*</span></label>
										<div class="controls">
											<select name="id_operator_api" id="id_operator_api" class="span6 m-wrap" disabled="disabled">
												<option value="">- Choose Operator APIs -</option>
												<?php
													if(isset($operator_api))
													{
												for($i=0; $i<count($operator_api['rows']); $i++){ ?>
												<option value="<?php echo $operator_api['rows'][$i]['id'];?>"><?php echo $operator_api['rows'][$i]['name'];?></option>
													<?php } }?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Partner<span class="required">*</span></label>
										<div class="controls">
											<select name="id_partner" id="id_partner" class="span6 m-wrap" disabled="disabled" onchange="getKeyword_Groups(this.value, ''),getshortcode(this.value, '')">
												<option value="">- Choose Partner -</option>
												 
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Shortcode<span class="required">*</span></label>
										<div class="controls">
											<select name="id_shortcode" id="id_shortcode" class="span6 m-wrap" onchange="getserviceVal(this.value)">
												<option value="">- Choose Shortcode -</option>
												
											</select>
										</div>
									</div>	
									<div class="control-group">
										<label class="control-label">Price<span class="required">*</span></label>
										<div class="controls">
											<select name="id_price" id="id_price" class="span6 m-wrap">
												<option value="">- Choose Price -</option>
												
											</select>
										</div>
									</div>									
									<div class="control-group">
										<label class="control-label">Service Type<span class="required">*</span></label>
										<div class="controls">
											<select name="id_service_type" id="id_service_type" class="span6 m-wrap">
												<option value="">- Choose Service Type -</option>
												<?php for($i=0; $i<count($service_type['rows']); $i++){ ?>
												<option value="<?php echo $service_type['rows'][$i]['id'];?>"><?php echo $service_type['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>										
									<div class="control-group">
										<label class="control-label">Keyword Group<span class="required">*</span></label>
										<div class="controls">
											<select name="id_keyword_group" id="id_keyword_group" class="span6 m-wrap" disabled="disabled">
												<option value="">- Choose Keyword Group -</option>
												<?php for($i=0; $i<count($keyword_group['rows']); $i++){ ?>
												<option value="<?php echo $keyword_group['rows'][$i]['id'];?>"><?php echo $keyword_group['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>	
									<div class="control-group hide">
										<label class="control-label">Service ID<span class="required">*</span></label>
										<div class="controls">	
											<input name="sid" id="sid" maxlengt="5" type="text" class="span3 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Keyword<span class="required">*</span></label>
										<div class="controls">	
											<input name="keyword" id="keyword" maxlengt="5" type="text" class="span3 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Price Code</label>
										<div class="controls">	
											<input name="price_code" id="price_code" type="text" class="span3 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Service Prefix</label>
										<div class="controls">
											<input name="sprefix" id="sprefix" maxlength="10" type="text" class="span3 m-wrap" />
										</div>
									</div>
									<div class="control-group">
									 <label class="control-label">No. of Content</label>
									 <div class="controls">
									  <input name="ncontent" id="ncontent" maxlength="10" type="text" class="span3 m-wrap" />
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
    
    <script src="assets/js/metronic/app/campaign-manager/master/partners_services.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadPartners_Services(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deletePartners_Services()">Delete</button>
        </div>
    </div>
