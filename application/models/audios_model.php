<?php

class Audios_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }

    function create_audio($project_id, $user_id, $audio_id, $permalink_url, $download_url)
    {
        $this->db->trans_start();
        $data = array(
            'project_id' => $project_id,
            'user_id' => $user_id,
            'audio_id' => $audio_id,
            'permalink_url' => $permalink_url,
            'download_url' => $download_url
        );
        $this->db->insert('audios', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function get_audios($id = null, $project_id = null, $audio_id = null, $user_id = null, $date_created = null, $choosen = null)
    {
        $this->db->select("*");
        $this->db->from("audios");
        if($id)
            $this->db->where("id", $id);
        if($project_id)
            $this->db->where("project_id", $project_id);
        if($audio_id)
            $this->db->where("audio_id", $audio_id);
        if($user_id)
            $this->db->where("user_id", $user_id);
        if($date_created)
            $this->db->where("date_created", $date_created);
        if($choosen)
            $this->db->where("choosen", $choosen);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function get_all_audios_where_choosen_not($choosen)
    {
        $sql = "SELECT * FROM `audios` WHERE project_id NOT IN (SELECT project_id FROM `audios` WHERE choosen = " . $choosen . ");";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return false;
        else
            return $query->result();
    }

    function set_choosen($audio_id, $choosen)
    {
        $this->db->trans_start();
        $data = array(
            'choosen' => $choosen
        );
        $this->db->where('id', $audio_id);
        $this->db->update('audios', $data);
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

    function delete_audio($id = null, $project_id = null, $audio_id = null, $user_id = null, $date_created = null)
    {
        $this->db->trans_start();
        if($id)
            $this->db->where('id', $id);
        if($project_id)
            $this->db->where('project_id', $project_id);
        if($audio_id)
            $this->db->where('audio_id', $audio_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($date_created)
            $this->db->where('date_created', $date_created);
        $this->db->delete('audios');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

}

?>