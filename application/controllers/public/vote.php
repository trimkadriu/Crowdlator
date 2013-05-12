<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Vote extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("translations_model");
        $this->load->model("tasks_model");
        $this->load->model("projects_model");
        $this->load->model("audios_model");
    }

    function index()
    {
        redirect(base_url());
    }

    function translation()
    {
        $temp = func_get_args(0);
        if($temp)
            $translation_id = $temp[0];
        if(!$translation_id)
            redirect(base_url());
        if($this->session->userdata("loggedin") && check_permissions(get_session_roleid(), 'admin/translate/vote'))
        {
            redirect(base_url('admin/translate/vote_translations'));
        }
        get_if_voted_by_id_type($translation_id, "text");
        $translation = $this->translations_model->get_translation_by_id($translation_id)[0];
        $task = $this->tasks_model->get_task_by_id($translation->task_id)[0];
        $temp1 = $this->projects_model->select_project_by_id($task->project_id)->result();
        $project = $temp1[0];
        if($translation)
        {
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $task->text;
            $data['translated'] = $translation->translated_text;
            $data['translation_id'] = $translation_id;
            $data['project_name'] = $project->project_name;
            $data['project_video_id'] = $project->video_id;
            $data['project_description'] = $project->project_description;
            $data['next_tasks'] = $this->tasks_model->get_tasks_by_project_id($project->id)->result();
            $data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $data['translation_type'] = "text";
            $this->load->view("public/public_vote", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This translation does not exist.');
            redirect(base_url());
        }
    }

    function audio()
    {
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        if(!$project_id)
            redirect(base_url());
        if($this->session->userdata("loggedin") && check_permissions(get_session_roleid(), 'admin/translate/vote'))
        {
            redirect(base_url('admin/translate/vote_translations/1'));
        }
        get_if_voted_by_id_type($project_id, "audio");
        $audio = $this->audios_model->get_audios(null, $project_id, null, null, null)[0];
        $temp2 = $this->projects_model->select_project_by_id($project_id)->result();
        $project = $temp2[0];
        if($audio)
        {
            $data['audio_id'] = $audio->audio_id;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $project->translated_text;
            $data['translation_id'] = $audio->id;
            $data['project_name'] = $project->project_name;
            $data['project_video_id'] = $project->video_id;
            $data['project_description'] = $project->project_description;
            $data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $data['translation_type'] = "audio";
            $this->load->view("public/public_vote_audio", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This audio does not exist.');
            redirect(base_url());
        }
    }

}
?>