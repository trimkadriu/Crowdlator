<?php

class Votes_model extends CI_Model {
	
    function __construct()
    {
        parent::__construct();
    }

    function create_new($translation_id = null, $audio_id = null, $user_id = null,
                        $type, $up_vote = null, $down_vote = null)
    {
		$this->db->trans_start();
		$data = array(
			'translation_id' => $translation_id,
            'audio_id' => $audio_id,
            'user_id' => $user_id,
			'type' => $type,
            'up_vote' => $up_vote,
            'down_vote' => $down_vote
		);
		$this->db->insert('votes', $data);
		$this->db->trans_complete();
        $log = $this->db->last_query();
		if($this->db->trans_status() === TRUE)
			return true;
		else
			return false;
	}

    function get_votes_by_parameters($id = null, $translation_id = null, $audio_id = null, $user_id = null,
                                     $type = null, $up_vote = null, $down_vote = null, $limit = null)
    {
        $this->db->select('*');
        $this->db->from('votes');
        if($id)
            $this->db->where('id', $id);
        if($translation_id)
            $this->db->where('translation_id', $translation_id);
        if($audio_id)
            $this->db->where('audio_id', $audio_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($type)
            $this->db->where('type', $type);
        if($up_vote)
            $this->db->where('up_vote', $up_vote);
        if($down_vote)
            $this->db->where('down_vote', $down_vote);
        if($limit)
            $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else{
            $temp = $query->result();
            return $temp[0]->count;
        }
    }

	function get_votes_count($id = null, $translation_id = null, $audio_id = null, $user_id = null,
                             $type = null, $up_vote = null, $down_vote = null)
	{
		$this->db->select('count(*) as count');
		$this->db->from('votes');
		if($id)
            $this->db->where('id', $id);
        if($translation_id)
            $this->db->where('translation_id', $translation_id);
        if($audio_id)
            $this->db->where('audio_id', $audio_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($type)
            $this->db->where('type', $type);
        if($up_vote)
            $this->db->where('up_vote', $up_vote);
        if($down_vote)
            $this->db->where('down_vote', $down_vote);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else{
            $temp = $query->result();
            return $temp[0]->count;
        }
	}

    function get_if_user_voted($translation_id = null, $audio_id = null, $user_id = null, $type = null)
    {
        $this->db->select("*");
        $this->db->from('votes');
        if($translation_id)
            $this->db->where('translation_id', $translation_id);
        if($audio_id)
            $this->db->where('audio_id', $audio_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($type)
            $this->db->where('type', $type);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        else
            return true;
    }

    function delete_votes($id = null, $translation_id = null, $audio_id = null, $user_id = null, $type = null,
                          $up_vote = null, $down_vote = null, $date_created = null)
    {
        $this->db->trans_start();
        if($id)
            $this->db->where('id', $id);
        if($translation_id)
            $this->db->where('translation_id', $translation_id);
        if($audio_id)
            $this->db->where('audio_id', $audio_id);
        if($user_id)
            $this->db->where('user_id', $user_id);
        if($type)
            $this->db->where('type', $type);
        if($up_vote)
            $this->db->where('up_vote', $up_vote);
        if($down_vote)
            $this->db->where('down_vote', $down_vote);
        if($date_created)
            $this->db->where('date_created', $date_created);
        $this->db->delete('votes');
        $this->db->trans_complete();
        $log = $this->db->last_query();
        if($this->db->trans_status() === TRUE)
            return true;
        else
            return false;
    }

}

?>