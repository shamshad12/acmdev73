<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_download_report extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loaddownload_report($params){
		$result = array();
		$user = $this->session->userdata('profile');
			
		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d'); 
			
		if(empty($params['datefrom']))
			$params['datefrom'] = date('Y-m-d');
			
		if(isset($params['datefrom']) && isset($params['dateto'])){
			$tableTraffic = 'flippy_access_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'flippy_access'.$this->tablePostFix;
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'flippy_access_', $tableTraffic);
		
	if(intval(date('m', strtotime($params['dateto'])))<6)
	{
	$searchBy='t.access_datetime';	
	}
	else 
	{	
	$searchBy='t.cdate';
	}
		
		if($params['selectdate']==2)
		{
			// echo "Select day- ".$params['selectdate'];die('a2');
			$this->dbAds->select(' COUNT(1) AS count ');
			$this->dbAds->from($tableTraffic.' t');
			
			if(!empty($params['search']))
				$this->dbAds->where('t.txn_id',$params['search']);
			if($params['status'] != '')
				$this->dbAds->where('t.status',$params['status']);
			if($params['adnetwork'] != 1)
				$this->dbAds->where("t.ad_network",$params['adnetwork']);
			$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
			
			$query = $this->dbAds->get();
			
			$result['total'] = 0;
				foreach($query->result_array() as $row)
					$result['total'] = $row['count'];
					
			$this->dbAds->select("t.txn_id, t.access_datetime, t.app_id, t.ad_network, t.phone_brand, t.phone_model, t.os, t.ip,t.user_agent, t.status",false);
			$this->dbAds->from($tableTraffic.' t');

			if(!empty($params['search']))
			$this->dbAds->where('t.txn_id',$params['search']);
			if($params['status'] != '')
				$this->dbAds->where('t.status',$params['status']);
			if($params['adnetwork'] != 1)
				$this->dbAds->where("t.ad_network",$params['adnetwork']);	
			$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
				
			$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
			
			// $this->dbAds->order_by('t.access_datetime', 'DESC');
			
			$query = $this->dbAds->get();
			// echo $this->dbAds->last_query();die('hi');
			$i=0;
			if($query->num_rows() != 0){
				$result['count'] = true;
				foreach($query->result_array() as $row) {
					if($row['status'] == 1)
					  $status = "Invalid Access";
					 else
					  $status = "Downloaded";
					$result['rows'][$i]['id']				= $row['txn_id'];
					$result['rows'][$i]['server_time']		= $row['access_datetime'];
					$result['rows'][$i]['appid']			= $row['app_id'];
					$result['rows'][$i]['adnetwork']		= $row['ad_network'];
					$result['rows'][$i]['phone_brand']		= $row['phone_brand'];
					$result['rows'][$i]['phone_model']		= $row['phone_model'];
					$result['rows'][$i]['os'] 				= $row['os'];
					$result['rows'][$i]['user_agent'] 		= $row['user_agent'];
					$result['rows'][$i]['status'] 			= $status;
					$i++;
				}
			} else {
				$result['count'] = false;
			}
		}
		else
		{
			$page_no=1;
			if($params['status']==1)
			$status = "Invalid Access";
			else
			$status = "Downloaded";
			if(isset($params['page_no']) && $params['page_no']!='')
			$page_no=$params['page_no'];
			$data = $this->getflippyAccesslog($params['adnetwork'],$page_no,500,$params['status'],0);
			$total_records=$data['total_records'];
			unset($data['total_records']);
			$result['total_records'] = $total_records;
			$result['total_pages'] = ceil($total_records/500);
			$result['next_page'] = $page_no+1;
			$result['total_found'] = count($data);
			if(!empty($data))
			{
				$result['count'] = true;
				$m003 = array();
				$l001 = array();
				$a011 = array();
				for($i=0;$i<count($data);$i++)
				{
					$exploded_data = explode(',', $data[$i]);
					$dvcstrng = $exploded_data[3].','.$exploded_data[4];
					$dvcdtl = $this->getDeviceModel($dvcstrng);
					parse_str(parse_url($exploded_data[1], PHP_URL_QUERY), $url);
					if($params['adnetwork']=='M003')
					{
						
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = $url['txn_id'];
						$m003[] = $url['txn_id'];
					}
					elseif($params['adnetwork']=='A011')
					 {
					 
					  $appid = strtoupper($url['app_id']);
					  $affid = $url['aff_id'];
					  $txnid = $url['txn_id'];
					  $a011[] = $url['txn_id'];
					 }
					elseif($params['adnetwork']=='L001')
					{
					
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = $url['txn_id'];
						$l001[] = $url['txn_id'];
					}
					elseif($params['adnetwork']=='MX')
					{
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = '';
						$status = '';
					}
					elseif($params['adnetwork']=='B003')
					{
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = '';
						$status = '';
					}
					else
					{
						$appid = '';
						$affid = '';
						$txnid = '';
						$status = '';
					}
					$result['rows'][$i]['server_time']		= $exploded_data[0];
					$result['rows'][$i]['appid']			= $appid;
					$result['rows'][$i]['adnetwork']		= $affid;
					$result['rows'][$i]['id']				= $txnid;
					$result['rows'][$i]['phone_brand']		= isset($dvcdtl['device']) && !empty($dvcdtl['device'])?$dvcdtl['device']:'';
					$result['rows'][$i]['phone_model']		= isset($dvcdtl['model']) && !empty($dvcdtl['model'])?$dvcdtl['model']:'';
					$result['rows'][$i]['os'] 				= isset($dvcdtl['os']) && !empty($dvcdtl['os'])?$dvcdtl['os']:'';
					$result['rows'][$i]['status'] 			= $status;
					$result['rows'][$i]['user_agent'] 		= $exploded_data[3];
					// echo "<pre>";
					// print_r($exploded_data);
				}
			}
			else
			{
				$result['count'] = false;
			}
			// echo "Select day- ".$params['selectdate'];die('a1');
		}
		return $result;
	}
	
	public function generate($params){
		ini_set('memory_limit','-1');
		set_time_limit(0);
		$user = $this->session->userdata('profile');
		
		if(!empty($params['datefrom'])){
			$tableTraffic = 'flippy_access_'.date('mY', strtotime($params['datefrom']));
		}	
		else if(!empty($params['dateto'])){
			$tableTraffic = 'flippy_access_'.date('mY', strtotime($params['dateto']));
		} else 
			$tableTraffic = 'flippy_access'.$this->tablePostFix;
		
			if(date('m', strtotime($params['dateto']))<6)
			{
			$searchBy='t.access_datetime';	
			}
			else 
			{	
			$searchBy='t.cdate';
			}
					
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'flippy_access_', $tableTraffic);
		
		$fieldList = "Time,TransactionID,App ID,Ad Network,Phone Brand,Phone Model,OS,Status";
		$result = array();
		$result[0] = $fieldList;
		
		if($params['selectdate']==2)
		{
			// echo "Select day- ".$params['selectdate'];die('a2');
			$this->dbAds->select("t.access_datetime, t.txn_id, t.app_id, t.ad_network, t.phone_brand, t.phone_model, t.os, t.status",false);
			$this->dbAds->from($tableTraffic.' t');

			if(!empty($params['search']))
			$this->dbAds->where('t.txn_id',$params['search']);
			if($params['status'] != '')
				$this->dbAds->where('t.status',$params['status']);
			if($params['adnetwork'] != 1)
				$this->dbAds->where("t.ad_network",$params['adnetwork']);
			$this->dbAds->where("$searchBy BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
			
			// $this->dbAds->order_by('t.access_datetime', 'DESC');
			
			$query = $this->dbAds->get();
			$i=1;
	   	    if($query->num_rows() != 0){
			    foreach($query->result_array() as $row) {
					if($row['status'] == 1)
					  $status = "Invalid Access";
					 else
					  $status = "Downloaded";
					$row['txn_id'] 				= '="'.$row['txn_id'].'"';
					$row['status'] 			= $status;
					$result[$i] = implode(',', $row);
					$i++;
				}
		    }
		}
		else
		{
			
			$data = $this->getflippyAccesslog($params['adnetwork'],1,0,$params['status'],1);
			$total_records=$data['total_records'];
			unset($data['total_records']);
			if(!empty($data))
			{
				$m003 = array();
				$l001 = array();
				$a011 = array();
				for($i=0;$i<count($data);$i++)
				{
					$exploded_data = explode(',', $data[$i]);
					$dvcstrng = $exploded_data[3].','.$exploded_data[4];
					$dvcdtl = $this->getDeviceModel($dvcstrng);
					parse_str(parse_url($exploded_data[1], PHP_URL_QUERY), $url);
					if($params['adnetwork']=='M003')
					{
						if(in_array($url['txn_id'], $m003))
							$status = "Invalid Access";
						else
							$status = "Downloaded";
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = $url['txn_id'];
						$m003[] = $url['txn_id'];
					}
					elseif($params['adnetwork']=='A011')
					 {
					  if(in_array($url['txn_id'], $a011))
					   $status = "Invalid Access";
					  else
					   $status = "Downloaded";
					  $appid = strtoupper($url['app_id']);
					  $affid = $url['aff_id'];
					  $txnid = $url['txn_id'];
					  $a011[] = $url['txn_id'];
					 }
					elseif($params['adnetwork']=='L001')
					{
						if(in_array($url['txn_id'], $l001))
							$status = "Invalid Access";
						else
							$status = "Downloaded";
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = $url['txn_id'];
						$l001[] = $url['txn_id'];
					}
					elseif($params['adnetwork']=='MX')
					{
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = '';
						$status = "";
					}
					elseif($params['adnetwork']=='B003')
					{
						$appid = strtoupper($url['app_id']);
						$affid = $url['aff_id'];
						$txnid = '';
						$status = "";
					}
					else
					{
						$appid = '';
						$affid = '';
						$txnid = '';
						$status = "Downloaded";
					}
					$row['server_time']		= $exploded_data[0];
					$row['id'] 				= '="'.$txnid.'"';
					$row['appid']			= $appid;
					$row['adnetwork']		= $affid;
					$row['phone_brand']		= isset($dvcdtl['device']) && !empty($dvcdtl['device'])?$dvcdtl['device']:'';
					$row['phone_model']		= isset($dvcdtl['model']) && !empty($dvcdtl['model'])?$dvcdtl['model']:'';
					$row['os']				= isset($dvcdtl['os']) && !empty($dvcdtl['os'])?$dvcdtl['os']:'';
					$row['status'] 			= $status;
					$result[$i+1] = implode(',', $row);
				}
			}
		}
	  	return $result;
	}

	
}
?>
