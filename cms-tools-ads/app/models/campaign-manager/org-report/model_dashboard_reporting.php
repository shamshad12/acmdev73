<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_dashboard_reporting extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadDashboard_Reporting($params){	
		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d');
		if(empty($params['date']))
			$params['date'] = date('Y-m-d');
			
		if(empty($params['datefrom']))
			$params['datefrom'] = date('Y-m-d');
			
		if(isset($params['datefrom']) && isset($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
               
                
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
			
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from($tableTraffic.' t');
		//if(!empty($params['operator-list']))
			//$this->dbAds->where('t.id_operator', $params['operator-list']);
		//if(!empty($params['partner-list']))
			//$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['campaign-list']))
			$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		#$this->dbAds->where("DATE(t.server_time) = '".$params['date']."'");
		
		$this->dbAds->group_by(array('t.name_operator','t.name_campaign','t.name_ads_publisher','t.shortcode','t.keyword'));
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('t.id, t.server_time, t.operator_time, t.partner_time, t.ads_publisher_time, t.name_country');
		$this->dbAds->select('count(1) as all_records');
		$this->dbAds->select('sum(case when is_publisher_send = 1 then 1 else 0 end) publisher_records');
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher');
		$this->dbAds->select('t.msisdn_detection, t.msisdn_charging, t.shortcode, t.sid, t.keyword, t.content');
		$this->dbAds->from($tableTraffic.' t');
		//if(!empty($params['operator-list']))
			//$this->dbAds->where('t.id_operator', $params['operator-list']);
		//if(!empty($params['partner-list']))
			//$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['campaign-list']))
			$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		#$this->dbAds->where("DATE(t.server_time) = '".$params['date']."'");
		
		$this->dbAds->group_by(array('t.name_operator','t.name_campaign','t.name_ads_publisher','t.shortcode','t.keyword'));
		
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		
		$this->dbAds->order_by('t.id', 'DESC');
		
		
		$query = $this->dbAds->get();
		
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id']				= $row['id'];
				$result['rows'][$i]['server_time']		= $row['server_time'];
				$result['rows'][$i]['operator_time'] 	= $row['operator_time'];
				$result['rows'][$i]['partner_time'] 	= $row['partner_time'];
				$result['rows'][$i]['ads_publisher_time'] 	= $row['ads_publisher_time'];
				$result['rows'][$i]['name_country'] 	= $row['name_country'];
				$result['rows'][$i]['name_campaign_media'] 	= $row['name_campaign_media'];
				$result['rows'][$i]['name_operator'] 	= $row['name_operator'];
				$result['rows'][$i]['name_partner'] 	= $row['name_partner'];
				$result['rows'][$i]['name_campaign'] 	= $row['name_campaign'];
				$result['rows'][$i]['name_ads_publisher'] 	= $row['name_ads_publisher'];
				$result['rows'][$i]['msisdn_detection'] 	= !empty($row['msisdn_detection'])?$row['msisdn_detection']:$row['msisdn_charging'];
				$result['rows'][$i]['shortcode'] 		= $row['shortcode'];
				$result['rows'][$i]['sid'] 				= $row['sid'];
				$result['rows'][$i]['keyword'] 			= $row['keyword'];
				$result['rows'][$i]['content'] 			= $row['content'];
				$result['rows'][$i]['all_records'] 			= $row['all_records'];
				$result['rows'][$i]['publisher_records'] 			= $row['publisher_records'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function generate($params){
		if(empty($params['date']))
			$params['date'] = date('Y-m-d');
	
		if(!empty($params['date'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['date']));
		}	
		else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		
		$fieldList = "OPERATOR,CAMPAIGN_TITLE,ADS_PUBLISHER,SHORTCODE,KEYWORD,ALL_RECORDS,PUBLISHER_RECORDS";
		
		$this->dbAds->select('t.name_operator, t.name_campaign, t.name_ads_publisher, t.shortcode, t.keyword, count(1) as all_records, sum(case when is_publisher_send = 1 then 1 else 0 end) publisher_records');
		
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['campaign-list']))
			$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%'");
			
		
		$this->dbAds->where("DATE(t.server_time) = '".$params['date']."'");
		
		$this->dbAds->group_by(array('t.name_operator','t.name_campaign','t.name_ads_publisher','t.shortcode','t.keyword'));
		
		$this->dbAds->order_by('t.id', 'DESC');
		
		
		
		$query = $this->dbAds->get();
		//print $this->dbAds->last_query();
		$result = array();
		$result[0] = $fieldList;
		$i=1;
   	    if($query->num_rows() != 0){
		    foreach($query->result_array() as $row) {
				$result[$i] = implode(',', $row);
				$i++;
			}
	    }
	  return $result;
	}
}
?>
