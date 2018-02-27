<?php
//print_r($this->session->userdata('profile'));die;
$partner_permissions = json_decode($this->session->userdata('partner_permissions'));
?>
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS --> 
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="campaigns_list active"><a href="#campaigns_list" data-toggle="tab">Campaigns List</a></li>
                        <li class="campaigns_form "><a href="#campaigns_form" data-toggle="tab">Campaigns Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="campaigns_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Campaigns List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								  <div class="btn-group">								
									<select class="m-wrap" id="search_country" name="search_country" style="width:110px;">
										<option value="">- Country -</option>
										<?php for($i=0; $i<count($country['rows']); $i++){
										  if(in_array($country['rows'][$i]['id'],$partner_permissions->country))
{
										?>
										<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
										<?php }} ?>
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_media" name="search_media" style="width:100px;">
										<option value="">- Media -</option>
										<?php for($i=0; $i<count($campaign_media['rows']); $i++){ ?>
										<option value="<?php echo $campaign_media['rows'][$i]['id'];?>"><?php echo $campaign_media['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_category" name="search_category" style="width:110px;">
										<option value="">- Category -</option>
										<?php for($i=0; $i<count($campaign_category['rows']); $i++){ ?>
										<option value="<?php echo $campaign_category['rows'][$i]['id'];?>"><?php echo $campaign_category['rows'][$i]['name'];?></option>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by id, Campaign name, Country name, Campaign type, Campaign category or content" title="Search by id, Campaign name, Country name, Campaign type, Campaign category or content">
									<button class="btn green" type="button" onclick="loadCampaigns(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="campaigns_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Campaigns Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formcampaigns" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="id_country" id="id_country" class="span6 m-wrap" onchange="getPartners_Services(this.value, '', ''), getDomainsList(this.value, ''), getAds_Publishers('', '', this.value)">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){
 if(in_array($country['rows'][$i]['id'],$partner_permissions->country))
{ 
?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php }} ?>
											</select>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label">Campaign Media<span class="required">*</span></label>
										<div class="controls">
											<select name="id_campaign_media" id="id_campaign_media" class="span6 m-wrap" onchange="getTemplates(this.value, ''), getPartners_Services('', '', this.value)">
												<option value="">- Choose Media -</option>
												<?php for($i=0; $i<count($campaign_media['rows']); $i++){ ?>
												<option value="<?php echo $campaign_media['rows'][$i]['id'];?>"><?php echo $campaign_media['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Campaign Category<span class="required">*</span></label>
										<div class="controls">
											<select name="id_campaign_category" id="id_campaign_category" class="span6 m-wrap">
												<option value="">- Choose Category -</option>
												<?php for($i=0; $i<count($campaign_category['rows']); $i++){ ?>
												<option value="<?php echo $campaign_category['rows'][$i]['id'];?>"><?php echo $campaign_category['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Banner<!--span class="required">*</span--></label>
										<div class="controls">
											<select name="id_banner" id="id_banner" class="span6 m-wrap">
												<option value="">- Choose Banner -</option>
												<?php for($i=0; $i<count($banner['rows']); $i++){ ?>
												<option value="<?php echo $banner['rows'][$i]['id'];?>"><?php echo $banner['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Template<span class="required">*</span></label>
										<div class="controls">
											<select name="id_template" id="id_template" class="span6 m-wrap" disabled="disabled">
												<option value="">- Choose Template -</option>
												<?php for($i=0; $i<count($template['rows']); $i++){ ?>
												<option value="<?php echo $template['rows'][$i]['id'];?>"><?php echo $template['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Language<span class="required">*</span></label>
										<div class="controls">
											<select name="id_language" id="id_language" class="span6 m-wrap">
												<option value="">- Choose Language -</option>
												<?php for($i=0; $i<count($language['rows']); $i++){ ?>
												<option value="<?php echo $language['rows'][$i]['id'];?>"><?php echo $language['rows'][$i]['language'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group alch_cont_id hide">
										<label class="control-label">Alchemy content id<span class="required">*</span></label>
										<div class="controls">
											<input name="content" id="content" maxlength="50" type="text" class="span2 m-wrap"/>
										</div>
									</div>									
									<!--<div class="control-group">
										<label class="control-label">ACM Campaign Code<span class="required">*</span></label>
										<div class="controls">
											<input name="acm_code" id="acm_code" maxlength="100" type="text" class="span6 m-wrap"/>
										</div>
									</div>-->
									<div class="control-group">
										<label class="control-label">Campaigns Name<span class="required">*</span></label>
										<div class="controls">
											<input name="name" id="name" maxlength="50" type="text" class="span6 m-wrap" />
										</div>
									</div>
									<div class="control-group hide">
										<label class="control-label">Description<span class="required">*</span></label>
										<div class="controls">	
											<input name="description" id="description" type="text" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Ads Vendors<span class="required">*</span></label>
										<div class="controls">	
											<div class="checkbox-list" id="ads_publishers_list">
												<?php// for($i=0; $i<count($ads_publisher['rows']); $i++){ if($i9){$class="afterten hidden";} else{$class="firstten";}?>
												<!--div class="<?php// echo $class;?>"><label class="span6 m-wrap"><input type='checkbox' id='ads-publisher' value='<?php// echo $ads_publisher['rows'][$i]['id'];?>'><?php //echo $ads_publisher['rows'][$i]['name'];?><span style="margin-right:150px; float:right; ">Cost/Ads : USD<input type="text" id="cost-<?php //echo $ads_publisher['rows'][$i]['id'];?>" value="" style="width:100px" title="Cost Ads per Click/Acquisition" /></span></label></div-->
												<?php //} ?>
											</div>
										</div>
									</div>
									<div style="text-align: center; margin-left: -10px;" class="control-group" id="showmrls">
										
									</div>
									<div class="control-group">
										<label class="control-label">Operators And Partners<span class="required">*</span></label>
										<div class="controls">	
											<div class="checkbox-list" id="operator_partner_list">
												
											</div>
										</div>
									</div>
									<div style="text-align: center; margin-left: -10px;" class="control-group" id="showmrlsapi">
										
									</div>
<!-- added for domain -->
									<div class="control-group">
										<label class="control-label">Domains<span class="required">*</span></label>
										<div class="controls">	
											<div class="checkbox-list" id="domain_list">
												
											</div>
										</div>
									</div>
<!-- end -->
									<div class="control-group hide" id="hide_show_confirmation_page">
										<label class="control-label">Use Confirmation Page?<span class="required">*</span></label>
										<div class="controls">	
											<select name="use_confirmation" id="use_confirmation" class="span6 m-wrap">
												<option value="1">Yes</option>
												<option value="0">No</option>
											</select>
										</div>
									</div>

									<!--<div class="control-group">
										<label class="control-label">Crossell<span class="required">*</span></label>
										<div class="controls">
											<select name="crossell" id="crossell" class="span6 m-wrap" disabled="disabled">
												<option value="">- Choose Crossell Campaign -</option>
												<?php//for($i=0; $i<count($campaign['rows']); $i++){ ?>
												<option value="<?php//echo $campaign['rows'][$i]['id'];?>"><?php//echo $campaign['rows'][$i]['name'];?></option>
												<?php //} ?>
											</select>
										</div>
									</div>-->

									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">	
											<select name="status" id="status" class="span6 m-wrap" onchange="setPf(this.value)">
												<option value="1">Active</option>
												<option value="0">Inactive</option>
												<option value="2">Dummy</option>
												<option value="3">External</option>
											</select>
										</div>
									</div>	
									<div class="control-group" id="expire_date" style="display:none">
										<label class="control-label">Expiry Date<span class="required">*</span></label>
										<div class="controls">
											<input name="expire_date_camp" id="expire_date_camp" type="text" class="span2 m-wrap"  placeholder="Expiry Date"/>
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
    
    <script src="assets/js/metronic/app/partner_campaign_manager/campaigns/campaigns.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var duplicateID = "",
			duplicateLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		var accessDuplicate = 1;

		jQuery(document).ready(function() {		
			loadCampaigns(1,"<?php echo $template_id;?>","<?php echo $media_id;?>","<?php echo $temp_country_id;?>");
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });

        jQuery(function() {	
			$( "#expire_date_camp" ).datepicker({
			  changeMonth: true,
			  changeYear: true,
			  dateFormat: 'yy-mm-dd'
			});
        });

    </script>
    <div id="duplicate" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to duplicate this data?</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">No</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="duplicateCampaigns()">Yes</button>
        </div>
    </div>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteCampaigns('')">Delete</button>
        </div>
    </div>  
    <div id="urlShow" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-footer" id="modal-body" style="overflow:scroll;max-height:400px">
			
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Close</button>
        </div>
    </div>
 
    
    <div id="changestatus" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to change status?</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="changeStatus()">Change</button>
        </div>
    </div>
  
