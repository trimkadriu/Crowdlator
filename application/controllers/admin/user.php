<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

    public function get_administrator_data()
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
        $data['right1'] = array('Share translations in social networks', '<i class="icon-share" style="margin-top:8px;"></i>',
            base_url('admin/projects/list_projects'));
        /*$data['right1'] = array('Add profile picture in user settings', '<i class="icon-picture" style="margin-top:8px;"></i>',
            base_url('admin/settings/account_settings'));*/
        $data['right2'] = array('', '', '');
        return $data;
    }

    public function get_editor_data()
    {
        $data['tasks'] = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        $data['translations'] = false;
        $data['offset'] = 5;
        if(sizeof($data['tasks']) < $data['offset'])
            $data['limit'] = sizeof($data['tasks']);
        else
            $data['limit'] = $data['offset'];
        if($data['tasks']){
            // Latest tasks
            for($i = 0; $i < sizeof($data['tasks']); $i++)
            {
                if($i > $data['limit']) break;
                $project_id = $data['tasks'][$i]->project_id;
                $temp_proj = $this->projects_model->select_project_by_id($project_id)->result();
                $project = $temp_proj[0];
                $data['projectname'][$i] = $project->project_name;
                $data['date'][$i] = $project->create_date;
                $data['from'][$i] = $project->translate_from_language;
                $data['to'][$i] = $project->translate_to_language;
                $data['type'][$i] = $data['tasks'][$i]->type;
            }
            // Latest translations
            $ids = array();
            for($i = 0; $i < sizeof($data['tasks']); $i++)
            {
                $ids[$i] = $data['tasks'][$i]->id;
            }
            if(sizeof($ids) > 0)
            {
                $data['translations'] = $this->translations_model->get_translations_by_task_ids($ids, $data['offset']);
            }
        }
        // START data for Forehead Message
        $data['title1'] = 'Get started:';
        $data['title2'] = 'Next steps:';
        $data['title3'] = '';
        $data['button'] = array('Vote translations', base_url("admin/translate/vote_translations"));
        $data['left1'] = array('List your tasks', '<i class="icon-list" style="margin-top:5px;"></i>',
            base_url('admin/projects/list_tasks'));
        $data['left2'] = array('Ask help for translation', '<i class="icon-share"></i>',
            base_url('admin/projects/list_tasks'));
        $data['right1'] = array('', '', '');
        $data['right2'] = array('', '', '');
        return $data;
    }

    public function get_super_editor_data()
    {
        // START data for Forehead Message
        $data['title1'] = 'Get started:';
        $data['title2'] = '';
        $data['title3'] = '';
        $data['button'] = array('Choose best translations', base_url("admin/translate/choose_translations"));
        $data['left1'] = array('', '', '');
        $data['left2'] = array('', '', '');
        $data['right1'] = array('', '', '');
        $data['right2'] = array('', '', '');
        return $data;
    }

    public function get_translator_data()
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
                $project = $this->projects_model->select_project_by_id($task->project_id)->result()[0];
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
        $data['title3'] = '';
        $data['button'] = array('Translate some tasks', base_url('admin/projects/list_projects'));
        $data['left1'] = array('Vote the best translation', '<i class="icon-thumbs-up" style="margin-top:8px;"></i>',
            base_url('admin/translate/vote_translations'));
        $data['left2'] = array('Share translations in social networks', '<i class="icon-share"></i>',
            base_url('admin/projects/list_projects'));
        $data['right1'] = array('', '', '');
        $data['right2'] = array('', '', '');
        return $data;
    }
}

?>