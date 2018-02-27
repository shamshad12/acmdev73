<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_blocker extends MY_Model {
	
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}
	
	public function loadBlocker($params){		
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		//$this->dbAds->from('blocker');
		if(!empty($params['search']))
		{
			$this->dbAds->join('countries co', 'bl.country_id = co.id');
			$this->dbAds->join('operators ot', 'bl.operator_id = ot.id');
			$this->dbAds->join('shortcodes sc', 'bl.shortcode_id = sc.id');
			$this->dbAds->distinct('bl.id') ;
			$this->dbAds->from('blocker bl');
			$this->dbAds->where("bl.service LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR ot.name LIKE '%".$params['search']."%' OR sc.code LIKE '%".$params['search']."%'");
		}
		
		$query = $this->dbAds->get();
		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];
				
		$this->dbAds->select('bl.id,co.name as country, ot.name as telco, bl.shortcode_id as shortcode, bl.service, bl.status');
		$this->dbAds->join('countries co', 'bl.country_id = co.id');
		$this->dbAds->join('operators ot', 'bl.operator_id = ot.id');
		// $this->dbAds->join('shortcodes sc', 'bl.shortcode_id = sc.id');
		$this->dbAds->distinct('bl.id') ;
		$this->dbAds->from('blocker bl');
		if(!empty($params['search']))
		$this->dbAds->where("bl.service LIKE '%".$params['search']."%' OR co.name LIKE '%".$params['search']."%' OR ot.name LIKE '%".$params['search']."%' OR sc.code LIKE '%".$params['search']."%'");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['country'] 	= $row['country'];
				$result['rows'][$i]['telco'] 	= $row['telco'];
				if($row['shortcode']=='All')
				{
					$shortcode = 'All';
				}
				else
				{
					$expl = explode("__", $row['shortcode']);
					$shortcode = $expl[1];
				}
				$result['rows'][$i]['shortcode'] 	= $shortcode;
				$result['rows'][$i]['service'] 	= $row['service'];
				$result['rows'][$i]['status'] 	= ($row['status']==1)?'Active':'Inactive';
				
				$i++;
			}
			$result['total']=$i+1;
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	
	
	public function saveBlocker($params){
           
		$result = $this->insertBlocker($params);
		return $result;
	}
	
	
	private function insertBlocker($params){
		//print_r($params);
		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('blocker');
		$this->dbAds->where('country_id',$params['country_id']);
		$this->dbAds->where('operator_id',$params['operator_id']);
		$this->dbAds->where('shortcode_id',$params['shortcode']);
		$this->dbAds->where('service',$params['service']);
		$query = $this->dbAds->get();
		$result = array();
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Duplicat Row can not add"; 
				$result['duplicat_data'] = true;
				return $result;
			}
		}


		$update = array(
						'country_id' 		=> $this->dbAds->escape_str($params['country_id']),
						'operator_id' 		=> $this->dbAds->escape_str($params['operator_id']),
						'shortcode_id' 	=> $this->dbAds->escape_str($params['shortcode']),
						'status' 	=> $this->dbAds->escape_str($params['status']),
						'service' => $this->dbAds->escape_str($params['service']),
					   );
		$this->dbAds->insert('blocker', $update);
		
		
		if($this->dbAds->affected_rows()){
			//$blockerId = $this->dbAds->insert_id();
			
			$result['success'] = true;
			//$result['id']		= $blockerId;
		} 
		else {
			$result['success'] = false;
		}
		
		return $result;
	}
	
	public function deleteBlocker($params){
		
		$result = array();
		$result['rows'] = $this->activatDeletRedisData($params);

		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->delete('blocker');
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	public function changeStatus($params){

		$update = array('status' 	=> $this->dbAds->escape_str($params['new_status']));
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('blocker', $update);
		$result = array();
		$result['rows'] = $this->activatDeletRedisData($params);
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function fetchOperators($country_id)
	{

	
		$this->dbAds->select('id, name');
		$this->dbAds->from('operators');
		$this->dbAds->where('id_country', $country_id);
		$query = $this->dbAds->get();
		$result = array();
		$i = 0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] = $row['id'];
				$result['rows'][$i]['name']= $row['name'];
				$i++;
				
			}
		} else {
			//$result['count'] = false;
		}
		return $result;
	}
	public function fetchServices($shortcode_id)
	{
		$shortcode = explode("__", $shortcode_id);
		$this->dbAds->distinct();
		$this->dbAds->select('ps.keyword as keyword');
		$this->dbAds->join('shortcodes sc', 'sc.code = ps.sid');
		$this->dbAds->from('partners_services ps');
		$this->dbAds->where('sc.id', $shortcode[0]);
		$query = $this->dbAds->get();
		$result = array();
		$i = 0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i] = $row['keyword'];
				$i++;
				
			}
		} else {
			//$result['count'] = false;
		}
		return $result;
	}
	public function fetchAllShortcode()
	{
		
		$this->dbAds->select('code as shortcode,id as id');
		$this->dbAds->from('shortcodes');
		$query = $this->dbAds->get();
		$result = array();
		if($query->num_rows() != 0)
		{
			$i=0;
			foreach($query->result_array() as $rows)
			{
				$result[$i]['shortcode'] = $rows['shortcode'];
				$result[$i]['id'] = $rows['id'];
				$i++;

			}

		}
		
		return $result;

	}
	public function fetchAllKeywords()
	{
	$this->dbAds->select('name');
		$this->dbAds->from('keyword_groups');
		$query = $this->dbAds->get();
		$result = array();
		if($query->num_rows() != 0)
		{
			$i=0;
			foreach($query->result_array() as $rows)
			{
				$result[$i] = $rows['name'];
				$i++;

			}

		}
		
		return $result;
	}
	public function activatDeletRedisData($params)
	{
		
			
			$this->dbAds->select('country_id, operator_id, shortcode_id, service, status');
			$this->dbAds->from('blocker');
			$this->dbAds->where('id',$this->dbAds->escape_str($params['id']));
			$query = $this->dbAds->get();
			$result = array();
			if($query->num_rows() != 0)
			{
				foreach($query->result_array() as $row) 
				{

					$result['country_id'] = $row['country_id'];
					$result['operator_id'] = $row['operator_id'];
					$result['shortcode_id'] = $row['shortcode_id'];
					$result['service'] = str_replace(' ', '', $row['service']);
					$result['status'] = $row['status'];
				}

		}
		return $result;
		
	}
	public function blockerBulkUpload($temp_file_path){
		error_reporting(E_ALL);
		$file = $temp_file_path;

		$this->load->library('excel');
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		foreach ($cell_collection as $cell) {
		    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    if ($row == 1) {
		        $header[$row][$column] = $data_value;
		    } else {
		        $arr_data[$row][$column] = $data_value;
		    }
		}
		$data['values'] = $arr_data;
		$i = 0;
		foreach($arr_data as $row)
		{

			$data_row[$i]['country_id'] = $row['A'];
			$data_row[$i]['operator_id'] = $row['B'];
			$data_row[$i]['shortcode_id'] = $row['C'];
			$data_row[$i]['service'] = $row['D'];
			$data_row[$i]['status'] = $row['E'];
			$i++;
		}
		
		return $data_row;


	}

	
}
?>
