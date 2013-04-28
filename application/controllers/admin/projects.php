<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('editors_model');
		$this->load->model("tasks_model");
		$this->load->model("projects_model");
        $this->load->model("translations_model");
        $this->load->model("youtube_model");
    }

	private function break_text($text, $type, $break_number)
	{
		//Break text function needed by CREATE PROJECT
		//page to create micro tasks for editors
        $index = 0; $tmp = array(); $result = array(); $results = array();
		if($type == 'sentences')
        {
			$tmp = explode(".", $text);
			while ($tmp) {
				$result[] = implode('.', array_splice($tmp, 0, $break_number));
			}
        }
		//For PARAGRAPHS text break
		if($type == 'paragraphs')
        {
            $text = preg_replace("\n(\s*\n)", "\n", $text);
			$tmp = explode("\n", $text);
			while ($tmp) {
				$result[] = implode('\n', array_splice($tmp, 0, $break_number));
			}
        }
		//Check if results contain empty/null rows
		/*foreach($result as $row)
		{
            if($row == " " || $row == "" || $row == null || strpos($row, "\n") || strpos($row, "\r"))
            {
                $results[] $row.'------------------';
                //unset($result[$index]);
                //$result[$index] = trim($row);
            }
            $index++;
		}*/
		return $result;
	}

	public function index()
	{
		redirect("admin/user/dashboard");
	}

	public function create_project()
	{
		if(!check_permissions(get_session_roleid(), 'admin/projects/create_project'))
			redirect("pages/permission_exception");
		if(!$_POST)
		{
			//GET Request
			$this->load->view('admin/projects/create_project');
		}
		else
		{
			//POST Request
			//Validate form fields
			$this->form_validation->set_rules('project_name', 'Project name', 'trim|required|min_length[3]|max_length[50]|xss_clean|strip_tags');
			$this->form_validation->set_rules('project_description', 'Description', 'trim|required|min_length[2]|max_length[250]|xss_clean|strip_tags');
			$this->form_validation->set_rules('translate_from_language', 'Translate from language', 'trim|required|min_length[2]|max_length[30]|xss_clean|strip_tags');
			$this->form_validation->set_rules('translate_to_language', 'Translate to language', 'trim|required|min_length[2]|max_length[30]|xss_clean|strip_tags');
			//$this->form_validation->set_rules('translations', 'Translations per task', 'trim|required|min_length[1]|max_length[2]|xss_clean|strip_tags');
			$this->form_validation->set_rules('microtasks_by', 'Microtasks assigned by','trim|required|min_length[3]|max_length[15]|xss_clean|strip_tags');
			$this->form_validation->set_rules('break_text', 'Break text number', 'trim|required|min_length[1]|max_length[2]|xss_clean|strip_tags');
			$this->form_validation->set_rules('paste_text', 'Text to be translated', 'trim|required|min_length[5]|xss_clean|strip_tags');
			$this->form_validation->set_rules('hashtags', 'Project Hashtags', 'trim|required|min_length[5]|max_length[50]|xss_clean|strip_tags');

			//If validation fails
			if ($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('message_type', 'error');
				$this->session->set_flashdata('message', 'Please fill all required form fields in correct format.');
				redirect('admin/projects/create_project');
			}
			else
			{
				//Get POST data
				$project_name = $this->input->post("project_name", TRUE);
				$project_description = $this->input->post("project_description", TRUE);
				$translate_from_language = $this->input->post("translate_from_language", TRUE);
				$translate_to_language = $this->input->post("translate_to_language", TRUE);
				//$translations = $this->input->post("translations", TRUE);
				$microtasks_by = $this->input->post("microtasks_by", TRUE);
				$break_text = $this->input->post("break_text", TRUE);
				$paste_text = $this->input->post("paste_text", TRUE);
				$hashtags = $this->input->post("hashtags", TRUE);
				//Create Project
				$create_project = $this->projects_model->create_new($project_name, $project_description,
                    $translate_from_language, $translate_to_language, $microtasks_by, $break_text, $paste_text, $hashtags);
				if($create_project)
				{
					//Create tasks
					$broken_text = $this->break_text($paste_text, $microtasks_by, $break_text); //Break TEXT
					$project_id = $this->projects_model->select_project_by_name($project_name)->row()->id;
					foreach($broken_text as $broken) //Create microtask for each text piece
					{
					    $create_task = $this->tasks_model->create_new($project_id, $broken);
					}
					//Success
                    $this->session->set_userdata("project_id", $project_id);
                    $data['project_status'] = true;
                    echo json_encode($data);
					/*$this->session->set_flashdata('message_type', 'success');
					$this->session->set_flashdata('message', 'The project is created successfully. '.
												'Now assign microtasks to the editors');
					redirect('admin/projects/assign_editors/'.$project_id);*/
				}
				else
				{
					//Some error occured
					$this->session->set_flashdata('message_type', 'error');
					$this->session->set_flashdata('message', $create_project);
					redirect('admin/projects/create_project');
				}
			}
		}
	}

    public function check_project()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/check_project'))
            redirect("pages/permission_exception");
        //Check if it has project id in session
        if($this->session->userdata("project_id")){
            $project_id = $this->session->userdata("project_id");
            $this->session->unset_userdata("project_id");
            //Check if the project is created by this user
            if(!$this->projects_model->check_project_by_user(get_session_username(), $project_id))
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This project is not created by you.');
                redirect('admin/user/dashboard');
            }
            //Get variables
            $video_id = strip_tags($this->input->get('id',true));
            $status = strip_tags($this->input->get('status', true));
            //Check if youtube video is uploaded
            if($this->youtube_model->check_video_existence($video_id) && $status == "200")
            {
                $this->projects_model->set_video_id($project_id, $video_id);
                redirect('admin/projects/assign_editors/'.$project_id);
            }
            else
                $this->delete_project($project_id);
        }
        else
            redirect('admin/projects/list_projects');
    }

    public function assign_editors_ajax()
    {
        // POST request
        if($_POST)
        {
            //Check permissions
            if(!check_permissions(get_session_roleid(), 'admin/projects/assign_editors'))
                redirect("pages/permission_exception");
            $task_id = strip_tags($this->input->post("task_id", TRUE));
            $editor = strip_tags($this->input->post("editor", TRUE));
            $project_id = strip_tags($this->input->post("project_id", TRUE));
            $editor_byname = $this->editors_model->get_editor_by_name($editor);
            if($editor_byname != false)
                $editor_id = $editor_byname[0]->id;
            else
                $editor_id = null;
            $result = $this->tasks_model->update_tasks($project_id, $task_id, $editor_id);
            if(!$result)
                if(!empty($editor) && $editor_id == null)
                    echo json_encode(array("msg" => "fail"));
                else
                    echo json_encode(array("msg" => "true"));
            else
                echo json_encode(array("msg" => "fail"));
        }
    }

	public function assign_editors()
	{
		//Check permissions
		if(!check_permissions(get_session_roleid(), 'admin/projects/assign_editors'))
			redirect("pages/permission_exception");

        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project does not exists.');
            redirect('admin/user/dashboard');
        }
        //GET Request
        //Check if the project is created by this user
        if(!$this->projects_model->check_project_by_user(get_session_username(), $project_id))
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project is not created by you.');
            redirect('admin/user/dashboard');
        }
        $data['project'] = $this->projects_model->select_project_by_id($project_id)->result();
        $data['tasks'] = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
        for($i = 0; $i <= sizeof($data['tasks']) - 1; $i++)
        {
            $editor_username[$i] = $this->editors_model->get_editor_by_id($data['tasks'][$i]->editor_id);
            if(!is_null($editor_username[$i]))
                $data['editor_username'][$i] = $editor_username[$i];
        }
        $this->load->view('admin/projects/assign_editors', $data);
	}

	public function list_projects()
	{
	    //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/list_projects'))
            redirect("pages/permission_exception");
        $user_role = get_user_role();
        $data['user_role'] = $user_role;
        if($user_role == "administrator")
        {
            $data['projects'] = $this->projects_model->select_project_by_user(get_session_username());
            $this->load->view('admin/projects/list_projects', $data);
        }
        if($user_role == "translator")
        {
            $data['projects'] = $this->projects_model->select_projects();
            $this->load->view('admin/projects/list_projects', $data);
        }
	}

    public function delete_project()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/delete_project'))
            redirect("pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'Please specify Project ID.');
            redirect('admin/user/dashboard');
        }
        //Check if the project is created by this user
        if(!$this->projects_model->check_project_by_user(get_session_username(), $project_id))
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project is not created by you.');
            redirect('admin/user/dashboard');
        }
        else
        {
            $project = $this->projects_model->select_project_by_id($project_id)->result();
            $temp = $project[0];
            if($temp->video_id != null)
            {
                $this->youtube_model->delete_youtube_video($temp->video_id);
            }
            $this->load->model("translations_model");
            $tasks = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
            //Delete all translations
            for($i = 0; $i <= sizeof($tasks) - 1; $i++)
            {
                $this->translations_model->delete_translations_by_task_id($tasks[$i]->id);
            }
            //Delete all project tasks
            if(!$this->tasks_model->delete_tasks_by_project_id($project_id))
            {
                $type = 'error';
                $message_text = $this->tasks_model->delete_tasks_by_project_id($project_id);
            }
            else
            {
                //Delete project itself
                if($this->projects_model->delete_project_by_id($project_id))
                {
                    $type = 'success';
                    $message_text = 'You have deleted the project successfully.';
                }
                else
                {
                    $type = 'error';
                    $message_text = $this->projects_model->delete_project_by_id($project_id);
                }
            }
            $this->session->set_flashdata('message_type', $type);
            $this->session->set_flashdata('message', $message_text);
            redirect('admin/projects/list_projects');
        }
    }

    function edit_project()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/edit_project'))
            redirect("pages/permission_exception");
        //Post request
        if($_POST)
        {
            $project_id = $this->input->post("project_id");
            //Check if the project is created by this user
            if(!$this->projects_model->check_project_by_user(get_session_username(), $project_id))
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This project is not created by you.');
                redirect('admin/user/dashboard');
            }
            else
            {
                $temp = $this->projects_model->select_project_by_id($project_id)->result();
                $data["project"] = $temp[0];
                $this->load->view("admin/projects/edit_project", $data);
            }

            //Validation rules
            $this->form_validation->set_rules('project_name', 'Full name', 'trim|required|min_length[3]|max_length[50]|xss_clean|strip_tags');
            $this->form_validation->set_rules('project_description', 'Address', 'trim|required|min_length[2]|max_length[250]|xss_clean|strip_tags');
            $this->form_validation->set_rules('hashtags', 'Project Hashtags', 'trim|required|min_length[5]|max_length[50]|xss_clean|strip_tags');

            if ($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Please fill all required form fields in correct format.');
                redirect('admin/projects/create_project');
            }
            else
            {
                //Get POST data
                $project_name = $this->input->post("project_name", TRUE);
                $project_description = $this->input->post("project_description", TRUE);
                $hashtags = $this->input->post("hashtags", TRUE);
            }
            // = $this->projects_model->select_project_by_id($project_id)->result()[0];
            $update = $this->projects_model->edit_project_details($project_id, $project_name, $project_description, $hashtags, get_session_user_id());
            if($update)
            {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'Project details are updated.');
                redirect('admin/projects/list_projects');
            }
            else
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', $update);
                redirect('admin/user/dashboard');
            }
        }
        //Get request
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project does not exists.');
            redirect('admin/user/dashboard');
        }
        //Check if the project is created by this user
        if(!$this->projects_model->check_project_by_user(get_session_username(), $project_id))
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project is not created by you.');
            redirect('admin/user/dashboard');
        }
        else
        {
            $temp = $this->projects_model->select_project_by_id($project_id)->result();
            $data["project"] = $temp[0];
            $this->load->view("admin/projects/edit_project", $data);
        }
    }

    // All project tasks list for translators
    function tasks_list()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/tasks_list'))
            redirect("pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project does not exists.');
            redirect('admin/user/dashboard');
        }
        $temp = $this->projects_model->select_project_by_id($project_id)->result();
        $data['project_name'] = $temp[0]->project_name;
        $data['tasks'] = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
        $this->load->model("translations_model");
        for($i = 0; $i <= sizeof($data['tasks']) - 1; $i++)
        {
            $task_id = $data['tasks'][$i]->id;
            $temp = $this->translations_model->count_translations($task_id);
            $data['translation_count'][$i] = $temp[0]->nr;
            $data['contributed_in_translation'][$i] = $this->translations_model->contributed_in_translation(get_session_user_id(), $task_id);
        }
        $this->load->view("admin/projects/tasks_list", $data);
    }

    // All assigned tasks of an editor user
    function list_tasks()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/list_tasks'))
            redirect("pages/permission_exception");
        $data['tasks'] = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        if($data['tasks'])
        {
            for($i = 0; $i < sizeof($data['tasks']); $i++)
            {
                $project_id = $data['tasks'][$i]->project_id;
                $task_id = $data['tasks'][$i]->id;
                $temp = $this->translations_model->count_translations($task_id);
                $data['count'][$i] = $temp[0]->nr;
                $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
                $data['projectname'][$i] = $temp1[0]->project_name;
            }
        }
        $this->load->view('admin/projects/list_tasks', $data);
    }

    function upload_video()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/upload_video'))
            redirect("pages/permission_exception");
        $data['youtube_response'] = $this->youtube_model->get_upload_token();
        $data['next_url'] = $this->config->item("youtube_next_url");
        echo json_encode($data);
    }
}

?>