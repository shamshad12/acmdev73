<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_filterlist extends MY_Model {
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
	}  
	
	public function loadFilterlist($params){ 
            //die('ff');
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count '); 
		$this->dbAds->from('filter_list c'); 
		$this->dbAds->join('countries co', 'c.id_country = co.id');
                $this->dbAds->join('operators so', 'c.id_telco = so.id');
                
		if(!empty($params['search']))
			$this->dbAds->where("c.filter_type LIKE '%".$params['search']."%' OR c.msisdn LIKE '%".$params['search']."%'");

		if(!empty($params['country']))
			$this->dbAds->where("co.id", $params['country']);
		
		if(!empty($params['operator']))
			$this->dbAds->where("so.id", $params['operator']);

		//print_r($this->dbAds);exit(); 
		 $query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count']; 
				
		$this->dbAds->select('f.id, f.filter_type,co.name AS co_name,so.name AS so_name,f.msisdn, f.status,f.id_country,f.id_telco,f.status');
		$this->dbAds->from('filter_list f');
		$this->dbAds->join('countries co', 'f.id_country = co.id');
        $this->dbAds->join('operators so', 'f.id_telco = so.id');
		if(!empty($params['search']))
			$this->dbAds->where("f.filter_type LIKE '%".$params['search']."%' OR f.msisdn LIKE '%".$params['search']."%'");

		if(!empty($params['country']))
			$this->dbAds->where("co.id", $params['country']);
		
		if(!empty($params['operator']))
			$this->dbAds->where("so.id", $params['operator']);

		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['filter_type'] 	= $row['filter_type'];
				$result['rows'][$i]['co_name'] 	= $row['co_name'];
                                 $result['rows'][$i]['so_name'] 	= $row['so_name'];
				$result['rows'][$i]['msisdn'] 	= $row['msisdn'];
				$result['rows'][$i]['id_country'] 	= $row['id_country'];
				$result['rows'][$i]['id_telco'] 	= $row['id_telco'];
				$result['rows'][$i]['status'] 	= $row['status'];
				$i++; 
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function loadFilterlistSelect(){		
		$result = array();
				
		$this->dbAds->select('id, filter_type, id_country,id_operator, msisdn');
		$this->dbAds->from('filter_list');
		$this->dbAds->where('status', '1');
               
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id'] 		= $row['id'];
				$result['rows'][$i]['filter_type'] 	= $row['filter_type'];
				$result['rows'][$i]['id_country'] 	= $row['id_country'];
                                $result['rows'][$i]['id_telco'] 	= $row['id_telco'];
				$result['rows'][$i]['msisdn'] 	= $row['msisdn'];
				$i++;
			}
                        
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function saveFilterlist($params){
		$this->dbAds->select('id');
		$this->dbAds->from('filter_list');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$data = $this->dbAds->get();
		if($data->num_rows() != 0){
			$result = $this->updateFilterlist($params);	
		} else {
			$result = $this->insertFilterlist($params);
		}
		return $result;
	}
	
       public function getcountrycode($id){ 
		$this->dbAds->select('prefix');
		$this->dbAds->from('countries');
		$this->dbAds->where('id', $this->dbAds->escape_str($id));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['prefix'] = $row['prefix'];
				
				$i++;
			}
		} else {
			$result['count'] = false;
		} 
		return $result;
	}
	private function updateFilterlist($params){
		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'filter_type' 		=> $this->dbAds->escape_str($params['filter_type']),
						'id_country' => $this->dbAds->escape_str($params['id_country']),
                                                'id_operator' => $this->dbAds->escape_str($params['id_operator']),
						'msisdn' 		=> $this->dbAds->escape_str($params['msisdn']),
						'update_user' => $this->dbAds->escape_str($this->profile['id']),
						'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('filter_list', $update);
		$result = array();
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	} 
	
	private function insertFilterlist($params){   

		$this->dbAds->select('COUNT(1) as exist_data');
		$this->dbAds->from('filter_list');
		
		$this->dbAds->where('msisdn',$params['msisdn']);
		$query = $this->dbAds->get();
		$result = array();
		foreach($query->result_array() as $row)
		{
			if($row['exist_data'] !=0)
			{
				$result['errors_message'] = "Msisdn Already Configured"; 
				$result['duplicat_data'] = true;
				return $result;
			}
		}
                
               $countyprefix=$this->getcountrycode($params['id_country']); 
             $lencountyprefix = strlen($countyprefix['prefix']);
        if(substr($params['msisdn'],0,1) == '0')
		$msisdn = $countyprefix['prefix'].ltrim($params['msisdn'], '0');
		elseif(substr($params['msisdn'],0,$lencountyprefix) != $countyprefix['prefix'])
		$msisdn = $countyprefix['prefix'].$params['msisdn']; 
         elseif(substr($params['msisdn'],0,$lencountyprefix) == $countyprefix['prefix'])
		$msisdn = $params['msisdn'];               
                
		#SettingUp the timezone

		$input_by = $this->session->userdata('id_user');
		$input_time = strtotime(date('Y-m-d H:i:s'));
		$update = array(
						'filter_type' 		=> $params['filter_type'], 
						'id_country' => $params['id_country'],
                                                'id_telco' => $params['id_telco'],
						'msisdn' 		=> $msisdn,
						'entry_user' => $this->dbAds->escape_str($this->profile['id']),
						'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')) 
					   );
		$this->dbAds->insert('filter_list', $update); 
		$result = array();
		if($this->dbAds->affected_rows()){
                         $result['success'] = true;
                         $result['msisdn'] = $msisdn;  
		} else {
			$result['success'] = false;
                        $result['msisdn'] = $msisdn;  
		}
		return $result;
	}  
	public function deleteFilterlistNew($param)
	{
		$this->dbAds->where('id',$param);
		$this->dbAds->delete('filter_list');
		$result = array();
		if($this->dbAds->affected_rows()){
                
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;


	}
	public function deleteFilterlist($params){
		
		$update = array(
						'status' 		=> $this->dbAds->escape_str($params['status'])
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update('filter_list', $update);
		
		$result = array();
		if($this->dbAds->affected_rows()){
                
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
	}
	
	public function getFilterlistData($params){ 
		$this->dbAds->select('id, filter_type, id_country,id_operator, msisdn');
		$this->dbAds->from('filter_list');
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['id'] = $row['id'];
				$result['filter_type'] = $row['filter_type'];
				$result['id_country'] = $row['id_country'];
                                $result['id_telco'] = $row['id_telco'];
				$result['msisdn'] = $row['msisdn'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		return $result;
	}
        
        
        public function loadOperator($params){
  $this->dbAds->select('id, name');
  $this->dbAds->from('operators');
  $this->dbAds->where('id_country', $this->dbAds->escape_str($params['id']));
  $this->dbAds->where('status', 1);
  $query = $this->dbAds->get();
  //echo $this->dbAds->last_query();die('kl');
  $result = array();
  $i=0;
  if($query->num_rows() != 0){
   $result['success'] = true;
   foreach($query->result_array() as $row) {
    $result[$i]['id'] = $row['id'];
    $result[$i]['name'] = $row['name'];
    $i++;
   }
   
  }
  else {
   $result['success'] = false;
  }

  return $result;
 }
 public function filterBulkUpload($temp_file_path){
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
			$data_row[$i]['filter_type'] = $row['B'];
			$data_row[$i]['msisdn'] = $row['C'];
			$data_row[$i]['operator_id'] = $row['D'];
			$data_row[$i]['status'] = $row['E'];
			$i++;
		}

		return $data_row;


	}
}
?>
  
