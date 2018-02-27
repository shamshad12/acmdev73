<?php
//http://venus.getmore2u.com/api/a84hSr/sales?apikey=Ief234248d9PibxsvnpAwE6tjAZDvAIf1t6rWatgLr3Ujr45R0yTJFt2&username=mku&telco=my_umobile&startdate=2015-08-06&enddate=2015-08-25&shortcode=32266&channel=sms&sales_type=charging_id&partner=acm&exclude_zero_charge=0
$data = array(
  "apikey" =>"Ief234248d9PibxsvnpAwE6tjAZDvAIf1t6rWatgLr3Ujr45R0yTJFt2",
  "username" =>"mku",
  "telco" =>"my_umobile",
  "startdate" => "2015-08-06",
  "enddate" => "2015-08-06",
  "shortcode" => "32266",
  "channel" => "sms",
  "sales_type" => "charging_id",
  "partner" => "acm",
  "exclude_zero_charge" => "0"
);
$url_send ="http://venus.getmore2u.com/api/a84hSr/sales";
//$str_data = json_encode($data);
//print_r($str_data); die;
//print $url_send.'?'.http_build_query($data);
print_r(file_get_contents($url_send.'?'.http_build_query($data)));

function sendPostData($url, $post){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
  
}

//echo " " . sendPostData($url_send, $str_data);
