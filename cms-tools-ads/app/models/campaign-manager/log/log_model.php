<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class log_model extends MY_Model {

	
	
	public function __construct () {
		parent::__construct();
		$this->loadDbAds();
		
	}
	
	public function loadlogList($params=array()){

		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d');
		$result = array();

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from('user_log t');
		$this->dbAds->where("t.action_type NOT LIKE '%C_images%' AND t.action_type NOT LIKE '%C_style.css%' AND t.action_type NOT LIKE '%C_exittraffic-v2.js%' AND t.action_type NOT LIKE '%C_scripts%' AND t.action_type NOT LIKE '%C_index%'");
		$default_date_flag=0;
		if(!empty($params['search']))
                {
			$this->dbAds->where("(t.user_name LIKE '%".$params['search']."%' OR t.action_type LIKE '%".$params['search']."%')");
                        $default_date_flag=1;
                }
		
		if((isset($params['log_type']) && trim($params['log_type'])!=''))
                {
                    if(trim($params['log_type'])==1)
                    {
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) <>'' ");
                    }elseif(trim($params['log_type'])==2){
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) ='' ");
                    }elseif(trim($params['log_type'])==3){
                        $this->dbAds->where("t.user_name", "Pixel Fire");
                    }
		
                    $default_date_flag=1;
                }
                if($default_date_flag==0){
                    if(empty($params['datefrom']))
			$params['datefrom'] = date('Y-m-d');
                    $this->dbAds->where("DATE(t.create_ts) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
                }else
                    if(!empty($params['datefrom']))
                        $this->dbAds->where("DATE(t.create_ts) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
                {
                    
                }
                
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('t.id, t.user_name, t.action_type,t.user_info, t.create_ts');
		$this->dbAds->from('user_log t');
		
		$this->dbAds->where("t.action_type NOT LIKE '%C_images%' AND t.action_type NOT LIKE '%C_style.css%' AND t.action_type NOT LIKE '%C_exittraffic-v2.js%' AND t.action_type NOT LIKE '%C_scripts%' AND t.action_type NOT LIKE '%C_index%'");
				
		if(!empty($params['search']))
			$this->dbAds->where("(t.user_name LIKE '%".$params['search']."%' OR t.action_type LIKE '%".$params['search']."%' OR t.user_info LIKE '%".$params['search']."%')");
			
		$this->dbAds->where("DATE(t.create_ts) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
                if(isset($params['log_type']) && trim($params['log_type'])!='')
                {
                     if(trim($params['log_type'])==1)
                    {
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) <>'' ");
                    }elseif(trim($params['log_type'])==2){
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) ='' ");
                    }elseif(trim($params['log_type'])==3){
                        $this->dbAds->where("t.user_name", "Pixel Fire");
                    }
                }
                
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);

		$this->dbAds->order_by('t.id', 'DESC');
		$query = $this->dbAds->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id']				= $row['id'];
				$result['rows'][$i]['user_name']		= $row['user_name'];
				$result['rows'][$i]['action_type'] 	= str_replace("C_detail.php","template form action not defined", $row['action_type']);
				$result['rows'][$i]['user_info'] 	= $row['user_info'];
				$result['rows'][$i]['create_ts'] 	= $row['create_ts'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}


	public function generate($params=array()){

		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d');
		$result = array();

		$fieldList = "User Name,Log Information,DateTime";
				
		$this->dbAds->select('t.id, t.user_name, t.action_type,t.user_info, t.create_ts');
		$this->dbAds->from('user_log t');
		
		if(!empty($params['search']))
			$this->dbAds->where("(t.user_name LIKE '%".$params['search']."%' OR t.action_type LIKE '%".$params['search']."%' OR t.user_info LIKE '%".$params['search']."%')");
			
		$this->dbAds->where("DATE(t.create_ts) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
                if(isset($params['log_type']) && trim($params['log_type'])!='')
                {
                     if(trim($params['log_type'])==1)
                    {
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) <>'' ");
                    }elseif(trim($params['log_type'])==2){
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) ='' ");
                    }elseif(trim($params['log_type'])==3){
                        $this->dbAds->where("t.user_name", "Pixel Fire");
                    }
                }
        
		$this->dbAds->order_by('t.id', 'DESC');
		$query = $this->dbAds->get();
		$result[0] = $fieldList;
		if($query->num_rows() != 0){
			$log_data = array();
			$i=1;
			foreach($query->result_array() as $row) {
				$log_data['user_name']	= $row['user_name']." [".$row['user_info']." ]";
				$log_data['log_info'] 	= $row['action_type'];
				$log_data['datetime'] 	= $row['create_ts'];
				$result[$i] = implode(',', $log_data);
				$i++;
			}
		}

		return $result;
	}

	
}
?>
