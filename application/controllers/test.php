<?php

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->config->item('test_controller'))
            redirect(base_url());
    }

    function index() {
        echo '<h3>Test</h3>'.
             '<a href="'.base_url().'/test/video">Video</a><br/>'.
             '<a href="'.base_url().'/test/audio">Audio</a><br/>'.
             '<a href="'.base_url().'/test/finalvideo">Final video<br/>';
    }

    function video() {
        $this->load->model("youtube_model");
        echo $this->youtube_model->do_login();
    }

    function audio() {
        $this->load->model("soundcloud_model");
        print_r($this->soundcloud_model->get_access_token());
    }

    function finalvideo() {
        $this->load->model("ffmpeg_model");
        $this->ffmpeg_model->generate_video('19', 'AqCJmD2rwLc', '109421575');
    }

    function deletefinal() {
        $this->load->model("projects_model");
        $this->projects_model->delete_final_video('19','AqCJmD2rwLc');
        echo 'done';
    }

}