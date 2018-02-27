<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_campaigns_auto_redirect extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadCampaigns_Auto_Redirect($params){
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('campaigns_auto_redirect car');
		$this->dbAds->join('campaigns c', 'car.id_campaign = c.id');
		$this->dbAds->join('ads_publishers ap', 'ap.id = car.id_ads_publisher');
		$this->dbAds->join('operators o', 'o.id = car.id_operator');
		if(!empty($params['search']))
			$this->dbAds->where("(car.id LIKE '%".$params['search']."%' OR o.name LIKE '%".$params['search']."%' OR 
								 c.name LIKE '%".$params['search']."%' OR ap.name LIKE '%".$params['search']."%')");
					
		if(!empty($params['operator']))
			$this->dbAds->where("car.id_operator", $params['operator']);
			
		if(!empty($params['ads_publisher']))
			$this->dbAds->where("car.id_ads_publisher", $params['ads_publisher']);
					
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('car.id, c.name AS name_campaign, ap.name AS name_ads_publisher, o.name AS name_operator, car.day_from, car.hour_from, car.day_to, car.hour_to, car.status');
		$this->dbAds->from('campaigns_auto_redirect car');
		$this->dbAds->join('campaigns c', 'car.id_campaign = c.id');
		$this->dbAds->join('ads_publishers ap', 'ap.id = car.id_ads_publisher');
		$this->dbAds->join('operators o', 'o.id = car.id_operator');
		if(!empty($params['search']))
			$this->dbAds->where("(car.id LIKE '%".$params['search']."%' OR o.name LIKE '%".$params['search']."%' OR 
								 c.name LIKE '%".$params['search']."%' OR ap.name LIKE '%".$params['search']."%')");
					
		if(!empty($params['operator']))
			$this->dbAds->where("car.id_operator", $params['operator']);
			
		if(!empty($params['ads_publisher']))
			$this->dbAds->where("car.id_ads_publisher", $params['ads_publisher']);
											 
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$this->dbAds->order_by('car.id', 'DESC');
				
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 			= $row['id'];
				$result['rows'][$i]['name_campaign'] = $row['name_campaign'];
				$result['rows'][$i]['name_ads_publisher'] 	= $row['name_ads_publisher'];
				$result['rows'][$i]['name_operator'] = $row['name_operator'];
				$result['rows'][$i]['day_from'] 	= $this->getDayList($row['day_from']);
				$result['rows'][$i]['hour_from'] 	= $row['hour_from'];
				$result['rows'][$i]['day_to'] 		= $this->getDayList($row['day_to']);
				$result['rows'][$i]['hour_to'] 		= $row['hour_to'];
				$result['rows'][$i]['status'] 		= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadCampaigns_Auto_RedirectSelect(){		
		$result = array();
				
		$this->dbAds->select('id, hour_from, id_operator, day_to');
		$this->dbAds->from('campaigns_auto_redirect');
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['hour_from'] 	= $row['hour_from'];
				$result['rows'][$i]['id_operator'] 	= $row['id_operator'];
				$result['rows'][$i]['day_to'] 	= $row['day_to'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveCampaigns_Auto_Redirect($params){
		$this->dbAds->select('id');
		$this->dbAds->from('campaigns_auto_redirect');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateCampaigns_Auto_Redirect($params);	
		} else {
			$result = $this->insertCampaigns_Auto_Redirect($params);
		}
		return $result;
	}
	
	private function updateCampaigns_Auto_Redirect($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$status = false;
		
		$update = array(
						'id_operator' 	=> $this->dbAds->escape_str($params['id_operator']),
						'id_ads_publisher' => $this->dbAds->escape_str($params['id_ads_publisher']),
						'id_campaign' => $this->dbAds->escape_str($params['id_campaign']),
						'day_from' 		=> $this->dbAds->escape_str($params['day_from']),
						'hour_from' 			=> $this->dbAds->escape_str($params['hour_from']),
						'day_to' 	=> $this->dbAds->escape_str($params['day_to']),
						'hour_to' 	=> $this->dbAds->escape_str($params['hour_to']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('campaigns_auto_redirect', $update);
		
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertCampaigns_Auto_Redirect($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'id_operator' 	=> $this->dbAds->escape_str($params['id_operator']),
						'id_ads_publisher' => $this->dbAds->escape_str($params['id_ads_publisher']),
						'id_campaign' 		=> $this->dbAds->escape_str($params['id_campaign']),
						'day_from' 		=> $this->dbAds->escape_str($params['day_from']),
						'hour_from' 	=> $this->dbAds->escape_str($params['hour_from']),
						'day_to' 	=> $this->dbAds->escape_str($params['day_to']),
						'hour_to' 	=> $this->dbAds->escape_str($params['hour_to']),
						'status' 		=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('campaigns_auto_redirect', $update);
		
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteCampaigns_Auto_Redirect($params){
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('campaigns_auto_redirect');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getCampaigns_Auto_RedirectData($params){
		$this->dbAds->select('id, hour_from, id_operator, id_ads_publisher, id_campaign, day_to, day_from,status, hour_to');
		$this->dbAds->from('campaigns_auto_redirect');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['hour_from'] = $row['hour_from'];
				$result['id_operator'] = $row['id_operator'];
				$result['id_ads_publisher'] = $row['id_ads_publisher'];
				$result['id_campaign'] = $row['id_campaign'];
				$result['day_to'] = $row['day_to'];
				$result['day_from'] = $row['day_from'];
				$result['hour_to'] = $row['hour_to'];
				$result['status'] = $row['status'];
				$i++;
			}			
		} else {
			$result['count'] = false;
		}
		return $result;
	}
}
?>
