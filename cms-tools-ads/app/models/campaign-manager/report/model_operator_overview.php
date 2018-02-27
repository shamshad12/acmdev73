<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_operator_overview extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();

		$this->curr_day = date("d");
		$this->tableTraffic = 'traffic_'.date('mY');

		if(!$this->dbAds->table_exists($this->tableTraffic))
		{
			$this->createTable($this->dbAds, 'traffic_', $this->tableTraffic);
		}

		if($this->curr_day=='01')
		{
			$this->tableTraffic_pm = 'traffic_'.date('mY', strtotime(date('Y-m')." -1 month"));
		}

		$this->user = $this->session->userdata('profile');
		$this->partner_permissions = "";
		if($this->user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			if(!empty($partner_permissions))
				$this->partner_permissions = json_decode($partner_permissions);
		}
	}

	public function getRecord($type,$result,$params)
	{
		$table = $this->tableTraffic;
		
		if(!empty($params['partner-list']))
			$this->dbAds->where('id_partner', $params['partner-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('shortcode', $params['shortcode-list']);
		
		// new changes
		//$qpartition='';
		$searchBy='ads_publisher_time';
		//

		if($type == 'today')
		{
			$this->dbAds->where("$searchBy = curdate()", null,false);
		}
		else
		{
			if($this->curr_day!='01')
			{
				$this->dbAds->where("$searchBy = DATE( DATE_SUB( NOW( ) , INTERVAL 1 DAY ))", NULL, FALSE);
			}
			else
			{
				$table = $this->tableTraffic_pm;
				$this->dbAds->where("$searchBy = '".date('Y-m-d', strtotime('last day of previous month'))."'", NULL, FALSE);
			}
		}

		if($this->user['tipe_user_id'] == 11)
		{
			if(!empty($this->partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('id_partner', $this->partner_permissions->partner_ids);
			}
			if(!empty($this->partner_permissions->shortcode))
			{
				$this->dbAds->where_in('shortcode', $this->partner_permissions->shortcode);
			}
			if(!empty($this->partner_permissions->keyword))
			{
				$this->dbAds->where_in('keyword', $this->partner_permissions->keyword);
			}
		}

		$this->dbAds->select('SUM(IF(status_campaign = 1, 1, 1)) AS total_landing_views, SUM(IF(status_campaign = 2, 1, 0)) AS total_web_entry, SUM(IF(transaction_id_billing IS NOT NULL, IF(delivery_status!="REJECTED",1,0), 0)) AS total_subscriber,name_operator',false);
		$this->dbAds->group_by('id_operator');

		$query = $this->dbAds->get($table); 
		
		//echo $this->dbAds->last_query();die;

		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result[$type][$i]['name_operator']			= $row['name_operator'];
				$result[$type][$i]['total_landing_views']	= $row['total_landing_views'];
				$result[$type][$i]['total_web_entry']		= $row['total_web_entry'];
				$result[$type][$i]['total_subscriber']		= $row['total_subscriber'];
				$subscriber_webentry				= ($row['total_subscriber']/$row['total_web_entry'])*100;
				$webentry_views						= ($row['total_web_entry']/$row['total_landing_views'])*100;
				$subscriber_views				= ($row['total_subscriber']/$row['total_landing_views'])*100;
				$result[$type][$i]['subscriber_webentry']	= number_format($subscriber_webentry, 2);
				$result[$type][$i]['webentry_views']	= number_format($webentry_views, 2);
				$result[$type][$i]['subscriber_views']	= number_format($subscriber_views, 2);

				$total[$type]['sum_landing_views'] += $row['total_landing_views'];
				$total[$type]['sum_web_entry'] += $row['total_web_entry'];
				$total[$type]['sum_subscriber'] += $row['total_subscriber'];

				$total[$type]['per_subscriber_webentry'] += number_format($subscriber_webentry, 2);
				$total[$type]['per_webentry_views'] += number_format($webentry_views, 2);
				$total[$type]['per_subscriber_views'] += number_format($subscriber_views, 2);
				$i++;
			}
			$result[$type][$i]['name_operator']		= "Grand Total";
			$result[$type][$i]['total_landing_views']	= $total[$type]['sum_landing_views'];
			$result[$type][$i]['total_web_entry']		= $total[$type]['sum_web_entry'];
			$result[$type][$i]['total_subscriber']	= $total[$type]['sum_subscriber'];

			$result[$type][$i]['subscriber_webentry']	= number_format($total[$type]['per_subscriber_webentry']/$i,2);
			$result[$type][$i]['webentry_views']	= number_format($total[$type]['per_webentry_views']/$i,2);
			$result[$type][$i]['subscriber_views']	= number_format($total[$type]['per_subscriber_views']/$i,2);
		}
		return $result;
	}
	
	public function loadoperator_overview($params){
		$result = array();
		$result['today'] = '';
		$result['yesterday'] = '';
		$result['count'] = false;
		$total = array();

		if($this->user['tipe_user_id'] == 11)
		{
			if(empty($this->partner_permissions))
				return $result;
		}
			
		$result = $this->getRecord('today',$result,$params);

		$result = $this->getRecord('yesterday',$result,$params);

		return $result;
	}
	
}
?>
