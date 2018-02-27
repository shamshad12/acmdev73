<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class model_log extends MY_Model {

	public function _construct () {
		parent::_construct();
	}

	public function saveLog($params){
		$datetime = explode('.', $params['datetime']);
		$data = array(
						'datetime' 				=> strtotime($datetime[0]).".".$datetime[1],
						'agent_transaction_id' 	=> $params['agent_transaction_id'],
						'wallet_transaction_id' => $params['wallet_transaction_id'],
						'reference' 			=> $params['reference'],
						'msisdn'   				=> $params['msisdn'],
						'merchant_id' 			=> $params['merchant_id'],
						'telco_id'				=> $params['telco_id'],
						'wallet_id'				=> $params['wallet_id'],
						'request'				=> $params['request'],
						'response'				=> $params['response'],
						'response_status' 		=> $params['response_status'],
						'response_message' 		=> $params['response_message']
					 );
		$this->db->insert('log_current', $data);
		$this->db->insert('log_summary', $data);
		return $this->db->affected_rows();
	}
	
	public function emptyLogCurrent(){
		$sql = "DELETE FROM log_current WHERE datetime < '".strtotime(date('Y-m-d'))."'";
		return $this->db->query($sql);
	}
}
?>
