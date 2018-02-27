				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="user_list active"><a href="#user_list" data-toggle="tab">User List</a></li>
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
                                 <div class="caption"><i class="icon-reorder"></i>User Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formUser" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<div class="control-group">
										<label class="control-label">ID<span class="required">*</span></label>
										<div class="controls">
											<input type="text" name="id" id="id" readonly class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Karyawan<span class="required">*</span></label>
										<div class="controls">
											<select name="karyawan_id" id="karyawan_id" data-required="1" class="span6 m-wrap">
												<option value="">-Karyawan-</option>
												<?php 
													if($karyawan['count']){
														for($i=0; $i<count($karyawan['rows']); $i++){
												?>
												<option value="<?php echo $karyawan['rows'][$i]['id'];?>"><?php echo $karyawan['rows'][$i]['nama_karyawan'];?></option>
												<?php } } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Tipe User<span class="required">*</span></label>
										<div class="controls">
											<select name="tipe_user_id" id="tipe_user_id" data-required="1" class="span6 m-wrap">
												<option value="">-Tipe User-</option>
												<?php 
													if($tipe_user['count']){
														for($i=0; $i<count($tipe_user['rows']); $i++){
												?>
												<option value="<?php echo $tipe_user['rows'][$i]['id'];?>"><?php echo $tipe_user['rows'][$i]['nama_tipe_user'];?></option>
												<?php } } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Username<span class="required">*</span></label>
										<div class="controls">
											<input name="username" id="username" maxlength="50" type="text" data-required="1" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Password<span class="required">*</span></label>
										<div class="controls">
											<input name="userpass" id="userpass" maxlength="50" type="password" data-required="1" class="span6 m-wrap"/>
										</div>
									</div>									
									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">
											<select name="status" id="status" data-required="1" class="span6 m-wrap">
												<option value="">-Status-</option>
												<option value="1">Active</option>
												<option value="0">Suspend</option>
											</select>
										</div>
									</div>
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
    
    <script src="assets/js/metronic/app/user-management/user.js" type="text/javascript"></script>

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
