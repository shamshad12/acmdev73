
<div id="dashboard">
	<!-- BEGIN DASHBOARD STATS -->
	<br />
	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			<li class="templates_list active"><a href="#templates_list"
				data-toggle="tab" title="Templates List">Templates List</a></li>
			<li class="templates_form "><a href="#templates_form"
				data-toggle="tab" title="Create WAP/WEB Template">Templates Form</a>
			</li>
			<li class="templates_static_form "><a href="#templates_static_form"
				data-toggle="tab" title="Create static WEB Template">Templates Web
					Static Form</a></li>
			<li class="templates_upload_form "><a href="#templates_upload_form"
				data-toggle="tab" title="Create WEB Template by uploading ZIP." onClick="clearFormEdit()">Templates
					WEB/WAP Upload Form</a></li>
					<li class="locked_template_list "><a href="#locked_template_list"
				data-toggle="tab" onclick="loadLockedTemplate()" title="Locked Template List" >Locked Template List</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="templates_list">
				<br />
				<div class="portlet box blue portlet-list">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>Templates List
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
						<a title="View Campaign list" style="color:white;font-size:18px;" href='campaign_manager/campaigns'> &nbsp;::&nbsp;View Campaigns</a>
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
							<select class="m-wrap" id="search_media" name="search_media"
								style="width: 100px;">
								<option value="">- Media -</option>
								<?php for($i=0; $i<count($campaign_media['rows']); $i++){ ?>
								<option value="<?php echo $campaign_media['rows'][$i]['id'];?>">
								<?php echo $campaign_media['rows'][$i]['name'];?>
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
								placeholder="Search by Id, Template name, Country name and Campaign media. "
								title="Search by Id, Template name, Country name and Campaign media. ">
							<button class="btn green" type="button"
								onclick="loadTemplates(1)" title="Search">
								<i class="icon-search"></i>
							</button>
						</div>
						<div id="table-append"></div>
					</div>
				</div>
			</div>
			<div class="tab-pane " id="templates_form">
				<!-- BEGIN DASHBOARD STATS -->
				<br />
				<div class="portlet box blue portlet-form">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>Templates Form
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body form">
						<form action="#" id="formtemplates" class="form-horizontal">
						<?php $this->load->view('layout/_errorhandling');?>
							<input name="id" id="id" maxlength="50" type="hidden"
								class="span6 m-wrap" /> <input name="is_uploaded"
								id="is_uploaded" maxlength="50" type="hidden"
								class="span6 m-wrap" />
							<div class="control-group">
								<label class="control-label">Country<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="id_country" id="id_country" class="span6 m-wrap">
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
								<label class="control-label">Campaign Media<span
									class="required">*</span> </label>
								<div class="controls">
									<select name="id_campaign_media" id="id_campaign_media"
										class="span6 m-wrap">
										<option value="">- Choose Media -</option>
										<?php for($i=0; $i<count($campaign_media['rows']); $i++){ ?>
										<option
											value="<?php echo $campaign_media['rows'][$i]['id'];?>">
											<?php echo $campaign_media['rows'][$i]['name'];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Templates Name<span
									class="required">*</span> </label>
								<div class="controls">
									<input name="name" id="name" maxlength="50" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Description<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="description" id="description" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
                                                        
                                                        <div class="control-group">
								<label class="control-label">CSS<span class="required">*</span>
								</label>
								<div class="controls">
									<textarea name="wap_css" id="wap_css" type="text"
										class="span6 m-wrap" style="height:200px;width:560px;"/></textarea>
                                                                                <input type="hidden" id="css_file_name">
								</div>
							</div>
                                                        
							<div class="control-group" id="page-confirm">
								<label class="control-label">Page Confirm<span class="required">*</span>
								</label>
								<div class="controls">
									<textarea name="page_confirm" id="page_confirm" class="m-wrap"></textarea>
								</div>
							</div>
							<div class="control-group" id="page-status">
								<label class="control-label">Page Status<span class="required">*</span>
								</label>
								<div class="controls">
									<textarea name="page_status" id="page_status" class="m-wrap"></textarea>
								</div>
							</div>
							<div class="control-group" id="page-error">
								<label class="control-label">Page Error<span class="required">*</span>
								</label>
								<div class="controls">
									<textarea name="page_error" id="page_error" class="m-wrap"></textarea>
								</div>
							</div>
							<div class="control-group" id="btn-confirm" style="display: none">
								<label class="control-label">Page Confirm<span class="required">*</span>
								</label>
								<div class="controls">
									<a href="" class="span1 btn blue" id="btn_page_confirm"
										target="_blank">Edit</a>
								</div>
							</div>
							<div class="control-group" id="btn-status" style="display: none">
								<label class="control-label">Page Status<span class="required">*</span>
								</label>
								<div class="controls">
									<a href="" class="span1 btn blue" id="btn_page_status"
										target="_blank">Edit</a>
								</div>
							</div>
							<div class="control-group" id="btn-footer" style="display: none">
								<label class="control-label">Page Footer<span class="required">*</span>
								</label>
								<div class="controls">
									<a href="" class="span1 btn blue" id="btn_page_footer"
										target="_blank">Edit</a>
								</div>
							</div>
							<div class="control-group" id="btn-error" style="display: none">
								<label class="control-label">Page Error<span class="required">*</span>
								</label>
								<div class="controls">
									<a href="" class="span1 btn blue" id="btn_page_error"
										target="_blank">Edit</a>
								</div>
							</div>
							<div class="control-group" id="btn-css" style="display: none">
								<label class="control-label">Page Css<span class="required">*</span>
								</label>
								<div class="controls">
									<a href="" class="span1 btn blue" id="btn_page_css"
										target="_blank">Edit</a>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Status<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="status" id="status" class="span6 m-wrap">
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>
							<div class="form-actions">
								<button type="<?php echo ($accessAdd)?'submit':'button';?>"
								<?php echo ($accessAdd)?'':'disabled="disabled"';?>
									class="btn purple">Save</button>
								<button type="button" class="btn" onClick="clearForm()">Cancel</button>
							</div>
						</form>
						<!-- END FORM-->
					</div>
				</div>
			</div>
			<div class="tab-pane " id="templates_static_form">
				<!-- BEGIN DASHBOARD STATS -->
				<br />
				<div class="portlet box blue portlet-form">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>Templates Web Static Form
						</div>
						<div class="tools">
							<a href="javascript:;" class="collapse"></a>
						</div>
					</div>
					<div class="portlet-body form">
						<form action="#" id="formtemplates_static" class="form-horizontal">
						<?php $this->load->view('layout/_errorhandling');?>
							<input name="static_id" id="static_id" maxlength="50"
								type="hidden" class="span6 m-wrap" /> <input type="hidden"
								name="static_id_campaign_media" id="static_id_campaign_media"
								class="span6 m-wrap" value="2" />
							<div class="control-group">
								<label class="control-label">Country<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="static_id_country" id="static_id_country"
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
								<label class="control-label">Default Template<span
									class="required">*</span> </label>
								<div class="controls">
									<select name="static_id_template" id="static_id_template"
										class="span6 m-wrap">
										<option value="">- Choose Template -</option>
										<?php for($i=0; $i<count($templateStatic['rows']); $i++){ ?>
										<option
											value="<?php echo $templateStatic['rows'][$i]['id'];?>">
											<?php echo $templateStatic['rows'][$i]['name'];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Templates Name<span
									class="required">*</span> </label>
								<div class="controls">
									<input name="static_name" id="static_name" maxlength="50"
										type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Description<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_description" id="static_description"
										type="text" class="span6 m-wrap" />
								</div>
							</div>
							<hr>
							<div class="control-group">
								<label class="control-label"><strong>Confirmation Page</strong>
								</label>
								<div class="controls"></div>
							</div>
							<div class="control-group">
								<label class="control-label">Header Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_conf_header_text"
										id="static_conf_header_text" type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Banner<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="static_conf_id_banner" id="static_conf_id_banner"
										class="span6 m-wrap">
										<option value="">- Choose Banner -</option>
										<?php for($i=0; $i<count($banner['rows']); $i++){ ?>
										<option value="<?php echo $banner['rows'][$i]['id'];?>">
										<?php echo $banner['rows'][$i]['name'];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_conf_text" id="static_conf_text"
										type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">MSISDN Prefix<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_conf_msisdn_prefix"
										id="static_conf_msisdn_prefix" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Button Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_conf_button_text"
										id="static_conf_button_text" type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">T&C Description<span
									class="required">*</span> </label>
								<div class="controls">
									<textarea name="static_conf_tc_description"
										id="static_conf_tc_description" class="span6 m-wrap"></textarea>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Footer Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_conf_footer_text"
										id="static_conf_footer_text" type="text" class="span6 m-wrap" />
								</div>
							</div>
							<hr>
							<div class="control-group">
								<label class="control-label">Thanks Page</label>
								<div class="controls"></div>
							</div>
							<div class="control-group">
								<label class="control-label">Header Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_thanks_header_text"
										id="static_thanks_header_text" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Banner<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="static_thanks_id_banner"
										id="static_thanks_id_banner" class="span6 m-wrap">
										<option value="">- Choose Banner -</option>
										<?php for($i=0; $i<count($banner['rows']); $i++){ ?>
										<option value="<?php echo $banner['rows'][$i]['id'];?>">
										<?php echo $banner['rows'][$i]['name'];?>
										</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_thanks_text" id="static_thanks_text"
										type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">SMS Keyword<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_thanks_sms_keyword"
										id="static_thanks_sms_keyword" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">SMS Shortcode<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_thanks_sms_shortcode"
										id="static_thanks_sms_shortcode" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Button Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_thanks_button_text"
										id="static_thanks_button_text" type="text"
										class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">T&C Description<span
									class="required">*</span> </label>
								<div class="controls">
									<textarea name="static_thanks_tc_description"
										id="static_thanks_tc_description" class="span6 m-wrap"></textarea>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Footer Text<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="static_thanks_footer_text"
										id="static_thanks_footer_text" type="text"
										class="span6 m-wrap" />
								</div>
							</div>


							<div class="control-group">
								<label class="control-label">Status<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="static_status" id="static_status"
										class="span6 m-wrap">
										<option value="1">Active</option>
										<option value="0">Inactive</option>
									</select>
								</div>
							</div>
							<div class="form-actions">
								<button type="<?php echo ($accessAdd)?'submit':'button';?>"
								<?php echo ($accessAdd)?'':'disabled="disabled"';?>
									class="btn purple">Save</button>
								<button type="button" class="btn" onClick="clearFormStatic()">Cancel</button>
							</div>
						</form>
						<!-- END FORM-->
					</div>
				</div>
			</div>
			<div class="tab-pane " id="templates_upload_form">
				<!-- BEGIN DASHBOARD STATS -->
				<br />
				<div class="portlet box blue portlet-form">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>Templates WEB/WAP Upload Form
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
							<input name="upload_id" id="upload_id" maxlength="50"
								type="hidden" class="span6 m-wrap" /> <input type="hidden"
								name="upload_id_campaign_media" id="upload_id_campaign_media"
								class="span6 m-wrap" value="2" /> <input type="hidden"
								name="upload_page_confirm" id="upload_page_confirm"
								class="span6 m-wrap" /> <input type="hidden"
								name="upload_page_status" id="upload_page_status"
								class="span6 m-wrap" />
								<input type="hidden" name="upload_page_pin" id="upload_page_pin" class="span6 m-wrap" />								
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
								<label class="control-label">Upload Media<span class="required">*</span>
								</label>
								<div class="controls">
									<select name="upload_id_media" id="upload_id_media"
										class="span6 m-wrap" onChange="activateUploadField(this.value)">
										<option value="">- Choose Media -</option>
										<option value="1">WAP</option>
                                                                                <option value="2">WEB</option>
									</select>
								</div>
							</div>
                                                                
							<div class="control-group">
								<label class="control-label">Templates Name<span
									class="required">*</span> </label>
								<div class="controls">
									<input name="upload_name" id="upload_name" maxlength="50"
										type="text" class="span6 m-wrap" /> <input
										name="upload_folder" id="upload_folder" type="hidden"
										class="span6 m-wrap" />
										 <input name="upload_id" id="upload_id"
										type="hidden" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Description<span class="required">*</span>
								</label>
								<div class="controls">
									<input name="upload_description" id="upload_description"
										type="text" class="span6 m-wrap" />
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">*.zip File<span class="required">*</span>
								</label>
								<div class="controls">
											<div style="width: 570px; float: left;">
												<input name="upload_file" id="upload_file" type="file" class="span6 m-wrap" disabled="disabled"/>
											</div>
											<div style="width: 150px ! important; float: left;display:none;" id="btn-preview">
												<a href="" class="span1 btn blue" id="btn_page_preview" target="_blank">Preview</a>
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
			
			<!--Locke Tempalet List -->
			<div class="tab-pane" id="locked_template_list">
				<br />
				<div class="portlet box blue portlet-list">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-reorder"></i>Locked Templates List
						</div>
					</div>
					

					<div class="portlet-body form">

						
					
						<div id="table-append-locked"></div>
					</div>
				</div>
			</div>
			<!-- end of Locked template List -->
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
	src="assets/js/metronic/app/campaign-manager/master/templates.js?<?php echo date('H:i:s');?>"
	type="text/javascript"></script>
<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			var templateid = '<?php echo $template_id; ?>';
			   if(templateid != '')
			   {
			    uploadNewTemplate(templateid);
			   }
			loadTemplates(1);
			FormValidation.init();
			StaticFormValidation.init();
			UploadFormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        
		CKEDITOR.replace( 'page_confirm', {
                        filebrowserBrowseUrl: 'http://'+document.location.hostname+'/ads/assets/js/ckeditor/browser/browser.php?type=Files',
                        filebrowserUploadUrl: 'http://'+document.location.hostname+'/ads/assets/js/ckeditor/uploader.php?type=Files',
			fullPage: true,
			allowedContent: true,
			extraPlugins: 'wysiwygarea'
		});
		
		CKEDITOR.replace( 'page_status', {
			fullPage: true,
			allowedContent: true,
			extraPlugins: 'wysiwygarea'
		});
	
		CKEDITOR.replace( 'page_error', {
			fullPage: true,
			allowedContent: true,
			extraPlugins: 'wysiwygarea'
		});

		
		});
        // check all
		
function toggle(source) {
  $("input:checkbox").prop('checked', $(source).prop("checked"));
}
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
			onClick="deleteTemplates('')">Delete</button>
	</div>
</div>
<div id="changestatus" class="modal hide fade" tabindex="-1"
	data-backdrop="static" data-keyboard="false">
	<div class="modal-body">
		<p>Are you sure want to change status?</p>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn">Cancel</button>
		<button type="button" data-dismiss="modal" class="btn green"
			onClick="changeStatus()">Change</button>
	</div>
</div>
