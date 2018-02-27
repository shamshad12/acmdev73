<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_Model
{
	protected $data;
	protected $profile;

	protected $tablePrivilegesMenu 	= 'cms_privilege_menu';
	protected $tablePrivileges 		= 'cms_privilege';
	protected $tableMenu 			= 'cms_menu';
	protected $tableTipeUser 		= 'cms_tipe_user';
	protected $tableUser 			= 'cms_user';
	protected $tableKaryawan 		= 'cms_karyawan';

	protected $dbCms = NULL;
	protected $dbPG = NULL;
	protected $dbWN = NULL;
	protected $dbWS = NULL;
	protected $dbAds = NULL;

	function __construct()
	{
		parent::__construct();

		$_sessProfile = $this->session->userdata('profile');

		$this->profile = $_sessProfile;
	}

	#CMS
	protected function loadDbCms(){
		$this->dbCms = $this->load->database('cms', TRUE);
		return true;
	}

	#Campaign Manager
	protected function loadDbAds(){
		$this->dbAds = $this->load->database('ads', TRUE);
		return true;
	}

	protected function createTable($conn, $baseTable, $newTable){
		$conn->query('CREATE TABLE IF NOT EXISTS '.$newTable.' LIKE '.$baseTable);
	}

	protected function getDayList($day){
		$dayList = array(
						'0' =>'Sun',
						'1' =>'Mon',
						'2' =>'Tue',
						'3' =>'Wed',
						'4' =>'Thu',
						'5' =>'Fri',
						'6' =>'Sat'
						);
						return $dayList[$day];
	}

	/*      Redis Command Function
	 *  params[0] -> Redis Command
	 *      params[1] -> Key
	 *      params[2] -> value
	 *  params[..]-> value
	 *  params[n] -> value
	 */
	protected function redisCommand($redisConnection = 'default', $params = array()){
		#Load Redis Library
		$this->load->library('CI_Redis', array('connection_group' => $redisConnection));

		if(!is_array($params[0])){
			#Upper Redis Command
			$params[0] = strtoupper($params[0]);
			#Converting Params Array() into String Command
			$params = implode(' ', $params);

			#Execute Redis Command
			$result = $this->ci_redis->command($params);
		} else {

		}

		return $result;
	}

	public function SaveLogdata($action_type,$data,$user_name='',$user_info=''){
		if(trim($user_name)=='')
		{
			$user_data=$this->session->userdata('profile');
			$user_name=$user_data['nama_karyawan'];
			$user_info=$user_data['username'];
		}
		$logdata = array(
						'user_name' 		=> ($user_name),
						'action_type' 		=> ($action_type),
						'data' 	=> serialize($data),
						'user_info'=>$user_info,
						'create_ts' 	=> (date('Y-m-d H:i:s'))
		);
		$this->dbAds->insert('user_log', $logdata);

	}
	public function getCampaignUser($id){
		$this->db->select('username');
		$this->db->from('campaign_cms.cms_user');

		$this->db->where('id', $id);
		$this->db->where('status', 1);

		$query = $this->db->get();

		$result = array();
		$i=0;
		foreach($query->result_array() as $row){

			$result['username'] = $row['username'];
			$i++;
		}

		return $result;
	}

	/*public function CheckExistData($table,$fieldname,$id)
	 {
	 $this->dbAds->select('COUNT(1) as exist_data');
	 $this->dbAds->from($table);
	 $this->dbAds->where($fieldname, $this->dbAds->escape_str($id));
	 $query = $this->dbAds->get();
	 foreach($query->result_array() as $row)
	 {
	 if($row['exist_data']==0)
	 {
	 $result['duplicat_data']=false;
	 }
	 else {
	 $result['errors_message'] = "Cant'delete?This data is in use in campaigns.";
	 $result['duplicat_data'] =  true;
	 }
	 return $result;
	 }

	 }*/
	public function CheckExistData($table,$fieldname,$id)
	{
		if($table=='campaigns' && $fieldname=='id_template')
		{
			$this->dbAds->select('name as exist_data');
			$this->dbAds->from($table);
			$this->dbAds->where($fieldname, $this->dbAds->escape_str($id));
		}
		elseif($table=='campaigns_details' && $fieldname=='id_partner_service')
		{
			$this->dbAds->select('c.name as exist_data');
			$this->dbAds->from($table.' cd');
			$this->dbAds->join('campaigns c', 'cd.id_campaign = c.id', 'left');
			$this->dbAds->where($fieldname, $this->dbAds->escape_str($id));
		}
		else
		{
			$this->dbAds->select('COUNT(1) as exist_data');
			$this->dbAds->from($table);
			$this->dbAds->where($fieldname, $this->dbAds->escape_str($id));
		}
		$query = $this->dbAds->get();
		// echo $this->dbAds->last_query();die('pop');
		foreach($query->result_array() as $row)
		{
			if(($table=='campaigns' && $fieldname=='id_template') || ($table=='campaigns_details' && $fieldname=='id_partner_service'))
			{
				if(count($query->result_array()))
				{
					$j = 1;
					$comma = ',';
					foreach($query->result_array() as $camp_name)
					{
						if($j==count($query->result_array()))
						$comma = '';
						$campign_name .= $camp_name['exist_data'].$comma.' ';
						$j++;
					}
					$result['errors_message'] = "[ ".$campign_name."] campaigns are using this service. Please first delete associated campaign(s) then try to delete again.";
					$result['duplicat_data'] =  true;
				}
				else
				{
					$result['duplicat_data']=false;
				}
				break;
			}
			else
			{
				if($row['exist_data']==0)
				{
					$result['duplicat_data']=false;
				}
				else {
					$result['errors_message'] = "Can't delete?This data is in use.";
					$result['duplicat_data'] =  true;
				}
			}

		}
		return $result;
	}
	public function getUserid($id){
		$this->db->select('tipe_user_id');
		$this->db->from('campaign_cms.cms_user');

		$this->db->where('id', $id);
		$this->db->where('status', 1);

		$query = $this->db->get();

		$result = array();
		$i=0;
		foreach($query->result_array() as $row){

				
			$result['tipe_user_id'] = $row['tipe_user_id'];
			$i++;
		}

		return $result;
	}
	
public function checkCode($table,$code)
	{			
			$result = array();
			$result['duplicat_data']=false;
			$this->dbAds->select('COUNT(1) as exist_data');
			$this->dbAds->from($table);
			$this->dbAds->where('code', $code);
			$query = $this->dbAds->get();
			if($query->row()->exist_data!=0)
				{
					$result['errors_message'] = "Code already exist. Please edit and add new country.";  
					$result['duplicat_data'] =  true;
					
			}	
	return $result;
}

public function getflippyPixellog($adnetwork)
	{
		$date = DATE("Y-m-d");
		$logurl = FLIPPY_REPORT_PIXEL.$date."_".$adnetwork."_pixel.log";
		$data=file($logurl,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if(!$data)
			$data = array();
		return $data;
	} 

	public function getflippyAccesslog($adnetwork,$serial_no=1,$no_of_records=500,$status=0,$type=0)
	{
				$date = DATE("Y-m-d");
                $offset=$no_of_records*$serial_no;
                $file_name=$date."_".$adnetwork."_access.log";
                if($status==1)
                    $file_name=$date."_".$adnetwork."Invalid_access.log";
                if($serial_no==1)
                exec("wget -O /var/www/html/cms-tools-ads/app/logs/flippy_access_log/".$file_name." http://blobbimobi.com/apk/log/".$file_name);
                $total=exec("wc -l < /var/www/html/cms-tools-ads/app/logs/flippy_access_log/".$file_name);
                if($type==0)
                {
                exec("tail -$offset"." /var/www/html/cms-tools-ads/app/logs/flippy_access_log/$file_name  | head -".$no_of_records." | tac > /var/www/html/cms-tools-ads/app/logs/flippy_access_log/temp_access.txt");
                $data=file("/var/www/html/cms-tools-ads/app/logs/flippy_access_log/temp_access.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                }else{
                    $data=file("/var/www/html/cms-tools-ads/app/logs/flippy_access_log/$file_name", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                }
                if(!$data)
			$data = array();
                $data['total_records']=$total;
                
		return $data;
	}
		
	public function getflippyAccesslog_old($adnetwork)
	{
		$date = DATE("Y-m-d");
		$logurl = FLIPPY_REPORT_ACCESS.$date."_".$adnetwork."_access.log";
		$data=file($logurl,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if(!$data)
			$data = array();
		return $data;
	}

	public function getDeviceModel($string) 
	{
    	$model_array = array('3Q', 'Acer', 'Ainol', 'Airness', 'Alcatel', 'Allview', 'Altech UEC', 'Arnova, Amazon', 'Amoi', 'Apple', 'Archos', 'ARRIS', 'Airties', 'Asus', 'Avvio', 'Audiovox', 'Axxion', 'BBK', 'Becker', 'Bird', 'Beetel', 'Bmobile', 'Barnes & Noble', 'BangOlufsen', 'BenQ', 'BenQ-Siemens', 'Blu', 'Boway', 'bq', 'Brondi', 'Bush', 'CUBOT', 'Carrefour', 'Captiva', 'Casio', 'Cat', 'Celkon', 'ConCorde', 'Changhong', 'Cherry Mobile', 'Cricket', 'Crosscall', 'Compal', 'CnM', 'Crius Mea', 'CreNova', 'Capitel', 'Compaq', 'Coolpad', 'Cowon', 'Cube', 'Coby Kyros', 'Danew', 'Datang', 'Denver', 'Desay', 'Dbtel', 'DoCoMo', 'Dicam', 'Dell', 'DNS', 'DMM', 'Doogee', 'Doov', 'Dopod', 'Dune HD', 'E-Boda', 'EBEST', 'Ericsson', 'ECS', 'Ezio', 'Elephone', 'Easypix', 'Energy Sistem', 'Ericy', 'Eton', 'eTouch', 'Evertek', 'Ezze', 'Fairphone', 'Fly', 'Foxconn', 'Fujitsu', 'Garmin-Asus', 'Gateway', 'Gemini', 'Gionee', 'Gigabyte', 'Gigaset', 'GOCLEVER', 'Goly', 'Google', 'Gradiente', 'Grundig', 'Haier', 'Hasee', 'Hisense', 'Hi-Level', 'Hosin', 'HP', 'HTC', 'Huawei', 'Humax', 'Hyrican', 'Hyundai', 'Ikea', 'iBall', 'i-Joy', 'iBerry', 'iKoMo', 'i-mate', 'iOcean', 'iNew', 'Infinix', 'Innostream', 'Inkti', 'Intex', 'i-mobile', 'INQ', 'Intek', 'Inverto', 'iTel', 'Jiayu', 'Jolla', 'Karbonn', 'KDDI', 'Kingsun', 'Konka', 'Komu', 'Koobee', 'K-Touch', 'KT-Tech', 'KOPO', 'Koridy', 'Kumai', 'Kyocera', 'Kazam', 'Lava', 'Lanix', 'LCT', 'Lenovo', 'Lenco', 'Le Pan', 'LG', 'Lingwin', 'Loewe', 'Logicom', 'Lexibook', 'Majestic', 'Manta Multimedia', 'Mobistel', 'Mecer', 'Medion', 'MEEG', 'Meizu', 'Metz', 'MEU', 'MicroMax', 'Mediacom', 'MediaTek', 'Mio', 'Mpman', 'Mofut', 'Motorola', 'Microsoft', 'MSI', 'Memup', 'Mitsubishi', 'MLLED', 'M.T.T.', 'MyPhone', 'NEC', 'Netgear', 'NGM', 'Nintendo', 'Noain', 'Nokia', 'Nomi', 'Nikon', 'Newgen', 'Nexian', 'NextBook', 'Onda', 'OnePlus', 'OPPO', 'Orange', 'O2', 'Ouki', 'OUYA', 'Opsson', 'Panasonic', 'PEAQ', 'Philips', 'Pioneer', 'Polaroid', 'Palm', 'phoneOne', 'Pantech', 'Ployer', 'Point of View', 'PolyPad', 'Pomp', 'Positivo', 'Prestigio', 'ProScan', 'PULID', 'Qilive', 'Qtek', 'QMobile', 'Quechua', 'Overmax', 'Oysters', 'Ramos', 'RCA Tablets', 'Readboy', 'Rikomagic', 'RIM', 'Roku', 'Rover', 'Samsung', 'Sega', 'Sony Ericsson', 'Sencor', 'Softbank', 'SFR', 'Sagem', 'Sharp', 'Siemens', 'Sendo', 'Skyworth', 'Smartfren', 'Sony', 'Spice', 'SuperSonic', 'Selevision', 'Sanyo', 'Symphony', 'Smart', 'Star', 'Storex', 'Stonex', 'SunVan', 'Sumvision', 'Tesla', 'TCL', 'Telit', 'ThL', 'TiPhone', 'Tecno Mobile', 'Tesco', 'TIANYU', 'Telefunken', 'Telenor', 'T-Mobile', 'Thomson', 'Tolino', 'Toplux', 'Toshiba', 'TechnoTrend', 'Trevi', 'Tunisie Telecom', 'Turbo-X', 'TVC', 'TechniSat', 'teXet', 'Unowhy', 'Uniscope', 'UTStarcom', 'Vastking', 'Videocon', 'Vertu', 'Vitelcom', 'VK Mobile', 'ViewSonic', 'Vestel', 'Vivo', 'Voto', 'Voxtel', 'Vodafone', 'Vizio', 'Videoweb', 'Walton', 'Web TV', 'WellcoM', 'Wexler', 'Wiko', 'Wolder', 'Wonu', 'Woxter', 'Xiaomi', 'Xolo', 'Unknown', 'Yarvik', 'Yuandao', 'Yusun', 'Ytone', 'Zeemi', 'Zonda', 'Zopo', 'ZTE', 'Huawrihol');
    	$os_array = array('AIX', 'MIDP', 'Android', 'AmigaOS', 'Apple TV', 'Arch Linux', 'BackTrack', 'Bada', 'BeOS', 'BlackBerry OS', 'BlackBerry Tablet OS', 'Brew', 'CentOS', 'Chrome OS', 'CyanogenMod', 'Debian', 'DragonFly', 'Fedora', 'Firefox OS', 'FreeBSD', 'Gentoo', 'Google TV', 'HP-UX', 'Haiku OS', 'IRIX', 'Inferno', 'Knoppix', 'Kubuntu', 'GNU/Linux', 'Lubuntu', 'VectorLinux', 'Mac', 'Maemo', 'Mandriva', 'MeeGo', 'MocorDroid', 'Mint', 'MildWild', 'MorphOS', 'NetBSD', 'MTK / Nucleus', 'Nintendo', 'Nintendo Mobile', 'OS/2', 'OSF1', 'OpenBSD', 'PlayStation Portable', 'PlayStation', 'Red Hat', 'RISC OS', 'RazoDroiD', 'Sabayon', 'SUSE', 'Sailfish OS', 'Slackware', 'Solaris', 'Syllable', 'Symbian', 'Symbian OS', 'Symbian OS Series 40', 'Symbian OS Series 60', 'Symbian^3', 'ThreadX', 'Tizen', 'Ubuntu', 'WebTV', 'Windows', 'Windows CE', 'Windows Mobile', 'Windows Phone', 'Windows RT', 'Xbox', 'Xubuntu', 'YunOs', 'iOS', 'palmOS', 'webOS');
    	$return_array = array('device' => 'Unkown', 'model' => 'Unkown', 'os' => 'unknown');
    	foreach ($model_array as $u) {
	        $pattern = "/($u)((\_|-|\s+)[A-Za-z0-9\+\/]+)/i";

	        if (@preg_match($pattern, $string, $match)) {
	            $return_array['device'] = $match[1];
	            $return_array['model'] = trim($match[2], '_');
	            $return_array['model'] = trim($return_array['model'], '-');
	        }
	    }
	    foreach ($os_array as $u) {
	        $pattern = "/($u\/*\s*\-*[A-Za-z0-9\+\/\.]+)/i";
	        if (@preg_match($pattern, $string, $match)) {
	            $return_array['os'] = $match[1];
	        }
	    }
    	return $return_array;
	}
	
	function getPartition($sdate, $edate)
	{
		$stimestamp = strtotime($sdate);
		$sday = (int)date("d", $stimestamp);
		$estimestamp = strtotime($edate);
		$eday = (int)date("d", $estimestamp);
		if($eday<4)
		{
			return "p0";
		}
		if($eday<8)
		{
			if($sday<=3)
			{
				return "p0, p1";
			}
			else
			{
				return "p1";
			}
			//return;
		}
		if($eday<12)
		{
			
			if($sday<=3)
			{
				return "p0, p1, p2";
			}
			else if($sday<=7)
			{
				return "p1, p2";
			}
			else
			{
				return "p2";
			}
			//return;
			
		}
		if($eday<16)
		{
			
			if($sday<=3)
			{
				return "p0, p1, p2, p3";
			}
			else if($sday<=7)
			{
				return "p1, p2, p3";
			}
			else if($sday<=11)
			{
				return "p2, p3";
			}
			else
			{
				return "p3";
			}
			//return;
			
		}
		if($eday<20)
		{
			
			if($sday<=3)
			{
				return "p0, p1, p2, p3, p4";
			}
			else if($sday<=7)
			{
				return "p1, p2, p3, p4";
			}
			else if($sday<=11)
			{
				return "p2, p3, p4";
			}
			else if($sday<=15)
			{
				return "p3, p4";
			}
			else
			{
				return "p4";
			}
			//return;
			
		}
		if($eday<24)
		{
			
			if($sday<=3)
			{
				return "p0, p1, p2, p3, p4, p5";
			}
			else if($sday<=7)
			{
				return "p1, p2, p3, p4, p5";
			}
			else if($sday<=11)
			{
				return "p2, p3, p4, p5";
			}
			else if($sday<=15)
			{
				return "p3, p4, p5";
			}
			else if($sday<=19)
			{
				return "p4, p5";
			}
			else
			{
				return "p5";
			}
			//return;
			
		}
		if($eday<28)
		{
			
			if($sday<=3)
			{
				return "p0, p1, p2, p3, p4, p5, p6";
			}
			else if($sday<=7)
			{
				return "p1, p2, p3, p4, p5, p6";
			}
			else if($sday<=11)
			{
				return "p2, p3, p4, p5, p6";
			}
			else if($sday<=15)
			{
				return "p3, p4, p5, p6";
			}
			else if($sday<=19)
			{
				return "p4, p5, p6";
			}
			else if($sday<=23)
			{
				return "p5, p6";
			}
			else
			{
				return "p6";
			}
			//return;
			
		}
		if($eday<32)
		{
			
			if($sday<=3)
			{
				return "p0, p1, p2, p3, p4, p5, p6, p7";
			}
			else if($sday<=7)
			{
				return "p1, p2, p3, p4, p5, p6, p7";
			}
			else if($sday<=11)
			{
				return "p2, p3, p4, p5, p6, p7";
			}
			else if($sday<=15)
			{
				return "p3, p4, p5, p6, p7";
			}
			else if($sday<=19)
			{
				return "p4, p5, p6, p7";
			}
			else if($sday<=23)
			{
				return "p5, p6, p7";
			}
			else if($sday<=27)
			{
				return "p6, p7";
			}
			else
			{
				return "p7";
			}
			return;
			
		}
	}

}// end class
