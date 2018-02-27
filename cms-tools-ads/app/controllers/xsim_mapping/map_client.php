<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class map_client extends Admin_Controller {
		
	public function __construct() {
		parent::__construct();
                $this->load->model('xsim_mapping/model_xsim_map');
	}
	
	public function index() {	
            
		$this->loadMenu();
		
		if(!$this->data['accessView'])
			redirect($this->data['base_url_index'].'login', 'location', 301);
                
                $client_data=  file_get_contents(XSIM_CLIENT_URL);
		$this->data['client_list'] = json_decode($client_data,true);
		$this->data['pageTemplate']		= "xsim_mapping/map_xsim.php";
		$this->load->view('layout/main', $this->data);
	}

	public function getCampaignList(){
		$params = $_POST;
                $json=array();
		$result = $this->model_xsim_map->getCampaignList($params);
                
                $json['html']='';
                $json['status']=0;
                if($result['campaign_count'])
                {
                    $json['status']=1;
                    $json['html'].="<table>";
                    for($i=0;$i<count($result['data']);$i++)
                    {
                        $checked='';
                        if(!empty($result['selectd']))
                        {
                            for($j=0;$j<count($result['selectd']);$j++)
                            {
                                if($result['data'][$i]['campaign_code']==$result['selectd'][$j]['campaign_code'])
                                {
                                    $checked='CHECKED';
                                }
                            }
                            /*if(in_array($result['data'][$i]['campaign_code'], $result['selectd']))
                            {
                                
                            }*/
                        }
                        $json['html'].=  "<tr><td>"
                                . "<input type='checkbox' class='check_campaign'   $checked  id='check_".$result['data'][$i]['campaign_code']."' value='".$result['data'][$i]['campaign_code']."'>   ".$result['data'][$i]['name']
                                . "</td></tr>";
                    }
                    $json['html'].="</table>";
                }else{
                    $json['html'].='<p>No campaign found for the current configuration.</p>';
                }
        $json['html'].='</div>';
		//$result['pagination'] = $this->pagination($params['page'], $result['total'], $params['limit'], 'loadUser');
		
		echo json_encode($json);
	}
        public function mapCampaign(){
		$params = $_POST;
                $json=array();
		$result = $this->model_xsim_map->mapCampaign($params);
                $json['status']=$result;
		echo json_encode($json);
	}
	
	
}
?>
