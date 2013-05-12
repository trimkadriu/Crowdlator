<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
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
            return false;
    }

    function get_all_translations()
    {
        $this->db->select('*');
        $this->db->from('translations');
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
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
            return false;
    }

    function delete_not_choosen_translations_by_task_id($task_id)
    {
        $this->db->trans_start();
        $this->db->where('task_id', $task_id);
        $this->db->where('choosen', 0);
        $this->db->delete('translations');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
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

    function get_translations_by_user_id($user_id, $limit = null)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('date_created', 'desc');
        if($limit)
            $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_translation_by_id($translation_id)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('id', $translation_id);
        $this->db->order_by('date_created', 'desc');
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_translation_by_params($id = null, $task_id = null, $user_id = null, $date_created = null,
                                       $reviewed = null, $approved = null, $choosen = null)
    {
        $this->db->select('*');
        $this->db->from('translations');
        if($id)
            $this->db->where('id', $id);
        if($task_id)
            $this->db->where('task_id', $task_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($date_created)
            $this->db->where('date_created', $date_created);
        if($reviewed)
            $this->db->where('reviewed', $reviewed);
        if($approved)
            $this->db->where('approved', $approved);
        if($choosen)
            $this->db->where('choosen', $choosen);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function check_translation_by_task_id($task_id)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('task_id', $task_id);
        //$this->db->order_by('date_created', 'desc');
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function delete_translation_by_id($translation_id = null, $task_id = null, $user_id = null, $date_created = null,
                                      $reviewed = null, $approved = null, $choosen = null)
    {
        $this->db->trans_start();
        if($translation_id)
            $this->db->where('id', $translation_id);
        if($task_id)
            $this->db->where('task_id', $task_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($date_created)
            $this->db->where('date_created', $date_created);
        if($reviewed)
            $this->db->where('reviewed', $reviewed);
        if($approved)
            $this->db->where('approved', $approved);
        if($choosen)
            $this->db->where('choosen', $choosen);
        $this->db->delete('translations');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function get_translations_by_task_ids($task_ids, $limit = null, $reviewed = null, $approved = null)
    {
        $this->db->select('*');
        $this->db->from('translations');
        $this->db->where('`task_id` IN ('.implode(',',$task_ids).')');
        if($reviewed != null)
            $this->db->where('reviewed', $reviewed);
        if($approved != null)
            $this->db->where('approved', $approved);
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
            return false;
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
            return false;
    }

    function set_approved($translation_id, $approved){
        $this->db->trans_start();
        $data = array(
            'approved' => $approved
        );
        $this->db->where('id', $translation_id);
        $this->db->update('translations', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function set_choosen($translation_id, $choosen){
        $this->db->trans_start();
        $data = array(
            'choosen' => $choosen
        );
        $this->db->where('id', $translation_id);
        $this->db->update('translations', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function get_all_translations_where_choosen_not($choosen)
    {
        $sql = "SELECT * FROM `translations` WHERE approved = 1 AND task_id NOT IN (SELECT task_id FROM `translations` WHERE choosen = " . $choosen . ");";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function check_translation_if_choosen($task_id)
    {
        $sql = "SELECT * FROM `translations` WHERE `task_id` = ".$task_id." AND `choosen` = 1;";
        $query = $this->db->query($sql);
        if($query->num_rows() == 0)
            return false;
        else
            return true;
    }

}

?>