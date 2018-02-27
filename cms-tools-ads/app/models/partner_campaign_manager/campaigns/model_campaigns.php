<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_campaigns extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}
	public function loadCampaigns($params){
		$result = array();
		$result['total'] = 0;
		$result['count'] = false;

		$partner_permissions = $this->session->userdata('partner_permissions');
		if(empty($partner_permissions))
			return $result;
		else
		{
			$partner_permissions = json_decode($partner_permissions);
			if(empty($partner_permissions->country) || empty($partner_permissions->shortcode) || empty($partner_permissions->keyword))
				return $result;
		}

		$user = $this->session->userdata('profile');
		$this->dbAds->select(' id ');
		$this->dbAds->from('campaigns');
		//$this->dbAds->where('entry_user', $user['id']);
		$this->dbAds->where_in('id_country', $partner_permissions->country);
		$this->dbAds->where('status', 2);
		$this->dbAds->where('expire_date_camp < CURDATE()');
		$query = $this->dbAds->get();
		if($query->num_rows() != 0){
			foreach($query->result_array() as $dummy)
			{
				file_get_contents(URL_CAMPAIGN_REDIS_DEL.$dummy['id']);

				$this->dbAds->where('id', $dummy['id']);
				$this->dbAds->delete('campaigns');
				if($this->dbAds->affected_rows()){
					$this->dbAds->where('id_campaign', $dummy['id']);
					$this->dbAds->delete('campaigns_details');

				}
			}
		}

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('campaigns c');
		$this->dbAds->join('campaigns_details cd', 'cd.id_campaign = c.id');
		$this->dbAds->join('partners_services ps', 'ps.id = cd.id_partner_service');
		$this->dbAds->join('shortcodes sh', 'sh.id = ps.id_shortcode');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		$this->dbAds->join('campaigns_media cm', 'c.id_campaign_media = cm.id');
		$this->dbAds->join('campaigns_categories cc', 'c.id_campaign_category = cc.id');
		$this->dbAds->join('banners b', 'c.id_banner = b.id', 'LEFT OUTER');
		//$this->dbAds->where('c.entry_user', $user['id']);
		$this->dbAds->where_in('id_country', $partner_permissions->country);
		$this->dbAds->where_in('sh.code', $partner_permissions->shortcode);
		$this->dbAds->where_in('ps.keyword', $partner_permissions->keyword);
		if(!empty($params['search']))
		$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR
								 co.name LIKE '%".$params['search']."%' OR cm.name LIKE '%".$params['search']."%' OR 
								 cm.name LIKE '%".$params['search']."%' OR cc.name LIKE '%".$params['search']."%' OR
								 c.content LIKE '%".$params['search']."%'");

		if(!empty($params['media']))
		$this->dbAds->where("c.id_campaign_media", $params['media']);
			
		if(!empty($params['category']))
		$this->dbAds->where("c.id_campaign_category", $params['category']);
			
		if(!empty($params['country']))
		$this->dbAds->where("c.id_country", $params['country']);
		$this->dbAds->group_by('c.id');
		$query = $this->dbAds->get();

		$total_records = 0;
		if(count($query->result_array()) > 0)
		{
			foreach($query->result_array() as $row)
				$total_records++;
		}
		$result['total'] = $total_records;

		$this->dbAds->select('c.id, c.name,c.id_template, c.content, c.status, c.use_confirmation,c.entry_user,c.edit_type,c.edit_user,c.entry_time,c.update_user,c.update_time');
		$this->dbAds->select('co.name AS country_name');
		$this->dbAds->select('ln.language AS language');
		$this->dbAds->select('cm.name AS media_name');
		$this->dbAds->select('cc.name AS categories_name');
		$this->dbAds->select('b.url_thumb');
		$this->dbAds->from('campaigns c');
		$this->dbAds->join('campaigns_details cd', 'cd.id_campaign = c.id');
		$this->dbAds->join('partners_services ps', 'ps.id = cd.id_partner_service');
		$this->dbAds->join('shortcodes sh', 'sh.id = ps.id_shortcode');
		$this->dbAds->join('countries co', 'c.id_country = co.id');
		$this->dbAds->join('language ln', 'c.id_language = ln.id', 'left');
		$this->dbAds->join('campaigns_media cm', 'c.id_campaign_media = cm.id');
		$this->dbAds->join('campaigns_categories cc', 'c.id_campaign_category = cc.id');
		$this->dbAds->join('banners b', 'c.id_banner = b.id', 'LEFT OUTER');
		//$this->dbAds->where('c.entry_user', $user['id']);
		$this->dbAds->where_in('id_country', $partner_permissions->country);
		$this->dbAds->where_in('sh.code', $partner_permissions->shortcode);
		$this->dbAds->where_in('ps.keyword', $partner_permissions->keyword);
		if(!empty($params['search']))
		$this->dbAds->where("(c.id LIKE '%".$params['search']."%' OR c.name LIKE '%".$params['search']."%' OR
								 co.name LIKE '%".$params['search']."%' OR cm.name LIKE '%".$params['search']."%' OR 
								 cm.name LIKE '%".$params['search']."%' OR cc.name LIKE '%".$params['search']."%' OR
								 c.content LIKE '%".$params['search']."%')");
			
		if(!empty($params['media']))
		$this->dbAds->where("c.id_campaign_media", $params['media']);
		if(!empty($params['category']))
		$this->dbAds->where("c.id_campaign_category", $params['category']);
			
		if(!empty($params['country']))
		$this->dbAds->where("c.id_country", $params['country']);
		$this->dbAds->group_by('c.id');
		$this->dbAds->order_by('c.id', 'DESC');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				try
				{
					$short_keyword      = $this->getShortcodesKeywords($row['id']);
					/*$shortcode_arr = array_unique(explode(',',$short_keyword['shortcode']));
					$shortcode_str = implode(', ',$shortcode_arr);
					$keyword_arr = array_unique(explode(',',$short_keyword['keyword']));
					$keyword_str = implode(', ',$keyword_arr);*/
					 $shortcode_arr = explode(',',$short_keyword['shortcode']);
					 $shortcode_arr = array_map('trim', $shortcode_arr);
					 $shortcode_arr = array_unique($shortcode_arr);
					 $shortcode_str = implode(', ',$shortcode_arr);
					 $keyword_arr = explode(',',$short_keyword['keyword']);
					 $keyword_arr = array_map('trim', $keyword_arr);
					 $keyword_arr = array_unique($keyword_arr);
					 $keyword_str = implode(', ',$keyword_arr);

					$result['rows'][$i]['shortcode']    = $shortcode_str;
					$result['rows'][$i]['keyword']    = $keyword_str;
				}
				catch (Exception $e){}
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['country_name'] = $row['country_name'];
				$result['rows'][$i]['media_name'] 	= $row['media_name'];
				$result['rows'][$i]['categories_name'] = $row['categories_name'];
				$result['rows'][$i]['url_thumb'] 	= $row['url_thumb'];
				$result['rows'][$i]['content'] 		= $row['content'];
				$result['rows'][$i]['name'] 		= $row['name'];
				$result['rows'][$i]['template'] 	= $row['id_template'];
				if($row['language'])
				$result['rows'][$i]['language'] 		= $row['language'];
				else
				$result['rows'][$i]['language'] 		= '';
				$result['rows'][$i]['use_confirmation'] = ($row['use_confirmation']==1)?'Yes':'No';
				$result['rows'][$i]['edit_type'] = $row['edit_type'];
				$result['rows'][$i]['edit_user'] = $row['edit_user'];
				$result['rows'][$i]['login_user'] = $this->profile['id'];
				if($row['status']==1)
				$result['rows'][$i]['status'] 		= 'Active';
				elseif($row['status']==2)
				$result['rows'][$i]['status'] 		= 'Dummy';
				elseif($row['status']==3)
				$result['rows'][$i]['status'] 		= 'External';
				else
				$result['rows'][$i]['status'] 		= 'Inactive';
				if(isset($row['update_user']))
				{
					$username = $this->getCampaignUser($row['update_user']);
					$result['rows'][$i]['user_updated']  = isset($username['username'])?$username['username']:'';
					$result['rows'][$i]['user_updated_time']  = $row['update_time'];
				}
				else
				{
					$result['rows'][$i]['user_updated']  = 'N/A';
					$result['rows'][$i]['user_updated_time']  = '';
				}
				if(isset($row['entry_user']))
				{
					$username = $this->getCampaignUser($row['entry_user']);
					$result['rows'][$i]['user_enter']  = isset($username['username'])?$username['username']:'';
					$result['rows'][$i]['user_enter_time']  = $row['entry_time'];
				}
				else
				{
					$result['rows'][$i]['user_enter']  = 'anb';
					$result['rows'][$i]['user_enter_time']  = '';
				}
				$session_user=$this->dbAds->escape_str($this->profile['id']);
				$session_user_id = $this->getUserid($session_user);
				$result['rows'][$i]['session_user_typeid'] 	= $session_user_id['tipe_user_id'];
				$result['rows'][$i]['edit_type'] 	=     $row['edit_type'];
					
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function changeStatus($params){
		 
		if($this->dbAds->escape_str($params['edit_type']==0))
		{
			$edit_user='';
		}
		else{
			$edit_user=$this->dbAds->escape_str($this->profile['id']);
		}
		$update = array('edit_type'=> $this->dbAds->escape_str($params['edit_type']),'edit_user'=> $edit_user);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('campaigns', $update);
		//echo $this->dbAds->last_query();die();
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;

	}

	public function getCampaignUser($id){
		$this->db->select('username');
		$this->db->from('campaign_cms.cms_user');

		$this->db->where('id', $id);
		$this->db->where('status', 1);

		$query = $this->db->get();

		$result = array();
		$i=0;
		foreach($query->result_array() as $row){

			$result['username'] = $row['username'];

			$i++;
		}

		return $result;
	}


	public function loadCampaignsSelect($params){
		$user = $this->session->userdata('profile');

		$result = array();

		$this->dbAds->select('c.id, c.name');
		$this->dbAds->from('campaigns c');
		$this->dbAds->join('campaigns_details cd', 'c.id = cd.id_campaign');
		$this->dbAds->join('partners_services pc', 'cd.id_partner_service = pc.id');

		$this->dbAds->where('c.entry_user', $user['id']);
		if(!empty($params['id_ads_publisher']))
		$this->dbAds->where('cd.id_ads_publisher', $params['id_ads_publisher']);
			
		if(!empty($params['id_operator']))
		$this->dbAds->where('pc.id_operator', $params['id_operator']);
		$this->dbAds->group_by('c.name');	
		$this->dbAds->order_by('c.name');
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['name'] 	= $row['name'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function saveCampaigns($params){
		if(!empty($params['publish_start']))
		{
			$params['publish_start'] = date_create($params['publish_start']);
			$params['publish_start'] = date_format($params['publish_start'], 'Y-m-d H:i:s');
		}
		if(!empty($params['publish_end']))
		{
			$params['publish_end'] = date_create($params['publish_end']);
			$params['publish_end'] = date_format($params['publish_end'], 'Y-m-d H:i:s');
		}
		
		$this->dbAds->select('id');
		$this->dbAds->from('campaigns');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateCampaigns($params);
		} else {
			$result = $this->insertCampaigns($params);
		}
		return $result;
	}

	public function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
	    $content = ""; 
	    if ($has_sections) { 
	        foreach ($assoc_arr as $key=>$elem) { 
	            $content .= "[".$key."]\n"; 
	            foreach ($elem as $key2=>$elem2) { 
	                if(is_array($elem2)) 
	                { 
	                    for($i=0;$i<count($elem2);$i++) 
	                    { 
	                        $content .= $key2."[] = \"".addslashes($elem2[$i])."\"\n"; 
	                    } 
	                } 
	                else if($elem2=="") $content .= $key2." = \n"; 
	                else $content .= $key2." = \"".addslashes($elem2)."\"\n"; 
	            } 
	        } 
	    }

	   	if (!$handle = fopen($path, 'w')) {
		        return false;
		}

	    $success = fwrite($handle, $content);
	    fclose($handle); 

	    return $success; 
	}

	private function updateCampaigns($params){
		$pars_upd_ini = parse_ini_file('/var/www/html/ads/thanks_msg.ini', true);
		if(isset($params['thnk_msg']) && !empty($params['thnk_msg']))
		{
			$thnk_msg = $params['thnk_msg'];
			if(isset($thnk_msg) && !empty($thnk_msg))
			{
				unset($params['thnk_msg']);
				$pars_upd_ini['thanks-msg'][$params['id']] = $thnk_msg;
			}
		}
		else
		{
			unset($pars_upd_ini['thanks-msg'][$params['id']]);
		}
		if(isset($params['alrt_msg']) && !empty($params['alrt_msg']))
		{
			$alrt_msg = $params['alrt_msg'];
			if(isset($alrt_msg) && !empty($alrt_msg))
			{
				unset($params['alrt_msg']);
				$pars_upd_ini['alert-msg'][$params['id']] = $alrt_msg;
			}
		}
		else
		{
			unset($pars_upd_ini['alert-msg'][$params['id']]);
		}
		$succ = $this->write_ini_file($pars_upd_ini, '/var/www/html/ads/thanks_msg.ini', true);

		$dom = '';
		for($d=0;$d<count($params['domain_list']);$d++)
		{
			if($d==count($params['domain_list'])-1)
			$sep = "";
			else
			$sep = ", ";
			$dom .=  $params['domain_list'][$d].$sep;
		}

		$status = false;

		$update = array(
						'id_country' 	=> $this->dbAds->escape_str($params['id_country']),
						'id_campaign_media' => $this->dbAds->escape_str($params['id_campaign_media']),
						'id_campaign_category' => $this->dbAds->escape_str($params['id_campaign_category']),
						'id_banner' 	=> $this->dbAds->escape_str($params['id_banner']),
						'id_template' 	=> $this->dbAds->escape_str($params['id_template']),
						'id_language' 	=> $this->dbAds->escape_str($params['id_language']),
						'content' 		=> $this->dbAds->escape_str($params['content']),
						'name' 			=> $this->dbAds->escape_str($params['name']),
						'description' 	=> $this->dbAds->escape_str($params['description']),
						'use_confirmation' 	=> $this->dbAds->escape_str($params['use_confirmation']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'expire_date_camp' => $this->dbAds->escape_str($params['expire_date_camp']),
						'update_user' 	=> $this->dbAds->escape_str($this->profile['id']),
						'update_time' 	=> $this->dbAds->escape_str(date('Y-m-d H:i:s')),
						'edit_type' 	=> '0',
						'edit_user' 	=> '',
						'publish_start' => $this->dbAds->escape_str($params['publish_start']),
						'publish_end' => $this->dbAds->escape_str($params['publish_end']),
						'thanksmsg' => $this->dbAds->escape_str($thnk_msg),
						'alertmsg' => $this->dbAds->escape_str($alrt_msg)
						);
						$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
						$this->dbAds->update('campaigns', $update);

						if($this->dbAds->affected_rows())
						$status = true;
						if(!empty($params['partner_service']) && !empty($params['ads_publisher'])){
							$code_result = array();
							$this->dbAds->select('campaign_code, campaign_code_new');
							$this->dbAds->from('campaigns_details');
							$this->dbAds->where('id_campaign', $this->dbAds->escape_str($params['id']));
							$this->dbAds->where('(`id_partner_service` NOT IN('.implode(',',$params['partner_service']).')');
							$this->dbAds->or_where('id_ads_publisher NOT IN('.implode(',',$params['ads_publisher']).'))');
							$query = $this->dbAds->get();
							if($query->num_rows() != 0)
							{
								$i = 0;
								foreach($query->result_array() as $code_dtls)
								{
									$code_result['campaign_code'][$i] = $code_dtls['campaign_code'];
									$code_result['campaign_code_new'][$i] = $code_dtls['campaign_code_new'];
									$i++;
								}
							}
						}
						if(!empty($params['partner_service']) && !empty($params['ads_publisher'])){
							$this->dbAds->where('id_campaign' , $this->dbAds->escape_str($params['id']));
							$this->dbAds->where('(`id_partner_service` NOT IN('.implode(',',$params['partner_service']).')');
							$this->dbAds->or_where('id_ads_publisher NOT IN('.implode(',',$params['ads_publisher']).'))');
							$this->dbAds->delete('campaigns_details');
						}

						if($this->dbAds->affected_rows())
						$status = true;
						$tot_pub_services = count($params['partner_service']) * count($params['ads_publisher']);
						$p = 0;
						foreach($params['partner_service'] as $key){
							$this->dbAds->select('code');
							$this->dbAds->from('partners_services');
							$this->dbAds->where('id', $key);
							$query = $this->dbAds->get();

							$code = "";
							foreach($query->result_array() as $row)
							$code = $row['code'];

							$i = 0;
							foreach($params['ads_publisher'] as $keyAds){
								if($p<=$tot_pub_services-1 && count($code_result)!=0)
								{
									$salt = empty($code_result['campaign_code'][$p])?md5($this->generateRandomString(32)):$code_result['campaign_code'][$p];
									$nameurl_salt = empty($code_result['campaign_code_new'][$p])?$this->generateRandomString(4):$code_result['campaign_code_new'][$p];
								}
								else
								{
									$salt = md5($this->generateRandomString(32));
									$nameurl_salt = $this->generateRandomString(4);
								}
								//$this->dbAds->query('INSERT IGNORE INTO campaigns_details(id_campaign, id_domain, id_ads_publisher, id_partner_service, campaign_code, cost, update_user, update_time) VALUES("'.$params['id'].'", "'.$dom.'", "'.$keyAds.'", "'.$key.'", "'.md5($params['id'].$keyAds.$code).'", "'.$params['cost'][$i].'", "'.$this->profile['id'].'", "'.date('Y-m-d H:i:s').'")');
								$this->dbAds->query('INSERT IGNORE INTO campaigns_details(id_campaign, id_domain, id_ads_publisher, id_partner_service, campaign_code, campaign_code_new, cost, update_user, update_time) VALUES("'.$params['id'].'", "'.$dom.'", "'.$keyAds.'", "'.$key.'", "'.$salt.'", "'.$nameurl_salt.'", "'.$params['cost'][$i].'", "'.$this->profile['id'].'", "'.date('Y-m-d H:i:s').'") ON DUPLICATE KEY UPDATE id_domain = "'.$dom.'", cost = "'.$params['cost'][$i].'"');
								if($this->dbAds->affected_rows())
								{
									$status = true;
									$p++;
								}
								$i++;
							}
						}

						$result = array();
						if($status){
							$result['success'] 	= true;
							$update_message=$this->config->item('update_message');
							$update_message=str_replace("{SECTION}","Campaign",$update_message);
							$update_message=str_replace("{TITLE}",$params['name'],$update_message);
							$update_message=str_replace("{ID}",$params['id'],$update_message);
							$this->SaveLogdata($update_message,$params);
							$result['id'] 		= $params['id'];
						} else {
							$result['success'] = false;
						}
						return $result;
	}

	public function checkeditCampaigns($params){
		$result = array();
		$this->dbAds->select('edit_type,edit_user');
		$this->dbAds->from('campaigns');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['edit_type'] = $row['edit_type'];
				$result['rows'][$i]['edit_user'] = $row['edit_user'];
				$result['rows'][$i]['login_user'] = $this->profile['id'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	private function insertCampaigns($params){
		/*$input_by = $this->session->userdata('id_user');
		 $input_time = strtotime(date('Y-m-d H:i:s'));*/
		$result = array();

		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('campaigns');
		$this->dbAds->where('name',$this->dbAds->escape_str($params['name']));

		$query = $this->dbAds->get();

		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Campaign Name you are using already added, so you can not use same name.";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}
		$dom = '';
		for($d=0;$d<count($params['domain_list']);$d++)
		{
			if($d==count($params['domain_list'])-1)
			$sep = "";
			else
			$sep = ", ";
			$dom .=  $params['domain_list'][$d].$sep;
		}

		$update = array(
						'id_country' 	=> $this->dbAds->escape_str($params['id_country']),
						'id_campaign_media' 		=> $this->dbAds->escape_str($params['id_campaign_media']),
						'id_campaign_category' 	=> $this->dbAds->escape_str($params['id_campaign_category']),
						'id_banner' 	=> $this->dbAds->escape_str($params['id_banner']),
						'id_template' 	=> $this->dbAds->escape_str($params['id_template']),
						'id_language' 	=> $this->dbAds->escape_str($params['id_language']),
		#'code_acm' 		=> $this->dbAds->escape_str($params['code_acm']),
						'content' 		=> $this->dbAds->escape_str($params['content']),
						'name' 			=> $this->dbAds->escape_str($params['name']),
						'description' 	=> $this->dbAds->escape_str($params['description']),
						'use_confirmation' 	=> $this->dbAds->escape_str($params['use_confirmation']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'expire_date_camp' => $this->dbAds->escape_str($params['expire_date_camp']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
						'publish_start' => $this->dbAds->escape_str($params['publish_start']),
						'publish_end' => $this->dbAds->escape_str($params['publish_end']),
						'thanksmsg' => $this->dbAds->escape_str($params['thnk_msg']),
						'alertmsg' => $this->dbAds->escape_str($params['alrt_msg'])
		);
		$this->dbAds->insert('campaigns', $update);

		// $result = array();
		if($this->dbAds->affected_rows()){
			$campaignId = $this->dbAds->insert_id();
			$pars_ini = parse_ini_file('/var/www/html/ads/thanks_msg.ini', true);
			if(isset($params['thnk_msg']) && !empty($params['thnk_msg']))
			{
				$thnk_msg = $params['thnk_msg'];
				if(isset($thnk_msg) && !empty($thnk_msg))
				{
					unset($params['thnk_msg']);
					$pars_ini['thanks-msg'][$campaignId] = $thnk_msg;
				}
			}
			else
			{
				unset($pars_ini['thanks-msg'][$campaignId]);
			}
			if(isset($params['alrt_msg']) && !empty($params['alrt_msg']))
			{
				$alrt_msg = $params['alrt_msg'];
				if(isset($alrt_msg) && !empty($alrt_msg))
				{
					unset($params['alrt_msg']);
					$pars_ini['alert-msg'][$campaignId] = $alrt_msg;
				}
			}
			else
			{
				unset($pars_ini['alert-msg'][$campaignId]);
			}
			$succ = $this->write_ini_file($pars_ini, '/var/www/html/ads/thanks_msg.ini', true);

			foreach($params['partner_service'] as $key){
				$this->dbAds->select('code');
				$this->dbAds->from('partners_services');
				$this->dbAds->where('id', $key);
				$query = $this->dbAds->get();
					
				$code = "";
				foreach($query->result_array() as $row)
				$code = $row['code'];
					
				$i=0;
				foreach($params['ads_publisher'] as $keyAds){
					$salt = $this->generateRandomString(32);
					$nameurl_salt = $this->generateRandomString(4);
					$this->dbAds->query('INSERT IGNORE INTO campaigns_details(id_campaign, id_domain, id_ads_publisher, id_partner_service, campaign_code,campaign_code_new, cost, entry_user, entry_time) VALUES("'.$campaignId.'", "'.$dom.'", "'.$keyAds.'", "'.$key.'", "'.md5($salt).'", "'.$nameurl_salt.'", "'.$params['cost'][$i].'", "'.$this->profile['id'].'", "'.date('Y-m-d H:i:s').'")');
					$i++;
				}
			}

			$result['success'] 	= true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Campaign",$add_message);
			$add_message=str_replace("{TITLE}",$params['name'],$add_message);
			$add_message=str_replace("{ID}",$campaignId,$add_message);
			$this->SaveLogdata($add_message,$params);
			$result['id']		= $campaignId;
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deleteCampaigns($params){
		$service_data=$this->getCampaignsData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('campaigns');
		$result = array();
		if($this->dbAds->affected_rows()){
			$this->dbAds->where('id_campaign' , $this->dbAds->escape_str($params['id']));
			//$this->dbAds->where('id_partner_service NOT IN('.implode(',',$params['partner_service']).')');
			$this->dbAds->delete('campaigns_details');

			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Campaign",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['name'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	public function getCampaignsData($params){
		$update = array('edit_type' 	=>1,
				   'edit_user' =>$this->profile['id']
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('campaigns', $update);
		$this->dbAds->select('id, name, id_country, id_campaign_media, id_campaign_category, id_banner, id_template, id_language, code_acm, description,content,status, use_confirmation, expire_date_camp,publish_start,publish_end');
		$this->dbAds->from('campaigns');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$key_val = '';
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['name'] = $row['name'];
				$result['id_country'] = $row['id_country'];
				$result['id_campaign_media'] = $row['id_campaign_media'];
				if ($row['id_campaign_media'] == 2) {
					$result['thnk_msg'] = ' ';
					$result['alrt_msg'] = ' ';
				}
				else
				{
					$get_thnkalrtmsg = parse_ini_file('/var/www/html/ads/thanks_msg.ini', true);
					$thnkmsg = stripslashes($get_thnkalrtmsg['thanks-msg'][$row['id']]);
					$alrtmsg = stripslashes($get_thnkalrtmsg['alert-msg'][$row['id']]);
					if(isset($thnkmsg) && !empty($thnkmsg))
					{
						$result['thnk_msg'] = $thnkmsg;
					}
					else
					{
						$result['thnk_msg'] = ' ';
					}
					if(isset($alrtmsg) && !empty($alrtmsg))
					{
						$result['alrt_msg'] = $alrtmsg;
					}
					else
					{
						$result['alrt_msg'] = ' ';
					}
				}
				$result['id_campaign_category'] = $row['id_campaign_category'];
				$result['id_banner'] = $row['id_banner'];
				$result['id_template'] = $row['id_template'];
				$result['id_language'] = $row['id_language'];
				#$result['code_acm'] = $row['code_acm'];
				$result['description'] = $row['description'];
				$result['content'] = $row['content'];
				$result['use_confirmation'] = $row['use_confirmation'];
				$result['status'] = $row['status'];
				$result['expire_date_camp'] = $row['expire_date_camp'];
				if($row['publish_start'] != "0000-00-00 00:00:00")
				{
					$publish_start = date_create($row['publish_start']);
					$result['publish_start'] = date_format($publish_start, 'Y/m/d H:i');
				}
				else
					$result['publish_start'] = "";
				if($row['publish_end'] != "0000-00-00 00:00:00")
				{
					$publish_end = date_create($row['publish_end']);
					$result['publish_end'] = date_format($publish_end, 'Y/m/d H:i');
				}
				else
					$result['publish_end'] = "";
				$i++;
			}

			$this->dbAds->select('id_partner_service, id_ads_publisher, cost, id_domain');
			$this->dbAds->from('campaigns_details');
			$this->dbAds->where('id_campaign', $params['id']);
			$query = $this->dbAds->get();

			$result['partners_services'] = array();
			$result['ads_publishers'] = array();
			$result['domain_list'] = array();

			foreach($query->result_array() as $row) {
				$result['partners_services'][$row['id_partner_service']] = TRUE;
				$result['ads_publishers'][$row['id_ads_publisher']] = TRUE;
				$result['cost'][$row['id_ads_publisher']] = empty($row['cost'])?0:$row['cost'];

				$key_val = explode(', ',$row['id_domain']);

			}

			foreach($key_val as $res){
				$result['domain_list'][$res] = TRUE;
			}

		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function getUrlData($params){

		$this->dbAds->select('Distinct(id_domain)');
		$this->dbAds->from('campaigns_details');
		$this->dbAds->where('id_campaign', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$domains = $row['id_domain'];
			}
		}

		$this->dbAds->select('c.id, c.name, cd.campaign_code,cd.campaign_code_new, d.name AS domain, ap.name AS ads_publisher_name, c.id_campaign_media, c.use_confirmation, cd.sub_use_confirmation,cd.id_campaign,cd.id_domain,cd.id_partner_service,cd.id_ads_publisher');
		$this->dbAds->select('o.name AS operator_name, p.name AS partner_name, cr.code AS currency_name, pr.value, s.code AS shortcode, ps.sid, ps.keyword');
		$this->dbAds->from('campaigns c');
		$this->dbAds->join('campaigns_details cd', 'c.id = cd.id_campaign');
		$this->dbAds->join('ads_publishers ap', 'ap.id = cd.id_ads_publisher');
		$this->dbAds->join('countries ct', 'c.id_country = ct.id');
		$this->dbAds->join('domains d', 'ct.id = d.id_country');
		$this->dbAds->join('partners_services ps', 'cd.id_partner_service = ps.id');
		$this->dbAds->join('operators o', 'ps.id_operator = o.id');
		$this->dbAds->join('shortcodes s', 'ps.id_shortcode = s.id');
		$this->dbAds->join('partners p', 'ps.id_partner = p.id');
		$this->dbAds->join('prices pr', 'ps.id_price = pr.id');
		$this->dbAds->join('currencies cr', 'pr.id_currency = cr.id');

		$this->dbAds->where('c.id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->where('d.id in ('.$domains.')');
		$query = $this->dbAds->get();

		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['slot'] = '[ '.$row['operator_name'].' ] [ '.$row['ads_publisher_name'].'('.$row['ads_code'].') ] [ '.$row['partner_name'].' ] [ '.$row['shortcode'].' ] [ '.$row['currency_name']. ' ' .number_format($row['value'],2).' ] [ '.$row['keyword'].' ]';
				//$result['rows'][$i]['url'] = $row['domain'].'/ads/'.$row['campaign_code'].'_'.strtolower(str_replace(' ', '-', $row['name']));
				$append = $row['campaign_code_new']?$row['campaign_code_new']:$row['name'];
				$result['rows'][$i]['url'] = $row['domain'].'/ads/'.$row['campaign_code'].'_'.$append;
				if($row['keyword'][0] == '*')
				{
					$_cb = $this->encode_base64($result['rows'][$i]['url'].'?type=IVR');
					$parameters = http_build_query(array(
					    '_cb' => $_cb,
					    'fc' => 1
					));
					$ivr_url = 'http://203.150.225.43/xyz.php?'.$parameters;
				}
				else
					$ivr_url = '';
				$result['rows'][$i]['ivr_url'] = $ivr_url;
				$result['rows'][$i]['id_campaign_media'] = $row['id_campaign_media'];
				$result['rows'][$i]['id_campaign'] = $row['id_campaign'];
				$result['rows'][$i]['id_domain'] = $row['id_domain'];
				$result['rows'][$i]['id_partner_service'] = $row['id_partner_service'];
				$result['rows'][$i]['id_ads_publisher'] = $row['id_ads_publisher'];
				$result['rows'][$i]['use_confirmation'] = $row['use_confirmation'];
				$result['rows'][$i]['sub_use_confirmation'] = $row['sub_use_confirmation'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function getUrlDataWithValues($params){

		$domains = '';
		$this->dbAds->select('Distinct(id_domain)');
		$this->dbAds->from('campaigns_details');
		$this->dbAds->where('id_campaign', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$domains = $row['id_domain'];
			}
		}

		$this->dbAds->select('c.id, c.name, cd.campaign_code,cd.campaign_code_new, d.name AS domain, ap.code AS ads_publisher_code, ap.name AS ads_publisher_name, ap.affiliate_params, ap.affiliate_values, c.id_campaign_media, c.use_confirmation, cd.sub_use_confirmation,cd.id_campaign,cd.id_domain,cd.id_partner_service,cd.id_ads_publisher');
		$this->dbAds->select('o.name AS operator_name, p.name AS partner_name, cr.code AS currency_name, pr.value, s.code AS shortcode, ps.sid, ps.keyword');
		$this->dbAds->from('campaigns c');
		$this->dbAds->join('campaigns_details cd', 'c.id = cd.id_campaign');
		$this->dbAds->join('ads_publishers ap', 'ap.id = cd.id_ads_publisher');
		$this->dbAds->join('countries ct', 'c.id_country = ct.id');
		$this->dbAds->join('domains d', 'ct.id = d.id_country');
		$this->dbAds->join('partners_services ps', 'cd.id_partner_service = ps.id');
		$this->dbAds->join('operators o', 'ps.id_operator = o.id');
		$this->dbAds->join('shortcodes s', 'ps.id_shortcode = s.id');
		$this->dbAds->join('partners p', 'ps.id_partner = p.id');
		$this->dbAds->join('prices pr', 'ps.id_price = pr.id');
		$this->dbAds->join('currencies cr', 'pr.id_currency = cr.id');

		$this->dbAds->where('c.id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->where('d.id in ('.$domains.')');
		$query = $this->dbAds->get();

		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {

				$affiliate_params = explode(',', $row['affiliate_params']);
				$affiliate_values = explode(',', $row['affiliate_values']);
				$camp_name_app = strtolower(str_replace(' ', '-', $row['name']));
				$camp_name_app = str_replace('--', '-', $camp_name_app);
				$lpname='&lp='.$camp_name_app;
				$affiliate = '';
				for($x=0;$x<count($affiliate_params);$x++){
					$separator = !empty($affiliate)?'&':'';
					$value = empty($affiliate_values[$x])?'':$affiliate_values[$x];
					$affiliate .= $separator.$affiliate_params[$x].'='.$value;
				}

				//$affiliate = str_replace('{affiliate_id}', $row['ads_publisher_code'], $affiliate);
				$pub_id = "&aff_id=".$row['ads_publisher_code'];

				$result['rows'][$i]['slot'] = '[ '.$row['operator_name'].' ] [ '.$row['ads_publisher_name'].'('.$row['ads_publisher_code'].') ] [ '.$row['partner_name'].' ] [ '.$row['shortcode'].' ] [ '.$row['currency_name']. ' ' .number_format($row['value'],2).' ] [ '.$row['keyword'].' ]';
				$append = $row['campaign_code_new']?$row['campaign_code_new']:$row['name'];
				//$result['rows'][$i]['url'] = $row['domain'].'/ads/'.$row['campaign_code'].'_'.strtolower(str_replace(' ', '-', $row['name'])).'?'.$affiliate.$pub_id;
				$result['rows'][$i]['url'] = $row['domain'].'/ads/'.$row['campaign_code'].'_'.$append.'?'.$affiliate.$pub_id.$lpname;
				if($row['keyword'][0] == '*')
				{
					$_cb = $this->encode_base64($result['rows'][$i]['url']);
					$parameters = http_build_query(array(
					    '_cb' => $_cb,
					    'fc' => 1
					));
					$ivr_url = 'http://203.150.225.43/xyz.php?'.$parameters;
				}
				else
					$ivr_url = '';
				$result['rows'][$i]['ivr_url'] = $ivr_url;
				$result['rows'][$i]['url_ecode'] =str_replace("=",'_',base64_encode($result['rows'][$i]['url']));
				$result['rows'][$i]['campaign_code'] =$row['campaign_code'];
                		$dirbanner = $this->config->item('aff_banner');
                		$result['rows'][$i]['banner_flag']=@file_exists($dirbanner['banner'].$row['campaign_code'])?1:0;
				$result['rows'][$i]['id_campaign_media'] = $row['id_campaign_media'];
				$result['rows'][$i]['id_campaign'] = $row['id_campaign'];
				$result['rows'][$i]['id_domain'] = $row['id_domain'];
				$result['rows'][$i]['id_partner_service'] = $row['id_partner_service'];
				$result['rows'][$i]['id_ads_publisher'] = $row['id_ads_publisher'];
				$result['rows'][$i]['use_confirmation'] = $row['use_confirmation'];
				$domain = explode("://",$row['domain']);
				$md5_domain = md5($domain[1]);
				if($row['use_confirmation'] == 1)
				{
					if($row['sub_use_confirmation'] == '')
						$result['rows'][$i]['sub_use_confirmation'] = 1;
					else
					{
						$sub_use_con_arr = json_decode($row['sub_use_confirmation']);
						if(isset($sub_use_con_arr->$md5_domain))
							$result['rows'][$i]['sub_use_confirmation'] = $sub_use_con_arr->$md5_domain;
						else
							$result['rows'][$i]['sub_use_confirmation'] = 1;
					}
				}
				else
				{
					$result['rows'][$i]['sub_use_confirmation'] = 0;
				}
				$result['rows'][$i]['curr_domain_id'] = $row['curr_domain_id'];
				$result['rows'][$i]['domain'] = $domain[1];
				$result['rows'][$i]['md5_domain'] = $md5_domain;
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function duplicateCampaigns($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$campaignId = '';
		$org_campaignId = $params[id];

		$this->dbAds->select('id_country,id_campaign_media,id_campaign_category,id_banner,id_template,id_language,code_acm,
							content,name,description,use_confirmation,crossell');
		$this->dbAds->from('campaigns');
		$this->dbAds->where('id', $org_campaignId);
		$query = $this->dbAds->get();

		$result = array();

		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$result['name'] = $row['name'].' - copy';
				$result['id_country'] = $row['id_country'];
				$result['id_campaign_media'] = $row['id_campaign_media'];
				$result['id_campaign_category'] = $row['id_campaign_category'];
				$result['id_banner'] = $row['id_banner'];
				$result['id_template'] = $row['id_template'];
				$result['id_language'] = $row['id_language'];
				#$result['code_acm'] = $row['code_acm'];
				$result['description'] = $row['description'];
				$result['content'] = $row['content'];
				$result['use_confirmation'] = $row['use_confirmation'];
				$result['crossell'] = $row['crossell'];
				$result['status'] = 0;
				$result['entry_user'] = $this->profile['id'];
				$result['entry_time'] = date('Y-m-d H:i:s');
				$result['update_user'] = $this->profile['id'];
				$result['update_time'] = date('Y-m-d H:i:s');
			}
		}
		$this->dbAds->insert('campaigns', $result);

		if($this->dbAds->affected_rows()){
			$campaignId = $this->dbAds->insert_id();
		}

		$this->dbAds->select('id_domain, id_partner_service, id_ads_publisher,	ads_publisher_code,	campaign_code, cost');
		$this->dbAds->from('campaigns_details');
		$this->dbAds->where('id_campaign', $org_campaignId);
		$query = $this->dbAds->get();

		$response = array();
		$i=0;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$this->dbAds->select('code');
				$this->dbAds->from('partners_services');
				$this->dbAds->where('id', $row['id_partner_service']);
				$query = $this->dbAds->get();
					
				$code = "";
				foreach($query->result_array() as $data)
				$code = $data['code'];

				$response[$i]['id_campaign'] = $campaignId;
				$response[$i]['id_domain'] = $row['id_domain'];
				$response[$i]['id_partner_service'] = $row['id_partner_service'];
				$response[$i]['id_ads_publisher'] = $row['id_ads_publisher'];
				$response[$i]['ads_publisher_code'] = $row['ads_publisher_code'];
				//$response[$i]['campaign_code'] = md5($campaignId.$row['id_ads_publisher'].$code);
				$response[$i]['campaign_code'] = md5($this->generateRandomString(32));
				$response[$i]['campaign_code_new'] = $this->generateRandomString(4);
				$response[$i]['cost'] = $row['cost'];
				$response[$i]['entry_user'] = $this->profile['id'];
				$response[$i]['entry_time'] = date('Y-m-d H:i:s');
				$response[$i]['update_user'] = $this->profile['id'];
				$response[$i]['update_time'] = date('Y-m-d H:i:s');
				$i++;
			}
		}

		for($j=0;$j<count($response);$j++)
		{
			$this->dbAds->insert('campaigns_details', $response[$j]);
		}

		$fnl_result = array();

		if($campaignId){
			$fnl_result['success'] 	= true;
			$fnl_result['id']		= $campaignId;
		} else {
			$fnl_result['success'] = false;
		}


		return $fnl_result;

	}
	public function getShortcodesKeywords($id)
	{
		$result = array();
		$this->dbAds->select('Distinct(cd.id_partner_service)');
		$this->dbAds->from('campaigns_details cd');
		if(!empty($id))
		$this->dbAds->where('cd.id_campaign', $id);
		$query = $this->dbAds->get();
		$i=1;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {

				if($i<count($query->result_array()))
				$comma = ', ';
				else
				$comma = '';
				$this->dbAds->select('ps.id_shortcode, ps.keyword, sh.code');
				$this->dbAds->from('partners_services ps');
				$this->dbAds->join('shortcodes sh', 'ps.id_shortcode=sh.id');
				if(!empty($row['id_partner_service']))
				$this->dbAds->where('ps.id', $row['id_partner_service']);

				$query2 = $this->dbAds->get();
				if($query2->num_rows() != 0){
					foreach($query2->result_array() as $col) {
						$result['shortcode'] .= $col['code'].$comma;
						$result['keyword'] .= $col['keyword'].$comma;
					}
				}
				$i++;
			}
		}
		return $result;
	}

	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[mt_rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	public function encode_base64($string, $key = 'Yx3LrD') {
	    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
	}

	public function setUseConfirmation($params)
	{
		$result['success'] = false;

		$data = $params['data'];
		$status = $params['status'];
		$sub_use_confirmation='';
		$sub_con_arr = array();
		$this->dbAds->select('sub_use_confirmation');
		$this->dbAds->from('campaigns_details');
		$this->dbAds->where('id_campaign', $this->dbAds->escape_str($data['id_campaign']));
		$this->dbAds->where('id_domain', $this->dbAds->escape_str($data['id_domain']));
		$this->dbAds->where('id_partner_service', $this->dbAds->escape_str($data['id_partner_service']));
		$this->dbAds->where('id_ads_publisher', $this->dbAds->escape_str($data['id_ads_publisher']));
		$query = $this->dbAds->get();
		if($query->num_rows()>0){
			$row = $query->result_array();
			$sub_use_confirmation = $row[0]['sub_use_confirmation'];
		}
		if($sub_use_confirmation == '')
		{
		$sub_con_arr[$data['md5_domain']] = $status;
		}
		else{
			$sub_con_arr = json_decode($sub_use_confirmation);
			$sub_con_arr->$data['md5_domain'] = $status;
		}
		$sub_con_str = json_encode($sub_con_arr);
		$update = array('sub_use_confirmation' => $sub_con_str);
		$this->dbAds->where('id_campaign', $this->dbAds->escape_str($data['id_campaign']));
		$this->dbAds->where('id_domain', $this->dbAds->escape_str($data['id_domain']));
		$this->dbAds->where('id_partner_service', $this->dbAds->escape_str($data['id_partner_service']));
		$this->dbAds->where('id_ads_publisher', $this->dbAds->escape_str($data['id_ads_publisher']));

		$this->dbAds->update('campaigns_details', $update);
		if($this->dbAds->affected_rows())
			$result['success'] = true;
		
		return $result;
	}
}
?>
