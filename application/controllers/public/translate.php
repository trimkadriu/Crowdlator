<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Translate extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("tasks_model");
        $this->load->model("projects_model");
        $this->load->model("youtube_model");
        $this->load->model("soundcloud_model");
    }

    function index()
    {
        redirect();
    }

    function task()
    {
        $temp = func_get_args(0);
        if($temp)
            $task_id = $temp[0];
        if(!$task_id)
            redirect();
        $temp1 = $this->tasks_model->get_task_by_id($task_id);
        $task = $temp1[0];
        $project_id = $task->project_id;
        $temp = $this->projects_model->select_project_by_id($project_id)->result();
        $project = $temp[0];
        if($project->status != "In Translation")
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This task belongs to a project which is not in translation stage.');
            redirect();
        }
        if($this->session->userdata("loggedin") && check_permissions(get_session_roleid(), 'admin/translate/task_id'))
        {
            redirect(base_url('admin/translate/task_id/'.$task_id));
        }
        if($task)
        {
            $data['project_name'] = $project->project_name;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['project_video_id'] = $project->video_id;
            $data['project_description'] = $project->project_description;
            $data['text'] = $task->text;
            $data['task_id'] = $task_id;
            $data['next_tasks'] = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
//            $data['next_tasks'] = $result;print_r($data['next_tasks']);exit;
            $data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $this->load->view("public/public_translate", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This task does not exist.');
            redirect();
        }
    }

    function audio()
    {
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        if(!$project_id)
            redirect(base_url());
        if($this->session->userdata("loggedin") && check_permissions(get_session_roleid(), 'admin/translate/audio_audition'))
        {
            redirect(base_url('admin/translate/audio_audition/'.$project_id));
        }
        $temp = $this->projects_model->get_project_by_params($project_id, null, null, null, null, "In Audition");
        $project = $temp[0];
        if($project)
        {
            $token = $this->soundcloud_model->get_access_token();
            if($token != null && $token != "" && is_array($token))
                $data['access_token'] = $token[0];
            else
                $data['access_token'] = "error";
            $data['project_name'] = $project->project_name;
            $data['project_id'] = $project_id;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['project_video_id'] = $project->video_id;
            $data['video_duration'] = $this->youtube_model->get_video_details($project->video_id)->data->duration;
            $data['project_description'] = $project->project_description;
            $data['translated_text'] = $project->translated_text;
            //$data['access_token'] = $token;
            $data['client_id'] = $this->config->item("soundcloud_client_id");
            $data['redirect_url'] = $this->config->item("soundcloud_redirect_url");
            $data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $this->load->view("public/public_audio", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project does not exists or is not in audition stage.');
            redirect();
        }
    }

    function validate_audio_captcha()
    {
        //reCaptcha Validation
        $this->load->library("recaptcha");
        $this->recaptcha->recaptcha_check_answer(
            $_SERVER['REMOTE_ADDR'],
            $this->input->post('recaptcha_challenge_field'),
            $this->input->post('recaptcha_response_field')
        );
        $data['return_result'] = $this->recaptcha->is_valid;
        echo json_encode($data);
    }

    function project()
    {
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        if(!$project_id)
            redirect();
        $temp = $this->projects_model->select_project_by_id($project_id)->result();
        $project = $temp[0];
        if($project)
        {
            if($project->status === "In Translation")
            {
                $temp = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
                $tasks = $temp[0];
                redirect("public/translate/task/".$tasks->id);
            }
            elseif($project->status === "In Audition")
                redirect("public/translate/audio/".$project_id);
            else
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This project is not in translation or in audition stage.');
                redirect();
            }
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project does not exists.');
            redirect();
        }
    }

}
?>