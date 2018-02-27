            <!-- BEGIN PAGE HEADER-->
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			      <h3 class="page-title">
                        <b><?php echo $pageTitle;?></b>
                        <small><?php echo $pageDescription;?></small>
                        <div class="app-logo"> 
                        <img alt="logo" src="assets/images/logo.png" width="90">
                        </div>
				  </h3>
			      <ul class="breadcrumb">
				  <li>
					<i class="icon-home"></i>
					<a href="<?php echo $base_url;?>">Home</a> 
					<i class="icon-angle-right"></i>
                  </li>
                  <?php 
					/*$temp = '';
					for($i=count($parentList['name'])-1; $i>=0; $i--){
						if($parentList['name'][$i]!=""){
				  ?>
                          <li>
                            <a href="<?php echo $_SERVER['REQUEST_URI'];?>"><?php echo $parentList['name'][$i];?></a>
                            <?php if($i!=0){ ?>
                            <i class="icon-angle-right"></i>   
                            <?php }?>
                          </li>               
                  <?php 
				  		}
				  	}*/?>
			      </ul>
		          <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->

