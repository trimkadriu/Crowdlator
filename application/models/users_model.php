<?php

class Users_model extends CI_Model {
	
    function __construct()
    {
        parent:: __construct();
    }
	
    function register($full_name, $address, $city, $country, $username, $password, $email)
    {
		if(!$this->check_user_by_username($username) && !$this->check_user_by_email($email))
		{
			$this->db->trans_start();
			$data = array(
				'fullname' => $full_name ,
				'address' => $address ,
				'city' => $city ,
				'country' => $country ,
				'username' => $username ,
				'password' => md5($password) ,
				'email' => $email ,
				'activated' => '1' ,
				'role_id' => '3'
			);
			$this->db->insert('users', $data);
			$this->db->trans_complete();
			return true;
		}
		else
			return false;
	}
	
	function check_user_by_username($username)
	{
		//Check if USER exists by username
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username', $username);
		$query = $this->db->get();
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	function check_user_by_email($email)
	{
		//Check if USER exists by email
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$query = $this->db->get();
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	function check_permissions($role_id, $permission)
	{
		//Check user role permissions
		$this->db->select('*');
		$this->db->from('roles_x_permissions');
		$this->db->where('id_role', $role_id);
		$this->db->where('`id_permission` = ( SELECT `id` FROM permissions '.
						'WHERE `permission` =  \''.$permission.'\' )', NULL, false);
		$query = $this->db->get();	
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}
	
	function get_user_by_username($username)
	{
		//Get USER by USERNAME
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username', $username);
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return $query;
		else
			return false;
	}

    function get_user_by_id($id)
    {
        //Get USER by USERNAME
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_users($limit)
    {
        //Get list of USERS
        //Limit 0 (zero) for all users
        $this->db->select('*');
        $this->db->from('users');
        if($limit > 0)
            $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_users_with_role_name($limit)
    {
        //Get list of USERS with role name
        //Limit 0 (zero) for all users
        $sql = 'SELECT DISTINCT u.id, u.fullname, u.country, u.email, if(u.role_id = r.id, r.role, \'no role\') AS `rolename`'.
                 'FROM users AS `u`, roles AS `r`'.
                 'HAVING rolename != \'no role\' ';
        if($limit > 0)
            $sql = $sql.'LIMIT = '.$limit;
        $query = $this->db->query($sql);
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function get_user_role()
    {
        $this->db->select("r.role");
        $this->db->from("roles as r, users as u");
        $this->db->where("u.id = '".get_session_user_id()."' and u.role_id = r.id");
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $temp = $query->result();
            $temp2 = $temp[0];
            $result = $temp2->role;
            return $result;
        }
        else
            return false;
    }

    function get_roles()
    {
        $this->db->select("id, role");
        $this->db->from("roles");
        $query = $this->db->get();
        if($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    function change_user_role($email, $role)
    {
        $this->db->trans_start();
        $data = array('role_id' => $role);
        $this->db->where('email', $email);
        $this->db->update('users', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

    function update_user_info($id, $full_name, $address, $city, $country, $password, $email)
    {
        $this->db->trans_start();
        if($password == "" || $password == " " || $password == null)
            $data = array('fullname' => $full_name, 'address' => $address, 'city' => $city,
                'country' => $country, 'email' => $email);
        else
            $data = array('fullname' => $full_name, 'address' => $address, 'city' => $city,
                'country' => $country, 'password' => $password, 'email' => $email);
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

}

?>