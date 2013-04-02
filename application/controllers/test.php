<?php

class Test extends CI_Controller {

    function index(){
        $this->load->model("youtube_model");
        print_r($this->youtube_model->delete_youtube_video("8LVlMdlbEEE"));
    }

    function trim(){
        $text = "HTTP/1.1 200 OK GData-Version: 2.1 Date: Mon, 01 Apr 2013 23:17:57 GMT Expires: Mon, 01 Apr 2013 23:17:57 GMT Cache-Control: private, max-age=0 X-Content-Type-Options: nosniff X-Frame-Options: SAMEORIGIN X-XSS-Protection: 1; mode=block Content-Length: 0 Server: GSE Content-Type: text/html; charset=UTF-8";
        $status = substr($text, 9, 6);
        echo $status;
    }

}

?>