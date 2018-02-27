<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_ads_publishers_pf extends MY_Model {

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

	}

	public function loadAds_Publishers_PF($params){
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('ads_publishers_pf app');
		$this->dbAds->join('ads_publishers ap', 'app.id_ads_publisher = ap.id');
		$this->dbAds->join('operators o', 'app.id_operator = o.id');
		if(!empty($params['search']))
		$this->dbAds->where("(app.id LIKE '%".$params['search']."%' OR app.pf_value LIKE '%".$params['search']."%' OR
								 ap.name LIKE '%".$params['search']."%' OR o.name LIKE '%".$params['search']."%')");

		if(!empty($params['ads_publisher']))
		$this->dbAds->where("app.id_ads_publisher", $params['ads_publisher']);
			
		if(!empty($params['operator']))
		$this->dbAds->where("app.id_operator", $params['operator']);
			
		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('app.id, ap.name AS name_ads_publisher, o.name AS name_operator, app.pf_value, app.status, app.acquisition_type');
		$this->dbAds->from('ads_publishers_pf app');
		$this->dbAds->join('ads_publishers ap', 'app.id_ads_publisher = ap.id');
		$this->dbAds->join('operators o', 'app.id_operator = o.id');
		if(!empty($params['search']))
		$this->dbAds->where("(app.id LIKE '%".$params['search']."%' OR app.pf_value LIKE '%".$params['search']."%' OR
								 ap.name LIKE '%".$params['search']."%' OR o.name LIKE '%".$params['search']."%')");
			
		if(!empty($params['ads_publisher']))
		$this->dbAds->where("app.id_ads_publisher", $params['ads_publisher']);
			
		if(!empty($params['operator']))
		$this->dbAds->where("app.id_operator", $params['operator']);
			

		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$this->dbAds->order_by('app.id', 'DESC');
		$query = $this->dbAds->get();

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 				= $row['id'];
				$result['rows'][$i]['name_operator'] 	= $row['name_operator'];
				$result['rows'][$i]['name_ads_publisher'] = $row['name_ads_publisher'];
				$result['rows'][$i]['acquisition_type'] = $row['acquisition_type'];
				$result['rows'][$i]['pf_value'] 		= ($row['acquisition_type'] == 'Amount')?number_format($row['pf_value'],2):number_format($row['pf_value'],0).'% Send';
				$result['rows'][$i]['status'] 			= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function loadAds_Publishers_PFSelect($params){
		$result = array();

		$this->dbAds->select('c.id, c.name');
		$this->dbAds->from('ads_publishers_pf c');
		$this->dbAds->join('ads_publishers_pf_details cd', 'c.id = cd.id_campaign');
		$this->dbAds->join('partners_services pc', 'cd.id_partner_service = pc.id');

		if(!empty($params['id_ads_publisher']))
		$this->dbAds->where('c.id_ads_publisher', $params['id_ads_publisher']);
			
		if(!empty($params['id_operator']))
		$this->dbAds->where('pc.id_operator', $params['id_operator']);

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

	public function saveAds_Publishers_PF($params){
		$this->dbAds->select('id');
		$this->dbAds->from('ads_publishers_pf');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateAds_Publishers_PF($params);
		} else {
			$result = $this->insertAds_Publishers_PF($params);
		}
		return $result;
	}

	private function updateAds_Publishers_PF($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$status = false;

		$update = array(
						'id_ads_publisher' 	=> $this->dbAds->escape_str($params['id_ads_publisher']),
						'id_operator' 		=> $this->dbAds->escape_str($params['id_operator']),
						'acquisition_type'	=> $this->dbAds->escape_str($params['acquisition_type']),
						'pf_value' 			=> $this->dbAds->escape_str($params['pf_value']),
						'status' 			=> $this->dbAds->escape_str($params['status']),
						'update_user' 		=> $this->dbAds->escape_str($this->profile['id']),
						'update_time' 		=> $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('ads_publishers_pf', $update);

		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Vendor Pixel Fire",$update_message);
			$update_message=str_replace("{TITLE}",$params['acquisition_type'].'_'.$params['pf_value'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);

		} else {
			$result['success'] = false;
		}
		return $result;
	}

	private function insertAds_Publishers_PF($params){

		$result = array();
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('ads_publishers_pf');
		$this->dbAds->where('id_operator', $params['id_operator']);
		$this->dbAds->where('id_ads_publisher', $params['id_ads_publisher']);
			
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('lop');
		foreach($query->result_array() as $row)
		{
			if($row[exist_data] !=0)
			{
				$result['errors_message'] = "Pixel fire for this Country and Ads Vendor combination is already exist, so add different one or edit";
				$result['duplicat_data'] =  true;
				return $result;
			}
		}

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'id_ads_publisher' 	=> $this->dbAds->escape_str($params['id_ads_publisher']),
						'id_operator' 		=> $this->dbAds->escape_str($params['id_operator']),
						'acquisition_type'	=> $this->dbAds->escape_str($params['acquisition_type']),
						'pf_value' 			=> $this->dbAds->escape_str($params['pf_value']),
						'status' 			=> $this->dbAds->escape_str($params['status']),
						'entry_user' 		=> $this->dbAds->escape_str($this->profile['id']),
						'entry_time' 		=> $this->dbAds->escape_str(date('Y-m-d H:i:s'))
		);
		$this->dbAds->insert('ads_publishers_pf', $update);

		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Vendor Pixel Fire",$add_message);
			$add_message=str_replace("{TITLE}",$params['acquisition_type'].'_'.$params['pf_value'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function deleteAds_Publishers_PF($params){
		$service_data=$this->getAds_Publishers_PFData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('ads_publishers_pf');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Vendor Pixel Fire",$update_message);
			$update_message=str_replace("{TITLE}",$params['acquisition_type'].'_'.$params['pf_value'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}

	public function getAds_Publishers_PFData($params){
		$this->dbAds->select('id, id_ads_publisher, id_operator, pf_value, status, acquisition_type');
		$this->dbAds->from('ads_publishers_pf');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] 				= $row['id'];
				$result['id_ads_publisher'] = $row['id_ads_publisher'];
				$result['id_operator'] 		= $row['id_operator'];
				$result['acquisition_type'] = $row['acquisition_type'];
				$result['pf_value'] 		= ($row['acquisition_type'] == 'Amount')?$row['pf_value']:number_format($row['pf_value'],0);
				$result['status'] 			= $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
	
	public function getCampaignDetails($params){
		$this->dbAds->select('ap.id, ap.id_ads_publisher, ap.id_operator');
		$this->dbAds->select('cd.id_campaign, cd.campaign_code');
		$this->dbAds->from('ads_publishers_pf ap');
		$this->dbAds->join('campaigns_details cd', 'ap.id_ads_publisher = cd.id_ads_publisher');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result[$i]['id'] 				= $row['id'];
				$result[$i]['id_ads_publisher'] = $row['id_ads_publisher'];
				$result[$i]['id_operator'] 		= $row['id_operator'];
				$result[$i]['id_campaign'] = $row['id_campaign'];
				$result[$i]['campaign_code'] = $row['campaign_code'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function getCampaignIdsByPublisherId($pub_id)
	{
		$this->dbAds->select('distinct(id_campaign)');
		$this->dbAds->from('campaigns_details');
		$this->dbAds->where('id_ads_publisher', $this->dbAds->escape_str($pub_id));
		$query = $this->dbAds->get();
		$result = array();
		if($query->num_rows() != 0){
			$result['count'] = true;
			$campaign_ids = array();
			foreach($query->result_array() as $row) {
				$campaign_ids[] = $row['id_campaign'];
			}
			$result['campaign_ids'] = $campaign_ids;
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function saveAll_PF(){
		$this->dbAds->select('id, id_ads_publisher, id_operator, pf_value, status, acquisition_type');
		$this->dbAds->from('ads_publishers_pf');
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['data'][$i]['id'] 				= $row['id'];
				$result['data'][$i]['id_ads_publisher'] = $row['id_ads_publisher'];
				$result['data'][$i]['id_operator'] 		= $row['id_operator'];
				$result['data'][$i]['acquisition_type'] = $row['acquisition_type'];
				$result['data'][$i]['pf_value'] 		= ($row['acquisition_type'] == 'Amount')?$row['pf_value']:number_format($row['pf_value'],0);
				$result['data'][$i]['status'] 			= $row['status'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}

}
?>
