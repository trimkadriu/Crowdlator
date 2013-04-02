<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("tasks_model");
        $this->load->model("projects_model");
    }

    function index()
    {
        redirect(base_url());
    }

    function task()
    {
        $temp = func_get_args(0);
        if($temp)
            $task_id = $temp[0];
        if(!$task_id)
            redirect(base_url());
        if($this->session->userdata("loggedin") && check_permissions(get_session_roleid(), 'admin/translate/task_id'))
        {
            redirect(base_url('admin/translate/task_id/'.$task_id));
        }
        $task = $this->tasks_model->get_task_by_id($task_id)[0];
        if($task)
        {
            $project_id = $task->project_id;
            $project = $this->projects_model->select_project_by_id($project_id)->result()[0];
            $data['project_name'] = $project->project_name;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $task->text;
            $data['task_id'] = $task_id;
            $data['next_tasks'] = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
//            $data['next_tasks'] = $result;print_r($data['next_tasks']);exit;
            $this->load->view("public/public_translate", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This task does not exist.');
            redirect(base_url());
        }
    }
}
?>