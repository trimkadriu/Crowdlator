<?php

class Test extends CI_Controller {

    function index(){
        $this->load->model("soundcloud_model");
        $token = $this->soundcloud_model->get_access_token();
        if($token != null && $token != "" && is_array($token))
            $data['access_token'] = $token[0];
        else
            echo "Token could not be initialized.";
        $data['client_id'] = $this->config->item("soundcloud_client_id");
        $data['redirect_url'] = $this->config->item("soundcloud_redirect_url");
        $this->load->view("test", $data);
    }

}

?>