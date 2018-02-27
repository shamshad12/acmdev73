<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class templates extends Admin_Controller {

	var $base_url;

	public function __construct() {
		parent::__construct();

		$this->load->model('partner_campaign_manager/templates/model_templates');
		$this->load->model('campaign-manager/campaigns/model_campaigns_media');
		$this->load->model('campaign-manager/parameter/model_country');
		$this->load->model('campaign-manager/master/model_banners');
	}

	public function index() {
		#Bring Menu for Sidebar
		$this->loadMenu();

		//if(!$this->data['accessView'])
		//redirect($this->data['base_url_index'].'login', 'location', 301);
		$this->data['template_id'] = '';
		if(isset($_GET['template_id']) && trim($_GET['template_id']) !='')
		{
		    $this->data['template_id'] = $_GET['template_id'];
		}
			
		#Load Campaign Media
		$this->data['campaign_media'] = $this->model_campaigns_media->loadCampaigns_MediaSelect();

		#Load Country
		$this->data['country'] = $this->model_country->loadCountrySelect();

		#Load Banner
		$this->data['banner'] = $this->model_banners->loadBannersSelect();

		#Load Template
		$this->data['template'] = $this->model_templates->loadTemplatesSelect();
		#Load Static Template
		$this->data['templateStatic'] = $this->model_templates->loadTemplatesSelect(array('campaign_media'=>2, 'is_uploaded'=>0));

		$this->data['pageBreadCumb'] = "";
		$this->data['pageTemplate']	= "partner_campaign_manager/templates/view_templates";
		$this->load->view('layout/main', $this->data);
	}

	public function loadTemplates(){
		$params = $_POST;
		$result = $this->model_templates->loadTemplates($params);

		$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadTemplates');

		echo json_encode($result);
	}

	public function loadTemplatesSelect(){
		$params = $_POST;
		$result = $this->model_templates->loadTemplatesSelect($params);

		echo json_encode($result);
	}

	public function saveTemplates(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_templates->saveTemplates($params);
				
			if($result['success'])
			file_get_contents(URL_TEMPLATE_REDIS.$result['id']);
				
			echo json_encode($result);
		}
	}

	public function saveTemplatesUpload(){
		$params = $_POST;
		if(sizeof($params)){
			$result = $this->model_templates->saveTemplatesUpload($params);
				
			if($result['success'])
			file_get_contents(URL_TEMPLATE_REDIS.$result['id']);
				
			echo json_encode($result);
		}
	}

	public function saveTemplatesStatic(){
		$params = $_POST;
		if(sizeof($params)){
			$bannerConf = $this->model_banners->getBannersData(array('id' => $params['static_conf_id_banner']));
			$bannerThanks = $this->model_banners->getBannersData(array('id' => $params['static_thanks_id_banner']));
				
			$template = $this->model_templates->getTemplatesData(array('id'=>$params['id_template']));
				
			$confirmationTemplate = $template['page_confirm'];
			$pintemplate=$template['page_pin'];
				
			#Begin Replace ConfirmationTemplate
			$confirmationTemplate = str_replace('{HEADER-ADS}', $params['static_conf_header_text'], $confirmationTemplate);
			$confirmationTemplate = str_replace('{BANNER-ADS}', '<img src="'.$bannerConf['url_ori'].'" />', $confirmationTemplate);
			$confirmationTemplate = str_replace('{TEXT-ADS}', $params['static_conf_text'], $confirmationTemplate);
			$confirmationTemplate = str_replace('{PREFIX-ADS}', $params['static_conf_msisdn_prefix'], $confirmationTemplate);
			$confirmationTemplate = str_replace('{BUTTON-ADS}', $params['static_conf_button_text'], $confirmationTemplate);
			$confirmationTemplate = str_replace('{DESCRIPTION-ADS}', $params['static_conf_tc_description'], $confirmationTemplate);
			$confirmationTemplate = str_replace('{FOOTER-ADS}', $params['static_conf_footer_text'], $confirmationTemplate);
			#End Replace
				
			$thanksTemplate = $template['page_status'];
				
			#Begin Replace StatusTemplate
			$thanksTemplate = str_replace('{HEADER-ADS}', $params['static_thanks_header_text'], $thanksTemplate);
			$thanksTemplate = str_replace('{BANNER-ADS}', '<img src="'.$bannerThanks['url_ori'].'" />', $thanksTemplate);
			$thanksTemplate = str_replace('{TEXT-ADS}', $params['static_thanks_text'], $thanksTemplate);
			$thanksTemplate = str_replace('{KEYWORD-ADS}', $params['static_thanks_sms_keyword'], $thanksTemplate);
			$thanksTemplate = str_replace('{SHORTCODE-ADS}', $params['static_thanks_sms_shortcode'], $thanksTemplate);
			$thanksTemplate = str_replace('{BUTTON-ADS}', $params['static_thanks_button_text'], $thanksTemplate);
			$thanksTemplate = str_replace('{DESCRIPTION-ADS}', $params['static_thanks_tc_description'], $thanksTemplate);
			$thanksTemplate = str_replace('{FOOTER-ADS}', $params['static_thanks_footer_text'], $thanksTemplate);
			#End Replace

			$paramsNew = array(
								'id' 			=> $params['id'],
								'name' 			=> $params['name'],
								'id_country' 	=> $params['id_country'],
								'id_campaign_media' => $params['id_campaign_media'],
								'description' 	=> $params['static_description'],
								'page_confirm' 	=> $confirmationTemplate,
								'page_status' 	=> $thanksTemplate,
								'page_pin'      =>$pintemplate,
								'status' 		=> $params['status']								
			);
				
			$result = $this->model_templates->saveTemplates($paramsNew);
				
			if($result['success'])
			file_get_contents(URL_TEMPLATE_REDIS.$result['id']);
				
			echo json_encode($result);
		}
	}

	public function deleteTemplates(){ 
		if(sizeof($_POST)){
                    
                      $params = $_POST;
			$result = $this->model_templates->checkeditTemplates($params); 
                        if($result['rows'][0]['edit_type']==0 && empty($result['rows'][0]['edit_user']))
                        {
                         file_get_contents(URL_TEMPLATE_REDIS_DEL.$params['id']);
                        $result = $this->model_templates->deleteTemplates($params);
                       
			} 
                        elseif($result['rows'][0]['edit_type']==1 && $result['rows'][0]['edit_user']==$result['rows'][0]['login_user'])
                        {
                         file_get_contents(URL_TEMPLATE_REDIS_DEL.$params['id']);
                        $result = $this->model_templates->deleteTemplates($params);
                       
                        }
                        else{
                        $result['edit_message'] = "Some of the user already editing this Templates so you cannot edit or delete";  
			$result['duplicateedit_data'] =  true; 
                      } 
                      echo json_encode($result);
		}
	}

	
        
        
        
      public function getTemplatesData(){
		if(sizeof($_POST)){
			$params = $_POST;
                        $result = $this->model_templates->checkeditTemplates($params);
                       
                        if($result['rows'][0]['edit_type']==0)
                        {
                       
                        $result = $this->model_templates->getTemplatesData($params);
                        $result['url'] = PATH_TEMPLATE.$result['url'];
			
			} 
                        elseif($result['rows'][0]['edit_type']==1 && $result['rows'][0]['edit_user']==$result['rows'][0]['login_user'])
                        {
                             
                        $result = $this->model_templates->getTemplatesData($params);
                        $result['url'] = PATH_TEMPLATE.$result['url'];
			
                        }
                        else{
                           
                        $result['edit_message'] = "Some of the user already editing this Templates so you cannot edit";   
			$result['duplicateedit_data'] =  true; 
                      } 
                   //  print_r($result);die();
                        echo json_encode($result);   
		}
			
			
	}
	
	
	public function Status(){
		if(sizeof($_POST)){
			$params = $_POST;
			$result = $this->model_templates->changeStatus($params);
			echo json_encode($result);
		}
	} 

	public function downloadZip(){
		$param = $_POST;
		$dirExtract = $this->config->item('template_extract');
		if(isset($param['folder']))
		{
			$curr = getcwd();
			chdir('/var/www/ads/tpl/');
			exec('mkdir -m 777 ../../tempadszip');
			exec('tar cf ../../tempadszip/'.$param['folder'].'.tar '.$param['folder'].'/');
			$result['url'] = 'http://202.153.129.88/tempadszip/'.$param['folder'].'.tar';
			$result['count'] = True;
			chdir($curr);
		}
		else
		{
			$result['url'] = '';
			$result['count'] = False;
		}
		echo json_encode($result);
	}
	
	public function uploadBackground(){
		if(!isset($_GET['files']))
			echo json_encode(array('error' => 'Missing Parameter', 'status' => false));
		
        $dirUpload = $this->config->item('template_upload');
		$dirAccess = $this->config->item('template_access');
		$dirExtract = $this->config->item('template_extract');
		$temp_path=$this->config->item('temp_upload_file');
                
		
		foreach($_FILES as $file)
		{
                      if(isset($_POST['upload_folder']) && trim($_POST['upload_folder'])!='')
                        {
                            $physicName = $_POST['upload_folder'];
                        }else{
                            $physicName = date('YmdHis'); 
                        }
                    //$physicName = '1234';
			#$physicName = strtolower(str_replace(' ', '',($file['name'])));
			//$physicName = date('YmdHis');
			#Upload Original Image
			if(move_uploaded_file($file['tmp_name'], $dirUpload['campaigns'].$physicName.'.zip')){	
                            
				$result['url'] 		= $dirAccess['campaigns'].$physicName;
				$result['path']		= $dirUpload['campaigns'].$physicName.'.zip';
				$result['view']		= $dirExtract['campaigns'].$physicName;
				$result['temp_path']= $temp_path['campaigns'];
				$result['status']	= true;
			} else {
                            
				$result['error'] 	= 'Uploading error.';
				$result['status'] 	= false;
			}
			
			if($result['status']){
                if(!isset($_POST['upload_folder']) || trim($_POST['upload_folder'])=='')
                {   
                    exec('mkdir -m 777 '.$result['view']);
                }else{
                	//exec('mv -f '.$result['view'].' '.$result['temp_path']);
					exec('chmod 0777 '.$result['temp_path']);
					exec('mv -f --backup=numbered '.$result['view'].' '.$result['temp_path']);
                	exec('mkdir -m 777 '.$result['view']);
                }

				exec('unzip -o '.$result['path'].' -d '.$result['view']);
				exec('chmod -R 777 '.$result['view']);
				$dir = scandir($result['view']);
				
				$baseFile = '';
				
                        
				if(count($dir) == 3){
					$baseFile = '/'.$dir[2];
					#Move all template data from sub folder
					exec('mv -f '.$result['view'].$baseFile.'/* '.$result['view'].'/');					
				}
				
				if((file_exists($result['view'].'/index.php') && file_exists($result['view'].'/detail.php')) || (file_exists($result['view'].'/index.html') && file_exists($result['view'].'/detail.html'))){
					
					if(file_exists($result['view'].'/index.php')){					
						$confirm = '/index.php';
						
						if(file_exists($result['view'].'/detail.php')){
							$status = '/detail.php';
						} else {
							exec('cp '.$result['view'].'/index.php '.$result['view'].'/detail.php');
							$status = '/detail.php';							
						}

						if(file_exists($result['view'].'/pin.php')){
							$pin = '/pin.php';
						} else {
							exec('cp '.$result['view'].'/index.php '.$result['view'].'/pin.php');
							$pin = '/pin.php';							
						}
					} 
					
					if(file_exists($result['view'].'/index.html')){					
						$confirm = '/index.html';
						
						if(file_exists($result['view'].'/detail.html')){
							$status = '/detail.html';
						} else {
							exec('cp '.$result['view'].'/index.html '.$result['view'].'/detail.html');
							$status = '/detail.html';
						}

						if(file_exists($result['view'].'/pin.html')){
							$pin = '/pin.html';
						} else {
							exec('cp '.$result['view'].'/index.html '.$result['view'].'/pin.html');
							$pin = '/pin.html';
						}

					} 
					
					$result['confirm'] = $result['url'].$confirm;
					$result['status'] = $result['url'].$status;
					$result['pin'] = $result['url'].$pin;
					$result['upload_folder'] = $_POST['upload_folder'];
					$editor = $this->createEditor();
										
					exec('chmod -R 777 '.$result['view']);
										
					file_put_contents($result['view'].'/editor.php', $editor);
					
					exec('chmod -R 777 '.$result['view']);
				} else {
					$result['error'] 	= 'file must be index.php, detail.php or index.html, detail.html';
					$result['status'] 	= false;
				}
			}
		}
		echo json_encode($result);
	}
	private function createEditor(){
		$pageEditor = '<?php
							if(isset($_GET["save"])){
								file_put_contents($_POST["file_name"], $_POST["editor"]);
							}
							
							if(!file_exists(base64_decode($_GET["data"]))){
								echo "<font style=color:red>File does not exist.</font>";
								http_response_code(500);
								die();
							}			
							
							$code = file_get_contents(base64_decode($_GET["data"]));
						?>
						<html>
						<head>
							<title>Editor</title>
							<script type="text/javascript" src="../../assets/js/ckeditor/ckeditor.js"></script>	
							<script type="text/javascript" src="../../assets/js/ckeditor/config.js"></script>								
						</head>
						<body>
							<form method="post" action="?data=<?php echo $_GET["data"];?>&save">
							<textarea id="editor" name="editor"><?php echo $code; ?></textarea>
							<br>
								<input type="hidden" name="file_name" value="<?php echo base64_decode($_GET["data"]);?>"/>
							<input type="submit" name="submit" value="Save Page"/>
							</form>
						</body>
						</html>';

		return $pageEditor;
	}
}

