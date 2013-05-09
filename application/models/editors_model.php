<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Editors_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }
	
    function getThreeRandomEditors()
    {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('role_id', '2');
		$this->db->or_where('role_id', '4');
		$this->db->order_by('id', 'RANDOM');
		$this->db->limit(3);
		$query = $this->db->get();
		if($query->num_rows() == 0)
			return false;
		else
			return $query->result();
	}
	
	function get_editor_by_name($username)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('username', $username);
		//$this->db->where('`role_id` = (SELECT id FROM roles WHERE `role`=\'editor\')', NULL, false);
		//$this->db->or_where('`role_id` = (SELECT id FROM roles WHERE `role`=\'super_editor\')', NULL, false);
		$query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
	}
	
	function get_editor_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $id);
		$query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
	}

    function get_editors_by_filter($filter)
    {
        $sql = "SELECT id, username FROM users ".
                "WHERE role_id = '2' ".
                "AND username LIKE '%%".
                $this->db->escape_like_str($filter)."%%%' ".
                "LIMIT 10";
        $query = $this->db->query($sql);
        return $query->result();
    }
	
}

?>