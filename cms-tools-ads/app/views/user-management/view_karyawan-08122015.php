				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="karyawan_list active"><a href="#karyawan_list" data-toggle="tab">Employee List</a></li>
                        <li class="karyawan_form "><a href="#karyawan_form" data-toggle="tab">Employee Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="karyawan_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Employee List</div>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Id, Name, Email or Phone number" title="Search by Id, Name, Email or Phone number">
									<button class="btn green" type="button" onclick="loadKaryawan(1)">Search!</button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="karyawan_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Karyawan Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">   
								<div class="portlet-title">
									<div class="caption"><i class="icon-reorder"></i>Data Form</div>
								</div><br>   
                                 <form action="#" id="formkaryawan" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<div class="control-group">
										<label class="control-label">ID<span class="required">*</span></label>
										<div class="controls">
											<input type="text" name="id" id="id" readonly class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Employee Name<span class="required">*</span></label>
										<div class="controls">
											<input name="nama_karyawan" id="nama_karyawan" maxlength="50" type="text" data-required="1" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Place of Birth<span class="required">*</span></label>
										<div class="controls">
											<input name="tempat_lahir" id="tempat_lahir" maxlength="50" type="text" data-required="1" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Date Of Birth<span class="required">*</span></label>
										<div class="controls">
											<input name="tanggal_lahir" id="tanggal_lahir" maxlength="50" type="text" data-required="1" class="span3 m-wrap"/>
											<span class="help-block">e.g: YYYY-MM-DD</span>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Country<span class="required">*</span></label>
										<div class="controls">
											<select name="id_country" id="id_country" data-required="1" class="span6 m-wrap">
												<option value="">-Country-</option>
												<?php for($i=0; $i<count($country['rows']); $i++){ ?>
												<option value="<?php echo $country['rows'][$i]['id'];?>"><?php echo $country['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">sex<span class="required">*</span></label>
										<div class="controls">
											<select name="jenis_kelamin" id="jenis_kelamin" data-required="1" class="span6 m-wrap">
												<option value="">-sex-</option>
												<option value="1">Male</option>
												<option value="0">female</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">religion<span class="required">*</span></label>
										<div class="controls">
											<select name="agama" id="agama" data-required="1" class="span6 m-wrap">
												<option value="">-religion-</option>
												<option value="Buddha">Buddha</option>
												<option value="Hindu">Hindu</option>
												<option value="Islam">Islam</option>
												<option value="Kristen Katolik">Kristen Katolik</option>
												<option value="Kristen Protestan">Kristen Protestan</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Address<span class="required">*</span></label>
										<div class="controls">
											<input name="alamat" id="alamat" maxlength="50" type="text" class="span6 m-wrap"/>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Email<span class="required">*</span></label>
										<div class="controls">
											<input name="email" id="email" maxlength="50" type="text" class="span6 m-wrap"/>
											<span class="help-block">e.g: example@example.com</span>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Telepon<span class="required">*</span></label>
										<div class="controls">
											<input name="telepon" id="telepon" maxlength="20" type="text" class="span6 m-wrap"/>
											<span class="help-block">e.g: 62</span>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Fax<span class="required">*</span></label>
										<div class="controls">
											<input name="fax" id="fax" maxlength="20" type="text" class="span6 m-wrap"/>
											<span class="help-block">e.g: 62</span>
										</div>
									</div>	
									<div class="control-group">
										<label class="control-label">Avatar<span class="required">*</span></label>
										<div class="controls">	
											<input name="avatar_ori" id="avatar_ori" type="hidden"/>
											<input name="avatar_thumb" id="avatar_thumb" type="hidden"/>
											<input type="file" name="file_upload" id="file_upload" multiple  class="span3 m-wrap">
											<span class="help-block" id='img-upload'></span>
										</div>
									</div>											
									
									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">
											<select name="status" id="status" data-required="1" class="span6 m-wrap">
												<option value="">-Status-</option>
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
										</div>
									</div>
									
									<div class="portlet-title">
										<div class="caption"><i class="icon-reorder"></i>Account Form</div>
									</div><br>
									
									<div class="control-group">
										<label class="control-label">User Type<span class="required">*</span></label>
										<div class="controls">
											<select name="tipe_user_id" id="tipe_user_id" data-required="1" class="span6 m-wrap">
												<option value="">-Type-</option>
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
									
									<!--<div class="control-group">
										<label class="control-label">User Type<span class="required">*</span></label>
										<div class="controls">
											<select name="tipe_user_id" id="tipe_user_id" data-required="1" class="span6 m-wrap">
												<option value="">-User Type-</option>
												<?php 
													if($tipeUser['count']){
													#	for($i=0; $i<count($tipeUser['rows']); $i++){
												?>
												<option value="<?php #echo $tipeUser['rows'][$i]['id'];?>"><?php #echo $tipeUser['rows'][$i]['nama_tipe_user'];?></option>
												<?php } #} ?>
											</select>
										</div>
									</div>-->
										
									<div class="form-actions">
										<?php echo ($accessAdd)?'<button type="submit" class="btn purple">Save</button>':'';?>
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
    
    <script src="assets/js/metronic/app/user-management/karyawan.js" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadKaryawan(1);
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
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteKaryawan()">Delete</button>
        </div>
    </div>
