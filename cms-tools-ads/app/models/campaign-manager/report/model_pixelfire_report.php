<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_pixelfire_report extends MY_Model {

	private $tablePostFix;
	
	public function __construct () {
		parent::__construct();
		
		$this->loadDbAds();
		
		$this->tablePostFix = '_'.date('mY');
	}
	
	public function loadpixelfire_report($params){
		$result = array();
		$user = $this->session->userdata('profile');
			
		if(empty($params['dateto']))
			$params['dateto'] = date('Y-m-d'); 
			
		if(empty($params['datefrom']))
			$params['datefrom'] = date('Y-m-d');
			
		if(isset($params['datefrom']) && isset($params['dateto'])){
			$tableTraffic = 'flippy_pixel_'.date('mY', strtotime($params['dateto']));
			$flippyaccess = 'flippy_access_'.date('mY', strtotime($params['dateto']));
		} else {
			$tableTraffic = 'flippy_pixel'.$this->tablePostFix;
			$flippyaccess = 'flippy_access'.$this->tablePostFix;
		}
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'flippy_pixel_', $tableTraffic);
		
		if($params['selectdate']==2)
		{
			// echo "Select day- ".$params['selectdate'];die('a2');
			$this->dbAds->select('distinct t.txn_id AS count,`t`.`pixel_datetime`,t.pixel_response',false);
			$this->dbAds->from($tableTraffic.' t');
			$this->dbAds->join($flippyaccess.' f', 't.txn_id = f.txn_id', 'LEFT');
			
			if(!empty($params['search']))
				$this->dbAds->where("t.txn_id",$params['search']);
			$this->dbAds->where("t.txn_id <>",'N/A');
			if($params['adnetwork'] != 1)
			$this->dbAds->where("f.ad_network",$params['adnetwork']);
			$this->dbAds->where("t.pixel_datetime BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
			
			$query = $this->dbAds->get();
			
			$result['total'] = $query->num_rows();
				// foreach($query->result_array() as $row)
					// $result['total'] = $row['count'];
					
			$this->dbAds->select("distinct t.txn_id, f.app_id, f.ad_network, t.pixel_datetime, t.pixel_response",false);
			$this->dbAds->from($tableTraffic.' t');
			$this->dbAds->join($flippyaccess.' f', 't.txn_id = f.txn_id', 'LEFT');
			if(!empty($params['search']))
				$this->dbAds->where("t.txn_id",$params['search']);
				$this->dbAds->where("t.txn_id <>",'N/A');
			if($params['adnetwork'] != 1)
			$this->dbAds->where("f.ad_network",$params['adnetwork']);	
			$this->dbAds->where("t.pixel_datetime BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
				
			$this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
			
			// $this->dbAds->order_by('t.id', 'DESC');
			
			$query = $this->dbAds->get();
			//echo $this->dbAds->last_query();die('hi');
			$i=0;
			if($query->num_rows() != 0){
				$result['count'] = true;
				foreach($query->result_array() as $row) {
					$result['rows'][$i]['server_time']		= $row['pixel_datetime'];
					$result['rows'][$i]['appid']			= $row['app_id'];
					$result['rows'][$i]['adnetwork']		= $row['ad_network'];
					$result['rows'][$i]['id']				= $row['txn_id'];
					$result['rows'][$i]['firepixelclckid']	= $row['txn_id'];
					$result['rows'][$i]['reponse'] 			= $row['pixel_response'];
					$i++;
				}
			} else {
				$result['count'] = false;
			}
		}
		else
		{
			$data = $this->getflippyPixellog($params['adnetwork']);
			$result['total'] = count($data);
			if(!empty($data))
			{
				$result['count'] = true;
				for($i=0;$i<count($data);$i++)
				{
					$exploded_data = explode(',', $data[$i]);
					parse_str(parse_url($exploded_data[2], PHP_URL_QUERY), $url);
					if($params['adnetwork']=='M003')
					{
						$response = $exploded_data[3].','.$exploded_data[4];
						$txnid = $url['mobvista_clickid'];
					}
					elseif($params['adnetwork']=='L001')
					{
						$response = $exploded_data[3];
						$txnid = $url['tid'];
					}
					elseif($params['adnetwork']=='A011')
					 {
					  $response = $exploded_data[3].','.$exploded_data[4].','.$exploded_data[5];
					  $txnid = $url['tid'];
					 }
					else
					{
						$response = '';
						$txnid = '';
					}
					$result['rows'][$i]['server_time']		= $exploded_data[0];
					$result['rows'][$i]['appid']			= strtoupper($exploded_data[1]);
					$result['rows'][$i]['adnetwork']		= $params['adnetwork'];
					$result['rows'][$i]['id']				= $txnid;
					$result['rows'][$i]['firepixelclckid']	= $txnid;
					$result['rows'][$i]['reponse'] 			= $response;
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
		$user = $this->session->userdata('profile');
		
		if(!empty($params['datefrom'])){
			$tableTraffic = 'flippy_pixel_'.date('mY', strtotime($params['datefrom']));
			$flippyaccess = 'flippy_access_'.date('mY', strtotime($params['datefrom']));
		}	
		else if(!empty($params['dateto'])){
			$tableTraffic = 'flippy_pixel_'.date('mY', strtotime($params['dateto']));
			$flippyaccess = 'flippy_access_'.date('mY', strtotime($params['dateto']));
		} else {
			$tableTraffic = 'flippy_pixel'.$this->tablePostFix;
			$flippyaccess = 'flippy_access'.$this->tablePostFix;
		}
			
		if(!$this->dbAds->table_exists($tableTraffic))
			$this->createTable($this->dbAds, 'flippy_pixel_', $tableTraffic);
		
		$fieldList = "TransactionID,App ID,Ad Network,Fire Pixel Click ID,Response,Time";
		$result = array();
		$result[0] = $fieldList;
		
		if($params['selectdate']==2)
		{
			// echo "Select day- ".$params['selectdate'];die('a2');
			$this->dbAds->select('distinct(t.txn_id) as txnid, f.app_id, f.ad_network,t.txn_id as clickid, t.pixel_response, t.pixel_datetime');
			$this->dbAds->from($tableTraffic.' t');
			$this->dbAds->join($flippyaccess.' f', 't.txn_id = f.txn_id', 'LEFT');
			if(!empty($params['search']))
			$this->dbAds->where("t.txn_id",$params['search']);		
			$this->dbAds->where("t.txn_id <>",'N/A');
			if($params['adnetwork'] != 1)
			$this->dbAds->where("f.ad_network",$params['adnetwork']);
			$this->dbAds->where("t.pixel_datetime BETWEEN '".$params['datefrom']." 00:00:00' AND '".$params['dateto']." 23:59:59'");
			
			// $this->dbAds->order_by('t.id', 'DESC');
			
			$query = $this->dbAds->get();
			$i=1;
	   	    if($query->num_rows() != 0){
			    foreach($query->result_array() as $row) {
					
					$row['txnid'] = '="'.$row['txnid'].'"';
					$row['clickid'] = '="'.$row['clickid'].'"';
					$row['pixel_response'] = str_replace(',',' ',$row['pixel_response']);
					$result[$i] = implode(',', $row);
					$i++;
				}
		    }
		}
		else
		{
			$data = $this->getflippyPixellog($params['adnetwork']);
			if(!empty($data))
			{
				for($i=0;$i<count($data);$i++)
				{
					$exploded_data = explode(',', $data[$i]);
					parse_str(parse_url($exploded_data[2], PHP_URL_QUERY), $url);
					if($params['adnetwork']=='M003')
					{
						$response = $exploded_data[3].'  '.$exploded_data[4];
						$txnid = $url['mobvista_clickid'];
					}
					elseif($params['adnetwork']=='L001')
					{
						$response = $exploded_data[3];
						$txnid = $url['tid'];
					}
					elseif($params['adnetwork']=='A011')
					{
						$response = $exploded_data[3].','.$exploded_data[4].','.$exploded_data[5];
					$txnid = $url['tid'];
					}
					else
					{
						$response = '';
						$txnid = '';
					}
					$row['server_time']		= $exploded_data[0];
					$row['appid']			= strtoupper($exploded_data[1]);
					$row['adnetwork']		= $params['adnetwork'];
					$row['id'] = '="'.$txnid.'"';
					$row['firepixelclckid']	='="'.$txnid.'"';
					$row['reponse'] 			= $response;
					$result[$i+1] = implode(',', $row);
				}
			}
		}
	  	return $result;
	}

		
}
?>
