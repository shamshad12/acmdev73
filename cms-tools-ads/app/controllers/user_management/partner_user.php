<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class partner_user extends Admin_Controller {
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('user-management/model_partner_user');
		$this->load->model('user-management/model_karyawan');
		$this->load->model('parameter/model_tipeuser');
                $this->load->model('campaign-manager/parameter/model_country');
                $this->load->model('campaign-manager/master/model_shortcodes');
                $this->load->model('campaign-manager/master/model_partners');
                //$this->load->model('campaign-manager/master/model_partners_services');
              
	}
	
	public function index() {		
		#Bring Menu for Sidebar`
		$this->loadMenu();
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['karyawan']			= $this->model_karyawan->reportKaryawan();
		$this->data['tipe_user']		= $this->model_tipeuser->loadTipeUser();
		$this->data['pageBreadCumb'] 	= "";
		$this->data['pageTemplate']		= "user-management/view_partner_user";
                $this->data['country'] = $this->model_country->loadCountrySelect();
                $this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect();
                $this->data['keyword'] = $this->model_partner_user->getKeywordList();
                $this->data['partner'] = $this->model_partners->loadPartnersSelect();
               
		$this->load->view('layout/main', $this->data);
	}

	public function loadUser(){
		$params = $_POST;
		$result = $this->model_partner_user->loadUser($params);
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadUser');
		
		echo json_encode($result);
	}
	
	public function saveUser(){
		$params = $_POST;
                
		if(sizeof($params)){
			$result = $this->model_partner_user->saveUser($params);
			echo json_encode($result);
		}
	}
	
	public function deleteUser(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partner_user->deleteUser($params);
			echo json_encode($result);
		}
	}
	
	public function getUserData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partner_user->getUserData($params);
			echo json_encode($result);
		}
	}
        
        public function getKeywordList()
        {
            $params=$_POST;
            $result = $this->model_partner_user->getKeywordList($params);
            echo json_encode($result);
        }
          public function loadShortcode(){
            if(sizeof($_POST)){
		     $params = $_POST;
		     $result = $this->model_partner_user->loadShortcode($params);
		    $html = '';
		   for($i=0;$i<count($result)-1;$i++)
		   {
		        $html .= '<div class="col-xs-1" style="width:20%;float:left"><input type="checkbox" name="shortcode"  class="shortcode_name" id="shortcode_'.$result[$i]['id'].'" value="'.$result[$i]['name'].'_'.$result[$i]['id'].'" onclick="getKeywordsList()" >'.$result[$i]['name'].'</div>'; 

            }

            echo json_encode($html);
           } 
 }

 public function loadDomain(){
		if(sizeof($_POST)){
		 $params = $_POST;
		 $result = $this->model_partner_user->loadDomain($params);
		$html = '';
		for($i=0;$i<count($result)-1;$i++)
		{
			$html .= '<div class="col-xs-1" style="width:25%;float:left">
			<!--<div class="" id="uniform-domain_'.$result[$i]['id'].'">--> 
			<input type="checkbox" name="domain_id"  class="domain_data" id="domain_'.$result[$i]['id'].'" value="'.$result[$i]['id'].'"><!--</div>-->'.$result[$i]['name'].'-<span class="code">'.$result[$i]['country_code'].'</span>
			</div>'; 

		}
			echo json_encode($html);
		} 
	}
	
	public function loadAdsPublisher(){
		if(sizeof($_POST)){
		 $params = $_POST;
		 $result = $this->model_partner_user->loadAdsPublisher($params);
		$html = '';
		for($i=0;$i<count($result)-1;$i++)
		{
			$html .= '<div class="col-xs-1" style="width:25%;float:left">			
			<input type="checkbox" name="ads_publisher_id"  class="ads_publisher_data" id="ads_publisher_'.$result[$i]['id'].'" value="'.$result[$i]['id'].'"> '.$result[$i]['name'].'('.$result[$i]['code'].')-<span class="code">'.$result[$i]['country_code'].'</span>
			</div>'; 

		}
			echo json_encode($html);
		} 
	}
}
?>
