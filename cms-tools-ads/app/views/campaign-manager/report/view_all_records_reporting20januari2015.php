				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="traffic_reporting_list active"><a href="#traffic_reporting_list" data-toggle="tab">All Records Reporting List</a></li>                        
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="traffic_reporting_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>All Records Reporting List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								<div class="btn-group">
									<select class="m-wrap" id="operators-list" name="operators-list" style="width:150px;">
										<option value="">- Choose Operators -</option>
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
									<select class="m-wrap" id="campaign-list" name="campaign-list" style="width:150px;">
										<option value="">- Choose Campaigns -</option>
									 <?php 
									 if($campaigns['count']){
										 $campaignsData = "";
										for($i=0; $i<count($campaigns['rows']); $i++){
									 ?>
										<option value='<?php echo $campaigns['rows'][$i]['id'];?>'><?php echo $campaigns['rows'][$i]['name'];?></option>
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
										<option value='<?php echo $shortcodes['rows'][$i]['id'];?>'><?php echo $shortcodes['rows'][$i]['code'];?></option>
									 <?php }
									 } ?>							 
									</select>
								 </div>
								 
								 <div class="btn-group">
									<select class="m-wrap" id="ads-publisher-list" name="ads-publisher-list" style="width:150px;">
										<option value="">- Choose Publisher -</option>
									 <?php 
									 if($ads_publisher['count']){
										 $adsPublisherData = "";
										for($i=0; $i<count($ads_publisher['rows']); $i++){
									 ?>
										<option value='<?php echo $ads_publisher['rows'][$i]['id'];?>'><?php echo $ads_publisher['rows'][$i]['name'];?></option>
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
										<option value='5'>5</option>
										<option value='10'>10</option>
										<option value='25'>25</option>
										<option value='50'>50</option>
									</select>
								 </div>
								 <div class="btn-group">								
									<input class="m-wrap" id="search" name="search" type="text">
									<button class="btn green" type="button" onclick="loadAllRecords_Reporting(1)"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
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
    
    <script src="assets/js/metronic/app/campaign-manager/report/all_records_reporting.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "";
		
		jQuery(document).ready(function() {		
			loadAllRecords_Reporting(1);
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
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to delete this data?</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        </div>
    </div>
