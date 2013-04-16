<?php

class Translations_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }

    function create_translation($task_id, $user_id, $translated_text)
    {
        $this->db->trans_start();
        $data = array(
            'task_id' => $task_id,
            'user_id' => $user_id,
            'translated_text' => $translated_text
        );
        $this->db->insert('translations', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

    function count_translations($task_id)
    {
        $this->db->select('count(*) as nr');
        $this->db->from('translations');
        $this->db->where('task_id', $task_id);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function delete_translations_by_task_id($task_id)
    {
        $this->db->trans_start();
        $this->db->where('task_id', $task_id);
        $this->db->delete('translations');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

    function contributed_in_translation($user_id, $task_id)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('task_id', $task_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return true;
    }

    function get_translations_by_user_id($user_id)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('date_created', 'desc');
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_translations_by_task_ids($task_ids, $limit, $reviewed = null)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('`task_id` IN ('.implode(',',$task_ids).')');
        if($reviewed != null)
            $this->db->where('reviewed', $reviewed);
        $this->db->order_by('date_created', 'desc');
        if($limit)
            $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function edit_translation_by_editor($translation_id, $edited_translation)
    {
        $this->db->trans_start();
        $data = array(
            'translated_text' => $edited_translation,
            'reviewed' => '1'
        );
        $this->db->where('id', $translation_id);
        $this->db->update('translations', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

    function set_reviewed($translation_id, $reviewed){
        $this->db->trans_start();
        $data = array(
            'reviewed' => $reviewed
        );
        $this->db->where('id', $translation_id);
        $this->db->update('translations', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return $log;
    }

}

?>