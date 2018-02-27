<?php $user = $this->session->userdata('profile');?>
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="reporting_profit_cost_list active"><a href="#reporting_profit_cost_list" data-toggle="tab">Monthly Revenue By Vendor</a></li>                        
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="reporting_profit_cost_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Monthly Revenue By Vendor</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	 <form method="post" action="campaign_manager/graphrevenue_vendor/generate" id="reprt_sbmt">
                  					
									<div class="btn-group">
                                    <select class="m-wrap" id="partner-list" name="partner-list" style="width:150px;">
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
                  									<button class="btn green" title="search" type="button" onclick="loadgraphrevenue_vendor(1)"><i class="icon-search"></i></button>
                  								 </div>
                  							</form>
                              	 <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto">
                                   Loading graph please wait...
                                 </div>
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
    
    <script src="assets/js/metronic/app/campaign-manager/report/graphrevenue_vendor.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
    <script src="assets/js/metronic/highchart/highcharts.js" type="text/javascript"></script>
    <script src="assets/js/metronic/highchart/exporting.js" type="text/javascript"></script>
	  <script type="text/javascript">
  		jQuery(document).ready(function() {		
  			loadgraphrevenue_vendor(1);
  		});
	
      jQuery(function() {	
          jQuery('button').button();
      });
      
      jQuery(function() {
      });
        
    </script>