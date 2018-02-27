				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Client Mapping  Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formUser" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									
									<div class="control-group">
										<label class="control-label">Client<span class="required">*</span></label>
										<div class="controls">
                                                                                    <select name="client_id" id="client_id" data-required="1" class="span6 m-wrap" onchange="getCampaignList()">
                                                                                            <option value="">--Select Client--</option>
                                                                                            <?php 
                                                                                                    if(count($client_list)){
                                                                                                            for($i=0; $i<count($client_list); $i++){
                                                                                            ?>
                                                                                            <option value="<?php echo $client_list[$i]['id'];?>"><?php echo $client_list[$i]['name'];?></option>
                                                                                            <?php } 
                                                                                            
                                                                                            } ?>
                                                                                    </select>
										</div>
									</div>
                                                                        <div class="control-group" >
                                                                            <label class="control-label">Choose Campaign<span class="required">*</span></label>
                                                                            <div class="controls" id="campaign_div1">
                                                                            <p id="campaign_div"></p>    
                                                                            </div>
									</div>
									
									<div class="form-actions">
                                                                            	<?php echo ($accessAdd)?'<button type="button" class="btn purple" id="submitbutton" disabled onclick="mapCampaign()">Save</button>':'';?>
										<button type="button" class="btn" onclick="clearForm()">Cancel</button>
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
    
    <script src="assets/js/metronic/app/xsim_mapping/map_xsim.js" type="text/javascript"></script>

    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onclick="deleteUser()">Delete</button>
        </div>
    </div>
