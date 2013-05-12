<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('ffmpeg_model');
    }

    function index()
    {
        $project_id = "6"; $video_id = "E1vz_X7WPVE";
        $this->load->helper('download');
        $ds = DIRECTORY_SEPARATOR;
        $final_location = dirname(__FILE__).$ds.'..'.$ds.'..'.$ds.'final_videos'.$ds;
        $video_filename = $project_id."_".$video_id.".mp4";
        $data = file_get_contents($final_location.$video_filename);
        $name = 'final_video_'.generateRandomString(5).'.mp4';
        force_download($name, $data);
    }

}
?>