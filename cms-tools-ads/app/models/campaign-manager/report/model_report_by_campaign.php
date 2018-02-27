<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_report_by_campaign extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadReport_By_Campaign($params){
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
			
		if(isset($params['datefrom']) && isset($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		
		// new changes
		$qpartition='';
		if(intval(date('m', strtotime($params['datefrom'])))<6)
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
		$this->dbAds->from($tableTraffic." $qpartition t");

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

		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		if(!empty($params['country-list']))
		$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['partner-list']))
			$this->dbAds->where('t.id_partner', $params['partner-list']);
		/* if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') "); */
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign", $params['search']);
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		$this->dbAds->where("status_campaign", "2");
		$this->dbAds->where("transaction_id_billing IS NOT NULL");
		$this->dbAds->where("delivery_status !=","REJECTED");
		$this->dbAds->group_by('date(t.operator_time), t.id_operator, t.id_partner, t.id_ads_publisher, t.id_campaign');
		#$this->dbAds->group_by('date(t.operator_time),t.id_campaign');
		$query = $this->dbAds->get();
		#echo $this->dbAds->last_query();die;
		$result['total'] = 0;
			//foreach($query->result_array() as $row)
				//$result['total'] = $row['count'];
		$result['total']=count($query->result_array());		
		$this->dbAds->select('t.id, t.server_time, t.operator_time, t.partner_time, t.ads_publisher_time, t.name_country');
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher, t.currency_code');
		#$this->dbAds->select('SUM(CASE WHEN t.is_publisher_send = 1 THEN t.campaign_cost else 0 end) AS cost');
		$this->dbAds->select('SUM(campaign_price) AS gross_revenue');
		$this->dbAds->from($tableTraffic." $qpartition t");

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

		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		 if(!empty($params['country-list']))
			$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['partner-list']))
			$this->dbAds->where('t.id_partner', $params['partner-list']);
		/* if(!empty($params['search']))
			$this->dbAds->where("( t.name_campaign LIKE '%".$params['search']."%') "); */
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign", $params['search']);	
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		
		$this->dbAds->where("status_campaign", "2");
		$this->dbAds->where("transaction_id_billing IS NOT NULL");
		$this->dbAds->where("delivery_status !=","REJECTED");
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		
		$this->dbAds->order_by('t.id', 'DESC');
		
		$this->dbAds->group_by('date(t.operator_time), t.id_operator, t.id_partner, t.id_ads_publisher, t.id_campaign');
		#$this->dbAds->group_by('date(t.operator_time),t.id_campaign');
		
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die('test');
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['rows'][$i]['server_time']		= date('d M Y', strtotime($row['operator_time']));
				$result['rows'][$i]['ads_publisher_time'] 	= $row['ads_publisher_time'];
				$result['rows'][$i]['name_country'] 	= $row['name_country'];
				$result['rows'][$i]['name_campaign_media'] 	= $row['name_campaign_media'];
				$result['rows'][$i]['name_operator'] 	= $row['name_operator'];
				$result['rows'][$i]['name_partner'] 	= $row['name_partner'];
				$result['rows'][$i]['name_campaign'] 	= $row['name_campaign'];
				$result['rows'][$i]['name_ads_publisher'] 	= $row['name_ads_publisher'];
				#$result['rows'][$i]['cost'] 			= 'USD '.number_format($row['cost'],4);
                #$cost = $this->vpb_concurrency_converter('USD', $code, $row['cost']); 
                #$result['rows'][$i]['cost_country_currrency']= $row['currency_code']. ' '.$cost; 
				$result['rows'][$i]['gross_revenue']	= $row['currency_code'] . ' ' . number_format($row['gross_revenue'],2);
				#$result['rows'][$i]['nett_revenue']		= $row['currency_code'] . ' ' . number_format(($row['gross_revenue'] - $cost), 2);
				#$result['rows'][$i]['color'] = (($row['gross_revenue'] - $cost) < 0)?'#FF0000':'#0000FF';
				$total_gross_revenue += $row['gross_revenue'];
				$i++;
			}
			$result['rows'][$i]['server_time']	= "Grand Total";
			$result['rows'][$i]['ads_publisher_time'] = "";
			$result['rows'][$i]['name_country'] 	= "";
			$result['rows'][$i]['name_campaign_media'] = "";
			$result['rows'][$i]['name_operator'] 	= "";
			$result['rows'][$i]['name_partner'] 	= "";
			$result['rows'][$i]['name_campaign'] 	= "";
			$result['rows'][$i]['name_ads_publisher'] = "";
			//$gcurrency_code = $this->getCurrencyCode($user['id_country']);
			$result['rows'][$i]['gross_revenue']	= $row['currency_code'] . ' ' . number_format($total_gross_revenue,2);
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

		if(!empty($params['datefrom'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['datefrom']));
		}	
		else if(!empty($params['dateto'])){
			$tableTraffic = 'traffic_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'traffic'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		
		// new changes
		$qpartition='';
		if(intval(date('m', strtotime($params['datefrom'])))<6)
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
		
		$fieldList = "Date,Country,Operator,Partner,Ads Vendors,Campaign,Currency Code,Revenue";
		
		$this->dbAds->select('t.server_time, t.name_country');
		$this->dbAds->select('t.name_operator, t.name_partner, t.name_ads_publisher, t.name_campaign, t.currency_code');
		$this->dbAds->select('SUM(campaign_price) AS gross_revenue');
		#$this->dbAds->select('SUM(CASE WHEN t.is_publisher_send = 1 THEN t.campaign_cost else 0 end) AS cost');
		$this->dbAds->from($tableTraffic." $qpartition t");

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

		if(!empty($params['operator-list']))
			$this->dbAds->where('t.id_operator', $params['operator-list']);
		 if(!empty($params['country-list']))
		$this->dbAds->where('t.id_country', $params['country-list']);
		if(!empty($params['partner-list']))
			$this->dbAds->where('t.id_partner', $params['partner-list']);
		/* if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign LIKE '%".$params['search']."%'"); */
		if(!empty($params['search']))
			$this->dbAds->where("t.name_campaign", $params['search']);
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		
		$this->dbAds->where("status_campaign", "2");
		$this->dbAds->where("transaction_id_billing IS NOT NULL");
		$this->dbAds->where("delivery_status !=","REJECTED");
		$this->dbAds->order_by('t.id', 'DESC');
		
		$this->dbAds->group_by('date(t.operator_time), t.id_operator, t.id_partner, t.id_ads_publisher, t.id_campaign');
		#$this->dbAds->group_by('date(t.operator_time),t.id_campaign');
		
		$query = $this->dbAds->get();
		$result[0] = $fieldList;
		$i=1;
   	    if($query->num_rows() != 0){
		    foreach($query->result_array() as $row) {
		    	#$row['nett_revenue']		= $row['currency_code'] . ' ' . number_format(($row['gross_revenue'] - $row['cost']), 2);
		    	#$row['cost'] 			= 'USD '.number_format($row['cost'],4);
				$row['gross_revenue']	= $row['currency_code'] . ' ' . number_format($row['gross_revenue'],2);

				$result[$i] = implode(',', $row);
				$i++;
			}
	    }
	  return $result;
	}
        
        public function vpb_concurrency_converter($vpb_from_currency, $vpb_to_currency, $vpb_amount)
	{
		$vpb_set_timeout = 0;
		$vpb_amount = urlencode($vpb_amount);
		$vpb_from_currency = urlencode($vpb_from_currency);
		$vpb_to_currency = urlencode($vpb_to_currency);
		$url = "http://www.google.com/finance/converter?a=$vpb_amount&from=$vpb_from_currency&to=$vpb_to_currency";
		$vpb_init_set = curl_init();
		curl_setopt ($vpb_init_set, CURLOPT_URL, $url);
		curl_setopt ($vpb_init_set, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($vpb_init_set, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
		curl_setopt ($vpb_init_set, CURLOPT_CONNECTTIMEOUT, $vpb_set_timeout);
		$vpb_execution = curl_exec($vpb_init_set);
		curl_close($vpb_init_set);
		
		$vpb_executed_info = explode('bld>', $vpb_execution);
		$vpb_executed_info = explode($vpb_to_currency, $vpb_executed_info[1]);

		return round($vpb_executed_info[0], 2);
	}
}
?>