/*if(file_exists($result['view'].'/'.$baseFile.'/index.php')){
 $result['confirm'] = $result['url'].'/'.$baseFile.'/index.php';

 if(file_exists($result['view'].'/'.$baseFile.'/detail.php')){
 $result['status'] = $result['url'].'/'.$baseFile.'/detail.php';
 } else {
 exec('cp '.$result['view'].'/'.$baseFile.'/index.php '.$result['view'].'/'.$baseFile.'/detail.php');
 $result['status'] = $result['url'].'/'.$baseFile.'/detail.php';
 }
 } elseif(file_exists($result['view'].'/'.$baseFile.'/index.html')){
 $result['confirm'] = $result['url'].'/'.$baseFile.'/index.html';

 if(file_exists($result['view'].'/'.$baseFile.'/detail.html')){
 $result['status'] = $result['url'].'/'.$baseFile.'/detail.html';
 } else {
 exec('cp '.$result['view'].'/'.$baseFile.'/index.html '.$result['view'].'/'.$baseFile.'/detail.html');
 $result['status'] = $result['url'].'/'.$baseFile.'/detail.html';
 }
 }
 if(file_exists($result['view'].'/index.php')){
 $result['confirm'] = $result['url'].'/index.php';

 if(file_exists($result['view'].'/detail.php')){
 $result['status'] = $result['url'].'/detail.php';
 } else {
 exec('cp '.$result['view'].'/index.php '.$result['view'].'/detail.php');
 $result['status'] = $result['url'].'/detail.php';
 }
 } elseif(file_exists($result['view'].'/index.html')){
 $result['confirm'] = $result['url'].'/index.html';

 if(file_exists($result['view'].'/detail.html')){
 $result['status'] = $result['url'].'/detail.html';
 } else {
 exec('cp '.$result['view'].'/index.html '.$result['view'].'/detail.html');
 $result['status'] = $result['url'].'/detail.html';
 }
 }

 exec('chmod -R 777 '.$result['view']);
 */
?>
