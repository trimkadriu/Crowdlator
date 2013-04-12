<?php

class Test extends CI_Controller {

    function index(){
        $this->load->model("soundcloud_model");
        //echo $this->soundcloud_model->get_access_token();
        $data['client_id'] = $this->config->item("soundcloud_client_id");
        $data['redirect_url'] = $this->config->item("soundcloud_redirect_url");
        $this->load->view("test", $data);
    }

}

?>