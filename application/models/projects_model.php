<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Projects_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }
	
    function create_new($project_name, $project_description, $translate_from_language, $translate_to_language,
                        $text, $microtasks_by, $break_text, $hashtags)
    {
		$this->load->model("users_model");
		$admin_id = $this->users_model->get_user_by_username(get_session_username())->row()->id;
		$this->db->trans_start();
		$data = array(
			'admin_id' => $admin_id,
			'project_name' => $project_name,
			'project_description' => $project_description,
			'translate_from_language' => $translate_from_language,
			'translate_to_language' => $translate_to_language,
			//'translations_per_task' => $translations,
			'microtasks_by' => $microtasks_by,
			'break_text' => $break_text,
			'hash_tags' => $hashtags,
            'text' => $text,
			'status' => 'In Translation'
		);
		$this->db->insert('projects', $data);
		$this->db->trans_complete();
        $log = $this->db->last_query();
		if($this->db->trans_status() === TRUE)
			return true;
		else
            return false;
	}
	
	function select_project_by_name($project_name)
	{
		//Check if PROJECT exists by project NAME
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('project_name', $project_name);
		$query = $this->db->get();
		return $query;
	}

    function select_project_by_id($id)
    {
        //Check if PROJECT exists by ID
        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query;
    }

    function select_project_by_user($username)
	{
		//Check if PROJECT exists by ID
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('admin_id =(SELECT id FROM users WHERE username = \''.$username.'\')', NULL, false);
		$query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function select_projects()
    {
        $this->db->select('*');
        $this->db->from('projects');
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_project_by_params($id = null, $admin_id = null, $project_name = null, $video_id = null,
                                    $create_date = null, $status = null)
    {
        $this->db->select('*');
        $this->db->from('projects');
        if($id)
            $this->db->where("id", $id);
        if($admin_id)
            $this->db->where("admin_id", $admin_id);
        if($project_name)
            $this->db->where("project_name", $project_name);
        if($video_id)
            $this->db->where("video_id", $video_id);
        if($create_date)
            $this->db->where("create_date", $create_date);
        if($status)
            $this->db->where("status", $status);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_random_projects($limit, $status = null, $user_id = null)
    {
        $this->db->select("*");
        $this->db->from("projects");
        if($status)
        {
            foreach($status as &$value)
                $value = "'".$value."'";
            $this->db->where("`status` IN (".implode(',', $status).")");
        }
        if($user_id)
            $this->db->where("admin_id", $user_id);
        $this->db->order_by('id', 'RANDOM');
        $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function check_project_by_user($username, $project_id)
	{
		//Check if PROJECT is created by specific USER
		$this->db->select('*');
		$this->db->from('projects');
        $this->db->where('id', $project_id);
		$this->db->where('admin_id =(SELECT id FROM users WHERE username = \''.$username.'\')', NULL, false);
		$query = $this->db->get();
		if($query->num_rows() == 0)
			return false;
		else
			return true;
	}

    function delete_project_by_id($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete('projects');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function get_latest_projects($limit, $admin_id)
    {
        $this->db->select('*');
        $this->db->from('projects');
        $this->db->where('admin_id', $admin_id);
        $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function edit_project_details($project_id, $project_name, $project_description, $hash_tags, $user_id = null)
    {
        $this->db->trans_start();
        $data = array(
            'project_name' => $project_name,
            'project_description' => $project_description,
            'hash_tags' => $hash_tags
        );
        $this->db->where('id', $project_id);
        if($user_id)
            $this->db->where('admin_id', $user_id);
        $this->db->update('projects', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function update_project_status($project_id, $status, $user_id = null)
    {
        $this->db->trans_start();
        $data = array(
            'status' => $status
        );
        $this->db->where('id', $project_id);
        if($user_id)
            $this->db->where('admin_id', $user_id);
        $this->db->update('projects', $data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function update_project_translated_text($project_id, $text, $user_id = null)
    {
        $this->db->trans_start();
        $data = array(
            'translated_text' => $text
        );
        $this->db->where('id', $project_id);
        if($user_id)
            $this->db->where('admin_id', $user_id);
        $this->db->update('projects', $data);
        $this->db->trans_complete();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function set_video_id($project_id, $video_id){
        $this->db->trans_start();
        $data = array(
            'video_id' => $video_id
        );
        $this->db->where('id', $project_id);
        $this->db->update('projects', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

}

?>