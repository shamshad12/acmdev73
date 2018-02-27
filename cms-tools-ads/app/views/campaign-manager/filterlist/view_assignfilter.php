				<div id="dashboard"> 
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="partners_list active"><a href="#partners_list" data-toggle="tab">Create Filter List</a></li>
                        <li class="partners_form "><a href="#partners_form" data-toggle="tab"> Create Filter Form</a></li>
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
									<select class="m-wrap" id="limit" name="limit" style="width:60px;">
										<option value='5'>5</option>
										<option value='10'>10</option>
										<option value='25'>25</option>
										<option value='50'>50</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search By Country or ListName" title="Search By Country or ListName">
									<button class="btn green" type="button" onclick="loadAssignfilter(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
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
										<label class="control-label">Select Country<span class="required">*</span></label>
										<select name="id_country" id="id_country" class="span6 m-wrap">
												<option value="">- Choose Country -</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
									</div>                                

									<div class="control-group">
										<label class="control-label">Service Type<span class="required">*</span></label>
										<div class="controls">
											<select name="id_listtype" id="id_listtype" class="span6 m-wrap">
												<option value="">- Choose Service Type -</option>
												<?php for($i=0; $i<count($list_name['rows']); $i++){ ?>
												<option value="<?php echo $list_name['rows'][$i]['id'];?>"><?php echo $list_name['rows'][$i]['list_name'];?></option>
												<?php } ?> 
											</select>
										</div>
									</div>	

                                                                        
                                                                       <div class="control-group"> 
										<label class="control-label">Allow Request<span class="required">*</span></label>
										<div class="controls">
											<input name="validdays" id="validdays" maxlength="25" type="text" class="span2 m-wrap"/>
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
    
    <script src="assets/js/metronic/app/campaign-manager/filterlist/assignfilter.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {
                    //alert('ssss');return false;
			loadAssignfilter(1);  
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    
    <Script>
        $("#validdays").keyup(function() {
var val = $("#validdays").val();
if(parseInt(val) < 1 || isNaN(val)) { 
alert("Please enter valid number, less than 1 not allowed.");   
$("#validdays").val("");
$("#validdays").focus();
}
});
        </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteAssignfilter()">Delete</button>
        </div>
    </div>
 