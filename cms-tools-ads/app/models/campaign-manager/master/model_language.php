<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_language extends MY_Model {
	
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadLanguage($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('language');
		if(!empty($params['search']))
			$this->dbAds->where("id LIKE '%".$params['search']."%' OR language LIKE '%".$params['search']."%'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('id, language, status');
		$this->dbAds->from('language');
		if(!empty($params['search']))
			$this->dbAds->where("id LIKE '%".$params['search']."%' OR language LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['language'] 	= $row['language'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadLanguageSelect(){		
		$result = array();
				
		$this->dbAds->select('id, language');
		$this->dbAds->from('language');
		$this->dbAds->where('status',1);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['language'] 	= $row['language'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveLanguage($params){
           
		$this->dbAds->select('id');
		$this->dbAds->from('language');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateLanguage($params);	
		} else {
                    
			$result = $this->insertLanguage($params);
		}
		return $result;
	}
	
	private function updateLanguage($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'language' 		=> $this->dbAds->escape_str($params['language']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('language', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('update_message');
			$update_message=str_replace("{SECTION}","Language",$update_message);
			$update_message=str_replace("{TITLE}",$params['language'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertLanguage($params){
            
                 $this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('language');
		$this->dbAds->where('language',$this->dbAds->escape_str($params['language']));
		
		$query = $this->dbAds->get();
		$result = array();
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Language Already Configured in this Settings So You can Edit not add";  
				$result['duplicat_data'] =  true;
				return $result;
			}
		} 
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'language' 		=> $this->dbAds->escape_str($params['language']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->insert('language', $update);
		//$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$add_message=$this->config->item('add_message');
			$add_message=str_replace("{SECTION}","Language",$add_message);
			$add_message=str_replace("{TITLE}",$params['language'],$add_message);
			$add_message=str_replace("{ID}",$this->dbAds->insert_id(),$add_message);
			$this->SaveLogdata($add_message,$params);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function deleteLanguage($params){
		$service_data=$this->getLanguageData($params);
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('language');
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
			$update_message=$this->config->item('delete_message');
			$update_message=str_replace("{SECTION}","Language",$update_message);
			$update_message=str_replace("{TITLE}",$service_data['language'],$update_message);
			$update_message=str_replace("{ID}",$params['id'],$update_message);
			$this->SaveLogdata($update_message,$service_data);
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getLanguageData($params){
		$this->dbAds->select('id, language, status');
		$this->dbAds->from('language');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['language'] = $row['language'];
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
