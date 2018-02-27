        <div id="dashboard">
          <!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="traffic_reporting_list active"><a href="#traffic_reporting_list" data-toggle="tab">Operator Overview List</a></li>                        
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="traffic_reporting_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Operator Overview List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
                                 <form method="post" action="index.php/campaign_manager/operator_overview/generate" id="operator_overview_form">
                                    <!-- <div class="btn-group">
                                      <select class="m-wrap" id="day-list" name="day-list" style="width:150px;">
                                          <option value="0">- Choose Day -</option>
                                          <option value="1">- Today -</option>
                                          <option value="2">- Yesterday -</option>
                                      </select>
                                    </div> -->

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
                                      <button class="btn green" type="button" style="margin-top: -10px;" onclick="loadoperator_overview(1)"><i class="icon-search"></i></button> 
                                    </div>
                                    <!-- <div class="btn-group">         
                                      <button class="btn green operator_overview_btn" onclick="formsbmt()" type="button" style="margin-top:-10px" title="Download *.csv"><i class="icon-download"></i></button>
                                    </div> -->
                                  </form>
                                <div id="table-append-today"></div>
                                <div id="table-append-yesterday">Loading Today/Yesterday Data...</div>
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
    
    <script src="assets/js/metronic/app/campaign-manager/report/operator_overview.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
  <script type="text/javascript">
    var deleteID = "";
    
    jQuery(document).ready(function() {   
      loadoperator_overview(1);
    });
  
        /*jQuery(function() { 
            jQuery('button').button();
        });*/
        
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