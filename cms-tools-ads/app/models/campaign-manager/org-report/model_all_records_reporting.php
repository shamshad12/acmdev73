<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_all_records_reporting extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadAllRecords_Reporting($params){	
		
		if(!empty($params['dateinternal'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
		}	
		else if(!empty($params['datepublisher'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
			
		$result = array();
		
		$this->dbAds->select(' COUNT(1) AS count ');
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
			
		//$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		if(!empty($params['dateinternal'])){
			$this->dbAds->where("DATE(t.server_time) = '".$params['dateinternal']."'");
		}
		else if(!empty($params['datepublisher'])){
			$this->dbAds->where("DATE(t.ads_publisher_time) = '".$params['datepublisher']."'");
		}
		else{
			$this->dbAds->where("DATE(t.server_time) = '".date('Y-m-d')."'");
		}
		
		$query = $this->dbAds->get();
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select('t.id, t.server_time, t.operator_time, t.partner_time, t.ads_publisher_time, t.name_country');
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher');
		$this->dbAds->select('t.msisdn_detection, t.msisdn_charging, t.shortcode, t.sid, t.keyword, t.content, t.referer');
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
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
			
		//$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		if(!empty($params['dateinternal'])){
			$this->dbAds->where("DATE(t.server_time) = '".$params['dateinternal']."'");
		}
		else if(!empty($params['datepublisher'])){
			$this->dbAds->where("DATE(t.ads_publisher_time) = '".$params['datepublisher']."'");
		}
		else{
			$this->dbAds->where("DATE(t.server_time) = '".date('Y-m-d')."'");
		}
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		
		$this->dbAds->order_by('t.id', 'DESC');
		
		$query = $this->dbAds->get();
		//print $this->dbAds->last_query();
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
				$result['rows'][$i]['referer'] 			= $row['referer'];
				$i++;
			}
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}
	
	public function generate($params){
		if(!empty($params['dateinternal'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
		}	
		else if(!empty($params['datepublisher'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		
		$fieldList = "OPERATOR,MSISDN,CAMPAIGN_TITLE,REFERER,TRANSACTION_ID,ADS_PUBLISHER,SHORTCODE,KEYWORD,COUNTRY,INTERNAL_DATE_TIME,PUBLISHER_DATETIME";
		
		$this->dbAds->select('t.name_operator, t.msisdn_detection, t.name_campaign, t.referer, t.id, t.name_ads_publisher, t.shortcode, t.keyword, t.name_country, t.server_time, t.ads_publisher_time');
		
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
			
		if(!empty($params['dateinternal'])){
			$this->dbAds->where("DATE(t.server_time) = '".$params['dateinternal']."'");
		} else if(!empty($params['datepublisher'])){
			$this->dbAds->where("DATE(t.ads_publisher_time) = '".$params['datepublisher']."'");
		} else{
			$this->dbAds->where("DATE(t.server_time) = '".date('Y-m-d')."'");
		}
		
		$this->dbAds->order_by('t.id', 'DESC');
		
		$query = $this->dbAds->get();
	  
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
