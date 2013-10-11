<?php

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if(!$this->config->item('test_controller'))
            redirect(base_url());
    }

    function index()
    {
        echo '<h3>Test</h3>'.
             '<a href="'.base_url().'/test/video">Video</a><br/>'.
             '<a href="'.base_url().'/test/audio">Audio</a><br/>';
    }

    function video()
    {
        echo 'Should return the Youtube TOKEN (a long string)<br/><br/>';
        $this->load->model("youtube_model");
        echo $this->youtube_model->do_login();
    }

    function audio()
    {
        echo 'Should return the SoundCloud TOKEN (a short string)<br/><br/>';
        $this->load->model("soundcloud_model");
        print_r($this->soundcloud_model->get_access_token());
    }

}