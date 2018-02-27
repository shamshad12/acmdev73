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
										<option value='25'>25</option>
										<option value='50'>50</option>
										<option value='75'>75</option> 
										<option value='100'>100</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by id, Campaign name, Country name, Campaign type, Campaign category or content" title="Search by id, Campaign name, Country name, Campaign type, Campaign category or content, Created User">
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
											<a href="#add_banner" data-toggle="modal">Add New Banner</a>
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
										<a href="javascript:void(0)" style="margin-top:5px;" id="alchemy_content_link" data-toggle="modal">Browse Alchemy Contents</a>
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

						<div class="control-group">
										<label class="control-label">Current Server Time</label>
										<div class="controls">	
											<span id="servertime"></span>
										</div>
									</div>
									
			
			<div class="control-group">
										<label class="control-label">Publish Start<span class="required"></span></label>
										<div class="controls">	
											<input type="text" placeholder="Publish Start" name="publish_start" id="publish_start" value='<?php echo date('Y/m/d H:i')?>' class="m-wrap small hasDatepicker pub_timepicker">
										
										</div>
									</div>

									<div class="control-group">
										<label class="control-label">Publish End<span class="required"></span></label>
										<div class="controls">	
											<input type="text" placeholder="Publish End" name="publish_end" id="publish_end" class="m-wrap small hasDatepicker pub_timepicker">
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
									<div class="control-group hide" id="hide_show_alrt_msg">
										<label class="control-label">Alert Message<span class="required"></span></label>
										<div class="controls">
											<textarea placeholder="Confirmation message" name="alrt_msg"  style="width:400px" rows="10"id="alrt_msg" class="m-wrap">
											</textarea>
											<label><code>for disable/inactive alert message: put alert message within &lt;disabled&gt;---&lt;/disabled&gt; tag</code></label>
										</div>
									</div>
									<div class="control-group hide" id="hide_show_thnk_msg">
										<label class="control-label">Thanks Message<span class="required"></span></label>
										<div class="controls">
											<textarea placeholder="Thank you message" name="thnk_msg" style="width:400px" rows="10" id="thnk_msg" class="m-wrap">
											</textarea>
										</div>
									</div>
									                                <div class="control-group hide" id="hide_publisher_alert">
                                                                            <label class="control-label">Configure alerts for publishers?<span class="required">*</span></label>
                                                                            <div class="controls">	
                                                                                <input type="checkbox" id="alert_checkbox" onclick="showHideConfiguration()">
                                                                            </div>
									</div>
                                                                        <div class="control-group hide configure_alert" id="hide_publisher_alert">
                                                                            <label class="control-label">Publisher code<span class="required">*</span></label>
                                                                            <div class="controls">	
                                                                                <input type="text" id="send_alert_publishers" placeholder="M001,M002" >
                                                                            </div>
									</div>
                                                                        <div class="control-group hide configure_alert" id="hide_publisher_alert">
                                                                            <label class="control-label">Alert Message<span class="required">*</span></label>
                                                                            <div class="controls">	
                                                                                <input type="text" id="alert_message"  >
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
    <script src="assets/js/jquery.datetimepicker.js" type="text/javascript"></script>
    <link href="assets/css/metronic/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
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
			//loadCampaigns(1);
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
	//strat and end date jquery
	jQuery(function(){
	jQuery('#publish_start').datetimepicker({
	  onShow:function( ct ){
	   this.setOptions({
	    minDate:new Date('<?php echo date("Y/m/d") ?>'),
		minTime:'<?php echo date("H:i") ?>',
		 })
	  },
	 });
	 jQuery('#publish_end').datetimepicker({
	  onShow:function( ct ){
	  	var min_val = jQuery('#publish_start').val();
		var arr = min_val.split(' ');
		min_date_end = arr[0];
	   this.setOptions({
	     minDate:min_date_end,
		 minTime:arr[1]
	   })
	   
	  },
	   onChangeDateTime:function(dp,$input){
	    var startDate = $("#publish_start").val().split(' ');
            var endDate = $input.val().split(' ');
	    if( startDate[0] < endDate[0] ){
		    this.setOptions({
		      minTime:'00:00'
		    });
		  }else{
		    this.setOptions({
		      minTime:startDate[1]
		    });
	    	}
	    }
	 });

/*	 jQuery('#publish_end').datetimepicker({
	  onShow:function( ct ){
	  	console.log(ct);
		var min_val = jQuery('#publish_start').val();
		var arr = min_val.split(' ');
		min_val_end = arr[0];
	   this.setOptions({
	     minDate:min_val_end,
		 minTime:'<?php echo date("H:i") ?>'
	   })
	   
	  },
	 /*onChangeDateTime:function(dp,$input){
    alert($input.val())
  }
	 }); */

	});
    </script>
	
<script type="text/javascript">

var currenttime = '<?php print date("F d, Y H:i:s", time())?>'; //PHP method of getting server date
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
var serverdate=new Date(currenttime);
function padlength(what){
var output=(what.toString().length==1)? "0"+what : what;
return output;
}
function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1);
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear();
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds());
document.getElementById("servertime").innerHTML=datestring+" "+timestring;
}
window.onload=function(){
setInterval("displaytime()", 1000);
}
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
    <div id="alchemy_content" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-header">
            <h4>Search Alchemy Content IDs</h4>
			<small>for package content please select type "Package" from content type dropdown and enter relative or exact name in the text-box.</small>
        </div>
        <div class="modal-body">
	    <iframe name="alchemy_content" id="iframe_content" src="" style="zoom:0.60" frameborder="0" height="680" width="100%"></iframe>		
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" id="addbtn" class="btn green">Add</button>
        </div>
		</div>
	<div id="add_banner" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-header">
            <h4>Add New Banner</h4>
        </div>
	<form action="#" id="formbanners" class="form-horizontal">
		<div class="modal-body">
		    	<div class="control-group">
				<label class="control-label">Banners Name<span class="required">*</span></label>
				<div class="controls">
					<input type="text" class="m-wrap" maxlength="25" id="banner_name" name="banner_name" placeholder="Enter Banner Name" style="width: 69%;">
				</div>
			</div>							
			<div class="control-group">
				<label class="control-label">Banner<span class="required">*</span></label>
				<div class="controls">
					<input type="file" class="m-wrap" multiple="" id="file_upload" name="file_upload">
					<span id="img-upload" class="help-block"></span>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		    	<input id="banner_id" class="span6 m-wrap" type="hidden" maxlength="50" name="id" value="">
			<input id="path_thumb" class="span2 m-wrap" type="hidden" name="path_thumb" value="">
			<input id="path_ori" class="span2 m-wrap" type="hidden" name="path_ori" value="">
			<input id="url_thumb" class="span6 m-wrap" type="hidden" name="url_thumb" value="">
			<input id="url_ori" class="span6 m-wrap" type="hidden" name="url_ori" value="">

		        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
		        <button type="button" class="btn green" onclick="saveBanner();">Save</button>
		</div>
	</form>
    </div>

<style>
#alchemy_content.modal{
	width:700px;
	height:80%;
	left:45%;
}
</style>
