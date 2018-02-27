<?php 	
	$this->load->view('layout/_header');
	$this->load->view('layout/_topbar'); 
?>

   <!-- BEGIN CONTAINER -->
   <div class="page-container">
      <!-- BEGIN SIDEBAR -->
	  <?php $this->load->view('layout/_sidebar');?>
      
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
         	<?php 
				$this->load->view('layout/_pageheader');
				$this->load->view($pageTemplate);
			?>
         </div>
         <!-- END PAGE CONTAINER-->    
      </div>
      <!-- END PAGE -->
   </div>
   <!-- END CONTAINER -->
<?php 
	$this->load->view('layout/_scripts');
	$this->load->view('layout/_footer');
?>
