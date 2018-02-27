				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
                          <br />      
                  <div class="tabbable tabbable-custom boxless">
                     <ul class="nav nav-tabs">
                        <li class="operators_partners_list active"><a href="#operators_partners_list" data-toggle="tab">Operators Partners List</a></li>
                        <li class="operators_partners_form "><a href="#operators_partners_form" data-toggle="tab">Operators Partners Form</a></li>
                     </ul>
                     <div class="tab-content">
                        <div class="tab-pane active" id="operators_partners_list">
                        <br />  
                           <div class="portlet box blue portlet-list">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Operators Partners List</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">
								 <div class="btn-group">								
									<select class="m-wrap" id="search_operator" name="search_operator" style="width:120px;">
										<option value="">- Operator -</option>
										<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
										<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
								 </div> 
								 <div class="btn-group">								
									<select class="m-wrap" id="search_partner" name="search_partner" style="width:100px;">
										<option value="">- Partner -</option>
										<?php for($i=0; $i<count($partner['rows']); $i++){ ?>
										<option value="<?php echo $partner['rows'][$i]['id'];?>"><?php echo $partner['rows'][$i]['name'];?></option>
										<?php } ?>
									</select>
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
									<input class="m-wrap" id="search" name="search" type="text" Placeholder="Search by Id, Operator name, Operator share, Operator name, Partner share, Partner name or VAT" title="Search by Id, Operator name, Operator share, Operator name, Partner share, Partner name or VAT">
									<button class="btn green" type="button" onclick="loadOperators_Partners(1)" title="Search"><i class="icon-search"></i></button>
								 </div>
                              	 <div id="table-append"></div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-pane " id="operators_partners_form">
                           <!-- BEGIN DASHBOARD STATS -->
                      <br />            
                           <div class="portlet box blue portlet-form">
                              <div class="portlet-title">
                                 <div class="caption"><i class="icon-reorder"></i>Operators Partners Form</div>
                                 <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                 </div>
                              </div>
                              <div class="portlet-body form">                              
                                 <form action="#" id="formoperators_partners" class="form-horizontal">
									<?php $this->load->view('layout/_errorhandling');?>
									<input name="id" id="id" maxlength="50" type="hidden" class="span6 m-wrap"/>
									<div class="control-group">
										<label class="control-label">Operator<span class="required">*</span></label>
										<div class="controls">
											<select name="id_operator" id="id_operator" class="span6 m-wrap">
												<option value="">- Choose Operator -</option>
												<?php for($i=0; $i<count($operator['rows']); $i++){ ?>
												<option value="<?php echo $operator['rows'][$i]['id'];?>"><?php echo $operator['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>									
									<div class="control-group">
										<label class="control-label">Partner<span class="required">*</span></label>
										<div class="controls">
											<select name="id_partner" id="id_partner" class="span6 m-wrap">
												<option value="">- Choose Operator -</option>
												<?php for($i=0; $i<count($partner['rows']); $i++){ ?>
												<option value="<?php echo $partner['rows'][$i]['id'];?>"><?php echo $partner['rows'][$i]['name'];?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Rev. Share Operator<span class="required">*</span></label>
										<div class="controls">
											<input name="share_operator" id="share_operator" maxlength="5" type="text" class="span3 m-wrap" /> % 
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Rev. Share Partner<span class="required">*</span></label>
										<div class="controls">	
											<input name="share_partner" id="share_partner" maxlengt="5" type="text" class="span3 m-wrap"/> %
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">VAT<span class="required">*</span></label>
										<div class="controls">	
											<input name="vat" id="vat" maxlengt="5" type="text" class="span3 m-wrap"/> %
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">Status<span class="required">*</span></label>
										<div class="controls">	
											<select name="status" id="status" class="span6 m-wrap">
												<option value="1">Active</option>
												<option value="0">Inactive</option>
											</select>
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
    <!-- END PAGE LEVEL PLUGINS -->
    
    <script src="assets/js/metronic/app/campaign-manager/master/operators_partners.js?<?php echo date('H:i:s');?>" type="text/javascript"></script>
	<script type="text/javascript">
		var deleteID = "",
			deleteLangID = "";
		var accessEdit = "<?php echo ($accessEdit)?'1':'0';?>";
		var accessDelete = "<?php echo ($accessDelete)?'1':'0';?>";
		
		jQuery(document).ready(function() {		
			loadOperators_Partners(1);
			FormValidation.init();
		});
	
        jQuery(function() {	
            jQuery('button').button();
        });
    </script>
    <div id="delete" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-body">
            <p><?php echo DELETE_MESSAGE;?></p>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn green" onClick="deleteOperators_Partners()">Delete</button>
        </div>
    </div>
