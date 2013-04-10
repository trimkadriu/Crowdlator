<?php

class Soundcloud_model extends CI_Model {

    function __construct()
    {
        parent:: __construct();
        $this->load->library("curl");
    }

    function get_access_token()
    {
        //Load Youtube authentication configuraiton
        $sc_client_id = $this->config->item("soundcloud_client_id");
        $sc_secret = $this->config->item("soundcloud_client_secret");
        $sc_user = $this->config->item("soundcloud_email");
        $sc_pass = $this->config->item("soundcloud_password");
        //SoundCloud token request - Python script
        $script_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'libraries/soundcloud_get_token.py';
        //Execute python script and save results
        exec("python \"".$script_path."\" ".$sc_client_id." ".$sc_secret." ".$sc_user." ".$sc_pass, $result);
        return $result[0];
    }

}

?>