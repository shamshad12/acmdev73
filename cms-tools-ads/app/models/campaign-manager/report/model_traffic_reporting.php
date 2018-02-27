<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_traffic_reporting extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadTraffic_Reporting($params){
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
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'traffic_', $tableTraffic);
		
		// new changes
		
		$qpartition='';
		if(date('m', strtotime($params['datefrom']))<6)
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
		$this->dbAds->from($tableTraffic.' t');
		if($user['tipe_user_id'] == 11)
		{
	
	if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}
	
	if(count($partner_permissions->shortcode))
			{
				$this->dbAds->where_in('t.shortcode', $partner_permissions->shortcode);
			}
			if(count($partner_permissions->keyword))
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
		if(!empty($params['filter-list']))
			$this->dbAds->where('t.status_campaign', $params['filter-list']);
		if(!empty($params['search']))
		{
			//$this->dbAds->where("t.name_campaign", $params['search']);
			//$this->dbAds->or_where("t.msisdn_detection", $params['search']);
			//$this->dbAds->or_where("t.id", $params['search']);
			$this->dbAds->where("(t.id ='". $params['search']."' OR t.msisdn_detection='".$params['search']."' OR t.name_campaign='".$params['search']."')");
		}
		//$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		
		$query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die;
		
		$result['total'] = 0;
			foreach($query->result_array() as $row)
				$result['total'] = $row['count'];
				
		$this->dbAds->select("t.id, t.operator_time as server_time, t.operator_time, t.partner_time, t.ads_publisher_time, t.name_country, t.transaction_id_billing",false);
		$this->dbAds->select('t.name_campaign_media, t.name_operator, t.name_partner, t.name_campaign, t.name_ads_publisher');
		$this->dbAds->select('t.msisdn_detection, t.msisdn_charging, t.shortcode, t.sid, t.keyword, t.content, t.status_campaign,t.delivery_status');
		$this->dbAds->from($tableTraffic.' t');

		if($user['tipe_user_id'] == 11)
		{
		if(!empty($partner_permissions->partner_ids))
			{
				$this->dbAds->where_in('t.id_partner', $partner_permissions->partner_ids);
			}

			if(count($partner_permissions->shortcode))
			{
				$this->dbAds->where_in('t.shortcode', $partner_permissions->shortcode);
			}
			if(count($partner_permissions->keyword))
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
		if(!empty($params['filter-list']))
			$this->dbAds->where('t.status_campaign', $params['filter-list']);
		/*if(!empty($params['search']))
			$this->dbAds->where("(t.name_campaign LIKE '%".$params['search']."%' OR t.shortcode LIKE '%".$params['search']."%' OR t.sid LIKE '%".$params['search']."%' OR t.id LIKE '%".$params['search']."%' OR t.msisdn_detection LIKE '%".$params['search']."%' OR t.keyword LIKE '%".$params['search']."%')");*/
		if(!empty($params['search']))
		{
			//$this->dbAds->where("t.name_campaign", $params['search']);
		    //$this->dbAds->or_where("t.msisdn_detection", $params['search']);
			//$this->dbAds->or_where("t.id", $params['search']);
			$this->dbAds->where("(t.id ='". $params['search']."' OR t.msisdn_detection='".$params['search']."' OR t.name_campaign='".$params['search']."')");
		}
			
		//$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
			
		$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
		
		//$this->dbAds->order_by('t.id', 'DESC');
		
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
				if(empty($row['transaction_id_billing']) || $row['delivery_status']=='REJECTED')
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
		$user = $this->session->userdata('profile');
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
		
		// new changes
		$qpartition='';
		if(date('m', strtotime($params['datefrom']))<6)
		{
		$searchBy='t.server_time';	
		}
		else 
		{	
		$searchBy='t.ads_publisher_time';
		$ispartition=$this->getPartition($params['datefrom'],$params['dateto']);
		if($ispartition)
			$qpartition='PARTITION('.$ispartition.')';
		}
		//print $qpartition; die;
		//
		
		//$fieldList = "Server Time,TransactionID,Country,TransactionID Billing,Operator,Partner,Ads Vendors,Media,Campaign,MSISDN,SDC,SID,Keyword,Status";
		$fieldList = "Server Time,TransactionID,Country,TransactionID Billing,Operator,Partner,Ads Vendors,Media,Campaign,MSISDN,SDC,SID,Keyword,Status,Referer";
		
		$this->dbAds->select('t.server_time, t.id, t.name_country, t.transaction_id_billing');
		$this->dbAds->select('t.name_operator, t.name_partner, t.name_ads_publisher, t.name_campaign_media, t.name_campaign');
		$this->dbAds->select('t.msisdn_detection, t.shortcode, t.sid, t.keyword, t.status_campaign,t.referer,t.delivery_status');
		$this->dbAds->from($tableTraffic.' t');
		
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
		if(!empty($params['filter-list']))
			$this->dbAds->where('t.status_campaign', $params['filter-list']);
		if(!empty($params['search']))
		{
			$this->dbAds->where("t.name_campaign", $params['search']);
		    $this->dbAds->or_where("t.id", $params['search']);
		}
			
		//$this->dbAds->where("DATE(t.server_time) BETWEEN '".$params['datefrom']."' AND '".$params['dateto']."'");
		$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
		
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

				if(empty($row['transaction_id_billing']) || $row['delivery_status']=='REJECTED')
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
				unset($row['delivery_status']);
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
		$this->dbAds->select('t.transaction_id_publisher, t.transaction_id_billing, t.server_time, cu.code as country_code, t.name_campaign, t.campaign_code, t.http_host, t.request_uri, t.msisdn_detection, t.referer,t.campaign_cost');
		$this->dbAds->select('t.name_partner, t.name_ads_publisher, t.id_ads_publisher, t.name_operator,t.shortcode,t.keyword,t.name_country,t.is_publisher_send,ap.code as pubcode,ap.ads_type as ads_type,ap.loading_url,ap.updation_url,ap.url_confirm,t.affiliate_params,t.pixel_response');
		$this->dbAds->from('traffic'.$tableTraffic.' t');
		$this->dbAds->join('ads_publishers ap', 't.id_ads_publisher = ap.id');
		$this->dbAds->join('countries cu', 't.id_country = cu.id');
		$this->dbAds->where('t.id', $this->dbAds->escape_str($params['id']));
		$query = $this->dbAds->get();

		$result = array();
		$i=0;
		if($query->num_rows() != 0){
			$result['count'] = true;
			foreach($query->result_array() as $row) {
				$result['transaction_id_publisher'] = $row['transaction_id_publisher'];
				$result['transaction_id_billing'] = $row['transaction_id_billing'];
				$result['server_time'] = $row['server_time'];
				$result['country_code'] = $row['country_code'];
				$result['name_campaign'] = $row['name_campaign'];
				$result['campaign_code'] = $row['campaign_code'];
				$result['http_host'] = $row['http_host'];
				$result['request_uri'] = $row['request_uri'];
				$result['msisdn_detection'] = $row['msisdn_detection'];
				$result['referer'] = $row['referer'];
				$result['name_partner'] = $row['name_partner'];
				#$result['code_acm'] = $row['code_acm'];
				$result['name_ads_publisher'] = $row['name_ads_publisher'];
				$result['pubcode'] = $row['pubcode'];
				$result['ads_type'] = $row['ads_type'];
				$result['name_operator'] = $row['name_operator'];
				$result['shortcode'] = $row['shortcode'];
				$result['keyword'] = $row['keyword'];
				$result['name_country'] = $row['name_country'];
				$result['affiliate_params'] = json_decode($row['affiliate_params'], true);
				$result['is_publisher_send']= ($row['is_publisher_send']==1)?'Sent':'Not Sent';
				$replaceParams = array();
               	foreach ($result['affiliate_params'] as $key => $val)
                    $replaceParams['{' . $key . '}'] = $val;
                $adsPublisherParams = $this->replaceTemplate($replaceParams, $row['url_confirm']);
				
				$result['url_confirm']= str_replace('{ACM-TRANSACTIONID}',$params['id'],$adsPublisherParams);
				$result['url_confirm']= str_replace('{COST-AMOUNT}',$row['campaign_cost'],$result['url_confirm']);
				
				$result['url_confirm']= str_replace('{shortcode}',$row['shortcode'],$result['url_confirm']);
				$result['url_confirm']= str_replace('{service}',$row['keyword'],$result['url_confirm']);
				$result['url_confirm']= str_replace('{keyword}',$row['keyword'],$result['url_confirm']);
				$result['url_confirm']= str_replace('{moid}',$row['transaction_id_billing'],$result['url_confirm']);
				$result['url_confirm']= str_replace('{country}',strtolower($row['country_code']),$result['url_confirm']);
				$result['url_confirm']= str_replace('{telco}',strtolower($row['country_code']).'_'.strtolower($row['name_operator']),$result['url_confirm']);
				if($row['id_ads_publisher']==273){
				$result['url_confirm']= str_replace('vn_vinaphone','31',$result['url_confirm']);
				$result['url_confirm']= str_replace('vn_mobifone','32',$result['url_confirm']);
				$result['url_confirm']= str_replace('vn_vietnamobile','33',$result['url_confirm']);
				}
				$result['url_confirm']= str_replace('{datetime}',str_replace(' ', '', $row['server_time']),$result['url_confirm']);
				$result['url_confirm']= str_replace('{msisdn}',$row['msisdn_detection'],$result['url_confirm']);
				
				if($row['transaction_id_publisher'])
				{
					//view A004
					$result['loading_url'] = str_replace('{txn_id}', $result['transaction_id_publisher'], $row['loading_url']);
					//Lead A004
					$result['updation_url'] = str_replace('{txn_id}', $result['transaction_id_publisher'], $row['updation_url']);
					
					#$row['url_confirm'] = str_replace('{txn_id}', $result['transaction_id_publisher'], $row['url_confirm']);
					#$row['url_confirm'] = str_replace('{pub_id}', $result['affiliate_params']['pub_id'], $row['url_confirm']);
					#$row['url_confirm'] = str_replace('{subid2}', $result['affiliate_params']['subid2'], $row['url_confirm']);
				}
				else
				{
					$result['loading_url'] = "";
					//Lead A004
					$result['updation_url'] = "";
				}
				#$result['url_confirm']= $row['url_confirm'];
				$result['pixel_response']= $row['pixel_response'];
				
				$i++;
			}
		}
			
		return $result;
	}
	
	protected function replaceTemplate($params, $template){
		$result = $template;
		foreach($params as $key => $val){
			$result = str_replace($key, $val, $result);
		}
		
		return $result;
	}

	public function resendPubreq($params)
	{
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

		$res = $this->geturl($params['url_val']);
		
		$this->SaveLogdata('[URL:'.$params['url_val'].'][Result:'.$res.']','ResendPixel');
		$update = array(
						'pixel_response' => $this->dbAds->escape_str($res)
					   );
		$this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
		$this->dbAds->update($tableTraffic, $update);
		$result = array();
		$result['curlresult'] = $res;
		if($this->dbAds->affected_rows()){
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}
		return $result;
		
	}
	public function geturl($url, $method = 'get', $header = null, $postdata = null, $new = false, $timeout = 15) {
        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $url);

        if ($header) {
            curl_setopt($s, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($s, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($s, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($s, CURLOPT_MAXREDIRS, 5);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);

        if (strtolower($method) == 'post') {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
        } else if (strtolower($method) == 'delete') {
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else if (strtolower($method) == 'put') {
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($s, CURLOPT_POSTFIELDS, $postdata);
        }

        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($s);

        curl_close($s);
        return $html;
    }

	
}
?>
