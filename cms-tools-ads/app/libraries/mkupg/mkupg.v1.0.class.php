<?php

/**
*
* Class PaymentGateway
* ver 1.0 
* support API MKU Payment Gateway v2.3
*
* Copyright 2013 PT Media Kreasindo Utama
*
**/
 
class PaymentGateway 
{
    public $secret_key = 'nokey';
    public $store_id = 'noid';
    public $zm_payment_url = '';
    public $valid_signature = FALSE;
    private $request = array();

    public function request($params) {
	$ardata = $this->_parseXML($params);
        $this->request = $ardata;
        $this->valid_signature = $this->_validSignature($ardata);
        return $ardata;
    }

    public function response($params=array()) {
        $ardata['Type'] = $this->request['Type'];
        $ardata['StoreId'] = $this->request['StoreId'];
        $ardata['TrxId'] = $this->request['TrxId'];
        $ardata['StoreTrxId'] = $params['StoreTrxId'];
// tambahan parameter price, partner bisa set sendiri
        $ardata['Price'] = $params['Price'];
// --
        $ardata['ResponseCode'] = ($this->valid_signature) ? $params['ResponseCode'] : '03';
        $ardata['ResponseMessage'] = ($this->valid_signature)? $params['ResponseMessage'] : 'Invalid signature';
        if($this->request['Type']=='CONFIRM'){
           $ardata['SN'] = $params['SN'];
           $ardata['PIN'] = $params['PIN'];
        }
        $ardata['Timestamp'] = date("U");

	return $this->_constructXML($ardata);
    }

    public function web_request($params = array()) {
	
	$ardata = array(
		'Type' => 'MO',
		'Channel' => 'WEB',
		'StoreId' => $this->store_id,
		'LinkId' => $params['LinkId'],
		'MSISDN' => $params['MSISDN'],
		'TelcoName' => $params['TelcoName'],
		'Reference' => $params['Reference'],
		'ProductId' => $params['ProductId'],
		'Timestamp' => date('U')
		);

        $body = $this->_constructXML($ardata);
        $response = $this->_doSubmit($this->zm_payment_url,$body);
#print $response;
        return $this->_parseXML($response);
    }

    private function _parseXML($ardata=''){
	$arr = array();
        $xml = simplexml_load_string($ardata);
        foreach($xml as $k=>$v) $arr[$k]=(string)$v;
	return $arr; 
    }


    private function _validSignature($ardata = array()) {
	$sign = $ardata['Signature'];
        unset($ardata['Signature']);
        if($sign == md5(implode('',$ardata).$this->secret_key)) return TRUE;
        else return FALSE;
    }

    private function _doSignature($ardata = array()) {
        return md5(implode('',$ardata).$this->secret_key);
    }

    private function _constructXML($ardata = array())
    {
        $xml = new simpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response></Response>");
        foreach($ardata as $k=>$v) $xml->addChild($k,$v);
        $xml->addChild('Signature',$this->_doSignature($ardata));
        $xml = $xml->asXML();
	return $xml;
 
    }

    private function _doSubmit($url,$body) {
        $headers = array('Content-Type' => 'text/xml');
	$data = $body;

	$cpinfo = parse_url($url);
	$cphost = $cpinfo['host'];
	$cpport = (!isset($cpinfo['port']))? '80' : $cpinfo['port'];
	$protocol = ($cpinfo['scheme'] == 'https') ? 'ssl://' : '';
	$path = $cpinfo['path'];
	if(array_key_exists('query',$cpinfo)) {
		$urlCP = "$path?".$cpinfo['query'];
	} else {
		$urlCP = $path;
	}

	$mfp    = fsockopen($cphost,$cpport,$errno,$errstr,10);
	if($mfp){

		$out  = "POST $urlCP HTTP/1.1\r\n";
		$out .= "Host: $cphost\r\n";
		$out .= "Content-length: " . strlen($data) . "\r\n";
		foreach($headers as $hkey => $hval) {
			$out .= "$hkey: $hval\r\n";
		}
		$out .= "User-Agent: Java/1.4.2_08\r\n";
		$out .= "Connection: close\r\n";
		$out .= "\r\n";
		$out .= $data;
		#print $out."\n";

		$buffer = '';
		fwrite($mfp, $out);

		while(!feof($mfp)) {
			$buffer.= fread($mfp, 4096);
		}
		//socket_set_timeout($mfp, 2);

		fclose($mfp);
		$contents = preg_split("/\r\n\r\n/",$buffer);
		$head = $this->_parse_http_header($contents[0]);

		if(isset($head['Transfer-Encoding'])) {
		   if($head['Transfer-Encoding'] == 'chunked')
			$content_body = $this->_decode_chunked($contents[1]);
		   else 
			$content_body = $contents[1];
		} 
		else
		   $content_body = $contents[1];
		

		return $content_body;
	}else{
		return false;
	}
    }

    private function _parse_http_header($str) 
    {
	$lines = explode("\r\n", $str);
	$head  = array(array_shift($lines));
	foreach ($lines as $line) {
		list($key, $val) = explode(':', $line, 2);
		if ($key == 'Set-Cookie') {
			$head['Set-Cookie'][] = trim($val);
		} else {
			$head[$key] = trim($val);
		}
	}
	return $head;
    }

    private function _decode_chunked($str) {
	for ($res = ''; !empty($str); $str = trim($str)) {
		$pos = strpos($str, "\r\n");
		$len = hexdec(substr($str, 0, $pos));
		$res.= substr($str, $pos + 2, $len);
		$str = substr($str, $pos + 2 + $len);
	}
	return $res;
    }

}

?>
