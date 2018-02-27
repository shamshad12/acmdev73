<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ads_publishers_pf extends Admin_Controller {

	var $base_url;
		
	public function __construct() {
		parent::__construct();
		
		$this->load->model('campaign-manager/master/model_ads_publishers_pf');
		$this->load->model('campaign-manager/master/model_operators');
		$this->load->model('campaign-manager/master/model_ads_publishers');
	}
	
	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
			
		#Load Ads Publishers
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();	
		#Load Operators
		$this->data['operator'] = $this->model_operators->loadOperatorsSelect();		
		
		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/master/view_ads_publishers_pf";
		$this->load->view('layout/main', $this->data);
	}

	public function loadAds_Publishers_PF(){
		$params = $_POST;
		$result = $this->model_ads_publishers_pf->loadAds_Publishers_PF($params);		
		
		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadAds_Publishers_PF');
	
		echo json_encode($result);
	}
	
	public function loadAds_Publishers_PFSelect(){
		$params = $_POST;
		$result = $this->model_ads_publishers_pf->loadAds_Publishers_PFSelect($params);		
		
		echo json_encode($result);
	}
	
	public function saveAds_Publishers_PF(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_ads_publishers_pf->saveAds_Publishers_PF($params);
			if($result['success'] == true)
			{
				$redisParams = array('set', (KEYS_PUB_PF.$params['id_operator'].'_'.$params['id_ads_publisher']), json_encode($params));
				$campaignRedis = $this->redisCommand('default', $redisParams);

				$result2 = $this->model_ads_publishers_pf->getCampaignIdsByPublisherId($params['id_ads_publisher']);

				if($result2['count'] == true)
				{
					foreach($result2['campaign_ids'] as $res)
					{
						
						file_get_contents(URL_PF_CAMPAIGN_REDIS.$res);
					}
				}
			}
			echo json_encode($result);
		}
	}

	public function saveAll_PF(){
		$result = $this->model_ads_publishers_pf->saveAll_PF();
		/*for($i=0;$i<count($result['data']);$i++)
		{
			$redisParams = array('set', (KEYS_PUB_PF.$result['data'][$i]['id_operator'].'_'.$result['data'][$i]['id_ads_publisher']), json_encode($result['data'][$i]));
			$campaignRedis = $this->redisCommand('default', $redisParams);
		}*/
	}
	
	public function deleteAds_Publishers_PF(){
		if(sizeof($_POST)){
			$params = $_POST;
			$getcode = $this->model_ads_publishers_pf->getCampaignDetails($params);
			if($getcode['count'])
			{
				$rP = array('DEL', (KEYS_PUB_PF.$getcode[0]['id_operator'].'_'.$getcode[0]['id_ads_publisher']));
				$this->redisCommand('default', $rP);
				for($i=0;$i<count($getcode);$i++)
				{
					$redisParams = array('EXISTS', 'QPF_'.$getcode[$i]['campaign_code'].'_'.$getcode[$i]['id_operator']);
					$campaignRedis = $this->redisCommand('default', $redisParams);
					if($campaignRedis)
					{
						$delredisParams = array('DEL', 'QPF_'.$getcode[$i]['campaign_code'].'_'.$getcode[$i]['id_operator']);
						$this->redisCommand('default', $delredisParams);
					}
				}
			}
			$result = $this->model_ads_publishers_pf->deleteAds_Publishers_PF($params);
			echo json_encode($result);
		}
	}
	
	public function getAds_Publishers_PFData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_ads_publishers_pf->getAds_Publishers_PFData($params);
			echo json_encode($result);
		}
	}
}
?>
