<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

#Ads Campaign
$db['ads']['hostname'] = 'acm2db.c8t8vb3tn114.ap-southeast-1.rds.amazonaws.com';
$db['ads']['username'] = 'acm2dbmaster';
$db['ads']['password'] = 'acm2zing2016db'; 
// $db['ads']['database'] = 'campaign_acm2staging';
$db['ads']['database'] = 'campaign_jul17';
$db['ads']['dbdriver'] = 'mysql';
$db['ads']['dbprefix'] = '';
$db['ads']['pconnect'] = FALSE;
$db['ads']['db_debug'] = TRUE;
$db['ads']['cache_on'] = FALSE;
$db['ads']['cachedir'] = '/tmp/';
$db['ads']['char_set'] = 'utf8';
$db['ads']['dbcollat'] = 'utf8_general_ci';
$db['ads']['swap_pre'] = '';
$db['ads']['autoinit'] = TRUE;
$db['ads']['stricton'] = FALSE;

$active_group = 'cms';
$active_record = TRUE;
/*$db['cms']['hostname'] = 'smsgw-9am.c8t8vb3tn114.ap-southeast-1.rds.amazonaws.com';
$db['cms']['username'] = 'camp_user';
$db['cms']['password'] = 'De^2bT//';*/
$db['cms']['hostname'] = 'acm2db.c8t8vb3tn114.ap-southeast-1.rds.amazonaws.com';
$db['cms']['username'] = 'acm2dbmaster';
$db['cms']['password'] = 'acm2zing2016db';  
// $db['cms']['database'] = 'campaign_cms_acm2staging';
$db['cms']['database'] = 'campaign_cms_jul17';
$db['cms']['dbdriver'] = 'mysql';
$db['cms']['dbprefix'] = '';
$db['cms']['pconnect'] = FALSE;
$db['cms']['db_debug'] = TRUE;
$db['cms']['cache_on'] = FALSE;
$db['cms']['cachedir'] = '';
$db['cms']['char_set'] = 'utf8';
$db['cms']['dbcollat'] = 'utf8_general_ci';
$db['cms']['swap_pre'] = '';
$db['cms']['autoinit'] = TRUE;
$db['cms']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
