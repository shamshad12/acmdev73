<?php
/* Copyright 2013 PT Media Kreasindo Utama
   Author: Imran Rosyadi <imran@zingmobile.com>

   2013-12-06: add function
     reserve_plain --> post plain parameter (no xml)
     confirm_plain --> post plain parameter (no xml)

   2014-02-13: ver.2.4
     add subscription flow
*/

define('MKU_DEFAULT_URL','http://localhost/paymentgw/tester/index-test.php');
define('MKU_DEFAULT_LOG','/usr/zingmobile/log/mkupaymentv2.6_'.date('Ym').'.log');
define('MKU_DEFAULT_LOGDIR','/usr/zingmobile/log/'.date('Ym').'/');

class mkupayment{

	var $logfile;
	var $logdir;
	var $url;
	var $webRequest; 
	var $secretKey;

	function mkupayment() {
		if($this->url == '') {
			$this->url = MKU_DEFAULT_URL;
		}
	}

	function getWebRequest() {
		return $this->webRequest;
	}

	function setWebRequest($webobj) {
		$this->webRequest = $webobj;
	}

	function setSecretKey($secretKey) {
		$this->secretKey= $secretKey;
	}

	function getSecretKey() {
		return $this->secretKey;
	}

	function showWebResponse($errorcode) {

		$params['Type'] = 'MO';
		$params['LinkId'] = $this->webRequest->LinkId;
		$params['ResponseCode']  = $errorcode;
		$params['ResponseMessage']  = $this->getErrorMsg($errorcode);
		$params['Timestamp']  = date('U');
		$params['Signature']  = $this->doSignature($params,$this->secretKey);

		return $this->MOResponse($params);
	}

	function doSignature($vars,$secretKey) {
		return md5(implode('',$vars).$secretKey);
	}

	function getErrorMsg($errorcode) {
		$respmsg['00'] = 'Transaction Success';
		$respmsg['01'] = 'Invalid Partner ID';
		$respmsg['02'] = 'Invalid Product ID';
		$respmsg['03'] = 'Invalid Signature';
		$respmsg['04'] = 'Other Error/System Error';
		return $respmsg[$errorcode];
	}

	function setLog($file) {
		$this->logfile = $file;
	}

	function getLogFile() {
		return $this->logfile;
	}

	function setPaymentUrl($url) {
		$this->url = $url;
	}

	function getPaymentUrl() {
		return $this->url;
	}

