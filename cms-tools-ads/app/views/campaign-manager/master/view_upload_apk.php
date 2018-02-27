
<div id="dashboard">
	<!-- BEGIN DASHBOARD STATS -->
	<br />
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			<li class="templates_list active"><a href="#templates_list"
				data-toggle="tab" title="Templates List">Apk List</a></li>
			<li class="templates_upload_form "><a href="#templates_upload_form" onclick="clearFormUpload()"
				data-toggle="tab" title="Create WEB Template by uploading ZIP." >APK Upload Form</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="templates_list">
				<br />
				<div class="portlet box blue portlet-list">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>APK List
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
						<!-- <a title="View Campaign list" style="color:white;font-size:18px;" href='campaign_manager/campaigns'> &nbsp;::&nbsp;View Campaigns</a> -->
					</div>
					<div class="portlet-body form">
						<div class="btn-group">
							<select class="m-wrap" id="search_user" name="search_user"
								style="width: 100px;">
								<option value="">- User -</option>
								<?php for($i=0; $i<count($user_list['rows']); $i++){ ?>
								<option value="<?php echo $user_list['rows'][$i]['id'];?>">
								<?php echo $user_list['rows'][$i]['username'];?>
								</option>
								<?php } ?>
							</select>
						</div>
						<div class="btn-group">
							<select class="m-wrap" id="limit" name="limit"
								style="width: 60px;">
								<option value='15'>15</option>
								<option value='25'>25</option>
								<option value='50'>50</option>
								<option value='100'>100</option>
							</select>
						</div>
						<div class="btn-group">
							<input class="m-wrap" id="search" name="search" type="text"
								placeholder="Search by Apk name, Apk Description, Country name."
								title="Search by Apk name, Apk Description, Country name.">
							<button class="btn green" type="button"
								onclick="loadTemplates(1)" title="Search">
								<i class="icon-search"></i>
							</button>
						</div>
						<div id="table-append"></div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane " id="templates_upload_form" >
				<!-- BEGIN DASHBOARD STATS -->
				<br />
				<div class="portlet box blue portlet-form">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>APK Upload Form
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body form">
						<form action="#" id="formtemplates_upload" class="form-horizontal">
						<?php $this->load->view('layout/_errorhandling');?>
							<div class="alert hide alert-error-upload-problem">
								<button class="close" data-dismiss="alert"></button>
								<span id="upload-problem"></span>
							</div>
							<input name="upload_id" id="upload_id" type="hidden" class="span6 m-wrap" />
							<input name="apkurl" id="apkurl" value="" type="hidden" class="span6 m-wrap" />

							<div class="control-group">
								<label class="control-label">Country<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="upload_id_country" id="upload_id_country"
										class="span6 m-wrap">
										<option value="">- Choose Country -</option>
										<?php for($i=0; $i<count($country['rows']); $i++){ ?>
										<option value="<?php echo $country['rows'][$i]['id'];?>">
										<?php echo $country['rows'][$i]['name'];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
                                                                
							<div class="control-group">
								<label class="control-label">Name<span
									class="required">*</span> </label>
								<div class="controls">
									<input name="upload_name" id="upload_name" maxlength="50" type="text" class="span6 m-wrap" /> 
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Description<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="upload_description" id="upload_description" type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">*.apk File<span class="required">*</span>
								</label>
								<div class="controls">
									<div style="width: 570px; float: left;">
										<input name="upload_file" id="upload_file" type="file" class="span6 m-wrap"/>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Status<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="upload_status" id="upload_status"
										class="span6 m-wrap">
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>
							<div class="form-actions">
								<button disabled="disabled" id="upload_web_temp"
									type="<?php echo ($accessAdd)?'submit':'button';?>"
									<?php echo ($accessAdd)?'':'disabled="disabled"';?>
									class="btn purple">Save</button>
								<button type="button" class="btn" onClick="clearFormUpload()">Cancel</button>
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
<script
	src="assets/js/metronic/plugins/data-tables/jquery.dataTables.js"
	type="text/javascript"></script>
<script
	src="assets/js/metronic/plugins/data-tables/DT_bootstrap.js"
	type="text/javascript"></script>
<script
	src="assets/js/metronic/plugins/jquery-validation/dist/jquery.validate.min.js"
	type="text/javascript"></script>
<script
	src="assets/js/metronic/plugins/jquery-validation/dist/additional-methods.min.js"
	type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script
	src="../../ads/assets/js/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<script
	src="assets/js/metronic/app/campaign-manager/master/upload_apk.js?<?php echo date('H:i:s');?>"
	type="text/javascript"></script>
<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadTemplates(1);
			UploadFormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
		});
        
		    </script>
<div id="delete" class="modal hide fade" tabindex="-1"
	data-backdrop="static" data-keyboard="false">
	<div class="modal-body">
		<p>
		<?php echo DELETE_MESSAGE;?>
		</p>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn">Cancel</button>
		<button type="button" data-dismiss="modal" class="btn green"
			onClick="deleteApk('')">Delete</button>
	</div>
</div>
