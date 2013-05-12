<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Ffmpeg_model extends CI_Model {

    private $ds;
    private $application_location;

    function __construct()
    {
        parent::__construct();
        $this->ds = DIRECTORY_SEPARATOR;
        $this->application_location = dirname(__FILE__).$this->ds.'..'.$this->ds;
    }

    function generate_video($project_id, $video_id, $audio_id)
    {
        $video_duration = $this->youtube_model->get_video_details($video_id)->data->duration;
        if(!$video_duration)
            return false;
        $os = strtolower(stristr(php_uname('s'), ' ', true));
        $final_location = $this->application_location.'..'.$this->ds.'final_videos'.$this->ds;
        if($os === "windows")
            $ffmpeg_location = $this->application_location.'third_party'.$this->ds.'ffmpeg'.$this->ds.'windows'.$this->ds.'ffmpeg.exe';
        elseif($os === "linux")
            $ffmpeg_location = $this->application_location.'third_party'.$this->ds.'ffmpeg'.$this->ds.'linux'.$this->ds.'ffmpeg';
        else
            return false;
        $videos_location = $this->application_location.'..'.$this->ds.'videos'.$this->ds;
        $video_filename = $project_id."_".$video_id.".mp4";
        $audios_location = $this->application_location.'..'.$this->ds.'audios'.$this->ds;
        $audio_filename= $project_id."_".$audio_id.".mp3";
        $params = "-i \"".$videos_location.$video_filename."\" -i \"".$audios_location.$audio_filename.
            "\" -y -map 0:0 -map 1 -vcodec copy -acodec copy -t ".$video_duration." \"".$final_location.$video_filename."\"";
        exec("\"".$ffmpeg_location."\" ".$params, $result);
        if(file_exists($final_location.$video_filename))
        {
            unlink($videos_location.$video_filename);
            unlink($audios_location.$audio_filename);
            return true;
        }
        else
            return false;
    }

    function check_already_generated_video($project_id, $video_id)
    {
        $final_location = $this->application_location.'..'.$this->ds.'final_videos'.$this->ds;
        $video_filename = $project_id."_".$video_id.".mp4";
        if(file_exists($final_location.$video_filename))
            return true;
        else
            return false;
    }

}

?>