<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class campaigns extends Admin_Controller {

	var $base_url;

	public function __construct() {
		parent::__construct();

		$this->load->model('campaign-manager/campaigns/model_campaigns');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/campaigns/model_campaigns_categories');
		$this->load->model('campaign-manager/master/model_banners');
		$this->load->model('campaign-manager/master/model_prices');
		$this->load->model('campaign-manager/master/model_ads_publishers');
		$this->load->model('campaign-manager/master/model_templates');
		$this->load->model('campaign-manager/master/model_language');
	}

	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();

		if(!$this->data['accessView'])
		redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['template_id'] = '';
		$this->data['media_id']='';
		$this->data['temp_country_id']='';
		if(isset($_GET['template_id']) && trim($_GET['template_id']) !='')
		{
			$this->data['template_id'] = $_GET['template_id'];
			$this->data['media_id']=$_GET['media_id'];
			$this->data['temp_country_id']=$_GET['country_id'];
		}
		#Load Country
		$this->data['country'] = $this->model_country->loadCountrySelect();

		#Load User List
		$this->data['user_list'] = $this->model_templates->getUserList();


		#Load Campaign Media
		$this->data['campaign_media'] = $this->model_campaigns_media->loadCampaigns_MediaSelect();
		#Load Campaign Category
		$this->data['campaign_category'] = $this->model_campaigns_categories->loadCampaigns_CategoriesSelect();
		#Load Price
		$this->data['price'] = $this->model_prices->loadPricesSelect();
		#Load Ads Publishers
		$this->data['ads_publisher'] = $this->model_ads_publishers->loadAds_PublishersSelect();
		#Load Banners
		$this->data['banner'] = $this->model_banners->loadBannersSelect();
		#Load Templates
		$this->data['template'] = $this->model_templates->loadTemplatesSelect();
		#Load Language
		$this->data['language'] = $this->model_language->loadLanguageSelect();

		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "campaign-manager/campaigns/view_campaigns";
		$this->load->view('layout/main', $this->data);
	}

	public function loadCampaigns(){
		$params = $_POST;
		$result = $this->model_campaigns->loadCampaigns($params);

		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadCampaigns');

		$dirAccess = $this->config->item('image_access');
		$result['host']			= $dirAccess['campaigns']['host'];

		echo json_encode($result);
	}

	public function loadLokedCampaigns()
	{
		$result = $this->model_campaigns->loadLokedCampaigns();
		echo json_encode($result);
	}
	public function unlokedCampaign()
	{
		$params = $_POST;
		$result = $this->model_campaigns->unlokedCampaign($params);
		echo json_encode($result);
	}
	
	public function loadCampaignsSelect(){
		$params = $_POST;
		$result = $this->model_campaigns->loadCampaignsSelect($params);

		echo json_encode($result);
	}

	public function getUrlData(){
		$params = $_POST;
		$result = $this->model_campaigns->getUrlData($params);

		echo json_encode($result);
	}

	public function getUrlDataWithValues(){
		$params = $_POST;
		$result = $this->model_campaigns->getUrlDataWithValues($params);

		echo json_encode($result);
	}

	public function saveCampaigns(){
		$params = $_POST;
		$operator_data = $_POST['operator_data'];
		$opt_data = array();
		foreach($operator_data as $opt)
		{
			$opt_arr = explode('__||__',$opt);
			$opt_data[$opt] = $opt_arr[1];
		}
		if(sizeof($params)){
			$result = $this->model_campaigns->saveCampaigns($params);

			if($result['success'])
			file_get_contents(URL_CAMPAIGN_REDIS.$result['id']);
			$key = 'opt_data_'.$result['id'];
			$redisParams = array('set', $key, json_encode($opt_data));
			$opt_data_set = $this->redisCommand('default', $redisParams);
			echo json_encode($result);
		}
	}

	public function deleteCampaigns(){
		if(sizeof($_POST)){
			$params = $_POST;

			$result = $this->model_campaigns->checkeditCampaigns($params);
			if($result['rows'][0]['edit_type']==0 && empty($result['rows'][0]['edit_user']))
			{
				file_get_contents(URL_CAMPAIGN_REDIS_DEL.$params['id']);
				$result = $this->model_campaigns->deleteCampaigns($params);
				$key = 'opt_data_'.$params['id'];
				$redisParams = array('set', $key);
				$opt_data_del = $this->redisCommand('default', $redisParams);

			}
			elseif($result['rows'][0]['edit_type']==1 && $result['rows'][0]['edit_user']==$result['rows'][0]['login_user'])
			{
				file_get_contents(URL_CAMPAIGN_REDIS_DEL.$params['id']);
				$result = $this->model_campaigns->deleteCampaigns($params);
				$key = 'opt_data_'.$params['id'];
				$redisParams = array('set', $key);
				$opt_data_del = $this->redisCommand('default', $redisParams);

					
			}
			else{
				$result['edit_message'] = "Some of the user already editing this Campaigns so you cannot edit or delete";
				$result['duplicateedit_data'] =  true;
			}


			echo json_encode($result);
		}
	}

	public function duplicateCampaigns(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns->duplicateCampaigns($params);

			if($result['success'])
			{
				file_get_contents(URL_CAMPAIGN_REDIS.$result['id']);
			}

			echo json_encode($result);
		}
	}

	public function getCampaignsData(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns->checkeditCampaigns($params);
			//  print_r($result);
			if($result['rows'][0]['edit_type']==0 && empty($result['rows'][0]['edit_user']))
			{
				$result = $this->model_campaigns->getCampaignsData($params);
			}
			elseif($result['rows'][0]['edit_type']==1 && $result['rows'][0]['edit_user']==$result['rows'][0]['login_user'])
			{
				$result = $this->model_campaigns->getCampaignsData($params);
			}
			else{
				$result['edit_message'] = "Some of the user already editing this Campaigns so you cannot edit";
				$result['duplicateedit_data'] =  true;
			}


			echo json_encode($result);
		}
	}
	public function Status(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_campaigns->changeStatus($params);
			echo json_encode($result);
		}
	}
	public function generateBannerCode()
        {
            
            $folder_name=$this->uri->segment(4);
			$url=  base64_decode(str_replace("_","=", $this->uri->segment(5)));
            if($folder_name=='')
            {
                echo 'Campaign name not found';
            }else{
            $dirUpload = $this->config->item('aff_banner');
            if(file_exists($dirUpload['banner'].$folder_name)){
                $banner_url=$this->config->item('image_access');
                $files=  scandir($dirUpload['banner'].$folder_name);
                $allowed_type=array('png','jpg','jpeg','gif');
                for($i=2;$i<count($files);$i++)
                {
                    $extension = pathinfo($files[$i], PATHINFO_EXTENSION);
                    //echo '<textarea style="width:1200px;">';
                    if(in_array(strtolower($extension), $allowed_type))
                    echo htmlentities('<a data-src="_X196aW5nSUQ" href="'.$url.'"><img src="'.$banner_url['campaigns']['host'].'aff-banners/'.$folder_name.'/'.$files[$i].'"/></a>').'<br><br>';
                    
                }
               echo '<br>';
               echo 'Include js file just before closing body tag. <br /><br />';
               echo htmlentities('<script type="text/javascript" src="'.$banner_url['campaigns']['host'].'assets/js/validate_image_ec.js"></script>');
            }else{
                echo 'Banner not uploaded for the this campaign';
            }
            }
        }

	public function setUseConfirmation(){
		$params = $_POST;
		$result = $this->model_campaigns->setUseConfirmation($params);
		if($result['success'])
			file_get_contents(URL_CAMPAIGN_REDIS.$params['data']['id_campaign']);

		echo json_encode($result);
	}
	
	public function timezone(){
		$params = $_POST;
		$result = $this->model_campaigns->timezone($params);
		echo json_encode($result);
	}
	
	public function  isTeraBilling(){
		
		$params = $_POST;
		$result = $this->model_campaigns->isTeraBilling($params);
		
		$str = '<input type="hidden" name="is_tera_billing" id="is_tera_billing" value="0">';
		if($result['status']==1){
			$selected = '';
			$selected_n = 'selected';
			if($result['is_tera_billing']==1){
				$selected = 'selected';
				$selected_n = '';
			}
			$str = '<label class="control-label">Is Tera Billing?<span class="required">*</span></label>
					<div class="controls">
						<select name="is_tera_billing" id="is_tera_billing" class="span6 m-wrap">
							<option value="1" '.$selected.'>Yes</option>
							<option value="0" '.$selected_n.'>No</option>
						</select>
					</div>';
		}	
		echo $str;
	}

}
?>
