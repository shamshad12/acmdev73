<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_traffic_reporting extends MY_Model {

	private $tablePostFix;

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

		$this->tablePostFix = '_'.date('mY');
	}

	public function loadTraffic_Reporting($params){
		if(empty($params['dateto']))
		$params['dateto'] = date('Y-m-d');
			
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
		if(!empty($params['operator-list']))
		$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
		$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['filter-list']))
		$this->dbAds->where('t.status_campaign', $params['filter-list']);
		if(!empty($params['search']))
		$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");

		$query = $this->dbAds->get();

		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];

		$this->dbAds->select('t.id, t.server_time, t.operator_time, t.partner_time, t.ads_publisher_time, t.name_country, t.transaction_id_billing');
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher');
		$this->dbAds->select('t.msisdn_detection, t.msisdn_charging, t.shortcode, t.sid, t.keyword, t.content, t.status_campaign');
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['operator-list']))
		$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
		$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['filter-list']))
		$this->dbAds->where('t.status_campaign', $params['filter-list']);
		if(!empty($params['search']))
		$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%'");
			
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);

		$this->dbAds->order_by('t.id', 'DESC');

		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('hi');
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
				// $status = ($row['status_campaign']==1)?'View':'Lead';
				if($row['status_campaign']==1)
				$status = 'View';
				elseif($row['status_campaign']==5 || $row['status_campaign']==6)
				$status = 'Reject';
				else
				$status = 'Lead';
				//$result['rows'][$i]['status'] 			= empty($row['transaction_id_billing'])?$status:'Sales';
				if(empty($row['transaction_id_billing']))
				$result['rows'][$i]['status'] = $status;
				elseif($row['status_campaign']==5 || $row['status_campaign']==6)
				$result['rows'][$i]['status'] = 'Rejected Sales';
				else
				$result['rows'][$i]['status'] = 'Sales';
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function generate($params){

		if(!empty($params['datefrom'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datefrom']));
		}
		else if(!empty($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else
		$tableTraffic = 'traffic'.$this->tablePostFix;
		 
		if(!$this->dbAds->table_exists($tableTraffic))
		$this->createTable($this->dbAds, 'traffic_', $tableTraffic);

		$fieldList = "Server Time,TransactionID,Country,TransactionID Billing,Operator,Partner,Ads Vendors,Media,Campaign,MSISDN,SDC,SID,Keyword,Status";

		$this->dbAds->select('t.server_time, t.id, t.name_country, t.transaction_id_billing');
		$this->dbAds->select('t.name_operator, t.name_partner, t.name_ads_publisher, t.name_campaign_media, t.name_campaign');
		$this->dbAds->select('t.msisdn_detection, t.shortcode, t.sid, t.keyword, t.status_campaign');
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['operator-list']))
		$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
		$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['filter-list']))
		$this->dbAds->where('t.status_campaign', $params['filter-list']);
		if(!empty($params['search']))
		$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%'");
		 
		$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");

		$this->dbAds->order_by('t.id', 'DESC');

		$query = $this->dbAds->get();
		$result = array();
		$result[0] = $fieldList;
		$i=1;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				if($row['status_campaign']==1)
				{
					$status = 'View';
				}
				elseif($row['status_campaign']==5 || $row['status_campaign']==6)
				{
					$status = 'Reject';
				}
				else
				{
					$status = 'Lead';
				}

				if(empty($row['transaction_id_billing']))
				{
					$row['status_campaign'] = $status;
				}
				elseif($row['status_campaign']==5 || $row['status_campaign']==6)
				{
					$row['status_campaign'] = 'Rejected Sales';
				}
				elseif($row['transaction_id_billing'])
				{
					$row['status_campaign'] = 'Sales';
				}

				$row['id'] = '="'.$row['id'].'"';
				$row['transaction_id_billing'] = '="'.$row['transaction_id_billing'].'"';
				$result[$i] = implode(',', $row);
				$i++;
			}
		}

		return $result;
	}

	public function loadTraffic_detail($params)
	{
		$tableTraffic = '_' . substr($params['id'], 4, 2) . substr($params['id'], 0, 4);
		$this->dbAds->select('t.transaction_id_publisher, t.name_campaign, t.campaign_code, t.http_host, t.request_uri, t.msisdn_detection, t.referer');
		$this->dbAds->select('t.name_partner, t.name_ads_publisher,t.name_operator,t.shortcode,t.keyword,t.name_country,t.is_publisher_send,ap.url_confirm,t.affiliate_params,t.pixel_response');
		$this->dbAds->from('traffic'.$tableTraffic.' t');
		$this->dbAds->join('ads_publishers ap', 't.id_ads_publisher = ap.id');
		$this->dbAds->where('t.id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();

		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['transaction_id_publisher'] = $row['transaction_id_publisher'];
				$result['name_campaign'] = $row['name_campaign'];
				$result['campaign_code'] = $row['campaign_code'];
				$result['http_host'] = $row['http_host'];
				$result['request_uri'] = $row['request_uri'];
				$result['msisdn_detection'] = $row['msisdn_detection'];
				$result['referer'] = $row['referer'];
				$result['name_partner'] = $row['name_partner'];
				#$result['code_acm'] = $row['code_acm'];
				$result['name_ads_publisher'] = $row['name_ads_publisher'];
				$result['name_operator'] = $row['name_operator'];
				$result['shortcode'] = $row['shortcode'];
				$result['keyword'] = $row['keyword'];
				$result['name_country'] = $row['name_country'];
				$result['affiliate_params'] = json_decode($row['affiliate_params'], true);
				$result['is_publisher_send']= ($row['is_publisher_send']==1)?'Sent':'Not Sent';
				if($row['transaction_id_publisher'])
				{
					$row['url_confirm'] = str_replace('{txn_id}', $result['transaction_id_publisher'], $row['url_confirm']);
					$row['url_confirm'] = str_replace('{pub_id}', $result['affiliate_params']['pub_id'], $row['url_confirm']);
				}
				$result['url_confirm']= $row['url_confirm'];
				$result['pixel_response']= $row['pixel_response'];

				$i++;
			}
		}
			
		return $result;
	}
}
?>
