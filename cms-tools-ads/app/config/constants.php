<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 * ---------------------------------------------------------------------
 * Define Variable on ACM
 * ---------------------------------------------------------------------
 */

#Set to Redis
define('KEYS_CAMPAIGN', 'C_');
define('KEYS_TEMPLATES', 'T_');
define('KEYS_WEBAPI', 'W_');
define('KEYS_BLOCKER', 'BK_');
define('KEYS_PUB_PF', 'PPF_');
define('URL_CAMPAIGN_REDIS', 'http://52.74.205.73/ads/redis_set/setCampaigns/');
define('URL_TEMPLATE_REDIS', 'http://52.74.205.73/ads/redis_set/setTemplates/');
define('URL_PF_CAMPAIGN_REDIS', 'http://52.74.205.73/ads/redis_set/setPfCampaigns/');
define('URL_CAMPAIGN_REDIS_DEL', 'http://52.74.205.73/ads/redis_set/delCampaigns/');
define('URL_TEMPLATE_REDIS_DEL', 'http://52.74.205.73/ads/redis_set/delTemplates/'); 
define('URL_WEBAPI_REDIS', 'http://52.74.205.73/ads/redis_set/setWebApi/'); 
define('URL_WEBAPI_REDIS_DEL', 'http://52.74.205.73/ads/redis_set/delWebApi/'); 
define('URL_BLOCKER_REDIS_DEL', 'http://52.74.205.73/ads/redis_set/setBlocker/'); 
define('URL_APK_REDIS', 'http://52.74.205.73/ads/redis_set/setApk/');
define('URL_APK_REDIS_DEL', 'http://52.74.205.73/ads/redis_set/delAPK/'); 

define('PATH_TEMPLATE', 'http://'.$_SERVER['HTTP_HOST'].'/ads/');
define('KEYS_GATEWAY', 'GTW_');
define('KEYS_TRIGGER', 'TRI_'); 
define('GATEWAY_PATH','/var/www/html/webapi/gateway/');
define('DELETE_MESSAGE','This action can’t be revert, Are you sure to delete?');
/**************ACM-XSIM Mapping************************************************/
define('XSIM_CLIENT_URL','http://52.74.205.73/xsimnew/ajax.php?mode=getclient');
define('XSIM_VENDER_MAPPING_ID',241);
/***********************/

/*********FLIPPY REPORT********************************/

define('FLIPPY_REPORT_ACCESS','http://blobbimobi.com/apk/log/');
define('FLIPPY_REPORT_PIXEL','http://blobbimobi.com/apk/pixel/log/');
define('KEYS_MO_API', 'MO_API_');
/* End of file constants.php */
/* Location: ./application/config/constants.php */
