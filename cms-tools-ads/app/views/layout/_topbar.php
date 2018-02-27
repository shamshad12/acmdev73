   <!-- BEGIN HEADER -->
   <div class="header navbar navbar-inverse navbar-fixed-top">
      <!-- BEGIN TOP NAVIGATION BAR -->
      <div class="navbar-inner">
         <div class="container-fluid">
            <!-- BEGIN LOGO -->
            <a class="brand" href="<?php echo $base_url;?>">
            <img src="assets/images/logo.png" alt="logo" style="height:40px !important; margin-top:-7px" />
            </a>
            <!-- END LOGO -->
			 <!---Start Domain expiry notification-->
			 <?php  
			  $CI =& get_instance();
              $CI->load->model('model_login');
			  $domainInfo = $CI->model_login->loadExpireDomain();
			  $count = count($domainInfo);
                if($count>0)
                {
             ?>
            <div class="nav navbar-nav"'' style='margin-left:15%; width:60%;'>
            <div style="float:left; width:30%;min-width:200px;height:20px; padding-top:10px; padding-right:0px" class="newsticker" >
              Domain expiry notification:
            </div>
            <div class="newsticker" style='min-width:330px; padding-left:0'>
            <ul class="newsticker-list " style="margin:0px;padding-left:0px;">
            <?php
            $i=0;
                foreach($domainInfo as $row) { 

                  $li_val = '<li class="newsticker-item">'.$row['name'].' => '.$row['description'].'</li>';
                 echo $li_val;
                }
                
             ?>
            </ul>
            </div>
            </div>
            <?php }?>
			<!---END Domain expiry notification section--?>
            <!-- BEGIN TOP NAVIGATION MENU -->              
            <ul class="nav pull-right">
               
               <!-- END TODO DROPDOWN -->
               <!-- BEGIN USER LOGIN DROPDOWN -->
               <li class="dropdown user">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img alt="" src="assets/img/metronic/avatar/default.png" />
                  <span class="username"><?php echo $this->session->userdata['profile']['username'];?></span>
                  <i class="icon-angle-down"></i>
                  </a>
                  <ul class="dropdown-menu">
                     <li><a href="<?php echo $base_url;?>user-management/account"><i class="icon-user"></i> My Profile</a></li>
                     <li class="divider"></li>
                     <li><a id="logout" href="javascript:;"><i class="icon-key"></i> Log Out</a></li>
                  </ul>
               </li>
               <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <!-- END TOP NAVIGATION MENU --> 
         </div>
      </div>
      <!-- END TOP NAVIGATION BAR -->
   </div>
   <div id="logout-confirm" title="Logout ?" class="hide">
   <p><span class="icon icon-warning-sign"></span>
   You are about to logout from <?php echo $globalParameter['AppTitle'];?>. Are you sure?</p>
   </div>
   <!-- END HEADER -->
