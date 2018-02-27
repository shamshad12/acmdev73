<?php $user = $this->session->userdata('profile');?>	
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Reports2 Reporting List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	 <form method="post" action="campaign_manager/report2_reporting/generate" id="reprt_sbmt">
                              	 <div class="btn-group">
									<select class="m-wrap" id="country-list" name="country-list" style="width:150px;">
									<!--<option value="">- Choose Country -</option>-->
									 <?php 
									 if($country['count']){
										 $countryData = "";
										for($i=0; $i<count($country['rows']); $i++){
											//if($country['rows'][$i]['id'] == $user['id_country'])
											//{
									 ?>
												<!--<option value='<?php //echo $country['rows'][$i]['id'];?>' selected="selected"><?php //echo $country['rows'][$i]['name'];?></option> -->
									 <?php 		//}
											//else
											//{
									?>
										<option value='<?php echo $country['rows'][$i]['id'];?>'><?php echo $country['rows'][$i]['name'];?></option>
									<?php
											//}
										}
									 } ?>							 
									</select>
								 </div>
								 <div class="btn-group">								
									<select class="m-wrap" id="search_media" name="media" style="width:100px;">
										<option value="">- Media -</option>
										<?php for($i=0; $i<count($campaign_media['rows']); $i++){ ?>
										<option value="<?php echo $campaign_media['rows'][$i]['id'];?>"><?php echo $campaign_media['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div>
								 <div class="btn-group">
									<select class="m-wrap" id="operator-list" name="operator-list" style="width:150px;">
										<option value="">- Choose Operator -</option>
									 <?php 
									 if($operators['count']){
										 $operatorsData = "";
										for($i=0; $i<count($operators['rows']); $i++){
									 ?>
										<option value='<?php echo $operators['rows'][$i]['id'];?>'><?php echo $operators['rows'][$i]['name'];?></option>
									 <?php }
									 } ?>							 
									</select>
								 </div>
								 <div class="btn-group">
									<select class="m-wrap" id="ads-publisher-list" name="ads-publisher-list" style="width:150px;">
										<option value="">- Choose Vendors -</option>
									 <?php 
									 if($ads_publisher['count']){
										 $operatorsData = "";
										for($i=0; $i<count($ads_publisher['rows']); $i++){
									 ?>
										<option value='<?php echo $ads_publisher['rows'][$i]['id'];?>'><?php echo $ads_publisher['rows'][$i]['name'];?></option>
									 <?php }
									 } ?>							 
									</select>
								 </div>
								 <div class="btn-group">
									<select class="m-wrap" id="shortcode-list" name="shortcode-list" style="width:150px;">
										<option value="">- Choose Shortcode -</option>
									 <?php 
									 if($shortcodes['count']){
										 $shortcodesData = "";
										for($i=0; $i<count($shortcodes['rows']); $i++){
									 ?>
										<option value='<?php echo $shortcodes['rows'][$i]['code'];?>'><?php echo $shortcodes['rows'][$i]['code'];?></option>
									 <?php }
									 } ?>							 
									</select>
								 </div>
								 <div class="btn-group">
									<input class="m-wrap small" id="datefrom" name="datefrom" type="text" placeholder="Date From">
								 </div>
								 <div class="btn-group">
									<input class="m-wrap small" id="dateto" name="dateto" type="text" placeholder="Date To">
								 </div>								 
								 <div class="btn-group">								
									<select class="m-wrap" id="limit" name="limit" style="width:60px;">
										<option value='25'>25</option>
										<option value='50'>50</option>
										<option value='75'>75</option>
										<option value='200'>200</option>
									</select>
								 </div>
								 <div class="btn-group">								
								<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by Campaign name" title="Search by Campaign name">
									<button class="btn green" type="button" title="search" onclick="loadreport2_reporting(1,'')"><i class="icon-search"></i></button>
								 </div>
								 <div class="btn-group">					
									<button class="btn green" type="button" style="margin-top:-10px" title="Download *.csv" onclick="checkDateEntered()"><i class="icon-download"></i></button>
									<button class="btn green" type="button" style="margin-top:-10px" title="" onclick="loadreport2_reporting(1,1)">Today</button>
									<button class="btn green" type="button" style="margin-top:-10px" title="" onclick="loadreport2_reporting(1,-1)">Yesterday</button>
									<button class="btn green" type="button" style="margin-top:-10px" title="" onclick="loadreport2_reporting(1,7)">Last 7 Days</button>
									<button class="btn green" type="button" style="margin-top:-10px" title="" onclick="loadreport2_reporting(1,30)">Last 30 Days</button>
									<button class="btn green" type="button" style="margin-top:-10px" title="" onclick="loadreport2_reporting(1,-30)">Prev Month</button>
									<input type="hidden" value="" id="days" name="days"/>
								 </div>
								<!-- <br/>
								Group By :
								<input type="checkbox" value="grpoperator" name="grpoperator">
								Operator
								<input type="checkbox" value="grpdate" name="grpdate">
								Date
								<input type="checkbox" value="grpshortcode" name="grpshortcode">
								Short Code
								<input type="checkbox" value="grpdate" name="grphour">
								Hour
								<input type="checkbox" value="grpcampaign" name="grpcampaign">
								Campaign
								<input type="checkbox" value="grpvendor" name="grpvendor">
								Vendor 
								<br><br> -->
								 <!-- <div class="btn-group">					
									<button class="btn green" type="button" style="margin-top:-10px" title="Download *.csv" onclick="checkDateEntered()"><i class="icon-download"></i></button>
								 </div> -->
								 </form>
                              	 <div id="table-append">Loading...</div>
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
    <!-- END PAGE LEVEL PLUGINS -->
    
    <script src="assets/js/metronic/app/campaign-manager/report/report2_reporting.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "";
		
		jQuery(document).ready(function() {		
			loadreport2_reporting(1,'');
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
        
        jQuery(function() {	
			$( "#dateto, #datefrom" ).datepicker({
			  changeMonth: true,
			  changeYear: true,
			  dateFormat: 'yy-mm-dd'
			});
        });
        
    </script>