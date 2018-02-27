<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class model_campaign_overview extends MY_Model {

	private $tablePostFix;

	public function __construct () {
		parent::__construct();

		$this->loadDbAds();

		// $this->tablePostFix = '_'.date('mY');
		// $this->tablePostFix = '_temp';
		$this->tablePostFix = '_summary';

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


		/* if(!empty($params['dateinternal'])){
			if(strtotime($params['dateinternal']) < strtotime('-2day') ) {
				$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
			}
			else{
				$tableTraffic = 'traffic_temp';
			}
			// $tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
		}
		else if(!empty($params['datepublisher'])){
			if(strtotime($params['datepublisher']) < strtotime('-2day') ) {
				$tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
			}
			else{
				$tableTraffic = 'traffic_temp';
			}
			// $tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
		} else
			$tableTraffic = 'traffic'.$this->tablePostFix; */
	
		$tableTraffic = 'traffic'.$this->tablePostFix;
			
		// if(!$this->dbAds->table_exists($tableTraffic))
		// $this->createTable($this->dbAds, 'traffic_', $tableTraffic);

	// new changes
	$qpartition='';
	
	/**** @Shailendra : DATE() is taking too much time to execute query ****/
	// if(intval(date('m', strtotime($params['dateinternal'])))<6)
	// {
	// $searchBy='DATE(t.server_time)';	
	// }
	// else 
	// {	
	// $searchBy='t.ads_publisher_time';
	// /*$ispartition=$this->getPartition($params['dateinternal'],$params['datepublisher']);
	// if($ispartition)
		// $qpartition='PARTITION('.$ispartition.')';*/
	// }
	//print $qpartition; die;
	//
	$searchBy='t.date';
		
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
		$this->dbAds->from($tableTraffic." $qpartition t");
		if($this->user['tipe_user_id'] == 11)
		{
			if(!empty($this->partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('partner_id', $this->partner_permissions->partner_ids);
			}
			if(!empty($this->partner_permissions->shortcode))
			{
				$this->dbAds->where_in('shortcode', $this->partner_permissions->shortcode);
			}
			if(!empty($this->partner_permissions->keyword))
			{
				$this->dbAds->where_in('service', $this->partner_permissions->keyword);
			}
		}
		if(!empty($params['operators-list']))
		$this->dbAds->where('t.id_operator', $params['operators-list']);
		if(!empty($params['campaign-list']))
		$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
		$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_vendor', $params['ads-publisher-list']);
		if(!empty($params['country-list']))
		$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['keywords-list']))
		$this->dbAds->where('t.service', $params['keywords-list']);
	if(!empty($params['partners-list']))
			$this->dbAds->where('t.partner_id', $params['partners-list']);  //SAM
	if(!empty($params['dateinternal']) && !empty($params['datepublisher']) )
			$this->dbAds->where("$searchBy BETWEEN '".$params['dateinternal']."' AND '".$params['datepublisher']."'");
	if(!empty($params['search']))
		$this->dbAds->where("t.name_campaign",$params['search']);		
	$this->dbAds->group_by('t.id_campaign, t.id_operator, t.id_vendor,date');
		$query = $this->dbAds->get();
		
		$result['total'] = count($query->result_array());
		$this->dbAds->select("t.id_campaign, t.name_campaign, t.service,t.partner_name, t.shortcode, t.id_country, t.name_country, t.id_operator, t.name_operator, t.id_vendor, t.name_vendor, t.date, views AS total_landing_views, lead AS total_web_entry, sales AS total_subscriber",false);
		$this->dbAds->from($tableTraffic." $qpartition t");

		if($this->user['tipe_user_id'] == 11)
		{
			if(!empty($this->partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('partner_id', $this->partner_permissions->partner_ids);
			}
			if(!empty($this->partner_permissions->shortcode))
			{
				$this->dbAds->where_in('shortcode', $this->partner_permissions->shortcode);
			}
			if(!empty($this->partner_permissions->keyword))
			{
				$this->dbAds->where_in('service', $this->partner_permissions->keyword);
			}
		}

		if(!empty($params['operators-list']))
		$this->dbAds->where('t.id_operator', $params['operators-list']);
		if(!empty($params['campaign-list']))
		$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
		$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_vendor', $params['ads-publisher-list']);
		if(!empty($params['country-list']))
		$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['keywords-list']))
		$this->dbAds->where('t.service', $params['keywords-list']);
		if(!empty($params['partners-list']))
			$this->dbAds->where('t.partner_id', $params['partners-list']);
		if(!empty($params['dateinternal']) && !empty($params['datepublisher']) )
			$this->dbAds->where("$searchBy BETWEEN '".$params['dateinternal']."' AND '".$params['datepublisher']."'");
		if(!empty($params['search']))
		$this->dbAds->where("t.name_campaign",$params['search']);
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.id_vendor, t.date');
		$this->dbAds->order_by('t.date','DESC');
		$this->dbAds->order_by('sales','DESC');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();
		// exit;
		

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['id_campaign']				= $row['id_campaign'];
				$result['rows'][$i]['name_campaign']			= $row['name_campaign'];
				$result['rows'][$i]['id_country']				= $row['id_country'];
				$result['rows'][$i]['name_country']				= $row['name_country'];
				$result['rows'][$i]['keyword']					= $row['service'];
				$result['rows'][$i]['shortcode']				= $row['shortcode'];
				$result['rows'][$i]['id_operator']				= $row['id_operator'];
				$result['rows'][$i]['name_operator']			= $row['name_operator'];
				$result['rows'][$i]['name_partner']				= $row['partner_name']; //SAM
				$result['rows'][$i]['id_ads_publisher']			= $row['id_vendor'];
				$result['rows'][$i]['name_ads_publisher']		= $row['name_vendor'];
				$result['rows'][$i]['server_time']				= $row['date'];
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
			$result['rows'][$i]['server_time']				= "Grand Total";
			$result['rows'][$i]['name_campaign']			= "";
			$result['rows'][$i]['keyword']					= "";
			$result['rows'][$i]['name_partner']				= ""; //SAM
			$result['rows'][$i]['name_country']				= ""; //SAM
			$result['rows'][$i]['shortcode']				= "";
			$result['rows'][$i]['name_operator']			= "";
			$result['rows'][$i]['name_ads_publisher']		= "";
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
		$result = array();
		if(!empty($params['dateinternal'])){
			if(strtotime($params['dateinternal']) < strtotime('-2day') ) {
				$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
			}
			else{
				$tableTraffic = 'traffic_temp';
			}
			// $tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
		}
		else if(!empty($params['partners-list']))
		{
			$where[] = "partner_id=".$params['partners-list'];
		}
		else if(!empty($params['datepublisher'])){
			if(strtotime($params['datepublisher']) < strtotime('-2day') ) {
				$tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
			}
			else{
				$tableTraffic = 'traffic_temp';
			}
			// $tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
		} 
		else
		{
			// $tableTraffic = 'traffic'.$this->tablePostFix;
			$tableTraffic = 'traffic_temp';
		}

	// new changes
	$qpartition='';
	
	/**** @Shailendra : DATE() is taking too much time to execute query ****/
	// if(intval(date('m', strtotime($params['dateinternal'])))<6)
	// {
	// $searchBy='DATE(server_time)';	
	// }
	// else 
	// {	
	// $searchBy='ads_publisher_time';
	// /*$ispartition=$this->getPartition($params['dateinternal'],$params['datepublisher']);
	// if($ispartition)
		// $qpartition='PARTITION('.$ispartition.')';*/
	// }
	
	$searchBy='ads_publisher_time';
	
	//print $qpartition; die;
	//
	
		$where = array();
		if(!empty($params['dateinternal']) || !empty($params['datepublisher']) ){
			if(!empty($params['dateinternal']))
{
			$where[] = "$searchBy >= '".$params['dateinternal']."'";
		$result['sdate'] = $params['dateinternal'];
}
			if(!empty($params['datepublisher']))
{
			$where[] = "$searchBy <= '".$params['datepublisher']."'";
			$result['edate'] = $params['datepublisher'];
}
		}
		else{
			$where[] = "$searchBy = '".date('Y-m-d')."'";
$result['sdate'] = date('Y-m-d');
			$result['edate'] = "";
		}
		$where_str = implode(' AND ',$where);
		$sql_today = "SELECT
						    date_format(server_time, '%H') time,
							SUM(IF(`status_campaign` = 2, 1, 0)) AS total_web_entry,
						    SUM(IF(`transaction_id_billing` !='', IF(delivery_status!='REJECTED',1,0), 0)) AS total_subscriber
						FROM 
						    ".$tableTraffic." $qpartition WHERE ".$where_str." AND `id_campaign`='".$params['id_campaign']."' AND `id_country`='".$params['id_country']."' AND `id_operator`='".$params['id_operator']."' AND `id_ads_publisher`='".$params['id_ads_publisher']."' AND `shortcode`='".$params['shortcode']."' AND `keyword`='".$params['keyword']."' GROUP BY time";
			
		$query = $this->dbAds->query($sql_today);
