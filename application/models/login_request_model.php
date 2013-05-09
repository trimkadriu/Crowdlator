<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Login_request_model extends CI_Model {
	
    function __construct()
    {
        parent:: __construct();
    }
	
    function do_login($user, $pass)
    {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username', $user);
		$this->db->where('password', $pass);
		$query = $this->db->get();
		if($query->num_rows() == 0)
			return false;
		else
		{
			foreach($query->result() as $row)
			{
				$data = array(
							  'loggedin' => 'true', 
							  'fullname' => $row->fullname,
							  'username' => $row->username,
                              'user_id' => $row->id,
                              'roleid' => $row->role_id);
			}
			return $data;
		}
	}
	
}

?>