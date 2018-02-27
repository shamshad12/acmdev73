				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="user_list active"><a href="#user_list" data-toggle="tab">Partner List</a></li>
                        <li class="user_form "><a href="#user_form" data-toggle="tab">User Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="user_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>User List</div>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by User Id, Name or Username" title="Search by User Id, Name or Username">
									<button class="btn green" type="button" onclick="loadUser(1)">Search!</button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="user_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Partner User Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formUser" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									
									<div class="control-group">
										<label class="control-label">User Name<span class="required">*</span></label>
										<div class="controls">
                                                                                    <input type="hidden" name="id" id="id" readonly class="span6 m-wrap"/>
                                                                                    <input type="text" name="employee_name" id="employee_name" disabled="disabled">
										</div>
									</div>
									
                                                                        <div class="control-group">
										<label class="control-label">Partner<span class="required">*</span></label>
										<div class="controls">
                                                                                   <?php for($i=0; $i<count($partner['rows']); $i++){ 
?>
											<div class="col-xs-1" style="width:20%;float:left">
                        <input type="checkbox" name="partner_id"  class="partner_data" id="partner_<?php echo $partner['rows'][$i]['id'].'_'.$partner['rows'][$i]['id_country'];?>" onclick="getshortcode(), getdomain(), getadspublisher()" value="<?php echo $partner['rows'][$i]['id'].'_'.$partner['rows'][$i]['id_country'].'_'.$partner['rows'][$i]['name'];?>"><?php echo $partner['rows'][$i]['name'];?></div>
                                                                                    <?php } ?>
										</div>
                                                                                <input type="hidden" name="selected_keywords" id="selected_keywords" >
									</div>
                                                                        
                                                                        
									<div class="control-group">
										<label class="control-label">Shortcode<span class="required">*</span></label>
										<div class="controls" id="shortcode_div">
                                                                                    <!--<?php for($i=0;$i<count($shortcode['rows']);$i++){?>
                                                                                        <div class="col-xs-1" style="width:20%;float:left"><input type="checkbox" name="shortcode"  class="shortcode_name" id="shortcode_<?php echo $shortcode['rows'][$i]['code']?>" value="<?php echo $shortcode['rows'][$i]['code']?>" ><?php echo $shortcode['rows'][$i]['code'];?></div>
                                                                                    <?php } ?>-->
										</div>
									</div>
                                     
                                                                        <div class="control-group">
										<label class="control-label">Keyword<span class="required">*</span></label>
										<div class="controls" id="keyword_div">
                                                                                    
										</div>
									</div>


                 <!-- <div class="control-group">
                      <label class="control-label">Partner<span class="required">*</span></label>
                      <div class="controls">
                        <?php for($i=0; $i<count($partner['rows']); $i++){ ?>
                        <div class="col-xs-1" style="width:20%;float:left">
                        <input type="checkbox" name="partner_id"  class="partner_data" id="partner_<?php echo $partner['rows'][$i]['id'].'_'.$partner['rows'][$i]['id_country'];?>" onclick="getshortcode(), getdomain(), getadspublisher()" value="<?php echo $partner['rows'][$i]['id'].'_'.$partner['rows'][$i]['id_country'].'_'.$partner['rows'][$i]['name'];?>"><?php echo $partner['rows'][$i]['name'];?>
                        </div>
                        <?php } ?>
                      </div>
                        <input type="hidden" name="selected_keywords" id="selected_keywords" >
                    </div>-->
                    <div class="control-group">
                      <label class="control-label">Domain<span class="required">*</span></label>
                      <div class="controls" id="domain_div"></div>
                    </div>
                    
                    <div class="control-group">
                      <label class="control-label">Ads Publisher<span class="required">*</span></label>
                      <div class="controls" id="ads_publisher_div"></div>
                        <input type="hidden" name="selected_ads_publisher" id="selected_ads_publisher" >
                    </div>
                    <style>
                  .code{color: #FF9800;}
                  </style>


									<div class="form-actions">
										<?php echo ($accessAdd)?'<button type="submit" class="btn purple">Save</button>':'';?>
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
    
    <script src="assets/js/metronic/app/user-management/partner_user.js" type="text/javascript"></script>

	<script type="text/javascript">
		var deleteID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadUser(1);
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onclick="deleteUser()">Delete</button>
        </div>
    </div>
