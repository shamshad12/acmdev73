<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class partners_services extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_partners_services');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/master/model_shortcodes');
		$this->load->model('campaign-manager/master/model_partners');
		$this->load->model('campaign-manager/master/model_prices');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_keyword_groups');
        $this->load->model('campaign-manager/parameter/model_country'); 
		$this->load->model('campaign-manager/parameter/model_services_types');
        $this->load->model('campaign-manager/filterlist/model_filterlist');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['country'] = $this->model_country->loadCountrySelect(); 
		$this->data['campaign_media'] = $this->model_campaigns_media->loadCampaigns_MediaSelect();	
		$this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect();
		$this->data['partner'] = $this->model_partners->loadPartnersSelect();
		$this->data['price'] = $this->model_prices->loadPricesSelect();
		$this->data['operator'] = $this->model_operators->loadOperatorsSelect();
		$this->data['service_type'] = $this->model_services_types->loadServices_TypesSelect();
		$this->data['keyword_group'] = $this->model_keyword_groups->loadKeyword_GroupsSelect();
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_partners_services";
		$this->load->view('layout/main', $this->data);
	}

	public function loadPartners_Services(){
		$params = $_POST;
		$result = $this->model_partners_services->loadPartners_Services($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadPartners_Services');
		
		echo json_encode($result);
	}
	
	public function loadPartners_ServicesSelect(){
		$params = $_POST;
		$result=array();
		if(!empty($params))
		$result = $this->model_partners_services->loadPartners_ServicesSelect($params);		
		
		echo json_encode($result);
	}
	
	public function loadPrice(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_prices->loadPricesSelect($params); 
		$html = '<option value="">- Choose Price -</option>"';
       for($i=0;$i<count($result['rows']);$i++) 
		{
		$html .= '<option value='.$result['rows'][$i]['id'].'>'.$result['rows'][$i]['cu_code'].$result['rows'][$i]['value'].'</option>';   
        }
  
  echo json_encode($html); 
        }
  }
	
	public function savePartners_Services(){
		$params = $_POST;
		if(sizeof($params)){
                    //print_r($params);die(); 
			$result = $this->model_partners_services->savePartners_Services($params);
			
			//if($result['success'])
				//file_get_contents(URL_CAMPAIGN_REDIS);
			
			echo json_encode($result);
		}
	}
	
	public function deletePartners_Services(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partners_services->deletePartners_Services($params);
			echo json_encode($result);
		}
	}
	
	public function getPartners_ServicesData(){
		if(sizeof($_POST)){
			$params = $_POST;
                       // print_r($params);die();
			$result = $this->model_partners_services->getPartners_ServicesData($params);
			echo json_encode($result);
		}
	}
	
	public function getserviceVal(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_partners_services->getserviceVal($params);
			echo json_encode($result);
		}
	}
  
        public function loadShortcode(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_partners_services->loadShortcode($params);
         //  print_r($result);die();   
        $html = '<option value="">- Choose Shortcode -</option>"';
       for($i=0;$i<count($result)-1;$i++)
   {
    $html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>'; 
       
   }
     
   echo json_encode($html);
  } 
 }
 
        public function loadOperator(){
        if(sizeof($_POST)){
         $params = $_POST;
         $result = $this->model_filterlist->loadOperator($params);
         $html = ' 
           
            <option value="">- Choose Operator -</option>"';
       for($i=0;$i<count($result)-1;$i++)
   {
    $html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>'; 
       
   }
   echo json_encode($html);
  } 
 }
 
 
}
?>
