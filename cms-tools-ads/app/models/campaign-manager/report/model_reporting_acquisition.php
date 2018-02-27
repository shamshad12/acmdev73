<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_reporting_acquisition extends MY_Model {

	private $tablePostFix;

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

		$this->tablePostFix = '_'.date('mY');
	}

	public function loadReporting_Acquisition($params){
		$result = array();
		$user = $this->session->userdata('profile');
		if($user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			$partner_permissions=json_decode($partner_permissions);
			if(empty($partner_permissions))
			return $result['count'] = false;
		}
		if(empty($params['dateto']))
		$params['dateto'] = date('Y-m-d');
		if(empty($params['datefrom']))
		$params['datefrom'] = date('Y-m-d');
		if(isset($params['datefrom']) && isset($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else
		$tableTraffic = 'traffic'.$this->tablePostFix;
	$qpartition='';
	// new changes
	if(intval(date('m', strtotime($params['dateto'])))<6)
	{
	$searchBy='t.server_time';	
	}
	else 
	{	
	$searchBy='t.ads_publisher_time';
	/*$ispartition=$this->getPartition($params['datefrom'],$params['dateto']);
	if($ispartition)
		$qpartition="PARTITION($ispartition)";*/
	}
	//print $qpartition; die;
	//
		$this->dbAds->_protect_identifiers=false;
		if(!$this->dbAds->table_exists($tableTraffic))
		$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		$this->dbAds->select('COUNT(1) AS count ');
		$this->dbAds->from($tableTraffic." $qpartition as t");
		if($user['tipe_user_id'] == 11)
		{
			if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}

			if(!empty($partner_permissions->shortcode))
			{
				$this->dbAds->where_in('t.shortcode', $partner_permissions->shortcode);
			}
			if(!empty($partner_permissions->keyword))
			{
				$this->dbAds->where_in('t.keyword', $partner_permissions->keyword);
			}
		}

		if(!empty($params['operator-list']))
		$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
		$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if($params['is_publisher_send'] != '*')
		$this->dbAds->where('t.is_publisher_send', $params['is_publisher_send']);
		if(!empty($params['search']))
		{
			$this->dbAds->where('(t.msisdn_detection="'. $params['search'].'" OR t.id="'.$params['search'].'" OR t.name_campaign="'.$params['search'].'")');
			//$this->dbAds->or_where('t.id', $params['search']);
			//$this->dbAds->or_where('t.name_campaign', $params['search']);
		}
		//$this->dbAds->where("(t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%')");
		// $this->dbAds->where("date_format(substring(transaction_id_billing,1,8),'%Y-%m-%d') BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		$this->dbAds->where("t.status_campaign", "2");
		$this->dbAds->where("t.transaction_id_billing IS NOT NULL");
		$this->dbAds->where("t.delivery_status !=",'REJECTED');
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query(); die;date_format(substring('201509181357016013660023083884135',1,14),'%Y-%m-%d %H:%i:%s')
		$result['total'] = 0;
		foreach($query->result_array() as $row)
		$result['total'] = $row['count'];
		$this->dbAds->select("t.id,t.transaction_id_publisher, t.operator_time as server_time ,t.charging_time, t.operator_time, t.partner_time, t.ads_publisher_time, t.name_country");
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher');
		$this->dbAds->select('t.msisdn_detection, t.msisdn_charging, t.shortcode, t.sid, t.keyword, t.content, t.status_campaign, t.is_publisher_send');
		//$this->dbAds->from($tableTraffic.' t');
		$this->dbAds->from($tableTraffic." $qpartition t");
		if($user['tipe_user_id'] == 11)
		{
			if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}

			if(!empty($partner_permissions->shortcode))
			{
				$this->dbAds->where_in('t.shortcode', $partner_permissions->shortcode);
			}
			if(!empty($partner_permissions->keyword))
			{
				$this->dbAds->where_in('t.keyword', $partner_permissions->keyword);
			}
		}

		if(!empty($params['operator-list']))
		$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
		$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if($params['is_publisher_send'] != '*')
		$this->dbAds->where('t.is_publisher_send', $params['is_publisher_send']);
		if(!empty($params['search']))
		{
			$this->dbAds->where('(t.msisdn_detection="'. $params['search'].'" OR t.id="'.$params['search'].'" OR t.name_campaign="'.$params['search'].'")');
			//$this->dbAds->or_where('t.id', $params['search']);
			//$this->dbAds->or_where('t.name_campaign', $params['search']);
		}	
	//$this->dbAds->where("(t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%')");
		//$this->dbAds->where("date_format(t.operator_time,'%Y-%m-%d') BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		$this->dbAds->where("t.status_campaign", "2");
		$this->dbAds->where("t.transaction_id_billing IS NOT NULL");
		$this->dbAds->where("t.delivery_status !=",'REJECTED');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		//$this->dbAds->order_by('t.id', 'DESC');
		$query = $this->dbAds->get();
		//print $this->dbAds->last_query(); die;
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id']				= $row['id'];
				$result['rows'][$i]['pub_trans_id']		= $row['transaction_id_publisher'];
				$result['rows'][$i]['server_time']		= $row['server_time'];
				$result['rows'][$i]['charging_time']		= $row['charging_time'];
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
				$result['rows'][$i]['is_publisher_send']= ($row['is_publisher_send']==1)?'Sent':'Not Sent';
				$i++;
			}
		} else {
			$result['count'] = false;
		}

		return $result;
	}

	public function generate($params){
	$user = $this->session->userdata('profile');
	$qpartition='';
	// new changes
	if(intval(date('m', strtotime($params['dateto'])))<6)
	{
	$searchBy='t.server_time';	
	}
	else 
	{	
	$searchBy='t.ads_publisher_time';
	/*$ispartition=$this->getPartition($params['datefrom'],$params['dateto']);
	if($ispartition)
		$qpartition='PARTITION('.$ispartition.')';*/
	}
	//print $qpartition; die;
	//
		$this->dbAds->_protect_identifiers=false;
		if($user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			if(empty($partner_permissions))
			return $result['count'] = false;
		}

		if(!empty($params['datefrom'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datefrom']));
		}
		else if(!empty($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else
		$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
		$this->createTable($this->dbAds, 'traffic_', $tableTraffic);

		$fieldList = "Server Time,Charging Time,ACM TransactionID,Publisher TransactionID,Country,Operator,Partner,Ads Vendors,Media,Campaign,MSISDN,SDC,SID,Keyword,Send?";

		$this->dbAds->select('t.operator_time,t.charging_time, t.id, t.transaction_id_publisher, t.name_country');
		$this->dbAds->select('t.name_operator, t.name_partner, t.name_ads_publisher, t.name_campaign_media, t.name_campaign');
		$this->dbAds->select('t.msisdn_detection, t.shortcode, t.sid, t.keyword, t.is_publisher_send');
		//$this->dbAds->from($tableTraffic.' t');
		$this->dbAds->from($tableTraffic."$partition t");
		if($user['tipe_user_id'] == 11)
		{
			if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}

			if(!empty($partner_permissions->shortcode))
			{
				$this->dbAds->where_in('t.shortcode', $partner_permissions->shortcode);
			}
			if(!empty($partner_permissions->keyword))
			{
				$this->dbAds->where_in('t.keyword', $partner_permissions->keyword);
			}
		}

		if(!empty($params['operator-list']))
		$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['partner-list']))
		$this->dbAds->where('t.id_partner', $params['partner-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if($params['is_publisher_send'] != '*')
		$this->dbAds->where('t.is_publisher_send', $params['is_publisher_send']);
		if(!empty($params['search']))
		{
			$this->dbAds->where('t.msisdn_detection', $params['search']);
			$this->dbAds->or_where('t.id', $params['search']);
			$this->dbAds->or_where('t.name_campaign', $params['search']);
		}
		//$this->dbAds->where("DATE(t.operator_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		$this->dbAds->where("status_campaign", "2");
		$this->dbAds->where("transaction_id_billing IS NOT NULL");
		$this->dbAds->where("delivery_status !=",'REJECTED');
		//$this->dbAds->order_by('t.id', 'DESC');
		$query = $this->dbAds->get();
		$result = array();
		$result[0] = $fieldList;
		$i=1;
		if($query->num_rows() != 0){
			foreach($query->result_array() as $row) {
				$row['is_publisher_send']= ($row['is_publisher_send']==1)?'Sent':'Not Sent';
				$row['id'] = '="'.$row['id'].'"';
				$row['transaction_id_publisher'] = '="'.$row['transaction_id_publisher'].'"';
				$result[$i] = implode(',', $row);
				$i++;
			}
		}
		return $result;
	}

}
?>
