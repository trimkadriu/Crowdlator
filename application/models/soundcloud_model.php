<?php

class Soundcloud_model extends CI_Model {

    private $sc_client_id;
    private $sc_secret;
    private $sc_user;
    private $sc_pass;

    private $ds;
    private $script_location;

    function __construct()
    {
        parent:: __construct();
        $this->load->library("curl");
        //Load SoundCloud authentication configuraiton
        $this->sc_client_id = $this->config->item("soundcloud_client_id");
        $this->sc_secret = $this->config->item("soundcloud_client_secret");
        $this->sc_user = $this->config->item("soundcloud_email");
        $this->sc_pass = $this->config->item("soundcloud_password");
        $this->ds = DIRECTORY_SEPARATOR;
        $this->script_location = dirname(__FILE__).$this->ds.'..'.$this->ds.'libraries'.$this->ds;
    }

    function get_access_token()
    {
        //SoundCloud token request - Python script
        $script_location = $this->script_location.'Soundcloud_get_token.py';
        $params = $this->sc_client_id." ".$this->sc_secret." ".$this->sc_user." ".$this->sc_pass;
        //Execute python script and save results
        exec("python \"".$script_location."\" ".$params, $result);
        return $result;
    }

    function delete_track($id)
    {
        //SoundCloud token request - Python script
        $script_location = $this->script_location.'Soundcloud_delete_track.py';
        $params = $this->sc_client_id." ".$this->sc_secret." ".$this->sc_user." ".$this->sc_pass." ".$id;
        //Execute python script and save results
        exec("python \"".$script_location."\" ".$params, $result);
        if($result[0] == "ok")
            return true;
        return false;
    }

}

?>