<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }
   public function insert($data) {
        if (!empty($data)) {
            $this->db->insert('users', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function Exist($data){
        $condition = "user_email =" . "'" . $data['user_email'] . "'";
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }        
    }

    public function send_validation_email($data=['user_email']) {
    
    $condition = "user_email =" . "'" . $data['user_email'] . "'";
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($condition);
        $this->db->limit(1);
        $sql = $this->db->get();

    return $sql->result();
}


 function verifyEmail( $email_address) {
    $data = array('status' => 1);
    $this->db->where('user_email', $email_address);
    //$this->db->where('md5(register_date)', $email_code);
    return $this->db->update('users', $data);
}
public function login($data) {

        $condition = "user_email =" . "'" . $data['user_email'] . "' AND " . "user_pasword =" . "'" . $data['user_pasword'] . "'";
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function updateToken($data, $user_id, $userData) {
        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
        return $this->login($userData);
    }

     public function getUserIdByToken($accessToken){
        if (!empty($accessToken)) {
            // Query to check whether username already exist or not
            $condition = "access_token =" . "'" . $accessToken . "'";
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
                return $query->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function getUserSearch($searchString){
        $condition = " nick_name LIKE '%" . $searchString . "%' OR age LIKE '%" . $searchString . "%' OR user_location LIKE '%" . $searchString . "%' OR user_net_worth LIKE '%" . $searchString . "%' OR user_annual_income LIKE '%" . $searchString . "%'" ;
        $this->db->select('nick_name', 'age', 'user_location', 'user_net_worth', 'user_annual_income');
        $this->db->from('users');
        $this->db->join('users_other_info', 'users_other_info.user_id = users.user_id');
        $this->db->where($condition);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }   
    }

 public function getUserIdByTokens($accessToken){
        if (!empty($accessToken)) {
            // Query to check whether username already exist or not
            $condition = "access_token =" . "'" . $accessToken . "'";
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
                return $query->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function insertimage($data) {
        if (!empty($data)) {
            $this->db->insert('users_images', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

}
public function getUserIdByTokenn($accessToken){
        if (!empty($accessToken)) {
            // Query to check whether username already exist or not
            $condition = "access_token =" . "'" . $accessToken . "'";
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where($condition);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
                return $query->result();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    //insertgender
    public function insertgender($data){
        if (!empty($data)) {
            $this->db->insert('user_lookfor', $data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

}

    }

