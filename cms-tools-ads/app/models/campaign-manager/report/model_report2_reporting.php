<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_report2_reporting extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadreport2_reporting($params){

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
			
		// new changes
		$qpartition='';
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
		
		$this->dbAds->select(' COUNT(1) AS count ');
		if(!empty($params['days']) && $params['days']==1)
		{
			$this->dbAds->from($tableTraffic." $qpartition t");
			$this->dbAds->where("t.ads_publisher_time BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-d')."'");
		}
		elseif(!empty($params['days']) && $params['days']==-1)
		{
			$currday = date("d");
			if($currday!='01')
			{
				$this->dbAds->from($tableTraffic.' t');
				$this->dbAds->where('t.ads_publisher_time = DATE( DATE_SUB( NOW( ) , INTERVAL 1 DAY ))', NULL, FALSE);
			}
			else
			{
				$table = 'traffic_'.date('mY', strtotime(date('Y-m')." -1 month"));
				$this->dbAds->from($table.' t');
				$this->dbAds->where("t.ads_publisher_time = '".date('Y-m-d', strtotime('last day of previous month'))."'", NULL, FALSE);
			}
		}
		elseif(!empty($params['days']) && $params['days']==-30)
		{
			$table = 'traffic_'.date('mY', strtotime(date('Y-m')." -1 month"));
			$this->dbAds->from($table.' t');
			$this->dbAds->where("t.ads_publisher_time BETWEEN '".date('Y-m-d', strtotime('first day of previous month'))."' AND '".date('Y-m-d', strtotime('last day of previous month'))."'", NULL, FALSE);
		}
		else
		{
			$this->dbAds->from($tableTraffic." $qpartition t");
			//$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
			$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		}

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
		if(!empty($params['media']))
			$this->dbAds->where("t.id_campaign_media", $params['media']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign", $params['search']);
			
		// $this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.ads_publisher_time, t.id_ads_publisher, t.shortcode, t.campaign_price');
		$this->dbAds->order_by('t.ads_publisher_time', 'DESC');
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('a');
		//$result['total'] = 0;
		$result['total'] = count($query->result_array());	
		$this->dbAds->select('SUM(IF(status_campaign = 1, 1, 1)) AS clicks, SUM(IF(status_campaign = 2, 1, 0)) AS leads, SUM(IF(transaction_id_billing IS NOT NULL, IF(delivery_status!="REJECTED",1,0), 0)) AS converts,t.name_country,t.name_operator,t.operator_time as server_time,t.name_campaign,t.name_ads_publisher,t.shortcode,t.campaign_price,t.status_campaign,t.currency_code',false);
		// $this->dbAds->from($tableTraffic.' t');
		if(!empty($params['days']) && $params['days']==1)
		{
			$this->dbAds->from($tableTraffic." $qpartition t");
			$this->dbAds->where("t.ads_publisher_time BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-d')."'");
		}
		elseif(!empty($params['days']) && $params['days']==-1)
		{
			$currday = date("d");
			if($currday!='01')
			{
				$this->dbAds->from($tableTraffic.' t');
				$this->dbAds->where('t.ads_publisher_time = DATE( DATE_SUB( NOW( ) , INTERVAL 1 DAY ))', NULL, FALSE);
			}
			else
			{
				$table = 'traffic_'.date('mY', strtotime(date('Y-m')." -1 month"));
				$this->dbAds->from($table.' t');
				$this->dbAds->where("t.ads_publisher_time = '".date('Y-m-d', strtotime('last day of previous month'))."'", NULL, FALSE);
			}
		}
		elseif(!empty($params['days']) && $params['days']==-30)
		{
			$table = 'traffic_'.date('mY', strtotime(date('Y-m')." -1 month"));
			$this->dbAds->from($table.' t');
			$this->dbAds->where("t.ads_publisher_time BETWEEN '".date('Y-m-d', strtotime('first day of previous month'))."' AND '".date('Y-m-d', strtotime('last day of previous month'))."'", NULL, FALSE);
		}
		else
		{
			$this->dbAds->from($tableTraffic." $qpartition t");
			$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		}

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
		if(!empty($params['media']))
			$this->dbAds->where("t.id_campaign_media", $params['media']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign", $params['search']);
			
		// $this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.ads_publisher_time, t.id_ads_publisher, t.shortcode, t.campaign_price');
		$this->dbAds->order_by('t.ads_publisher_time', 'DESC');

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
				$result['rows'][$i]['shortcode'] 	= $row['shortcode'];
				$result['rows'][$i]['campaign_price'] 	= $row['currency_code'] . ' '.number_format($row['campaign_price'],2);
				$result['rows'][$i]['clicks']		= $row['clicks'];
				//$result['rows'][$i]['leads']		= $row['leads']-$row['converts'];
				$result['rows'][$i]['leads']		= $row['leads'];
				$result['rows'][$i]['converts']		= $row['converts'];
				//$result['rows'][$i]['convR']		= number_format((($row['converts']/($row['clicks']+$row['leads']))*100), 2);
				$result['rows'][$i]['convR']		= number_format((($row['converts']/($row['clicks']))*100), 2);
				$result['rows'][$i]['revenue']		= $row['currency_code'] . ' '.number_format(($row['campaign_price']*$row['converts']), 2);
				
				$total['clicks'] += $row['clicks'];
				//$total['leads'] += ($row['leads']-$row['converts']);
				$total['leads'] += $row['leads'];
				$total['converts'] += $row['converts'];
				$total['revenue'] += $row['campaign_price']*$row['converts'];

				$i++;
			}
				$result['rows'][$i]['server_time']		= "Grand Total";
				$result['rows'][$i]['name_country'] 	= "";
				$result['rows'][$i]['name_operator']	= "";
				$result['rows'][$i]['name_campaign']	= "";
				$result['rows'][$i]['name_ads_publisher']	= "";
				$result['rows'][$i]['shortcode'] 	= "";
				$result['rows'][$i]['campaign_price'] 	= "";
				$result['rows'][$i]['clicks']	= $total['clicks'];
				$result['rows'][$i]['leads']		= $total['leads'];
				$result['rows'][$i]['converts']	= $total['converts'];
				//$result['rows'][$i]['convR']	= number_format((($total['converts']/($total['clicks']+$total['leads']))*100), 2);
				$result['rows'][$i]['convR']	= number_format((($total['converts']/($total['clicks']))*100), 2);
				$result['rows'][$i]['revenue']		=$row['currency_code']. number_format($total['revenue'], 2);
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function loadreport2_reportinglastdays($params){

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
		
		$tablepm = 'traffic_'.date('mY', strtotime(date('Y-m')." -1 month"));	
		if(!empty($params['days']) && $params['days']==7)
		{
			$enddate = date('Y-m-d');
			$startdate = date('Y-m-d', strtotime('-7 days'));
			if(date("d")<7)
  			{
  				$count1 = $this->getCrosstabledata($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$count2 = $this->getCrosstabledata($params, $tablepm, $startdate, $enddate, $user, $partner_permissions);
  				$total = $count1 + $count2;
  				$data1 = $this->getCrosstabledatashow($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$data2 = $this->getCrosstabledatashow($params, $tablepm, $startdate, $enddate, $user, $partner_permissions);
  				$data['count'] = $data1['count']?$data1['count']:$data2['count'];
  				$data['rows'] = array_merge($data1['rows'],$data2['rows']);
  				$cuurency = $data1['rows'][$count1]['currency_code']?$data1['rows'][$count1]['currency_code']:$data2['rows'][$count2]['currency_code'];
  				
  				if(isset($data1['rows']) && isset($data2['rows']))
  				{
  					unset($data['rows'][$count1]);
					unset($data['rows'][$total+1]);
					$data['rows'] = array_values($data['rows']);
					
  					$data['rows'][$total]['server_time'] =  "Grand Total"; 
					$data['rows'][$total]['name_country'] 	= "";
					$data['rows'][$total]['name_operator']	= "";
					$data['rows'][$total]['name_campaign']	= "";
					$data['rows'][$total]['currency_code']	= "";
					$data['rows'][$total]['name_ads_publisher']	= "";
					$data['rows'][$total]['shortcode'] 	= "";
					$data['rows'][$total]['campaign_price'] 	= "";
					$data['rows'][$total]['clicks']	= $data1['rows'][$count1]['clicks'] + $data2['rows'][$count2]['clicks'];
					$data['rows'][$total]['leads']		= $data1['rows'][$count1]['leads'] + $data2['rows'][$count2]['leads'];
					$data['rows'][$total]['converts']	= $data1['rows'][$count1]['converts'] + $data2['rows'][$count2]['converts'];
					//$data['rows'][$total]['convR']		= number_format((($data['rows'][$total]['converts']/($data['rows'][$total]['clicks']+$data['rows'][$total]['leads']))*100), 2);
					$data['rows'][$total]['convR']		= number_format((($data['rows'][$total]['converts']/($data['rows'][$total]['clicks']))*100), 2);
					$data['rows'][$total]['revenue']	= $cuurency.' '. number_format(($data1['rows'][$count1]['revenue'] + $data2['rows'][$count2]['revenue']),2);
  				}
				$result = $data;
				$result['total'] = $total;
  			}
  			else
  			{
  				$total = $this->getCrosstabledata($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$data = $this->getCrosstabledatashow($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$result = $data;
  				$result['total'] = $total;
  			}
		}
		elseif(!empty($params['days']) && $params['days']==30)
		{
			$enddate = date('Y-m-d');
			$startdate = date('Y-m-d', strtotime('-30 days'));
			if(date("d")<30)
  			{
  				$count1 = $this->getCrosstabledata($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$count2 = $this->getCrosstabledata($params, $tablepm, $startdate, $enddate, $user, $partner_permissions);
  				$total = $count1 + $count2;
  				$data1 = $this->getCrosstabledatashow($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$data2 = $this->getCrosstabledatashow($params, $tablepm, $startdate, $enddate, $user, $partner_permissions);
  				$data['count'] = $data1['count']?$data1['count']:$data2['count'];
  				$data['rows'] = array_merge($data1['rows'],$data2['rows']);
  				$cuurency = $data1['rows'][$count1]['currency_code']?$data1['rows'][$count1]['currency_code']:$data2['rows'][$count2]['currency_code'];
				
  				if(isset($data1['rows']) && isset($data2['rows']))
  				{
  					unset($data['rows'][$count1]);
					unset($data['rows'][$total+1]);
					$data['rows'] = array_values($data['rows']);
					
  					$data['rows'][$total]['server_time'] =  "Grand Total"; 
					$data['rows'][$total]['name_country'] 	= "";
					$data['rows'][$total]['name_operator']	= "";
					$data['rows'][$total]['name_campaign']	= "";
					$data['rows'][$total]['currency_code']	= "";
					$data['rows'][$total]['name_ads_publisher']	= "";
					$data['rows'][$total]['shortcode'] 	= "";
					$data['rows'][$total]['campaign_price'] 	= "";
					$data['rows'][$total]['clicks']	= $data1['rows'][$count1]['clicks'] + $data2['rows'][$count2]['clicks'];
					$data['rows'][$total]['leads']		= $data1['rows'][$count1]['leads'] + $data2['rows'][$count2]['leads'];
					$data['rows'][$total]['converts']	= $data1['rows'][$count1]['converts'] + $data2['rows'][$count2]['converts'];
					//$data['rows'][$total]['convR']		= number_format((($data['rows'][$total]['converts']/($data['rows'][$total]['clicks']+$data['rows'][$total]['leads']))*100), 2);
					$data['rows'][$total]['convR']		= number_format((($data['rows'][$total]['converts']/($data['rows'][$total]['clicks']))*100), 2);
					$data['rows'][$total]['revenue']	= $cuurency.' '.number_format(($data1['rows'][$count1]['revenue'] + $data2['rows'][$count2]['revenue']),2);
  				}
				$result = $data;
				$result['total'] = $total;
  			}
  			else
  			{
  				$total = $this->getCrosstabledata($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$data = $this->getCrosstabledatashow($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions);
  				$result = $data;
  				$result['total'] = $total;
  			}
		}
		return $result;
	}

	public function getCrosstabledata($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions)
	{

		$this->dbAds->select(' COUNT(1) AS count ');
		$this->dbAds->from($tableTraffic.' t');
		$this->dbAds->where("t.ads_publisher_time BETWEEN '".$startdate."' AND '".$enddate."'");

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
		if(!empty($params['media']))
			$this->dbAds->where("t.id_campaign_media", $params['media']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') ");
			
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.ads_publisher_time, t.id_ads_publisher, t.shortcode, t.campaign_price');
		$this->dbAds->order_by('t.ads_publisher_time', 'DESC');
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();
		$result = count($query->result_array());
		// echo "<pre>";print_r($result);die('ll');
		return $result;
	}

	public function getCrosstabledatashow($params, $tableTraffic, $startdate, $enddate, $user, $partner_permissions)
	{
		$this->dbAds->select('SUM(IF(status_campaign = 1, 1, 1)) AS clicks, SUM(IF(status_campaign = 2, 1, 0)) AS leads, SUM(IF(transaction_id_billing IS NOT NULL, IF(delivery_status!="REJECTED",1,0), 0)) AS converts,t.name_country,t.name_operator,t.server_time,t.name_campaign,t.name_ads_publisher,t.shortcode,t.campaign_price,t.status_campaign,t.currency_code',false);
		$this->dbAds->from($tableTraffic.' t');
		$this->dbAds->where("t.ads_publisher_time BETWEEN '".$startdate."' AND '".$enddate."'");

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
		if(!empty($params['media']))
			$this->dbAds->where("t.id_campaign_media", $params['media']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') ");
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.ads_publisher_time, t.id_ads_publisher, t.shortcode, t.campaign_price');
		$this->dbAds->order_by('t.ads_publisher_time', 'DESC');
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
				$result['rows'][$i]['currency_code']	= $row['currency_code'];
				$result['rows'][$i]['name_ads_publisher'] 	= $row['name_ads_publisher'];
				$result['rows'][$i]['shortcode'] 	= $row['shortcode'];
				$result['rows'][$i]['campaign_price'] 	= $row['currency_code'] . ' '.number_format($row['campaign_price'],2);
				$result['rows'][$i]['clicks']		= $row['clicks'];
				//$result['rows'][$i]['leads']		= $row['leads']-$row['converts'];
				$result['rows'][$i]['leads']		= $row['leads'];
				$result['rows'][$i]['converts']		= $row['converts'];
				//$result['rows'][$i]['convR']		= number_format((($row['converts']/($row['clicks']+$row['leads']))*100), 2);
				$result['rows'][$i]['convR']		= number_format((($row['converts']/($row['clicks']))*100), 2);
				$result['rows'][$i]['revenue']		= $row['currency_code'] . ' '.number_format(($row['campaign_price']*$row['converts']), 2);
				
				$total['clicks'] += $row['clicks'];
				//$total['leads'] += ($row['leads']-$row['converts']);
				$total['leads'] += $row['leads'];
				$total['converts'] += $row['converts'];
				$total['revenue'] += $row['campaign_price']*$row['converts'];

				$i++;
			}
				$result['rows'][$i]['server_time']		= "Grand Total";
				$result['rows'][$i]['name_country'] 	= "";
				$result['rows'][$i]['name_operator']	= "";
				$result['rows'][$i]['name_campaign']	= "";
				$result['rows'][$i]['currency_code']	= $row['currency_code'];
				$result['rows'][$i]['name_ads_publisher']	= "";
				$result['rows'][$i]['shortcode'] 	= "";
				$result['rows'][$i]['campaign_price'] 	= "";
				$result['rows'][$i]['clicks']	= $total['clicks'];
				$result['rows'][$i]['leads']		= $total['leads'];
				$result['rows'][$i]['converts']	= $total['converts'];
				//$result['rows'][$i]['convR']	= number_format((($total['converts']/($total['clicks']+$total['leads']))*100), 2);
				$result['rows'][$i]['convR']	= number_format((($total['converts']/($total['clicks']))*100), 2);
				$result['rows'][$i]['revenue']		= $total['revenue'];
		} else {
			$result['count'] = false;
		}
		return $result;
	}

	public function generate($params){
		if(empty($params['country-list']))
		{
			if($user['id_country'] > 0)
				$params['country-list'] = $user['id_country'];
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
		
		$fieldList = "Server Time, Country, Campaign, Operator, Ads Vendors, Shortcodes, Campaign Price, Click, 	Lead, Convert, ConvR(%), Gross Rev";
		
		$this->dbAds->select('t.server_time,t.name_country,t.name_campaign,t.name_operator,t.name_ads_publisher,t.shortcode,t.campaign_price,SUM(IF(status_campaign = 1, 1, 1)) AS clicks, SUM(IF(status_campaign = 2, 1, 0)) AS leads, SUM(IF(transaction_id_billing IS NOT NULL, IF(delivery_status!="REJECTED",1,0), 0)) AS converts',false);
		$this->dbAds->from($tableTraffic.' t');
		if(!empty($params['country-list']))
			$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['media']))
			$this->dbAds->where("t.id_campaign_media", $params['media']);
		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['ads-publisher-list']))
			$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
		if(!empty($params['shortcode-list']))
			$this->dbAds->where('t.shortcode', $params['shortcode-list']);
		if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') ");
			
		$this->dbAds->where("t.ads_publisher_time BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->group_by('t.id_campaign, t.id_operator, t.ads_publisher_time, t.id_ads_publisher, t.shortcode, t.campaign_price');
		$this->dbAds->order_by('t.ads_publisher_time', 'DESC');
		
		$query = $this->dbAds->get();
		$result = array();
		$total = array();
		$tot = array();
		$result[0] = $fieldList;
		$i=1;
   	    if($query->num_rows() != 0){
		    foreach($query->result_array() as $row) {
		    	$lead = $row['leads'];
				$row['clicks']		= $row['clicks'];
				//$row['leads']		= $row['leads']-$row['converts'];
				$row['leads']		= $row['leads'];
				$row['converts']	= $row['converts'];
				//$row['convR']		= number_format((($row['converts']/($row['clicks']+$lead))*100), 2);
				$row['convR']		= number_format((($row['converts']/($row['clicks']))*100), 2);
				$row['revenue']		= round(($row['campaign_price']*$row['converts']), 2);
				$result[$i] = implode(',', $row);

				$total['clicks'] += $row['clicks'];
				$total['leads'] += $row['leads'];
				$total['converts'] += $row['converts'];
				$total['revenue'] += $row['revenue'];

				$i++;
			}
				$tot['server_time']		= "Grand Total";
				$tot['name_country'] 	= "";
				$tot['name_campaign']	= "";
				$tot['name_operator']	= "";
				$tot['name_ads_publisher']	= "";
				$tot['shortcode'] 	= "";
				$tot['campaign_price'] 	= "";
				$tot['clicks']	= $total['clicks'];
				$tot['leads']		= $total['leads'];
				$tot['converts']	= $total['converts'];
				//$tot['convR']	= number_format((($total['converts']/($total['clicks']+$total['leads']))*100), 2);
				$tot['convR']	= number_format((($total['converts']/($total['clicks']))*100), 2);
				$tot['revenue']		= round($total['revenue'], 2);
				$result[$i] = implode(',', $tot);
	    }

	  return $result;
	}

}
?>
