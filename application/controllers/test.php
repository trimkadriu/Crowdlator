<?php

class Test extends CI_Controller {

    function index(){
        $this->load->model("soundcloud_model");
        echo $this->soundcloud_model->get_access_token();
    }

}

?>