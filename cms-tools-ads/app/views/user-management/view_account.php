				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />                                   
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Account Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formAccount" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>									
									<input type="hidden" name="id" id="id" value="<?php echo $account['id'];?>" readonly class="span6 m-wrap"/>
									
									<div class="control-group">
										<label class="control-label">Fullname<span class="required">*</span></label>
										<div class="controls">
											<input type="text" name="nama_karyawan" id="nama_karyawan" readonly data-required="1" class="span3 m-wrap" value="<?php echo $account['nama_karyawan'];?>" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Tipe Account<span class="required">*</span></label>
										<div class="controls">
											<input type="text" name="nama_tipe_user" id="nama_tipe_user" value="<?php echo $account['nama_tipe_user'];?>" readonly data-required="1" class="span3 m-wrap" />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Username<span class="required">*</span></label>
										<div class="controls">
											<input name="accountname" id="username" maxlength="50" value="<?php echo $account['username'];?>" type="text" readonly data-required="1" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Old Password<span class="required">*</span></label>
										<div class="controls">
											<input name="old_userpass" id="old_userpass" maxlength="50" type="password" data-required="1" class="span6 m-wrap"/>
										</div>
									</div>	
									<div class="control-group">
										<label class="control-label">Password<span class="required">*</span></label>
										<div class="controls">
											<input name="userpass" id="userpass" maxlength="50" type="password" data-required="1" class="span6 m-wrap"/>
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
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="assets/js/metronic/plugins/data-tables/jquery.dataTables.js" type="text/javascript"></script>  
    <script src="assets/js/metronic/plugins/data-tables/DT_bootstrap.js" type="text/javascript"></script>
    <script src="assets/js/metronic/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
	<script src="assets/js/metronic/plugins/jquery-validation/dist/additional-methods.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    
    <script src="assets/js/metronic/app/user-management/account.js" type="text/javascript"></script>

	<script type="text/javascript">
		var deleteID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