	function _constructxml($method,$params) {
		switch ($method) {
			case 'MOResponse':
				$xml = new simpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response></Response>");
				foreach($params as $k => $v) {
					$xml->addChild($k,$v);
				}

				$xml = $xml->asXML();
				break;
			case 'Reserve':
				$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				$xml.= "<Request>";
				$xml.= "<Type>".$params['Type']."</Type>";
				$xml.= "<StoreId>".$params['StoreId']."</StoreId>";
				$xml.= "<TrxId>".$params['TrxId']."</TrxId>";
				$xml.= "<LinkId>".$params['LinkId']."</LinkId>";
				$xml.= "<MSISDN>".$params['MSISDN']."</MSISDN>";
				$xml.= "<SMS>".$params['SMS']."</SMS>";
				$xml.= "<TelcoName>".$params['TelcoName']."</TelcoName>";
				$xml.= "<Shortcode>".$params['Shortcode']."</Shortcode>";
				$xml.= "<Channel>".$params['Channel']."</Channel>";
				$xml.= "<Reference>".$params['Reference']."</Reference>";
				$xml.= "<ProductId>".$params['ProductId']."</ProductId>";
				$xml.= "<Timestamp>".$params['Timestamp']."</Timestamp>";
				$xml.= "<Signature>".$params['Signature']."</Signature>";
				$xml.= "</Request>";
				break;
			case 'Confirm':
				$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				$xml.= "<Request>";
				$xml.= "<Type>CONFIRM</Type>";
				$xml.= "<StoreId>".$params['StoreId']."</StoreId>";
				$xml.= "<TrxId>".$params['TrxId']."</TrxId>";
				$xml.= "<StoreTrxId>".$params['StoreTrxId']."</StoreTrxId>";
				$xml.= "<DeliveryCode>".$params['DeliveryCode']."</DeliveryCode>";
				$xml.= "<DeliveryResponse>".$params['DeliveryResponse']."</DeliveryResponse>";
				$xml.= "<Timestamp>".$params['Timestamp']."</Timestamp>";
				$xml.= "<Signature>".$params['Signature']."</Signature>";
				$xml.= "</Request>";
				break;
			case 'Renewal':
				$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				$xml.= "<Request>";
				$xml.= "<Type>RENEWAL</Type>";
				$xml.= "<StoreId>".$params['StoreId']."</StoreId>";
				$xml.= "<TrxId>".$params['TrxId']."</TrxId>";
				$xml.= "<MSISDN>".$params['MSISDN']."</MSISDN>";
				$xml.= "<TelcoName>".$params['TelcoName']."</TelcoName>";
				$xml.= "<Shortcode>".$params['Shortcode']."</Shortcode>";
				$xml.= "<DeliveryCode>".$params['DeliveryCode']."</DeliveryCode>";
				$xml.= "<DeliveryResponse>".$params['DeliveryResponse']."</DeliveryResponse>";
				$xml.= "<Reference>".$params['Reference']."</Reference>";
				$xml.= "<ProductId>".$params['ProductId']."</ProductId>";
				$xml.= "<Timestamp>".$params['Timestamp']."</Timestamp>";
				$xml.= "<Signature>".$params['Signature']."</Signature>";
				$xml.= "</Request>";
				break;
			case 'Unsub':
				$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
				$xml.= "<Request>";
				$xml.= "<Type>UNSUB</Type>";
				$xml.= "<StoreId>".$params['StoreId']."</StoreId>";
				$xml.= "<TrxId>".$params['TrxId']."</TrxId>";
				$xml.= "<MSISDN>".$params['MSISDN']."</MSISDN>";
				$xml.= "<TelcoName>".$params['TelcoName']."</TelcoName>";
				$xml.= "<Shortcode>".$params['Shortcode']."</Shortcode>";
				$xml.= "<Reference>".$params['Reference']."</Reference>";
				$xml.= "<ProductId>".$params['ProductId']."</ProductId>";
				$xml.= "<Timestamp>".$params['Timestamp']."</Timestamp>";
				$xml.= "<Signature>".$params['Signature']."</Signature>";
				$xml.= "</Request>";
				break;
		}

		return $xml;
	}


	function reserve($params = array()) {
		$body = $this->_constructxml('Reserve',$params);
		$user = '';
		$password = '';
		$url = $this->getPaymentUrl();
		$headers = array('Content-Type' => 'text/xml');
		$response = $this->submit($url,$user,$password,$headers,$body);

		return $response;

	}

	function confirm($params = array()) {
		$body = $this->_constructxml('Confirm',$params);
		$user = '';
		$password = '';
		$url = $this->getPaymentUrl();
		//$headers = array('Content-Type' => 'application/soap+xml; charset=utf-8');
		$headers = array('Content-Type' => 'text/xml');
		$response = $this->submit($url,$user,$password,$headers,$body);

		return $response;
	}

	function renewal($params = array()) {
		$body = $this->_constructxml('Renewal',$params);
		$user = '';
		$password = '';
		$url = $this->getPaymentUrl();
		//$headers = array('Content-Type' => 'application/soap+xml; charset=utf-8');
		$headers = array('Content-Type' => 'text/xml');
		$response = $this->submit($url,$user,$password,$headers,$body);

		return $response;
	}

