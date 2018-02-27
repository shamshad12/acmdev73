<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class login extends Public_Controller {
		
	public function __construct() {
		parent::__construct();
			
		$this->load->model('model_login');
	}
	
	public function index() {                    
        if($this->session->userdata('isLogged')){
			$privileges = $this->session->userdata('privilege');
			redirect($privileges['defaultUrl']);
		} else
			$this->load->view('login', $this->data);
	}	
	
	public function auth(){
		// This is supposed to be Ajax Request. Otherwise, ignore it.
        if (!$this->input->is_ajax_request ()) {return $this->redirect(); }
        
        // Post Request
        $_username = $this->input->post('username');
        $_password = $this->input->post('password');
        
        $aUser = $this->model_login->auth($_username);
		 $_responseStatus = false;
        if (is_array($aUser) && !empty($aUser))
        {
            if ($aUser['userpass'] != md5($_password))
            {
                $response['message'] = 'Invalid Username or Password';
            }
            elseif ($aUser['status'] != 1)
            {
                $response['message'] = 'User does not active.';
            }
            else
            {
                $aPrivilege = $this->model_login->getPrivilege($aUser['tipe_user_id']);
                
                if ($aPrivilege['status'] != 1 && $aPrivilege['status'] != 2)
                {
                    $response['message'] = 'User does not have privilege.';
                }
                else
                {
					$aUserProfile = array(
										'id' 			=> $aUser['id'],
										'karyawan_id' 	=> $aUser['karyawan_id'],
										'store_id' 		=> $aUser['store_id'],
										'nama_karyawan' => $aUser['nama_karyawan'],
										'tipe_user_id' 	=> $aUser['tipe_user_id'],
										'nama_tipe_user' => $aUser['nama_tipe_user'],
										'username' 		=> $aUser['username'],
										'isAdmin' 		=> $aUser['isAdmin'],
										'isPartner' 	=> $aUser['isPartner'],
										'id_country'	=> $aUser['id_country']	
										#'menu' 			=> implode(',',$aPrivilege['menu'])
									);
									
					$aUserPrivilege = array(
										'menu' 			=> implode(',',$aPrivilege['menu']),
										'defaultUrl'	=> $aPrivilege['default_url'],
										'privilege_id'	=> $aPrivilege['id_privilege']		
									);
									
                    $_responseStatus = true;
                    $this->session->set_userdata('isLogged', true);
                    $this->session->set_userdata('profile', $aUserProfile);
                    $this->session->set_userdata('privilege', $aUserPrivilege);
					
		    if($aUser['tipe_user_id'] == 11)
		    {
			$partner_permissions = $this->model_login->partnerPermissions($aUser['id']);
			$this->session->set_userdata('partner_permissions', $partner_permissions);
		    }
		    
                    $response['url'] = $aPrivilege['default_url'];                    
                }
            }
        }
        else
        {
            // Response Failed
            $response['message'] = 'Invalid Username and Password';
        }

        $response['status'] = $_responseStatus;
        
        echo json_encode ($response); 
	}
	
	public function out() {
		$this->session->sess_destroy();
		redirect('login', 'location');
	}
	
}
?>
