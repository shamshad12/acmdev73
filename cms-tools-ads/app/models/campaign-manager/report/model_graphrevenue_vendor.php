<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_graphrevenue_vendor extends My_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		// $this->tablePostFix = '_'.date('mY');
	}
	
	public function loadgraphrevenue_vendor($params){
		$result = array();
		$data = array();
		$user = $this->session->userdata('profile');
		if($user['tipe_user_id'] == 11)
		{
			$partner_permissions = $this->session->userdata('partner_permissions');
			if(empty($partner_permissions))
				return $result['count'] = false;
			else
				$partner_permissions = json_decode($partner_permissions);
		}
/*
		if(empty($params['country-list']))
		{
			if($user['id_country'] > 0)
				$params['country-list'] = $user['id_country'];
		}*/
	
		for($i=1;$i<=date('n');$i++)
		{
			$tableTraffic = '';
			if($i<10)
		   	{
		   		$tableTraffic = 'traffic_0'.$i.date('Y');
		  	}
		  	else
		  	{
		    	$tableTraffic = 'traffic_'.$i.date('Y');
		  	}

		  	$this->dbAds->select('pub.name, cur.code');
		  	$this->dbAds->from('ads_publishers pub');
		  	$this->dbAds->join('currencies cur', 'pub.id_country = cur.id_country');
		  	$this->dbAds->where('pub.id', $params['ads-publisher-list']);
		  	$query = $this->dbAds->get();
		  	if($query->num_rows() != 0){
				foreach($query->result_array() as $row) {
					$result['publisher'] = $row['name'];
					$result['currency_code'] = $row['code'];
				}
			}

			$this->dbAds->select('`t`.`id`,  `t`.`name_ads_publisher`, `t`.`currency_code`, SUM(`t`.`campaign_cost`) AS cost, SUM(`t`.`campaign_price`) AS campaign_price');
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

			if(!empty($params['ads-publisher-list']))
				$this->dbAds->where('t.id_ads_publisher', $params['ads-publisher-list']);
			if(!empty($params['partner-list']))
				$this->dbAds->where('t.id_partner', $params['partner-list']);
			
			$this->dbAds->where("status_campaign", "2");
			$this->dbAds->where("transaction_id_billing IS NOT NULL");
			$this->dbAds->where("delivery_status !=","REJECTED");
			$this->dbAds->order_by('t.id', 'DESC');
			
			$this->dbAds->group_by('t.id_ads_publisher, t.id_partner');
			
			$query = $this->dbAds->get();
			// echo $this->dbAds->last_query();die('test');

			if($query->num_rows() != 0){
				$result['count'] = true;
				foreach($query->result_array() as $row) {
					if($row['currency_code']=='RM')
					{
						$row['currency_code'] = 'MYR';
					}
					else{
						$row['currency_code'] = $row['currency_code'];
					}
					$data['cost'] = 'USD '.number_format($row['cost'],4);
	                $cost = $this->vpb_concurrency_converter('USD', $row['currency_code'], $row['cost']); 
					// $data['nett_revenue']	= $row['currency_code'] . ' ' . number_format(($row['campaign_price'] - $cost), 2);
					$data['nett_revenue']	= number_format(($row['campaign_price']), 2);
					if( is_numeric(str_replace(",", "", $data['nett_revenue'])) ) {
					    $data['nett_revenue'] = str_replace(",", "", $data['nett_revenue']);
					}
					$result['revenue'][$i] = $data['nett_revenue'];
				}
			} else {
				$result['count'] = true;
				$result['revenue'][$i] = 0;
			}


		}	
		/*echo "<pre>";
		print_r($result);
		die('aa');*/
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
