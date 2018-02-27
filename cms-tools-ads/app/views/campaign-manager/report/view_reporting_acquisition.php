				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="reporting_acquisition_list active"><a href="#reporting_acquisition_list" data-toggle="tab">Acquisition Reporting List</a></li>                        
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="reporting_acquisition_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Acquisition Reporting List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	 <form method="post" action="campaign_manager/reporting_acquisition/generate" id="reprt_sbmt">	 
								 <div class="btn-group">								
									<select class="m-wrap" id="is_publisher_send" name="is_publisher_send" style="width:110px;">
										<option value='*'>Send Status</option>
										<option value='1'>Send</option>
										<option value='0'>Not Send</option>
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
									<select class="m-wrap" id="partner-list" name="partner-list" style="width:150px;">
										<option value="">- Choose Partner -</option>
									 <?php 
									 if($partners['count']){
										 $operatorsData = "";
										for($i=0; $i<count($partners['rows']); $i++){
									 ?>
										<option value='<?php echo $partners['rows'][$i]['id'];?>'><?php echo $partners['rows'][$i]['name'];?></option>
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
									<input class="m-wrap" id="search" name="search" type="text" placeholder="Search by ACM transaction id or MSISDN" title="Search by ACM transaction id or MSISDN">
									<button class="btn green" type="button" onclick="loadReporting_Acquisition(1)"><i class="icon-search"></i></button>
								 </div>
								 <div class="btn-group">					
									<button class="btn green" type="button" title="Download *.csv" onclick="checkDateEntered()"><i class="icon-download"></i></button>
								 </div>
								 </form>
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
    
    <script src="assets/js/metronic/app/campaign-manager/report/reporting_acquisition.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "";
		
		jQuery(document).ready(function() {		
			loadReporting_Acquisition(1);
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
    <!--div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p>Are you sure want to delete this data?</p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        </div>
    </div-->

    <div id="show_detail" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="max-width:600px;max-height:600px;">
    	<!--button type="button" data-dismiss="modal" class="btn"><span class="icon-remove"></span></button-->
		<p style="text-align:center">Full Detail</p>
        <div class="modal-footer" id="modal-body" style="overflow:scroll;max-width:600px;max-height:400px;">
			
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn pull-right">Close</button>
        </div>
    </div>
