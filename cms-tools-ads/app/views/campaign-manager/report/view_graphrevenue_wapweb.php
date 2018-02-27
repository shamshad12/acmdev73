<?php $user = $this->session->userdata('profile');?>
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="reporting_profit_cost_list active"><a href="#reporting_profit_cost_list" data-toggle="tab">Revenue By WAP/WEB</a></li>                        
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="reporting_profit_cost_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Revenue By WAP/WEB</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                              	 <form method="post" action="#" id="reprt_sbmt">
                                   <div class="btn-group">
                                    <select class="m-wrap" id="country" name="country" style="width:150px;">
                                     <?php 
							 if($country['count']){
								 $countryData = "";
								for($i=0; $i<count($country['rows']); $i++){
									if($country['rows'][$i]['id'] == $user['id_country'])
									{
							 ?>
										<option value='<?php echo $country['rows'][$i]['id'];?>' selected="selected"><?php echo $country['rows'][$i]['name'];?></option>
							 <?php 		}
									else
									{
							?>
								<option value='<?php echo $country['rows'][$i]['id'];?>'><?php echo $country['rows'][$i]['name'];?></option>
							<?php
									}
								}
							 } 
							?>	             
                                    </select>
                                   </div>
                  								 <div class="btn-group">
                  									<select class="m-wrap" id="campaign_media" name="campaign_media" style="width:150px;">
                  									 <?php 
                  									 if($campaign_media['count']){
                  										 // $campaign_media = "";
                  										for($i=0; $i<count($campaign_media['rows']); $i++){
                  									 ?>
                  										<option value="<?php echo $campaign_media['rows'][$i]['id'];?>"><?php echo $campaign_media['rows'][$i]['name'];?></option>
                  									 <?php }
                  									 } ?>							 
                  									</select>
                  								 </div>		
                                   <div class="btn-group">
                                    <select class="m-wrap" id="select_month" name="select_month" style="width:150px;">
                                      <option value="">Select Month</option>
                                     <?php 
                                      for($i=1;$i<=date('n');$i++)
                                      {
                                        if($i<10)
                                        {
                                          $tableTraffic = 'traffic_0'.$i.date('Y');
                                        }
                                        else
                                        {
                                          $tableTraffic = 'traffic_'.$i.date('Y');
                                        }
                                     ?>
                                      <option value="<?php echo $tableTraffic;?>"><?php echo date('M Y', mktime(0, 0, 0, $i, 10));?></option>
                                     <?php 
                                      }
                                     ?>              
                                    </select>
                                   </div>   
                  								 <div class="btn-group">								
                  									<button class="btn green" title="search" type="button" onclick="loadgraphrevenue_wapweb(1)"><i class="icon-search"></i></button>
                  								 </div>
                  							</form>
                              	 <div id="container" style="min-width: 310px; height: 500px; margin: 0 auto">
                                   Loading...
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
    
    <script src="assets/js/metronic/app/campaign-manager/report/graphrevenue_wapweb.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
    <script src="assets/js/metronic/highchart/highcharts.js" type="text/javascript"></script>
    <script src="assets/js/metronic/highchart/exporting.js" type="text/javascript"></script>
	  <script type="text/javascript">
  		jQuery(document).ready(function() {		
  			loadgraphrevenue_wapweb(1);
  		});
	
      jQuery(function() {	
          jQuery('button').button();
      });
      
      jQuery(function() {
      });
        
    </script>