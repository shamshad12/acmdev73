<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_campaign_overview extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
		
		$this->user = $this->session->userdata('profile');
		$this->partner_permissions = "";
		if($this->user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			if(!empty($partner_permissions))
				$this->partner_permissions = json_decode($partner_permissions);
		}
	}
	
	public function loadcampaign_overview($params){	
		$result = array();
		$result['total'] = 0;
		$result['count'] = false;

		
		if(!empty($params['dateinternal'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
		}	
		else if(!empty($params['datepublisher'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
			
		$total = array();

		if($this->user['tipe_user_id'] == 11)
		{
			if(empty($this->partner_permissions))
				return $result;
			else
			{
				if(empty($this->partner_permissions->country))
					return $result;
			}
		}
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from($tableTraffic.' t');
		if($this->user['tipe_user_id'] == 11)
		{
			$this->dbAds->join('campaigns c', 'c.id = t.id_campaign');
			$this->dbAds->where('c.entry_user', $this->user['id']);
			$this->dbAds->where_in('c.id_country', $this->partner_permissions->country);
		}
		if(!empty($params['operators-list']))
			$this->dbAds->where('t.id_operator', $params['operators-list']);
		if(!empty($params['campaign-list']))
			$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['country-list']))
			$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['keywords-list']))
			$this->dbAds->where('t.keyword', $params['keywords-list']);
		if(!empty($params['search']))
			$this->dbAds->where("(t.name_campaign LIKE '%".$params['search']."%' OR t.name_operator LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.name_ads_publisher LIKE '%".$params['search']."%' OR t.name_country LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%')");
			
		if(!empty($params['dateinternal']) || !empty($params['datepublisher']) ){
			if(!empty($params['dateinternal']))
				$this->dbAds->where("DATE(`server_time`) >= '".$params['dateinternal']."'");
			if(!empty($params['datepublisher']))
				$this->dbAds->where("DATE(`server_time`) <= '".$params['datepublisher']."'");
		}
		else{
			$this->dbAds->where("DATE(t.server_time) = '".date('Y-m-d')."'");
		}
		$this->dbAds->group_by('t.id_campaign, t.keyword, t.shortcode, t.id_operator, t.id_ads_publisher, t.id_country');
		$query = $this->dbAds->get();
		$result['total'] = count($query->result_array());
		$this->dbAds->select("t.id_campaign, t.name_campaign, t.keyword, t.shortcode, t.name_operator, t.name_ads_publisher, t.server_time, SUM( IF( t.`status_campaign` =1, 1, 0 ) ) AS total_landing_views, SUM( IF( t.`status_campaign` =2, 1, 0 ) ) AS total_web_entry, SUM( IF( t.`transaction_id_billing` IS NOT NULL , 1, 0 ) ) AS total_subscriber",false);
		$this->dbAds->from($tableTraffic.' t');
		$this->dbAds->order_by('total_subscriber','DESC');
		if($this->user['tipe_user_id'] == 11)
		{
			$this->dbAds->join('campaigns c', 'c.id = t.id_campaign');
			$this->dbAds->where('c.entry_user', $this->user['id']);
			$this->dbAds->where_in('c.id_country', $this->partner_permissions->country);
		}		
		
		if(!empty($params['operators-list']))
			$this->dbAds->where('t.id_operator', $params['operators-list']);
		if(!empty($params['campaign-list']))
			$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['country-list']))
			$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['keywords-list']))
			$this->dbAds->where('t.keyword', $params['keywords-list']);
		if(!empty($params['dateinternal']) || !empty($params['datepublisher']) ){
			if(!empty($params['dateinternal']))
				$this->dbAds->where("DATE(t.`server_time`) >= '".$params['dateinternal']."'", null, false);
			if(!empty($params['datepublisher']))
				$this->dbAds->where("DATE(t.`server_time`) <= '".$params['datepublisher']."'", null, false);
		}
		else{
			$this->dbAds->where("DATE(t.`server_time`) = '".date('Y-m-d')."'", null, false);
		}
		if(!empty($params['search']))
			$this->dbAds->where("(t.name_campaign LIKE '%".$params['search']."%' OR t.name_operator LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.name_ads_publisher LIKE '%".$params['search']."%' OR t.name_country LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%')", null, false);
		$this->dbAds->group_by('t.id_campaign, t.keyword, t.shortcode, t.id_operator, t.id_ads_publisher, t.id_country');
		$this->dbAds->order_by('t.server_time','DESC');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id_campaign']				= $row['id_campaign'];
				$result['rows'][$i]['name_campaign']			= $row['name_campaign'];
				$result['rows'][$i]['keyword']					= $row['keyword'];
				$result['rows'][$i]['shortcode']				= $row['shortcode'];
				$result['rows'][$i]['name_operator']			= $row['name_operator'];
				$result['rows'][$i]['name_ads_publisher']		= $row['name_ads_publisher'];
				$result['rows'][$i]['server_time']				= $row['server_time'];
				$result['rows'][$i]['total_landing_views']		= $row['total_landing_views'];
				$result['rows'][$i]['total_web_entry']			= $row['total_web_entry'];
				$result['rows'][$i]['total_subscriber']			= $row['total_subscriber'];

				$subscriber_webentry				= ($row['total_subscriber']/$row['total_web_entry'])*100;
				$webentry_views						= ($row['total_web_entry']/$row['total_landing_views'])*100;
				$subscriber_views					= ($row['total_subscriber']/$row['total_landing_views'])*100;
				
				$result['rows'][$i]['subscriber_webentry']	= number_format($subscriber_webentry, 2);
				$result['rows'][$i]['webentry_views']	= number_format($webentry_views, 2);
				$result['rows'][$i]['subscriber_views']	= number_format($subscriber_views, 2);

				$total['rows']['sum_landing_views'] += $row['total_landing_views'];
				$total['rows']['sum_web_entry'] += $row['total_web_entry'];
				$total['rows']['sum_subscriber'] += $row['total_subscriber'];

				$total['rows']['per_subscriber_webentry'] += number_format($subscriber_webentry, 2);
				$total['rows']['per_webentry_views'] += number_format($webentry_views, 2);
				$total['rows']['per_subscriber_views'] += number_format($subscriber_views, 2);

				$i++;
			}
				$result['rows'][$i]['name_campaign']		= "Grand Total";
				$result['rows'][$i]['keyword']					= "";
				$result['rows'][$i]['shortcode']				= "";
				$result['rows'][$i]['name_operator']			= "";
				$result['rows'][$i]['name_ads_publisher']		= "";
				$result['rows'][$i]['server_time']				= "";
				$result['rows'][$i]['total_landing_views']		= "";
				$result['rows'][$i]['total_web_entry']			= "";
				$result['rows'][$i]['total_subscriber']			= "";

				$result['rows'][$i]['total_landing_views']	= $total['rows']['sum_landing_views'];
				$result['rows'][$i]['total_web_entry']		= $total['rows']['sum_web_entry'];
				$result['rows'][$i]['total_subscriber']	= $total['rows']['sum_subscriber'];

				$result['rows'][$i]['subscriber_webentry']	= number_format($total['rows']['per_subscriber_webentry']/$i,2).'%';
				$result['rows'][$i]['webentry_views']	= number_format($total['rows']['per_webentry_views']/$i,2).'%';
				$result['rows'][$i]['subscriber_views']	= number_format($total['rows']['per_subscriber_views']/$i,2).'%';
		} else {
			$result['count'] = false;
		}
		
		return $result;
	}

	public function getcampaign_hourlydetail($params){
		$tableTraffic = 'traffic'.$this->tablePostFix;
		
		$sql_today = "SELECT 
						    date_format(server_time, '%H') time,
							SUM(IF(`status_campaign` = 2, 1, 0)) AS total_web_entry,
						    SUM(IF(`transaction_id_billing` IS NOT NULL, 1, 0)) AS total_subscriber
						FROM 
						    ".$tableTraffic." WHERE DATE(server_time) = DATE(NOW( )) AND id_campaign=".$params['id_campaign']." AND shortcode=".$params['shortcode']." AND keyword='".$params['keyword']."' GROUP BY date_format(server_time, '%H') order by time";
					
		$query = $this->dbAds->query($sql_today);
		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['time'][$i]['time']		= $row['time'];
				$result['total_web_entry'][$i]['total_web_entry']		= $row['total_web_entry'];
				$result['total_subscriber'][$i]['total_subscriber']		= $row['total_subscriber'];
				$i++;
			}
		}
		else {
			$result['count'] = false;
		}
		return $result;
	}
	

	public function loadkeyword_select(){ 
		  $this->dbAds->select('keyword');
		  $this->dbAds->from('partners_services');
		  $this->dbAds->group_by('keyword');
		  $query = $this->dbAds->get();
		  $result = array();
		  $i=0;
		  if($query->num_rows() != 0){
			   $result['count'] = true;
			   foreach($query->result_array() as $row) {
			    $result['rows'][$i]['id'] = $row['keyword'];
			    $result['rows'][$i]['name'] = $row['keyword'];
			    $i++;
			   }
	   
	  		}
		  else {
		   		$result['count'] = false;
		  	}

	  	return $result;
	}
	public function loadcampaign_select(){ 
		  $this->dbAds->select('id, name');
		  $this->dbAds->from('campaigns');
		  $this->dbAds->group_by('name');
		  $query = $this->dbAds->get();
		  $result = array();
		  $i=0;
		  if($query->num_rows() != 0){
			   $result['count'] = true;
			   foreach($query->result_array() as $row) {
			    $result['rows'][$i]['id'] = $row['id'];
			    $result['rows'][$i]['name'] = $row['name'];
			    $i++;
			   }
	   
	  		}
		  else {
		   		$result['count'] = false;
		  	}

	  	return $result;
	}

}
?>
