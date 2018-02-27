<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class model_xsim_map extends MY_Model {

    public function __construct() {
        parent::__construct();

        $this->loadDbAds();
    }

    public function getCampaignList($params) {
        $this->dbAds->select('c.id, c.name, c.status');
        $this->dbAds->select('b.campaign_code');
        $this->dbAds->from('campaigns c');
        $this->dbAds->join('campaigns_details b', 'c.id = b.id_campaign', 'LEFT');
        $this->dbAds->join('ads_publishers p', 'b.id_ads_publisher = p.id', 'LEFT');
        $this->dbAds->where("p.code", $params['client_id'] . '-xsim');
        $this->dbAds->order_by('c.name', 'ASC');
        $query = $this->dbAds->get();
        $i = 0;
        if ($query->num_rows() != 0) {
            $result['campaign_count'] = true;
            foreach ($query->result_array() as $row) {
                $result['data'][$i]['id'] = $row['id'];
                $result['data'][$i]['name'] = $row['name'];
                $result['data'][$i]['campaign_code'] = $row['campaign_code'];
                $i++;
            }
        } else {
            $result['campaign_count'] = false;
        }

        $this->dbAds->select('campaign_id');
        $this->dbAds->from('xsim_mapping');
        if(!empty($params['client_id']))
        $this->dbAds->where("client_id", $params['client_id']);
        $query = $this->dbAds->get();
        $i = 0;
        if ($query->num_rows() != 0) {
            $result['client_count'] = true;
            foreach ($query->result_array() as $row) {
                $result['selectd'][$i]['campaign_code'] = $row['campaign_id'];

                $i++;
            }
        } else {
            $result['client_count'] = false;
        }
        return $result;
    }

    public function mapCampaign($params) {

        $this->dbAds->where('client_id', $params['client_id']);
        $this->dbAds->delete('xsim_mapping');

        $query = 'INSERT IGNORE INTO xsim_mapping(client_id, campaign_id,create_ts) values ';
        for ($i = 0; $i < count($params['data']); $i++) {
            $query.="('" . $params['client_id'] . "','" . $params['data'][$i] . "','" . date('Y-m-d H:i:s', time()) . "'),";
        }
        $query = rtrim($query, ',');

        $this->dbAds->query($query);
        $status = false;
        if ($this->dbAds->affected_rows()) {
            $status = true;
            $p++;
        }
        return $status;
    }

    public function getCampaignUrl($client_id) {
        $this->dbAds->select('c.name, cd.campaign_code,cd.campaign_code_new, d.name AS domain');
        $this->dbAds->from('campaigns c');
        $this->dbAds->join('campaigns_details cd', 'c.id = cd.id_campaign', 'LEFT');
        $this->dbAds->join('xsim_mapping xm', 'cd.campaign_code=xm.campaign_id', 'LEFT');
        $this->dbAds->join('domains d', 'cd.id_domain = d.id', 'LEFT');
        $this->dbAds->where('xm.client_id', $client_id);
        $query = $this->dbAds->get();
        $result = array();
        $i = 0;
        if ($query->num_rows() != 0) {
            $result['count'] = $query->num_rows();
            foreach ($query->result_array() as $row) {
                $result['data'][$i]['name'] = $row['name'];
                $result['data'][$i]['url'] = $row['domain'] . DIRECTORY_SEPARATOR . 'ads' . DIRECTORY_SEPARATOR . $row['campaign_code'] . '_' . $row['campaign_code_new'] . DIRECTORY_SEPARATOR;
                $i++;
            }
        } else {
            $result['count'] = false;
        }

        return $result;
    }

}

?>
