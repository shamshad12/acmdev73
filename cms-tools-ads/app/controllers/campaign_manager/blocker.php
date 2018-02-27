<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class blocker extends Admin_Controller {

	var $base_url;
	//error_reporting(E_ALL);
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/filterlist/model_blocker');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/master/model_shortcodes'); 
		$this->load->model('campaign-manager/master/model_partners_services');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		if(!$this->data['accessView'])
		redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['country'] = $this->model_country->loadCountrySelect();
		$this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect();
		$this->data['keyword'] = $this->model_partners_services->loadPartners_ServicesSelect();
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/filterlist/view_blocker";
		$this->load->view('layout/main', $this->data);
	}

	public function loadBlocker(){
		$params = $_POST;

		$result = $this->model_blocker->loadBlocker($params);

		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadBlocker');
		echo json_encode($result);
	}
	public function blockUpload()
	{

		print_r($_POST);
		die;
	}
	public function fetchOperators(){

		$params = $_POST['country_id'];

		$result = $this->model_blocker->fetchOperators($params);
		echo json_encode($result);
	}
	public function fetchServices(){

		$params = $_POST['shortcode_id'];

		$result = $this->model_blocker->fetchServices($params);
		echo json_encode($result);
	}
	public function saveBlocker($bulkUpload='')
	{

		if($bulkUpload)
		{
			
			for($i=0; $i<count($bulkUpload); $i++)
			{
				$params['country_id'] = $bulkUpload[$i]['country_id'];
				$params['operator_id'] = $bulkUpload[$i]['operator_id'];
				$params['shortcode'] = $bulkUpload[$i]['shortcode_id'];
				$params['service'] = $bulkUpload[$i]['service'];
				$params['status'] = $bulkUpload[$i]['status'];
				$result = $this->model_blocker->saveBlocker($params);
				if($result['success'])
				{

					$servic_string = str_replace(' ', '',$params['service']);
					if(!empty($params['country_id'] ) && !empty($params['operator_id']) && !empty($params['shortcode']) && $params['status']==1 )
                    {   
					
						
						$redisParams = array('sadd', (KEYS_BLOCKER.$params['country_id'].'_'.$params['operator_id'].'_'.$params['shortcode'].'_'.$servic_string), 1);
						$campaignRedis = $this->redisCommand('default', $redisParams);
					}



			}


			}
			
			redirect('/campaign_manager/blocker', 302);
		}
		else{
			$params = $_POST; 
			if($params['shortcode']=='All')
			{
				$shortcode = 'All';
			}
			else
			{
				$expl = explode("__", $params['shortcode']);
				$shortcode = $expl[1];
			}
			if(sizeof($params)){ 
				$result = $this->model_blocker->saveBlocker($params);
				if($result['success'])
				{
					$servic_string = str_replace(' ', '',$params['service']);
					if(!empty($params['country_id'] ) && !empty($params['operator_id']) && $params['status']==1 )
                    {
						//$this->load->model("model_redis_set");
						$redisParams = array('set', (KEYS_BLOCKER.$params['country_id'].'_'.$params['operator_id'].'_'.$shortcode.'_'.$servic_string), 1);
						$campaignRedis = $this->redisCommand('default', $redisParams);
					}
				}
				echo json_encode($result);
			}
		}
	}
	
	public function deleteBlocker(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_blocker->deleteBlocker($params);
			if($result['success'])
			{
				if($result['rows']['status']==1)
				{
					$redisParams = array('del', (KEYS_BLOCKER.$result['rows']['country_id'].'_'.$result['rows']['operator_id'].'_'.$result['rows']['shortcode_id'].'_'.$result['rows']['service']));
					$campaignRedis = $this->redisCommand('default', $redisParams);
				}
			}
			echo json_encode($result);
		}
	}
	public function changeStatus(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_blocker->changeStatus($params);
			if($result['success'])
			{

				if($params['new_status']==1)
				{
					$redisParams = array('sadd', (KEYS_BLOCKER.$result['rows']['country_id'].'_'.$result['rows']['operator_id'].'_'.$result['rows']['shortcode_id'].'_'.$result['rows']['service']), 1);
					$campaignRedis = $this->redisCommand('default', $redisParams);

				}
				else
				{
					$redisParams = array('del', (KEYS_BLOCKER.$result['rows']['country_id'].'_'.$result['rows']['operator_id'].'_'.$result['rows']['shortcode_id'].'_'.$result['rows']['service']));
					$campaignRedis = $this->redisCommand('default', $redisParams);

				}

			}

			echo json_encode($result);
		}
	}
	public function blockerBulkUpload(){
		$temp_file_path = $_FILES['upload_blocker']['tmp_name'];
		
		$result_bulk = $this->model_blocker->blockerBulkUpload($temp_file_path);
		
		$this->saveBlocker($result_bulk);



	}
	public function checkExistRedis($country_id, $operator_id, $shortcode_id, $keyword)
	{

		
		$redis_key_formate = "BK_".$country_id."_".$operator_id."_".$shortcode_id."_".$keyword;
		$redisParams = array('exists', $redis_key_formate);	
		$redis_data_exists = $this->redisCommand('default', $redisParams); 
		echo $redis_data_exists;

	}
	
	
}
?>
