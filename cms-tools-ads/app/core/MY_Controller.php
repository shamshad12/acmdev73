<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller
{
	protected $data			= array();
	protected $monthList	= array();
	protected $yearList		= array();
	protected $utcTimezone	= array();
	
	function __construct()
	{
		parent::__construct();
				
		#Set data to display on View
		$this->data['base_url'] = $this->config->item('base_url');
		$this->data['base_url_index'] = $this->config->item('base_url_index');
				
		#Set data to display on View
		$this->data['globalParameter'] = $this->model_global_parameter->loadGlobalParameter();
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

        protected function redisCommandNew($redisConnection = 'default', $params = array()){
                #Load Redis Library
                $this->load->library('CI_Redis', array('connection_group' => $redisConnection));

                if(!is_array($params[0])){
                        #Upper Redis Command
                        $params[0] = strtoupper($params[0]);
                       // $params[2]="'".$params[2]."'";

                        #Converting Params Array() into String Command
                        $params = implode(' ', $params);


                        #Execute Redis Command
                        $result = $this->ci_redis->newCommand($params);
                } else {

                }

                return $result;
        }
    
    protected function setMonthList(){
		$month = array(
						'01' =>'Jan',
						'02' =>'Feb',
						'03' =>'Mar',
						'04' =>'Apr',
						'05' =>'May',
						'06' =>'Jun',
						'07' =>'Jul',
						'08' =>'Aug',
						'09' =>'Sep',
						'10' =>'Oct',
						'11' =>'Nov',
						'12' =>'Des'
					  );
		return $this->monthList = $month;
	}
	
	protected function setDayList(){
		$day = array(
						'0' =>'Sun',
						'1' =>'Mon',
						'2' =>'Tue',
						'3' =>'Wed',
						'4' =>'Thu',
						'5' =>'Fri',
						'6' =>'Sat'
					  );
		return $day;
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
	
	protected function setUtcTimezone(){
		$utcTimezone = array();
		for($i=0; $i<24; $i++){
			$timezone = ($i<10)?'0'.$i:$i;
			$utcTimezone[$i] = 'UTC +'.$timezone.'.00';
		}
		return $this->utcTimezone = $utcTimezone;
	}
	
	protected function setTime(){
		$utcTimezone = array();
		for($i=0; $i<24; $i++){
			$time = ($i<10)?'0'.$i:$i;
			$utcTimezone[$i] = $time;
		}
		return $utcTimezone;
	}
	
	protected function setYearList($yearFirst = 2013){
		$year = array();
		for($i=date('Y'); $i>=$yearFirst; $i--){
			$year[] = $i;
		}
		return $this->yearList = $year;
	}
}