//		 echo $this->dbAds->last_query();die('lop');
		
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
	
	public function generate($params){

		$result['total'] = 0;
		$result['count'] = false;

		$user = $this->session->userdata('profile');
		if($user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			if(empty($partner_permissions))
			return $result['count'] = false;
		}

		/* if(!empty($params['dateinternal'])){
			if(strtotime($params['dateinternal']) < strtotime('-2day') ) {
				$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
			}
			else{
				$tableTraffic = 'traffic_temp';
			}
			// $tableTraffic = 'traffic_'.date('mY', strtotime($params['dateinternal']));
		}
		else if(!empty($params['datepublisher'])){
			if(strtotime($params['datepublisher']) < strtotime('-2day') ) {
				$tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
			}
			else{
				$tableTraffic = 'traffic_temp';
			}
			// $tableTraffic = 'traffic_'.date('mY', strtotime($params['datepublisher']));
		} else
			$tableTraffic = 'traffic'.$this->tablePostFix; */
		
		$tableTraffic = 'traffic'.$this->tablePostFix;
			
		// if(!$this->dbAds->table_exists($tableTraffic))
		// $this->createTable($this->dbAds, 'traffic_', $tableTraffic);

	// new changes
	$qpartition='';
	
	/**** @Shailendra : DATE() is taking too much time to execute query ****/
	// if(intval(date('m', strtotime($params['dateinternal'])))<6)
	// {
		// $searchBy='DATE(t.server_time)';	
	// }
	// else 
	// {	
		// $searchBy='t.ads_publisher_time';
		
		// /*$ispartition=$this->getPartition($params['dateinternal'],$params['datepublisher']);
		// if($ispartition)
			// $qpartition='PARTITION('.$ispartition.')';*/
	// }
	
	$searchBy='t.date';
	
	//print $qpartition; die;
	//
	
	
		$fieldList = "Date,Campaign Name,Country,Partner,Keyword,shortcode,Operators,Affliates,View,Web Entry,Sales,Subscriber/Web Entry (%),Web Entry/Views (%),Subscriber/Views (%)";

		$this->dbAds->select('t.date, t.name_campaign, t.name_country, t.partner_name,t.service, t.shortcode, t.name_operator, t.name_vendor,');
		$this->dbAds->select('views AS total_landing_views');
		$this->dbAds->select('lead AS total_web_entry');
		$this->dbAds->select('sales AS total_subscriber');

		$this->dbAds->from($tableTraffic." $qpartition t");

		if($this->user['tipe_user_id'] == 11)
		{
			if(!empty($this->partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('partner_id', $this->partner_permissions->partner_ids);
			}
			if(!empty($this->partner_permissions->shortcode))
			{
				$this->dbAds->where_in('shortcode', $this->partner_permissions->shortcode);
			}
			if(!empty($this->partner_permissions->keyword))
			{
				$this->dbAds->where_in('service', $this->partner_permissions->keyword);
			}
		}
		if(!empty($params['operators-list']))
		$this->dbAds->where('t.id_operator', $params['operators-list']);
		if(!empty($params['campaign-list']))
		$this->dbAds->where('t.id_campaign', $params['campaign-list']);
		if(!empty($params['shortcode-list']))
		$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['ads-publisher-list']))
		$this->dbAds->where('t.id_vendor', $params['ads-publisher-list']);
		if(!empty($params['country-list']))
		$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['keywords-list']))
		$this->dbAds->where('t.service', $params['keywords-list']);
		if(!empty($params['partner-list']))
			$this->dbAds->where('t.partner_id', $params['partner-list']);
		/*if(!empty($params['search']))
		$this->dbAds->where("(t.name_campaign LIKE '%".$params['search']."%' OR t.name_operator LIKE '%".$params['search']."%' OR t.name_partner LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.name_ads_publisher LIKE '%".$params['search']."%' OR t.name_country LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%')");*/
	if(!empty($params['search']))
		$this->dbAds->where("t.name_campaign",$params['search']);	
			
		$this->dbAds->where("$searchBy BETWEEN '".$params['dateinternal']."' AND '".$params['datepublisher']."'");
		
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.id_vendor, date');
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();exit;
		$result = array();
		$result[0] = $fieldList;
		$i=1;

		if($query->num_rows() != 0){
			//$result['count'] = true;
			foreach($query->result_array() as $row) {

				$subscriber_webentry				= ($row['total_subscriber']/$row['total_web_entry'])*100;
				$webentry_views						= ($row['total_web_entry']/$row['total_landing_views'])*100;
				$subscriber_views					= ($row['total_subscriber']/$row['total_landing_views'])*100;

				$row['per_subscriber_webentry'] += number_format($subscriber_webentry, 2,'.','');
				$row['per_webentry_views'] += number_format($webentry_views, 2,'.','');
				$row['per_subscriber_views'] += number_format($subscriber_views, 2,'.','');


				$result[$i] = implode(',', $row);
				$i++;
			}
		}
		return $result;
	}

}
?>
