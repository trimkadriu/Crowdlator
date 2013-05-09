<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Drafts_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }

    function create_draft($user_id, $task_id, $draft_text)
    {
        $this->db->trans_start();
        $data = array(
            'user_id' => $user_id,
            'task_id' => $task_id,
            'draft_text' => $draft_text
        );
        $this->db->insert('drafts', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function get_draft_by_id($draft_id, $user_id = null)
    {
        $this->db->select("*");
        $this->db->from("drafts");
        $this->db->where("id", $draft_id);
        if($user_id)
            $this->db->where("user_id", $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_drafts_by_user_id($user_id, $limit = null)
    {
        $this->db->select("*");
        $this->db->from("drafts");
        $this->db->where("user_id", $user_id);
        if($limit)
            $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function delete_draft_by_id($id = null, $user_id = null, $task_id = null)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($task_id)
            $this->db->where('task_id', $task_id);
        $this->db->delete('drafts');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function update_draft_by_id($id, $draft_text, $user_id = null)
    {
        $this->db->trans_start();
        $data = array(
            'draft_text' => $draft_text
        );
        $this->db->where('id', $id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        $this->db->update('drafts', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }
}

?>