	function unsub($params = array()) {
		$body = $this->_constructxml('Unsub',$params);
		$user = '';
		$password = '';
		$url = $this->getPaymentUrl();
		//$headers = array('Content-Type' => 'application/soap+xml; charset=utf-8');
		$headers = array('Content-Type' => 'text/xml');
		$response = $this->submit($url,$user,$password,$headers,$body);

		return $response;
	}



	function reserve_plain($params = array()) {
		$body = http_build_query($params);
		$user = '';
		$password = '';
		$url = $this->getPaymentUrl();
		$headers = array('Content-Type' => 'text/xml');
		$response = $this->submit($url,$user,$password,$headers,$body);

		return $response;

	}

	function confirm_plain($params = array()) {
		$body = http_build_query($params);
		$user = '';
		$password = '';
		$url = $this->getPaymentUrl();
		$headers = array('Content-Type' => 'text/xml');
		$response = $this->submit($url,$user,$password,$headers,$body);

		return $response;
	}

	function MOResponse($params = array()) {
		$body = $this->_constructxml('MOResponse',$params);
		return $body;
	}

        function parseXML($response,$array=true) {

            try {
                $xmlobj = new simpleXMLElement($response);
                if(!$xmlobj) {
                        return false;
                }
                if(is_object($xmlobj)) {
                        if($array) {
                                return (array) $xmlobj->children();
                        } else {
                                return $xmlobj->children();
                        }
                }
            } catch(Exception $e) {
                return false;
            }

        }

	function submit($url,$user,$password,$headers,$body) {
		// add authorization headers
		if($user != '' || $password != '') {
			$headers['Authorization'] = 'Basic '.base64_encode("$user:$password");
		}

		// get body part
		$data = $body;

		$cpinfo = parse_url($url);
		$cphost = $cpinfo['host'];
		$protocol = ($cpinfo['scheme'] == 'https') ? 'ssl://' : '';
		$cpport = (!isset($cpinfo['port']))? ($protocol ? '443' : '80' ) : $cpinfo['port'];
		$path = $cpinfo['path'];
		if(array_key_exists('query',$cpinfo)) {
			$urlCP = "$path?".$cpinfo['query'];
		} else {
			$urlCP = $path;
		}

		$mfp    = fsockopen($protocol.$cphost,$cpport,$errno,$errstr,10);
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
			$head = $this->parse_http_header($contents[0]);

			if($head['Transfer-Encoding'] == 'chunked') {
				$content_body = $this->decode_chunked($contents[1]);
			} else {
				$content_body = $contents[1];
			}

			// log body and respond
			$logfile = $this->generateFileLog();
			$this->doRawLog("request_".$logfile,$body);
			$this->doRawLog("response_".$logfile,$content_body);

			// apps log
			$this->doLog("[URL:$urlCP] [POST:$body] [RESP:".preg_replace("/[\n\r]$/","",$content_body)."]");

			return $content_body;
		}else{
			return false;
		}
	}

	function parse_http_header($str) 

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

	function decode_chunked($str) {
		for ($res = ''; !empty($str); $str = trim($str)) {
			$pos = strpos($str, "\r\n");
			$len = hexdec(substr($str, 0, $pos));
			$res.= substr($str, $pos + 2, $len);
			$str = substr($str, $pos + 2 + $len);
		}
		return $res;
	}

	function generateFileLog() {
		return date('YmdHis')."_".mt_rand().".log";
	}

	function doRawLog($logfile,$content) {
		$logdir= $this->logdir;
		if($logdir == '') {
			$logdir = constant('MKU_DEFAULT_LOGDIR');
		}
		if (!is_dir($logdir)) mkdir($logdir);
		$logfile = $logdir."/".$logfile;
		error_log($content,3,$logfile);
	}

	function doLog($content) {
		$logfile = $this->logfile;
		if($logfile == '') {
			$logfile = constant('MKU_DEFAULT_LOG');
		}

		$content = date('Y-m-d H:i:s')." ".$content."\n";
		error_log($content,3,$logfile);
	}

}
?>
