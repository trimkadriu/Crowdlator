<?php

class Tasks_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }
	
    function create_new($project_id, $text)
    {
		$this->db->trans_start();
		$data = array(
			'project_id' => $project_id,
			'text' => $text,
		);
		$this->db->insert('tasks', $data);
		$this->db->trans_complete();
        $log = $this->db->last_query();
		if($this->db->trans_status() === TRUE)
			return true;
		else
			return $log;
	}
	
	function get_tasks_by_project_id($id)
	{
		$this->db->select('*');
		$this->db->from('tasks');
		$this->db->where('project_id', $id);
		$query = $this->db->get();
		return $query;
	}

    function get_task_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('tasks');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_task_by_user_id($user_id)
    {
        $this->db->select('*');
        $this->db->from('tasks');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function update_tasks($project_id, $id, $editor)
	{
		$this->db->trans_start();
		$data = array('editor_id' => $editor);
		$this->db->where('project_id', $project_id);
		$this->db->where('id', $id);
		$this->db->update('tasks', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
		if($this->db->trans_status() === TRUE)
			return true;
		else
			return $log;
	}

    function delete_tasks_by_project_id($project_id)
    {
        $this->db->trans_start();
        $this->db->where('project_id', $project_id);
        $this->db->delete('tasks');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

    function get_tasks_by_editor($editor_id)
    {
        $this->db->select('*');
        $this->db->from('tasks');
        $this->db->where('editor_id', $editor_id);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }
	
}

?>