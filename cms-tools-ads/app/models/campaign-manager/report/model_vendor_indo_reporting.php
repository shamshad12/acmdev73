<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_vendor_indo_reporting extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadvendor_indo_reporting($params){
		$result = array();
		$user = $this->session->userdata('profile');
		if($user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			if(empty($partner_permissions))
				return $result['count'] = false;
			else
				$partner_permissions = json_decode($partner_permissions);
		}

		if(empty($params['country-list']))
		{
			if($user['id_country'] > 0)
				$params['country-list'] = $user['id_country'];
		}
			
		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d');
			
		if(empty($params['datefrom']))
			$params['datefrom'] = date('Y-m-d');
			
		if(isset($params['datefrom'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datefrom']));
		} elseif(isset($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from($tableTraffic.' t');
		
		if($user['tipe_user_id'] == 11)
		{
			if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}
			if(!empty($partner_permissions->country))
			{
				$this->dbAds->where_in('t.id_country', $partner_permissions->country);
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

		if(!empty($params['country-list']))
			$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') ");
		$this->dbAds->where("DATE(t.operator_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->group_by('t.id_campaign, t.id_operator, DATE(t.operator_time), t.id_ads_publisher');
		$this->dbAds->order_by('DATE(t.operator_time)', 'DESC');
		$query = $this->dbAds->get();
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = count($query->result_array());
		$this->dbAds->select('SUM(IF(status_campaign = 1, 1, 1)) AS clicks, SUM(IF(status_campaign = 2, 1, 0)) AS leads, SUM(IF(transaction_id_billing IS NOT NULL, IF(delivery_status!="REJECTED",1,0), 0)) AS converts,t.name_country,t.name_operator,if(transaction_id_billing,date_format(substring(transaction_id_billing,1,14),"%Y-%m-%d %H:%i:%s"),t.operator_time) as server_time,t.name_campaign,t.name_ads_publisher,t.status_campaign',false);
		$this->dbAds->from($tableTraffic.' t');
		if($user['tipe_user_id'] == 11)
		{
			if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}
			if(!empty($partner_permissions->country))
			{
				$this->dbAds->where_in('t.id_country', $partner_permissions->country);
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

		if(!empty($params['country-list']))
			$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') ");
		$this->dbAds->where("DATE(t.operator_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->group_by('t.id_campaign, t.id_operator, DATE(t.server_time), t.id_ads_publisher');
		$this->dbAds->order_by('DATE(t.operator_time)', 'DESC');
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		$query = $this->dbAds->get();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			$total = array();	
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['server_time']		= $row['server_time'];
				$result['rows'][$i]['name_country'] 	= $row['name_country'];
				$result['rows'][$i]['name_operator'] 	= $row['name_operator'];
				$result['rows'][$i]['name_campaign'] 	= $row['name_campaign'];
				$result['rows'][$i]['name_ads_publisher'] 	= $row['name_ads_publisher'];
				$result['rows'][$i]['clicks']		= $row['clicks'];
				// $result['rows'][$i]['leads']		= $row['leads']-$row['converts'];
				$result['rows'][$i]['leads']		= $row['leads'];
				$result['rows'][$i]['converts']		= $row['converts'];
				// $result['rows'][$i]['convR']		= number_format((($row['converts']/($row['clicks']+$row['leads']))*100), 2);
				$result['rows'][$i]['convR']		= number_format((($row['converts']/($row['clicks']))*100), 2);
				// $status = ($row['status_campaign']==1)?'View':'Lead';
				$total['clicks'] += $row['clicks'];
				// $total['leads'] += ($row['leads']-$row['converts']);
				$total['leads'] += $row['leads'];
				$total['converts'] += $row['converts'];

				$i++;
			}
				$result['rows'][$i]['server_time']		= "Grand Total";
				$result['rows'][$i]['name_country'] 	= "";
				$result['rows'][$i]['name_operator']	= "";
				$result['rows'][$i]['name_campaign']	= "";
				$result['rows'][$i]['name_ads_publisher']	= "";
				$result['rows'][$i]['clicks']	= $total['clicks'];
				$result['rows'][$i]['leads']		= $total['leads'];
				$result['rows'][$i]['converts']	= $total['converts'];
				// $result['rows'][$i]['convR']	= number_format((($total['converts']/($total['clicks']+$total['leads']))*100), 2);
				$result['rows'][$i]['convR']	= number_format((($total['converts']/($total['clicks']))*100), 2);
		} else {
			$result['count'] = false;
		}
		return $result;
	}

}
?>
