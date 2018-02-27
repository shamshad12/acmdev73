      <div class="page-sidebar nav-collapse collapse">
         <!-- BEGIN SIDEBAR MENU -->        	
         <ul>
				<li>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler hidden-phone tooltips" data-desktop="tooltips" data-placement="right" data-original-title="Toggle Sidemenu"></div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li>
					&nbsp;
				</li>

         <!-- BEGIN DYNAMIC MENU -->    
<?php	
	if(sizeof($menu)){
		$first = true;
		for($i=0; $i<count($menu['rows']); $i++){
			$start = "";
			if($first == true){
				$start = 'start ';
				$first = false;
			}
			$active = '';
			$open = '';
			for($x=0; $x<count($parentList['rows']); $x++){				
				if($menu['rows'][$i]['id']==$parentList['rows'][$x]){
					$active = 'active ';
					$open = 'open ';
					
					#Set The title, description, breadcumb
					$menu['pageTitle'] = $menu['rows'][$i]['name'];
					$pageTitle = $menu['rows'][$i]['name'];
					$pageDescription = $menu['rows'][$i]['description'];
				}
			}
			
			if(!empty($menu['rows'][$i]['sub'])){
				?>
                <li class="<?php echo $start.$active;?> has-sub">
					<a href="javascript:;">
					<i class="icon-<?php echo $menu['rows'][$i]['icon'];?>"></i> 
					<span class="title"><?php echo $menu['rows'][$i]['name'];?></span>
					<span class="arrow <?php echo $open;?>"></span>
					<?php if(!empty($open)){?> <span class="selected"></span><?php }?>
					</a>
					<ul class="sub-menu">
                    	<?php
							for($ii=0; $ii<count($menu['rows'][$i]['sub']['rows']); $ii++){
								$activeSub = '';
								$openSub = '';
								for($x=0; $x<count($parentList['rows']); $x++){				
									if($menu['rows'][$i]['sub']['rows'][$ii]['id']==$parentList['rows'][$x]){
										$activeSub = 'active ';
										$openSub = 'open ';
										
										#Set The title, description, breadcumb
										$menu['pageTitle'] = $menu['rows'][$i]['sub']['rows'][$ii]['name'];
										$this->pageTitle = $menu['rows'][$i]['sub']['rows'][$ii]['name'];
										$pageDescription = $menu['rows'][$i]['sub']['rows'][$ii]['description'];
									}
								}
								if(!empty($menu['rows'][$i]['sub']['rows'][$ii]['sub']) && !empty($menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'])){
								?>
                                <li class="<?php echo $activeSub;?> has-sub">
                                	<a href="javascript:;">
                                    <span class="title"><?php echo $menu['rows'][$i]['sub']['rows'][$ii]['name'];?></span>
                                    <span class="arrow <?php echo $open;?>"></span>
                                    <?php if(!empty($open)){?> <span class="selected"></span><?php }?>
                                    </a>
                                    <ul class="sub-menu">
                                    	<?php 
											for($iii=0; $iii<count($menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows']); $iii++){
												$activeSubSub = '';
												$openSubSub = '';
												for($x=0; $x<count($parentList['rows']); $x++){				
													if($menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'][$iii]['id']==$parentList['rows'][$x]){
														$activeSubSub = 'active ';
														$openSubSub = 'open ';
														
														#Set The title, description, breadcumb
														$menu['pageTitle'] = $menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'][$iii]['name'];
														$this->pageTitle = $menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'][$iii]['name'];
														$pageDescription = $menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'][$iii]['description'];
													}
												}
										?>
                                        <li class="<?php echo $activeSubSub;?>">
                                            <a href="<?php echo $base_url_index.$menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'][$iii]['url'];?>">
                                            <span class="title"><?php echo $menu['rows'][$i]['sub']['rows'][$ii]['sub']['rows'][$iii]['name'];?></span>
                                        	</a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                </li>
                                <?php
								} else {
								?>
                                	<li class="<?php echo $activeSub;?>">
                                    	<a href="<?php echo $base_url_index.$menu['rows'][$i]['sub']['rows'][$ii]['url'];?>"><?php echo $menu['rows'][$i]['sub']['rows'][$ii]['name'];?></a></li>
                                <?php
								}
							}
						?>
					</ul>
				</li>
                <?php
			} else {
				?>
                <li class="<?php echo $start.$active;?>">
					<a href="<?php echo $base_url_index.$menu['rows'][$i]['url'];?>">
						<i class="icon-<?php echo $menu['rows'][$i]['icon'];?>"></i> 
						<span class="title"><?php echo $menu['rows'][$i]['name'];?></span>
						<?php if(!empty($open)){?> <span class="selected"></span><?php }?>
					</a>
				</li>
                <?php
			}
		}
	}
?> 
         <!-- END DYNAMIC MENU -->    				
		</ul>
		<!-- END SIDEBAR MENU -->
      </div>
