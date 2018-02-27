<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_webapi extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadWebAPI($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('webapi c');
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.campaign_code LIKE '%".$params['search']."%' OR c.affiliate_name LIKE '%".$params['search']."%' OR c.api_url LIKE '%".$params['search']."%' OR c.param LIKE '%".$params['search']."%' OR c.domain LIKE '%".$params['search']."%'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('c.id, c.campaign_code, c.affiliate_name, c.api_url, c.status, c.param, c.domain');
		$this->dbAds->from('webapi c');
		if(!empty($params['search']))
			$this->dbAds->where("c.id LIKE '%".$params['search']."%' OR c.campaign_code LIKE '%".$params['search']."%' OR c.affiliate_name LIKE '%".$params['search']."%' OR c.api_url LIKE '%".$params['search']."%' OR c.param LIKE '%".$params['search']."%' OR c.domain LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['campaign_code'] 	= $row['campaign_code'];
				$result['rows'][$i]['affiliate_name'] 	= $row['affiliate_name'];
				$result['rows'][$i]['api_url'] 	= $row['api_url'];
				$result['rows'][$i]['param'] 	= $row['param'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$result['rows'][$i]['domain'] 	= $row['domain'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadWebAPISelect(){		
		$result = array();
				
		$this->dbAds->select('id, campaign_code, affiliate_name, api_url, param, domain');
		$this->dbAds->from('webapi');
		$this->dbAds->where('status', '1');
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['campaign_code'] 	= $row['campaign_code'];
				$result['rows'][$i]['affiliate_name'] 	= $row['affiliate_name'];
				$result['rows'][$i]['api_url'] 	= $row['api_url'];
				$result['rows'][$i]['param'] 	= $row['param'];
				$result['rows'][$i]['domain'] 	= $row['domain'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveWebAPI($params){
		$this->dbAds->select('id');
		$this->dbAds->from('webapi');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateWebAPI($params);	
		} else {
			$result = $this->insertWebAPI($params);
		}
		return $result;
	}
	
	private function updateWebAPI($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'campaign_code' 		=> $this->dbAds->escape_str($params['campaign_code']),
						'affiliate_name' 		=> $this->dbAds->escape_str($params['affiliate_name']),
						'api_url' 	=> $this->dbAds->escape_str($params['api_url']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'param' 		=> $this->dbAds->escape_str($params['param']),
						'domain' 		=> $this->dbAds->escape_str($params['domain']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('webapi', $update);
		$result = array();
		if($this->dbAds->affected_rows()){

			$result['success'] = true;
			$result['id']		= $params['id'];

		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertWebAPI($params){

		$result = array();    
        $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('webapi');
		$this->dbAds->where("campaign_code", $this->dbAds->escape_str($params['campaign_code'])); 
               
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "This campaign code is already added, use different camapign code.";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'campaign_code' 		=> $this->dbAds->escape_str($params['campaign_code']),
						'affiliate_name' 		=> $this->dbAds->escape_str($params['affiliate_name']),
						'api_url' 	=> $this->dbAds->escape_str($params['api_url']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'param' 		=> $this->dbAds->escape_str($params['param']),
						'domain' 		=> $this->dbAds->escape_str($params['domain']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('webapi', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$webapiId = $this->dbAds->insert_id();
			
			$result['success'] = true;
			$result['id']		= $webapiId;

		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function saveTestwebAPI($params){

		$code_api = explode('_', $params['campaign_code_test']);

		$result = array();

		$result['count'] = true;
		$result['campaign_code_test'] 	= $params['campaign_code_test'];
		$result['msisdn_test'] 	= $params['msisdn_test'];
		$result['refferal_test'] 	= $params['refferal_test'];
		$result['domain_test'] 	= $params['domain_test'];
		$result['visitorip'] 	= $params['visitorip'];
		if($params['pin'])
		{
			$url =  "http://54.169.14.129/webapi/validatepin?pin=".$params['pin'];

			$curl_hit = $this->getcurlurl($url, 'GET');

			$result['test_result'] 	= json_decode($curl_hit, TRUE);
			$result['test_result_url'] 	= $code_api[1]."?authkey=".$code_api[0]."&msisdn={MSISDN}&referer={REFERRAL-URL}&visitorip={IP}"."\nvalidate url: http://54.169.14.129/webapi/validatepin?pin={pin}";
		}
		else
		{
			$url =  $code_api[1]."?authkey=".$code_api[0]."&msisdn=".$params['msisdn_test']."&referer=".$params['refferal_test']."&userip=".$params['domain_test']."&visitorip=".$params['visitorip'];

			$curl_hit = $this->getcurlurl($url, 'GET');

			$result['test_result'] 	= json_decode($curl_hit, TRUE);
			// print_r($result['test_result']);die('lop');
			$result['test_result_url'] 	= $code_api[1]."?authkey=".$code_api[0]."&msisdn={MSISDN}&referer={REFERRAL-URL}&visitorip={IP}";
		}

		return $result;
	}
	
	public function deleteWebAPI($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('webapi');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$result['id']		= $params['id'];
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getWebAPIData($params){
		$this->dbAds->select('id, campaign_code, affiliate_name, api_url, param, domain, status');
		$this->dbAds->from('webapi');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['campaign_code'] 	= $row['campaign_code'];
				$result['affiliate_name'] 	= $row['affiliate_name'];
				$result['api_url'] 	= $row['api_url'];
				$result['param'] 	= $row['param'];
				$result['domain'] 	= $row['domain'];
				$result['status'] = $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function getcurlurl($url, $method = 'get', $header = null, $postdata = null, $new = false, $timeout = 15) 
	{
        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $url);

        if ($header) {
            curl_setopt($s, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($s, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($s, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($s, CURLOPT_MAXREDIRS, 5);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);

        if (strtolower($method) == 'post') {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
        } else if (strtolower($method) == 'delete') {
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else if (strtolower($method) == 'put') {
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
        }

        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($s);

        curl_close($s);
        return $html;
    }

}
?>
