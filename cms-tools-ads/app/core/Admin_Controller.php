<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		#Check the request User
		$this->isAdmin();	
				
		#Set data to display on View
		$this->data['globalParameter'] = $this->model_global_parameter->loadGlobalParameter();				
	
	}

	private function isAdmin(){
		$_sessProfile = $this->session->userdata('profile');
		
		$this->data['profile'] = $_sessProfile;		
				
		if (!$_sessProfile['isAdmin'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		return true;	
	}
	
	protected function loadMenu(){		
		$_sessProfile = $this->session->userdata('profile');
		$_sessPrivilege = $this->session->userdata('privilege');
		$_sessIsLogged = $this->session->userdata('isLogged');
		
		if (!$_sessProfile['isAdmin'])
			return false;		
		
		$_modelMenu = $this->load->model('model_menu');
				
		$this->data['parentList']		= $this->model_menu->loadParentList($this->uri->uri_string(), $_sessPrivilege['privilege_id']);
		$this->data['menu']				= $this->model_menu->loadMenu($_sessPrivilege['menu']);
		// $this->data['pageTitle'] 		= $this->data['parentList']['name'][0];
		// $this->data['pageDescription'] 	= $this->data['parentList']['description'][0];	
		$this->data['pageTitle'] 		= (isset($this->data['parentList']['name']))?($this->data['parentList']['name'][0]):'';
		$this->data['pageDescription'] 	= (isset($this->data['parentList']['description']))?($this->data['parentList']['description'][0]):'';	
		
		#Get Access Button
		if(isset($this->data['parentList']['access']))
			$this->data['parentList']['access'] = $this->data['parentList']['access'];
		else
			$this->data['parentList']['access'] = '';
		$this->getAccess($this->data['parentList']['access']);
			
		return true;			
	}
	
	protected function getAccess($access){
		$this->data['accessView'] 	= (!(1&$access) != 0)?0:1;
		$this->data['accessAdd'] 	= (!(2&$access) != 0)?0:1;
		$this->data['accessEdit'] 	= (!(4&$access) != 0)?0:1;
		$this->data['accessDelete'] = (!(8&$access) != 0)?0:1;
		return true;
	}
	
	protected function pagination($pageCurrent, $totalData, $limit, $onclick, $dayss=''){
		$result = "<div class='dataTables_paginate paging_bootstrap pagination'><ul>";
		
		$pageTotal = ceil($totalData / $limit);
		$appo = $dayss?", $dayss":"";
		if ($pageCurrent > 1) 
			$result .= "<li class='prev'><a href='javascript:;' onclick='".$onclick."(".($pageCurrent-1).$appo.")'>← Prev</a></li>";
			
		$showPage = 0;
		for($page = 1; $page <= $pageTotal; $page++)
		{
			 if ((($page >= $pageCurrent - 3) && ($page <= $pageCurrent + 3)) || ($page == 1) || ($page == $pageTotal))
			 {
				if (($showPage == 1) && ($page != 2))  
					$result .= "<li class='prev'><a href='javascript:;'>...</a></li>";
					
				if (($showPage != ($pageTotal - 1)) && ($page == $pageTotal))
					$result .= "<li class='prev'><a href='javascript:;'>...</a></li>";
				
				$active = ($page == $pageCurrent)?'active':'';
				
				$result .= "<li class='".$active."'><a href='javascript:;' onclick='".$onclick."(".$page.$appo.")'>".$page."</a></li>";
				
				$showPage = $page;
			 }
		}
		$result .= "</ul></div>";
		
		return $result;
	}
}
