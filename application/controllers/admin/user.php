<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class User extends CI_Controller {
	
	function __construct()
    {
        parent::__construct();
		$this->load->model('login_request_model');
        $this->load->model('editors_model');
        $this->load->model('tasks_model');
        $this->load->model('projects_model');
        $this->load->model('translations_model');
        $this->load->model('drafts_model');
    }

	public function index()
	{
		//PASS SOME DATA DEPENDING ON USER ROLE
        if(get_user_role() == 'administrator')
        {
            $data = $this->get_administrator_data();
        }
        if(get_user_role() == 'editor')
        {
            $data = $this->get_editor_data();
        }
        if(get_user_role() == 'super editor')
        {
            $data = $this->get_super_editor_data();
        }
        if(get_user_role() == 'translator')
        {
            $data = $this->get_translator_data();
        }
		$this->load->view('admin/dashboard', $data);
	}
	
	public function dashboard()
	{
		$this->index();
	}
	
	public function login()
	{
		//Validate form inputs
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[32]|md5');
		
		if ($this->form_validation->run() == FALSE)
		{
			//Validation failed
			$this->session->set_flashdata('message_type', 'error');
			$this->session->set_flashdata('message', 'The username and/or password are blank or have validation errors.');
			redirect('pages/home');
		}
		else
		{
			$data['login'] = $this->login_request_model->do_login($this->input->post("username"), $this->input->post("password"));
			if($data['login'])
			{
				//USER APROVED LOGIN
				$this->session->set_userdata('loggedin', $data['login']['loggedin']);
                $this->session->set_userdata('user_id', $data['login']['user_id']);
				$this->session->set_userdata('username', $data['login']['username']);
				$this->session->set_userdata('fullname', $data['login']['fullname']);
				$this->session->set_userdata('roleid', $data['login']['roleid']);
				//LOAD VIEW
				redirect('admin/user/dashboard');
			}
			else
			{
				//ACCESS DENIED or USERNAME/PASSWORD do not match
				$this->session->set_flashdata('message_type', 'error');
				$this->session->set_flashdata('message', 'The username and/or password do not match.');
				redirect('pages/home');
			}
		}
	}
	
	public function logout()
	{
		//Destroy SESSION for logout
		$this->session->sess_destroy();
		redirect('pages/home');
	}

    private function get_administrator_data()
    {
        $data['editors'] = $this->editors_model->getThreeRandomEditors();
        $data['projects'] = $this->projects_model->get_latest_projects(5, get_session_user_id());

        // START data for Forehead Message
        $data['title1'] = 'Get started:';
        $data['title2'] = 'Next steps:';
        $data['title3'] = 'More action:';
        $data['button'] = array('Create a project', base_url('admin/projects/create_project'));
        $data['left1'] = array('List all projects', '<i class="icon-tasks" style="margin-top:8px;"></i>',
            base_url('admin/projects/list_projects'));
        $data['left2'] = array('Vote the best translation', '<i class="icon-thumbs-up"></i>',
            base_url('admin/translate/vote_translations'));
        $data['right1'] = array('See statuses of your projects', '<i class="icon-info-sign" style="margin-top:8px;"></i>',
            base_url('admin/projects/projects_status'));
        $data['right2'] = array('Share translations in social networks', '<i class="icon-share"></i>',
            base_url('admin/projects/list_projects'));
        return $data;
    }

    private function get_editor_data()
    {
        $tasks = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        $data['translations'] = false;
        $data['limit'] = 5;
        if($tasks){
            // Latest tasks
            $ids = array();
            for($i = 0; $i < sizeof($tasks); $i++)
            {
                $temp_proj = $this->projects_model->select_project_by_id($tasks[$i]->project_id)->result();
                $project = $temp_proj[0];
                $ids[$i] = $tasks[$i]->id;
                $data['tasks'][$i] = $tasks[$i];
                $data['projectname'][$i] = $project->project_name;
                $data['project_status'][$i] = $project->status;
                $data['date'][$i] = $project->create_date;
                $data['from'][$i] = $project->translate_from_language;
                $data['to'][$i] = $project->translate_to_language;
            }
            // Latest translations
            if(sizeof($ids) > 0)
            {
                $translations = $this->translations_model->get_translations_by_task_ids($ids, false);//print_r($translations);exit;
                for($i = 0; $i < sizeof($translations); $i++)
                {
                    $task = $this->tasks_model->get_task_by_id($translations[$i]->task_id)[0];
                    $temp_proj = $this->projects_model->select_project_by_id($task->project_id)->result();
                    $project = $temp_proj[0];
                    $data['translations'][$i] = $translations[$i];
                    $data['tr_project_status'][$i] = $project->status;
                }//print_r($data['translations']);exit;
            }
        }
        // START data for Forehead Message
        $data['title1'] = 'Get started:';
        $data['title2'] = 'Next steps:';
        $data['title3'] = 'More action';
        $data['button'] = array('Vote translations', base_url("admin/translate/vote_translations"));
        $data['left1'] = array('List your tasks', '<i class="icon-list" style="margin-top:5px;"></i>',
            base_url('admin/projects/list_tasks'));
        $data['left2'] = array('Ask help for translation', '<i class="icon-share"></i>',
            base_url('admin/projects/list_tasks'));
        $data['right1'] = array('Vote the best audios', '<i class="icon-thumbs-up" style="margin-top:8px;"></i>',
            base_url('admin/translate/vote_translations/1'));
        $data['right2'] = array('List my translated tasks', '<i class="icon-tasks"></i>',
            base_url('admin/translate/translations'));
        return $data;
    }

    private function get_super_editor_data()
    {
        // START data for Forehead Message
        $data['title1'] = 'Get started:';
        $data['title2'] = 'Next steps';
        $data['title3'] = 'More action';
        $data['button'] = array('Choose best translations', base_url("admin/translate/choose_translations"));
        $data['left1'] = array('Vote the best translation', '<i class="icon-thumbs-up" style="margin-top:8px;"></i>',
            base_url('admin/translate/vote_translations'));
        $data['left2'] = array('See projects statuses', '<i class="icon-info-sign"></i>',
            base_url('admin/projects/projects_status'));
        $data['right1'] = array('Vote the best audios', '<i class="icon-thumbs-up" style="margin-top:8px;"></i>',
            base_url('admin/translate/vote_translations/1'));
        $data['right2'] = array('Choose best audios', '<i class="icon-check"></i>',
            base_url("admin/translate/choose_translations/1"));
        return $data;
    }

    private function get_translator_data()
    {
        //My drafts component
        $data['drafts'] = $this->drafts_model->get_drafts_by_user_id(get_session_user_id(), 5);
        //My translation component
        $translations = $this->translations_model->get_translations_by_user_id(get_session_user_id(), 5);
        $data['translation_nr'] = false;
        if($translations)
        {
            $data['translation_nr'] = sizeof($translations);
            for($i = 0; $i < $data['translation_nr']; $i++)
            {
                $task = $this->tasks_model->get_task_by_id($translations[$i]->task_id)[0];
                $temp = $this->projects_model->select_project_by_id($task->project_id)->result();
                $project = $temp[0];
                $data['translation_id'][$i] = $translations[$i]->id;
                $data['project_name'][$i] = $project->project_name;
                $data['translated_date'][$i] = $translations[$i]->date_created;
                $data['translated_from'][$i] = $project->translate_from_language;
                $data['translated_to'][$i] = $project->translate_to_language;
            }
        }
        // START data for Forehead Message
        $data['title1'] = 'Get started:';
        $data['title2'] = 'Next steps:';
        $data['title3'] = 'More action';
        $data['button'] = array('Translate some tasks', base_url('admin/projects/list_projects'));
        $data['left1'] = array('Vote the best translation', '<i class="icon-thumbs-up" style="margin-top:8px;"></i>',
            base_url('admin/translate/vote_translations'));
        $data['left2'] = array('Share translations in social networks', '<i class="icon-share"></i>',
            base_url('admin/projects/list_projects'));
        $data['right1'] = array('Vote the best audios', '<i class="icon-thumbs-up" style="margin-top:8px;"></i>',
            base_url('admin/translate/vote_translations/1'));
        $data['right2'] = array('View my draft list', '<i class="icon-th-list"></i>',
            base_url('admin/translate/draft_list'));
        return $data;
    }
}

?>