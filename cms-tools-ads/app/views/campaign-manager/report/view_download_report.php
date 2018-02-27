				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="traffic_reporting_list active"><a href="#traffic_reporting_list" data-toggle="tab">Download Reporting List</a></li>                        
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="traffic_reporting_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Download Reporting List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	<form method="post" action="campaign_manager/download_report/generate" id="reprt_sbmt">
								 <div class="btn-group">
									<select class="m-wrap" id="select-date" name="selectdate" style="width:150px;" onchange="showhidedates(this.value)">
										<option value="0">- Choose an option -</option>
									 	<option value='1'>Today</option>
									 	<option value='2'>Daterange</option>					 
									</select>
								 </div>
								 <div class="btn-group">
									<select class="m-wrap" id="ad-network" name="adnetwork" style="width:130px;">
										<option value="0">- Select Ad Network -</option>
										<option class="hidden daterange6" value="1">All</option>
									 	<option value='M003'>M003</option>
									 	<option value='L001'>L001</option>
									 	<option value='Y001'>Y001</option>
									 	<option value='MX'>MX</option>
									 	<option value='B003'>B003</option>
									 	<option value='A011'>A011</option>
									 	<option value='CR01'>CR01</option>					 
									</select>
								 </div>
								 <div class="btn-group daterange1 hidden">
									<input class="m-wrap small" id="datefrom" name="datefrom" type="text" placeholder="Date From">
								 </div>
								 <div class="btn-group daterange2 hidden">
									<input class="m-wrap small" id="dateto" name="dateto" type="text" placeholder="Date To">
								 </div>		
								 <div class="btn-group daterange5">								
									<select class="m-wrap" id="status" name="status" style="width:120px;">
										<option value=''>Select Status</option>
										<option value='0'>Download</option>
										<option value='1'>Invalid Access</option>
									</select>
								 </div>			 
								 <div class="btn-group daterange3 hidden">								
									<select class="m-wrap" id="limit" name="limit" style="width:60px;">
										<option value='25'>25</option>
										<option value='50'>50</option>
										<option value='75'>75</option>
										<option value='200'>200</option>
									</select>
								 </div>
								 <div class="btn-group daterange4 hidden">								
									<input class="m-wrap" id="search" name="search" type="text" placeholder="" title="">
								 </div>
								 <div class="btn-group">
								 	<button class="btn green" type="button" title="search" onclick="loaddownload_report(1)"><i class="icon-search"></i></button>
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
    
    <script src="assets/js/metronic/app/campaign-manager/report/download_report.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "";
		
		jQuery(document).ready(function() {		
			loaddownload_report(1);
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
        
        jQuery(function() {	
			$( "#dateto, #datefrom" ).datepicker({
			  changeMonth: true,
			  changeYear: true,
			  maxDate: -1,
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