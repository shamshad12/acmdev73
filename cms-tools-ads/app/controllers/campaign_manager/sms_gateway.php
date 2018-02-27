<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sms_gateway extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_gateway');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_shortcodes');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();

		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['country'] = $this->model_country->loadCountrySelect();
		$this->data['operator'] = $this->model_operators->loadOperatorsSelect();
		$this->data['shortcode'] = $this->model_shortcodes->loadShortcodesSelect();
		$this->data['pageBreadCumb'] = "";
		//for only php file
		$dircontents = scandir(GATEWAY_PATH,1);
		$i=0;
		foreach($dircontents as $row)
		{
			$extension = pathinfo($row, PATHINFO_EXTENSION);
			if ($extension == 'php') 
			{
				$this->data['gateway_urls'][$i] =$row;
				$i++;
			}
			

		}
	


		$this->data['pageTemplate']	= "campaign-manager/master/view_gateway";

		$this->load->view('layout/main', $this->data);
	}

	public function loadGateway(){

		$params = $_POST;
		$result = $this->model_gateway->loadGateway($params);
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadGateway');
		//print_r($result) ;
		echo json_encode($result);
	}
	
	public function saveGateway(){
			$params = $_POST;
			
			if(sizeof($params)){
			$result = $this->model_gateway->saveGateway($params);
			if($result['success'])
			{
				
				if(!isset($params['id']) || trim($params['id'])=='')
				{
					$params['id']=$result['id'];	
				}
				if($params['telco_id']==0)
				{
					$redisParams = array('KEYS', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_*');
                	$campaignRedis = $this->redisCommand('default', $redisParams);
                	if(count($campaignRedis))
                	{
                		for($i=0;$i<count($campaignRedis);$i++)
                		{
                			$redisParams = array('del', $campaignRedis[$i]);
                			$campaignRedis_new = $this->redisCommand('default', $redisParams);		
                		}
                	}
				}
				else
				{
					$redisParams = array('del', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_0');
                	$campaignRedis = $this->redisCommand('default', $redisParams);	
				}
				$redisParams = array('exists', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_'.$params['telco_id']);
                $campaignRedis = $this->redisCommand('default', $redisParams);
                if($campaignRedis==0)
                { 
                	$redisParams = array('set', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_'.$params['telco_id'],json_encode($params));
                    $campaignRedis = $this->redisCommandNew('default', $redisParams);
                }
                else{
                    $redisParams = array('del', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_'.$params['telco_id']);
                	$campaignRedis = $this->redisCommand('default', $redisParams);
                    $redisParams = array('set', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_'.$params['telco_id'],json_encode($params));
                	$campaignRedis = $this->redisCommandNew('default', $redisParams); 
                }
                
			}				
			//echo json_encode($result);
		}
		echo json_encode($result);
	}
	
	public function deleteGateway(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_gateway->deleteGateway($params);
			if($result['success'])
			{
				$redisParams = array('del', KEYS_GATEWAY.$params['country_id'].'_'.$params['shortcode_id'].'_'.$params['telco_id']);
                $campaignRedis = $this->redisCommand('default', $redisParams);
			}
			echo json_encode($result);
		}
	}
	
	public function getGatewayData(){
		
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_gateway->getGatewayData($params);
			
			echo json_encode($result);
		}
	}
	public function loadOperator(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_gateway->loadOperator($params);
			//print_r(json_encode($result));die('lll');
			$html = '<select name="operator_id" id="operator_id" class="span6 m-wrap"> 
			<option value="">- Choose Operator -</option><option value="0">ALL</option>"';

			for($i=0;$i<count($result)-1;$i++)
			{
				$html .= '<option value='.$result[$i]['id'].'>'.$result[$i]['name'].'</option>';

			}
			$html .= "</select>";
			echo json_encode($html);
		}
	}

	
	
}
?>
