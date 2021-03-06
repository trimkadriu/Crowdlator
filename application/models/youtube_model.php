<?php

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Youtube_model extends CI_Model {

    private $ds;
    private $application_location;

    function __construct()
    {
        parent:: __construct();
        $this->load->library("curl");
        $this->ds = DIRECTORY_SEPARATOR;
        $this->application_location = dirname(__FILE__).$this->ds.'..'.$this->ds;
    }

    function get_upload_token()
    {
        if($_POST)
        {
            $this->form_validation->set_rules('video_title', 'Video Title', 'trim|required|min_length[3]|max_length[50]|xss_clean|strip_tags');
            $this->form_validation->set_rules('video_description', 'Description', 'trim|required|min_length[2]|max_length[250]|xss_clean|strip_tags');
            if($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Please fill all required form fields in correct format.');
                redirect('admin/projects/create_project');
            }
            else
            {
                //Get youtube video upload token
                $login_response = $this->do_login();
                //Get post variables
                $youtube_video_title = strip_tags($this->input->post("video_title", TRUE));
                $youtube_video_description = $this->input->post("video_description", TRUE);
                //Load configuration variables
                $youtube_developer_key = $this->config->item("youtube_developer_key");
                $youtube_video_keywords = $this->config->item("youtube_keywords");
                $youtube_video_category = $this->config->item("youtube_category");
                list($auth, $youtubeuser) = explode("\n", $login_response);
                list($authlabel, $authvalue) = array_map("trim", explode("=", $auth));
                //list($youtubeuserlabel, $youtubeuservalue) = array_map("trim", explode("=", $youtubeuser));
                //Prepare request
                $data = '<?xml version="1.0"?>
                <entry xmlns="http://www.w3.org/2005/Atom"
                  xmlns:media="http://search.yahoo.com/mrss/"
                  xmlns:yt="http://gdata.youtube.com/schemas/2007">
                  <media:group>
                    <media:title type="plain">' . stripslashes( $youtube_video_title ) . '</media:title>
                    <media:description type="plain">' . stripslashes( $youtube_video_description ) . '</media:description>
                    <media:category
                      scheme="http://gdata.youtube.com/schemas/2007/categories.cat">'.$youtube_video_category.'</media:category>
                    <media:keywords>'.$youtube_video_keywords.'</media:keywords>
                  </media:group>
                </entry>';

                $headers = array( "Authorization: GoogleLogin auth=".$authvalue,
                    "GData-Version: 2",
                    "X-GData-Key: key=".$youtube_developer_key,
                    "Content-length: ".strlen( $data ),
                    "Content-Type: application/atom+xml; charset=UTF-8" );
                //Make request
                $curl = curl_init("http://gdata.youtube.com/action/GetUploadToken");
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_REFERER, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                //Set response
                $response = simplexml_load_string(curl_exec($curl));
                curl_close($curl);
                return $response;
            }
        }
        else
            redirect("admin/user/dashboard");
    }

    function do_login()
    {
        //Load Youtube authentication configuraiton
        $youtube_email = $this->config->item("youtube_email");
        $youtube_password = $this->config->item("youtube_password");
        $source = $this->config->item("youtube_source");
        //Make request on Youtube
        $postdata = "Email=".$youtube_email."&Passwd=".$youtube_password."&service=youtube&source=".$source;
        $curl = curl_init("https://www.google.com/youtube/accounts/ClientLogin");
        curl_setopt($curl, CURLOPT_HEADER, "Content-Type:application/x-www-form-urlencoded");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        //Set response
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function check_video_existence($video_id){
        $headers = get_headers('http://gdata.youtube.com/feeds/api/videos/'.$video_id);
        if (strpos($headers[0], '200'))
            return true;
        else
            return false;
    }

    function delete_youtube_video($video_id)
    {
        //Get youtube video upload token
        $login_response = $this->do_login();
        //Load configuration variables
        $youtube_user_id = $this->config->item("youtube_user_id");
        $youtube_developer_key = $this->config->item("youtube_developer_key");
        list($auth, $youtubeuser) = explode("\n", $login_response);
        list($authlabel, $authvalue) = array_map("trim", explode("=", $auth));
        //Header
        $headers = array( "Content-Type: application/atom+xml; charset=UTF-8",
            "X-GData-Key: key=".$youtube_developer_key,
            "Authorization: GoogleLogin auth=".$authvalue,
            "GData-Version: 2",
            "Host: gdata.youtube.com" );
        //Make request
        $request = "https://gdata.youtube.com/feeds/api/users/".$youtube_user_id."/uploads/".$video_id;
        $this->curl->create($request);
        $this->curl->http_header($headers);
        $this->curl->http_method("DELETE");
        $this->curl->option("RETURNTRANSFER", true);
        $this->curl->option("SSL_VERIFYHOST", 0);
        $this->curl->option("SSL_VERIFYPEER", 0);
        $this->curl->option("HEADER", true);
        $response = $this->curl->execute();
        $status = substr($response, 9, 6);
        if($status === "200 OK")
            return true;
        else
            return $response;
    }

    function get_video_details($video_id)
    {
        $data=@file_get_contents('http://gdata.youtube.com/feeds/api/videos/'.$video_id.'?v=2&alt=jsonc');
        if (false===$data)
            return false;
        $obj=json_decode($data);
        return $obj;
    }

    function download_video($project_id, $video_id)
    {
        /**
         * For video formats please refer to:
         * https://en.wikipedia.org/wiki/YouTube#Quality_and_codecs
         */
        $youtubedl_location = $this->application_location.'third_party'.$this->ds.'youtube-dl';
        $videos_location = $this->application_location.'..'.$this->ds.'videos'.$this->ds;
        $filename = $project_id."_".$video_id.".mp4";
        $video_link = "www.youtube.com/watch?v=".$video_id;
        $params = "-o \"".$videos_location.$filename."\" -f 22/18/34 ".$video_link;
        exec("python \"".$youtubedl_location."\" ".$params, $result);
        if(file_exists($videos_location.$filename))
            return true;
        return false;
    }

    function upload_direct_video($project_id, $video_id, $title, $description, $tags)
    {
        $videos_location = $this->application_location.'..'.$this->ds.'final_videos'.$this->ds;
        $filename = $project_id.'_'.$video_id.'.mp4';
        $video_path = $videos_location.$filename;
        if(!file_exists($video_path))
            return false;
        //Get youtube video upload token
        $login_response = $this->do_login();
        //Load configuration variables
        $youtube_user_id = $this->config->item("youtube_user_id");
        $youtube_developer_key = $this->config->item("youtube_developer_key");
        list($auth, $youtubeuser) = explode("\n", $login_response);
        list($authlabel, $authvalue) = array_map("trim", explode("=", $auth));
        $data = '<?xml version="1.0"?>
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <entry xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:yt="http://gdata.youtube.com/schemas/2007">
                    <media:group>
                        <media:title type="plain">' . $title . '</media:title>
                        <media:description type="plain">' . trim($description) . '</media:description>
                        <media:keywords>' . $tags . '</media:keywords>
                        <media:category scheme="http://gdata.youtube.com/schemas/2007/categories.cat">Music</media:category>
                    </media:group>
                </entry>';
        $headers = array(
            'Authorization: GoogleLogin auth=' . $authvalue,
            'GData-Version: 2',
            'X-GData-Key: key='.$youtube_developer_key,
            'Slug: ' . basename($video_path)
        );

        $tmpfname = tempnam("/tmp", "youtube");
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $data);
        fclose($handle);

        $post = array(
            'xml' => '@' . $tmpfname . ";type=application/atom+xml",
            'video' => '@' . $video_path . ";type=video/mp4",
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "uploads.gdata.youtube.com/feeds/api/users/default/uploads");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        $object = simplexml_load_string($content);
        return substr($object->id, strrpos($object->id, ':') + 1);
    }

}

?>