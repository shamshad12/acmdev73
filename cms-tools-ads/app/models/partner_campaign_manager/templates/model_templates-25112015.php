<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class model_templates extends MY_Model {

    public function __construct() {
        parent::__construct();

        $this->loadDbAds();
    }

    public function loadTemplates($params) {
        $user = $this->session->userdata('profile');
        $result = array();
        $this->dbAds->select(' COUNT(1) AS count ');
        $this->dbAds->from('templates c');
        $this->dbAds->join('campaigns_media cm', 'c.id_campaign_media = cm.id');
        $this->dbAds->join('countries ct', 'c.id_country = ct.id');
        //$this->dbAds->where('c.entry_user', $user['id']);
        if (!empty($params['search']))
            $this->dbAds->where("(c.id LIKE '%" . $params['search'] . "%' OR c.name LIKE '%" . $params['search'] . "%' OR
								 cm.name LIKE '%" . $params['search'] . "%' OR ct.name LIKE '%" . $params['search'] . "%')");

        if (!empty($params['media']))
            $this->dbAds->where("c.id_campaign_media", $params['media']);

        $query = $this->dbAds->get();
        $result['total'] = 0;
        foreach ($query->result_array() as $row)
            $result['total'] = $row['count'];

        $this->dbAds->select('c.id, c.name, ct.name AS name_country,c.id_country, c.page_confirm, c.is_uploaded, c.status, c.page_status,c.edit_user,c.edit_type, c.description,c.entry_user,c.entry_time,c.update_user,c.update_time, c.id_campaign_media');
        $this->dbAds->select('cm.name AS media_name');
        $this->dbAds->from('templates c');
        $this->dbAds->join('campaigns_media cm', 'c.id_campaign_media = cm.id');
        $this->dbAds->join('countries ct', 'c.id_country = ct.id');
        //$this->dbAds->where('c.entry_user', $user['id']);
        if (!empty($params['search']))
            $this->dbAds->where("c.id LIKE '%" . $params['search'] . "%' OR c.name LIKE '%" . $params['search'] . "%' OR
								 cm.name LIKE '%" . $params['search'] . "%' OR ct.name LIKE '%" . $params['search'] . "%'");

        if (!empty($params['media']))
            $this->dbAds->where("c.id_campaign_media", $params['media']);

        $this->dbAds->limit($params['limit'], ($params['page'] - 1) * $params['limit']);
        $this->dbAds->order_by('c.id', 'DESC');
        $query = $this->dbAds->get();
		//echo $this->dbAds->last_query();die;
        $i = 0;
        if ($query->num_rows() != 0) {
            $result['count'] = true;
            foreach ($query->result_array() as $row) {
                $result['rows'][$i]['id'] = $row['id'];
                $result['rows'][$i]['name_country'] = $row['name_country'];
                $result['rows'][$i]['id_country'] = $row['id_country'];
                $result['rows'][$i]['is_uploaded'] = $row['is_uploaded'];
                $result['rows'][$i]['media_name'] = $row['media_name'];
                $result['rows'][$i]['page_confirm'] = $row['page_confirm'];
                $result['rows'][$i]['name'] = $row['name'];
                $result['rows'][$i]['description'] = $row['description'];
                $result['rows'][$i]['page_status'] = ($row['page_status'] == 1) ? 'Yes' : 'No';
                $result['rows'][$i]['status'] = ($row['status'] == 1) ? 'Active' : 'Inactive';
                $result['rows'][$i]['id_campaign_media'] = $row['id_campaign_media'];
                if (isset($row['update_user'])) {
                    $username = $this->getCampaignUser($row['update_user']);
                    $result['rows'][$i]['user_updated'] = isset($username['username']) ? $username['username'] : '';
                    $result['rows'][$i]['user_updated_time'] = $row['update_time'];
                } else {
                    $result['rows'][$i]['user_updated'] = 'anb';
                    $result['rows'][$i]['user_updated_time'] = '';
                }
                if (isset($row['entry_user'])) {
                    $username = $this->getCampaignUser($row['entry_user']);
                    $result['rows'][$i]['user_enter'] = isset($username['username']) ? $username['username'] : '';
                    $result['rows'][$i]['user_enter_time'] = $row['entry_time'];
                } else {
                    $result['rows'][$i]['user_enter'] = 'anb';
                    $result['rows'][$i]['user_enter_time'] = '';
                }
                $session_user = $this->dbAds->escape_str($this->profile['id']);
                $session_user_id = $this->getUserid($session_user);
                $result['rows'][$i]['session_user_typeid'] = $session_user_id['tipe_user_id'];
                $result['rows'][$i]['edit_type'] = $row['edit_type'];
                $result['rows'][$i]['edit_user'] = $row['edit_user'];
                $result['rows'][$i]['login_user'] = $this->profile['id'];
                $i++;
            }
        } else {
            $result['count'] = false;
        }

        return $result;
    }

    public function changeStatus($params) {

        if ($this->dbAds->escape_str($params['edit_type'] == 0)) {
            $edit_user = '';
        } else {
            $edit_user = $this->dbAds->escape_str($this->profile['id']);
        }
        $update = array('edit_type' => $this->dbAds->escape_str($params['edit_type']), 'edit_user' => $edit_user);
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $this->dbAds->update('templates', $update);
        //echo $this->dbAds->last_query();die(); 
        $result = array();
        if ($this->dbAds->affected_rows()) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
        }
        return $result;
    }

      public function loadTemplatesSelect($params = array()) {
        $user = $this->session->userdata('profile');

        $result = array();

        $this->dbAds->select('id, name, description');
        $this->dbAds->from('templates');
        //$this->dbAds->where('entry_user', $user['id']);
        $this->dbAds->where('status', '1');

        if (!empty($params['campaign_media']))
            $this->dbAds->where('id_campaign_media', $params['campaign_media']);

        if (isset($params['is_uploaded']))
            $this->dbAds->where('is_uploaded', $params['is_uploaded']);

        $query = $this->dbAds->get();

        $i = 0;
        if ($query->num_rows() != 0) {
            $result['count'] = true;
            foreach ($query->result_array() as $row) {
                $result['rows'][$i]['id'] = $row['id'];
                $result['rows'][$i]['name'] = $row['name'];
                $result['rows'][$i]['description'] = $row['description'];
                $i++;
            }
        } else {
            $result['count'] = false;
        }

        return $result;
    }

    public function checkeditTemplates($params) {
        $result = array();
        $this->dbAds->select('edit_type,edit_user');
        $this->dbAds->from('templates');
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $query = $this->dbAds->get();
        $result = array();
        $i = 0;
        if ($query->num_rows() != 0) {
            $result['count'] = true;
            foreach ($query->result_array() as $row) {
                $result['rows'][$i]['edit_type'] = $row['edit_type'];
                $result['rows'][$i]['edit_user'] = $row['edit_user'];
                $result['rows'][$i]['login_user'] = $this->profile['id'];
                $i++;
            }
        } else {
            $result['count'] = false;
        }
        return $result;
    }

    public function saveTemplates($params) {
        $this->dbAds->select('id');
        $this->dbAds->from('templates');
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $data = $this->dbAds->get();
        if ($data->num_rows() != 0) {
            $result = $this->updateTemplates($params);
        } else {
            $result = $this->insertTemplates($params);
        }
        return $result;
    }

    private function updateTemplates($params) {
        $input_by = $this->session->userdata('id_user');
        $input_time = strtotime(date('Y-m-d H:i:s'));
        if (isset($params['page_footer']))
            $params['page_footer'] = $params['page_footer'];
        else
            $params['page_footer'] = '';
        if (!$params['is_uploaded']) {
            $update = array(
                'id_campaign_media' => $this->dbAds->escape_str($params['id_campaign_media']),
                'id_country' => $this->dbAds->escape_str($params['id_country']),
                'name' => $this->dbAds->escape_str($params['name']),
                'description' => $this->dbAds->escape_str($params['description']),
                'page_confirm' => htmlspecialchars($params['page_confirm']),
                'page_status' => htmlspecialchars($params['page_status']),
                'page_footer' => htmlspecialchars($params['page_footer']),
                'page_error' => htmlspecialchars($params['page_error']),
                'status' => $this->dbAds->escape_str($params['status']),
                'update_user' => $this->dbAds->escape_str($this->profile['id']),
                'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
                'edit_type' => '0',
                'edit_user' => ''
            );
        } else {
            $update = array(
                'id_campaign_media' => $this->dbAds->escape_str($params['id_campaign_media']),
                'id_country' => $this->dbAds->escape_str($params['id_country']),
                'name' => $this->dbAds->escape_str($params['name']),
                'description' => $this->dbAds->escape_str($params['description']),
                'status' => $this->dbAds->escape_str($params['status']),
                'update_user' => $this->dbAds->escape_str($this->profile['id']),
                'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
                'edit_type' => '0',
                'edit_user' => ''
            );
        }
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $this->dbAds->update('templates', $update);
        $result = array();
        if ($this->dbAds->affected_rows()) {
            $result['success'] = true;
            $update_message = $this->config->item('update_message');
            $update_message = str_replace("{SECTION}", "Template", $update_message);
            $update_message = str_replace("{TITLE}", $params['name'], $update_message);
            $update_message = str_replace("{ID}", $params['id'], $update_message);
            $this->SaveLogdata($update_message, $params);
            $result['id'] = $params['id'];
        } else {
            $result['success'] = false;
        }
        return $result;
    }

    private function insertTemplates($params) {

        $result = array();
        //$where = "(name='".$this->dbAds->escape_str($params['name'])."' or code ='".$this->dbAds->escape_str($params['code'])."' or prefix ='".$this->dbAds->escape_str($params['prefix'])."' ) ";
        $this->dbAds->select('COUNT(1) as exist_data');
        $this->dbAds->from('templates');
        $this->dbAds->where('name', $this->dbAds->escape_str($params['name']));

        $query = $this->dbAds->get();

        foreach ($query->result_array() as $row) {
            if ($row['exist_data'] != 0) {
                $result['errors_message'] = "Template Name you are using already added, so you can not use same name.";
                $result['duplicat_data'] = true;
                return $result;
            }
        }
        if (isset($params['page_footer']))
            $params['page_footer'] = $params['page_footer'];
        else
            $params['page_footer'] = '';
        if (isset($params['page_pin']))
            $params['page_pin'] = $params['page_pin'];
        else
            $params['page_pin'] = '';
        $input_by = $this->session->userdata('id_user');
        $input_time = strtotime(date('Y-m-d H:i:s'));
        $update = array(
            'id_campaign_media' => $this->dbAds->escape_str($params['id_campaign_media']),
            'id_country' => $this->dbAds->escape_str($params['id_country']),
            'name' => $this->dbAds->escape_str($params['name']),
            'description' => $this->dbAds->escape_str($params['description']),
            'page_confirm' => htmlspecialchars($params['page_confirm']),
            'page_status' => htmlspecialchars($params['page_status']),
            'page_footer' => htmlspecialchars($params['page_footer']),
            'page_error' => htmlspecialchars($params['page_error']),
            'page_pin' => htmlspecialchars($params['page_pin']),
            'status' => $this->dbAds->escape_str($params['status']),
            'entry_user' => $this->dbAds->escape_str($this->profile['id']),
            'entry_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s'))
        );
        $this->dbAds->insert('templates', $update);
        //$result = array();
        if ($this->dbAds->affected_rows()) {
            $result['success'] = true;
            $result['id'] = $this->dbAds->insert_id();
            $add_message = $this->config->item('add_message');
            $add_message = str_replace("{SECTION}", "Template", $add_message);
            $add_message = str_replace("{TITLE}", $params['name'], $add_message);
            $add_message = str_replace("{ID}", $this->dbAds->insert_id(), $add_message);
            $this->SaveLogdata($add_message, $params);
        } else {
            $result['success'] = false;
        }
        return $result;
    }

    public function saveTemplatesUpload($params) {

        $result = array();
        if (isset($params['id']) && trim($params['id']) != '') {
            $result = $this->updateTemplateUpload($params);
        } else {
            //$where = "(name='".$this->dbAds->escape_str($params['name'])."' or code ='".$this->dbAds->escape_str($params['code'])."' or prefix ='".$this->dbAds->escape_str($params['prefix'])."' ) ";
            $this->dbAds->select('COUNT(1) as exist_data');
            $this->dbAds->from('templates');
            $this->dbAds->where('name', $this->dbAds->escape_str($params['name']));

            $query = $this->dbAds->get();

            foreach ($query->result_array() as $row) {
                if ($row['exist_data'] != 0) {
                    $result['errors_message'] = "Template Name you are using already added, so you can not use same name.";
                    $result['duplicat_data'] = true;
                    return $result;
                }
            }
            if (isset($params['page_footer']))
                $params['page_footer'] = $params['page_footer'];
            else
                $params['page_footer'] = '';
            if (isset($params['page_pin']))
                $params['page_pin'] = $params['page_pin'];
            else
                $params['page_pin'] = '';
            $input_by = $this->session->userdata('id_user');
            $input_time = strtotime(date('Y-m-d H:i:s'));
            $update = array(
                'id_campaign_media' => $this->dbAds->escape_str($params['id_campaign_media']),
                'id_country' => $this->dbAds->escape_str($params['id_country']),
                'name' => $this->dbAds->escape_str($params['name']),
                'description' => $this->dbAds->escape_str($params['description']),
                'page_confirm' => $params['page_confirm'],
                'page_status' => $params['page_status'],
                'page_footer' => $params['page_footer'],
                'page_pin' => $params['page_pin'],
                #'page_error' 	=> $params['page_error'],
                'is_uploaded' => '1',
                'entry_user' => $this->dbAds->escape_str($this->profile['id']),
                'status' => $this->dbAds->escape_str($params['status'])
            );
            $this->dbAds->insert('templates', $update);
            //$result = array();
            if ($this->dbAds->affected_rows()) {
                $result['success'] = true;
                $result['id'] = $this->dbAds->insert_id();
            } else {
                $result['success'] = false;
            }
        }

        return $result;
    }

    public function updateTemplateUpload($params) {
        if (isset($params['page_footer']))
            $params['page_footer'] = $params['page_footer'];
        else
            $params['page_footer'] = '';
        if (isset($params['page_pin']))
            $params['page_pin'] = $params['page_pin'];
        else
            $params['page_pin'] = '';

        $update = array(
            'id_campaign_media' => $this->dbAds->escape_str($params['id_campaign_media']),
            'id_country' => $this->dbAds->escape_str($params['id_country']),
            'name' => $this->dbAds->escape_str($params['name']),
            'description' => $this->dbAds->escape_str($params['description']),
            'is_uploaded' => '1',
            'status' => $this->dbAds->escape_str($params['status']),
            'update_user' => $this->session->userdata('id_user'),
            'update_time' => $this->dbAds->escape_str(date('Y-m-d H:i:s')),
            'edit_type' => '0',
            'edit_user' => ''
        );
        if (isset($params['page_footer']) && trim($params['page_footer']) != '') {
            $update['page_footer'] = $params['page_footer'];
        }
        if (isset($params['page_status']) && trim($params['page_status']) != '') {
            $update['page_status'] = $params['page_status'];
        }
        if (isset($params['page_pin']) && trim($params['page_pin']) != '') {
            $update['page_pin'] = $params['page_pin'];
        }
	else
		$update['page_pin']="";
        if (isset($params['page_confirm']) && trim($params['page_confirm']) != '') {
            $update['page_confirm'] = $params['page_confirm'];
        }
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $this->dbAds->update('templates', $update);

        $result = array();
        if ($this->dbAds->affected_rows()) {
            $result['success'] = true;
            $result['id'] = $params['id'];
        } else {
            $result['success'] = false;
        }
        return $result;
    }

    public function deleteTemplates($params) {

        $result = array();
        $isExist = $this->CheckExistData('campaigns', 'id_template', $params['id']);
        if ($isExist['duplicat_data']) {
            $result['errors_message'] = $isExist['errors_message'];
            $result['duplicat_data'] = $isExist['duplicat_data'];
            return $result;
            exit;
        }

        $service_data = $this->getTemplatesData($params);
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $this->dbAds->delete('templates');

        if ($this->dbAds->affected_rows()) {
            $result['success'] = true;
            $update_message = $this->config->item('delete_message');
            $update_message = str_replace("{SECTION}", "Template", $update_message);
            $update_message = str_replace("{TITLE}", $service_data['name'], $update_message);
            $update_message = str_replace("{ID}", $params['id'], $update_message);
            $this->SaveLogdata($update_message, $service_data);
        } else {
            $result['success'] = false;
        }
        return $result;
    }

    public function getTemplatesData($params) {
        $update = array('edit_type' => 1,
            'edit_user' => $this->profile['id']
        );
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $this->dbAds->update('templates', $update);
        $this->dbAds->select('id, name, id_country, id_campaign_media, description,page_confirm,status, page_status,page_footer, page_error,page_pin, is_uploaded');
        $this->dbAds->from('templates');
        $this->dbAds->where('id', $this->dbAds->escape_str($params['id']));
        $query = $this->dbAds->get();
        $result = array();
        $i = 0;
        if ($query->num_rows() != 0) {
            $result['count'] = true;
            foreach ($query->result_array() as $row) {
                $result['id'] = $row['id'];
                $result['name'] = $row['name'];
                $result['id_country'] = $row['id_country'];
                $result['id_campaign_media'] = $row['id_campaign_media'];
                $result['description'] = $row['description'];
                $result['page_confirm'] = htmlspecialchars_decode($row['page_confirm']);
                $result['preview_page'] = PREVIEW_PATH . htmlspecialchars_decode($row['page_confirm']);
                $result['page_status'] = htmlspecialchars_decode($row['page_status']);
                $result['page_footer'] = htmlspecialchars_decode($row['page_footer']);
                $result['page_error'] = htmlspecialchars_decode($row['page_error']);
                $result['page_pin'] = htmlspecialchars_decode($row['page_pin']);
                $result['is_uploaded'] = $row['is_uploaded'];
                $result['status'] = $row['status'];

                if ($row['is_uploaded']) {
                    // if(strpos('.php', $result['page_confirm'])){
                    if (strpos($result['page_confirm'], '.php')) {
                        $result['url'] = str_replace('index.php', '', $result['page_confirm']);
                        $result['decode_confirm'] = base64_encode('index.php');
                        $result['decode_status'] = base64_encode('detail.php');
                        $result['page_pin'] = base64_encode('pin.php');
                        $result['decode_footer'] = base64_encode('footer.php');
                        $result['decode_error'] = base64_encode('error.php');
                        $result['decode_css'] = base64_encode('style.css');
                    } else {
                        $result['url'] = str_replace('index.html', '', $result['page_confirm']);
                        $result['decode_confirm'] = base64_encode('index.html');
                        $result['decode_status'] = base64_encode('detail.html');
                        $result['decode_footer'] = base64_encode('footer.html');
                        $result['decode_error'] = base64_encode('error.html');
                        $result['page_pin'] = base64_encode('pin.html');
                        $result['decode_css'] = base64_encode('style.css');
                    }
                }

                $i++;
            }
        } else {
            $result['count'] = false;
        }
        return $result;
    }

}

?>
