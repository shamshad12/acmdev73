<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_publisher_traffic_reporting extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadPublisher_Traffic_Reporting($params){	
		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d');
			
		if(empty($params['datefrom']))
			$params['datefrom'] = date('Y-m-d');
			
		if(isset($params['datefrom']) && isset($params['dateto'])){
			$tableTraffic = 'rpt_publisher_traffic_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'rpt_publisher_traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
			
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
			$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('t.id, t.transaction_id_publisher, t.server_time, t.ads_publisher_time, t.name_country');
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher, t.is_send');
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
			$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		
		$this->dbAds->order_by('t.id', 'DESC');
		
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id']				= $row['id'];
				$result['rows'][$i]['transaction_id_publisher']	= $row['transaction_id_publisher'];
				$result['rows'][$i]['server_time']		= $row['server_time'];
				$result['rows'][$i]['ads_publisher_time'] 	= $row['ads_publisher_time'];
				$result['rows'][$i]['name_country'] 	= $row['name_country'];
				$result['rows'][$i]['name_campaign_media'] 	= $row['name_campaign_media'];
				$result['rows'][$i]['name_operator'] 	= $row['name_operator'];
				$result['rows'][$i]['name_partner'] 	= $row['name_partner'];
				$result['rows'][$i]['name_campaign'] 	= $row['name_campaign'];
				$result['rows'][$i]['name_ads_publisher'] = $row['name_ads_publisher'];
				$result['rows'][$i]['is_send'] 			= ($row['is_send']==1)?'Sent':'Pending';
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
}
?>
