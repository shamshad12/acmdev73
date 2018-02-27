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
		$default_date_flag=0;
		if(!empty($params['search']))
                {
			$this->dbAds->where("t.user_name LIKE '%".$params['search']."%' OR t.action_type LIKE '%".$params['search']."%'");
                        $default_date_flag=1;
                }
		
		if((isset($params['log_type']) && trim($params['log_type'])!=''))
                {
                    if(trim($params['log_type'])==1)
                    {
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) <>'' ");
                    }elseif(trim($params['log_type'])==2){
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) ='' ");
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
		$this->dbAds->where("t.action_type NOT LIKE '%C_images%' AND t.action_type NOT LIKE '%C_style.css%' AND t.action_type NOT LIKE '%C_exittraffic-v2.js%' AND t.action_type NOT LIKE '%C_scripts%'");
		if(!empty($params['search']))
			$this->dbAds->where("t.user_name LIKE '%".$params['search']."%' OR t.action_type LIKE '%".$params['search']."%' OR t.user_info LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.create_ts) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
                if(isset($params['log_type']) && trim($params['log_type'])!='')
                {
                     if(trim($params['log_type'])==1)
                    {
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) <>'' ");
                    }elseif(trim($params['log_type'])==2){
                        $this->dbAds->where("trim(coalesce(t.user_name, '')) ='' ");
                    }
                }
                
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);

		$this->dbAds->order_by('t.id', 'DESC');
		$query = $this->dbAds->get();
		
		//echo $this->dbAds->last_query();die('hi');
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id']				= $row['id'];
				$result['rows'][$i]['user_name']		= $row['user_name'];
				$result['rows'][$i]['action_type'] 	= $row['action_type'];
				$result['rows'][$i]['user_info'] 	= $row['user_info'];
				$result['rows'][$i]['create_ts'] 	= $row['create_ts'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	} 

	

}
?>
