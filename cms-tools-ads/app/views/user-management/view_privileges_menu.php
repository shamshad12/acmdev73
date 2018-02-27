				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
				   <br />   
				   <div class="portlet box blue portlet-list">
					  <div class="portlet-title">
						 <div class="caption"><i class="icon-reorder"></i>PrivilegesMenu List</div>
						 <div class="tools">
							<a href="javascript:;" class="collapse"></a>
						 </div>
					  </div>
					  <div class="portlet-body form">
						 <div class="btn-group">							
							<select class="m-wrap" id="privilege" name="privilege" onchange="loadPrivilegesMenu()" style="width:200px;margin-top:10px">
								<option value=''>All Privilege</option>
								<?php 
									if($privileges['count']){
										for($i=0; $i<count($privileges['rows']); $i++){
								?>
								<option value="<?php echo $privileges['rows'][$i]['id'];?>"><?php echo $privileges['rows'][$i]['description'];?></option>
								<?php } } ?>
							</select>
						 </div>
						 <div class="btn-group">								
							<button class="btn blue" <?php echo ($accessAdd)?'type="button" data-toggle="modal" href="#add"':'';?> <?php echo ($accessAdd)?'':'disabled="disabled"';?>>Add</button>
						 </div>
						 <div id="table-append"></div>
					  </div>
				   </div>
				</div>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="assets/js/metronic/plugins/data-tables/jquery.dataTables.js" type="text/javascript"></script>  
    <script src="assets/js/metronic/plugins/data-tables/DT_bootstrap.js" type="text/javascript"></script>
    <script src="assets/js/metronic/plugins/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
	<script src="assets/js/metronic/plugins/jquery-validation/dist/additional-methods.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    
    <script src="assets/js/metronic/app/user-management/privileges_menu.js" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadPrivilegesMenu();
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="add" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="width:width+100px;">
        <div class="modal-body">
            <div class="portlet-body form" style="margin-left:-10px;margin-top:20px">                              
				 <form action="#" id="formprivileges_menu" class="form-horizontal" >
					<?php $this->load->view('layout/_errorhandling');?>
					<div class="control-group" style="margin-left:-50px">
						<label class="control-label">Name<span class="required">*</span></label>
						<div class="controls">
							<input name="id" id="id" type="hidden" />
							<input name="name" id="name" maxlength="50" type="text" class="span4 m-wrap"/>
						</div>
					</div>
					<div class="control-group" style="margin-left:-50px">
						<label class="control-label">Description<span class="required">*</span></label>
						<div class="controls">
							<input name="description" id="description" maxlength="50" type="text" class="span4 m-wrap"/>
						</div>
					</div>
					<div class="control-group" style="margin-left:-50px">
						<label class="control-label">Url<span class="required">*</span></label>
						<div class="controls">
							<input name="url" id="url" maxlength="50" type="text" class="span4 m-wrap"/>
						</div>
					</div>
					<div class="control-group" style="margin-left:-50px">
						<label class="control-label">Icon<span class="required">*</span></label>
						<div class="controls">
							<select name="icon" id="icon" class="span6 m-wrap">		
								<option value="">--Icon--</option>										
								<option value="home">home</option>
								<option value="group">group</option>
								<option value="basket">basket</option>
								<option value="rocket">rocket</option>
								<option value="star">star</option>
								<option value="diamond">diamond</option>
								<option value="puzzle">puzzle</option>
								<option value="setting">setting</option>
								<option value="logout">logout</option>
								<option value="envelope-open">envelope-open</option>
								<option value="docs">docs</option>
								<option value="present">present</option>
								<option value="folder">folder</option>
								<option value="user">user</option>
								<option value="briefcase">briefcase</option>
								<option value="wallet">wallet</option>
								<option value="pointer">pointer</option>
								<option value="chart">chart</option>
								<option value="cog">cog</option>
							</select>
						</div>
					</div>
					<div class="control-group" style="margin-left:-50px">
						<label class="control-label">Parent<span class="required">*</span></label>
						<div class="controls">	
							<select name="parent" id="parent" class="span4 m-wrap"></select>
						</div>
					</div>
					<div class="control-group" style="margin-left:-50px">
						<label class="control-label">Status<span class="required">*</span></label>
						<div class="controls">
							<select name="status" id="status" class="span4 m-wrap">		
								<option value="">--Status--</option>										
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
							<span class="help-block"></span>
						</div>
					</div>		
					<div class="form-actions">
						<button type="<?php echo ($accessEdit)?'submit':'button';?>" <?php echo ($accessEdit)?'':'disabled="disabled"';?> class="btn purple">Save</button>
						<button type="button" data-dismiss="modal" class="btn" onClick="clearForm()">Cancel</button>
					</div>
				</form>
				<!-- END FORM-->
			  </div>
        </div>
    </div>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to delete All menu in All Privileges??</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deletePrivilegesMenu()">Delete</button>
        </div>
    </div>
