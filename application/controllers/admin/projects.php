<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Projects extends CI_Controller {

	function __construct()
    {
        parent::__construct();
		$this->load->model('editors_model');
		$this->load->model("tasks_model");
		$this->load->model("projects_model");
        $this->load->model("translations_model");
        $this->load->model("youtube_model");
        $this->load->model("soundcloud_model");
        $this->load->model("audios_model");
        $this->load->model("votes_model");
        $this->load->model("drafts_model");
    }

	private function break_text($text, $type, $break_number)
	{
	    $result = array();
		if($type == 'sentences')
        {
			$tmp = explode(".", $text);
			while ($tmp) {
				$result[] = implode('.', array_splice($tmp, 0, $break_number));
			}
        }
		elseif($type == 'paragraphs')
        {
            $text = preg_replace("\n(\s*\n)", "\n", $text);
			$tmp = explode("\n", $text);
			while ($tmp) {
				$result[] = implode('\n', array_splice($tmp, 0, $break_number));
			}
        }
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
                $data['project_status'] = true;
                echo json_encode($data);
				$this->session->set_flashdata('message_type', 'error');
				$this->session->set_flashdata('message', 'Please fill all required form fields in correct format.<br/>'.validation_errors());
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
                    $translate_from_language, $translate_to_language, $paste_text, $microtasks_by, $break_text, $hashtags);
				if($create_project)
				{
					//Create tasks
					$broken_text = $this->break_text($paste_text, $microtasks_by, $break_text); //Break TEXT
					$project_id = $this->projects_model->select_project_by_name($project_name)->row()->id;
					foreach($broken_text as $broken) //Create microtask for each text piece
					{
					    $this->tasks_model->create_new($project_id, $broken);
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
        if($data['project'][0]->status != "In Translation")
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project status is not in translation.');
            redirect('admin/user/dashboard');
        }
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
        }
        if($user_role == "translator")
        {
            $data['projects'] = $this->projects_model->select_projects();
        }
        $this->load->view('admin/projects/list_projects', $data);
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
            $temp = $this->projects_model->select_project_by_id($project_id)->result();
            $project = $temp[0];

            //Delete youtube video
            $this->youtube_model->delete_youtube_video($project->video_id);

            //Delete all audios
            $audios = $this->audios_model->get_audios(null, $project->id, null, null, null, null);
            if($audios)
                foreach($audios as $audio)
                {
                    $this->soundcloud_model->delete_track($audio->audio_id);
                    $this->votes_model->delete_votes(null, null, $audio->id, null, "audio", null, null, null);
                }
            $this->audios_model->delete_audio(null, $project->id, null, null, null);

            //Delete all translations
            $task_ids = array();
            $tasks = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
            for($i = 0; $i <= sizeof($tasks) - 1; $i++)
            {
                $task_ids[$i] = $tasks[$i]->id;
                //Delete drafts
                $this->drafts_model->delete_draft_by_id(null, null, $tasks[$i]->id);
            }
            $translations = $this->translations_model->get_translations_by_task_ids($task_ids, null, null, null);
            if($translations)
                foreach($translations as $translation)
                {
                    $this->votes_model->delete_votes(null, $translation->id, null, null, "text", null, null, null);
                    $this->translations_model->delete_translation_by_id($translation->id, null, null, null, null, null, null);
                }

            //Delete all project tasks
            $this->tasks_model->delete_tasks_by_project_id($project_id);
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
            $this->session->set_flashdata('message_type', $type);
            $this->session->set_flashdata('message', $message_text);
            redirect('admin/projects/list_projects');
        }
    }

    public function edit_project()
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
    public function tasks_list()
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
        if($temp[0]->status != "In Translation")
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This project is not in translation stage.');
            redirect('admin/projects/list_projects');
        }
        $data['project_name'] = $temp[0]->project_name;
        $data['hash'] = $temp[0]->hash_tags;
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
    public function list_tasks()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/list_tasks'))
            redirect("pages/permission_exception");
        $tasks = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        $data = null;
        $data['tasks'] = false;
        if($tasks)
        {
            for($i = 0; $i < sizeof($tasks); $i++)
            {
                $project_id = $tasks[$i]->project_id;
                $project = $this->projects_model->select_project_by_id($project_id)->result();
                if($project[0]->status == "In Translation")
                {
                    $data['tasks'][$i] = $tasks[$i];
                    $data['projectname'][$i] = $project[0]->project_name;
                    $data['date_created'][$i] = $project[0]->create_date;
                    $data['hash'][$i] = $project[0]->hash_tags;
                    $task_id = $data['tasks'][$i]->id;
                    $temp = $this->translations_model->count_translations($task_id);
                    $data['count'][$i] = $temp[0]->nr;
                }
            }
        }
        $this->load->view('admin/projects/list_tasks', $data);
    }

    public function upload_video()
    {
        //Check permissions
        if(!check_permissions(get_session_roleid(), 'admin/projects/upload_video'))
            redirect("pages/permission_exception");
        $data['youtube_response'] = $this->youtube_model->get_upload_token();
        $data['next_url'] = $this->config->item("youtube_next_url");
        echo json_encode($data);
    }

    public function projects_status()
    {
        if(!check_permissions(get_session_roleid(), 'admin/projects/projects_status'))
            redirect("pages/permission_exception");
        $role = get_user_role();
        $projects = false;
        $data = null;
        $data['projects_nr'] = false;
        if($role == "administrator")
        {
            $projects = $this->projects_model->get_project_by_params(null, get_session_user_id(), null, null, null, null);
        }
        elseif($role == "super editor")
        {
            $projects = $this->projects_model->select_projects();
        }
        //Create model and return to view
        if($projects)
        {
            $data['projects_nr'] = sizeof($projects);
            foreach($projects as $key=>$project)
            {
                $temp = $this->audios_model->get_audios(null, $project->id, null, null, null, 1);
                $audio = $temp[0];
                $data['project_id'][$key] = $project->id;
                $data['project_name'][$key] = $project->project_name;
                $data['project_description'][$key] = $project->project_description;
                $data['date_created'][$key] = $project->create_date;
                $data['translate_from'][$key] = $project->translate_from_language;
                $data['translate_to'][$key] = $project->translate_to_language;
                $data['status'][$key] = $project->status;
                $data['original_text'][$key] = $project->text;
                $data['translated_text'][$key] = $project->translated_text;
                $data['video_id'][$key] = $project->video_id;
                if($audio)
                    $data['audio_id'][$key] = $audio->audio_id;
            }
        }
        $this->load->view("admin/projects/projects_status", $data);
    }

    public function generate_video()
    {
        if(!check_permissions(get_session_roleid(), 'admin/projects/generate_video'))
            echo json_encode(array('return_result' => false));
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        else
            echo json_encode(array('return_result' => false));
        $temp1 = $this->projects_model->get_project_by_params($project_id, get_session_user_id(), null, null, null, "Finished");
        $project = $temp1;
        $this->session->set_flashdata('message_type', 'error');
        if($project)
        {
            $this->load->model("ffmpeg_model");
            if($this->ffmpeg_model->check_already_generated_video($project->id, $project->video_id))
                echo json_encode(array('return_result' => true));
            else
            {
                if($this->youtube_model->download_video($project->id, $project->video_id))
                {
                    $temp2 = $this->audios_model->get_audios(null, $project->id, null, null, null, 1);
                    $audio = $temp2[0];
                    if($this->soundcloud_model->download_track($project_id, $audio->audio_id, $audio->permalink_url))
                    {
                        if($this->ffmpeg_model->generate_video($project->id, $project->video_id, $audio->audio_id))
                        {
                            echo json_encode(array('return_result' => true));
                            exit;
                        }
                        else
                        {
                            $this->session->set_flashdata('message', 'Generating video cannot be done. Please contact administrator.');
                            echo json_encode(array('return_result' => false));
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('message', 'Audio cannot be downloaded. Please contact administrator.');
                        echo json_encode(array('return_result' => false));
                    }
                }
                else
                {
                    $this->session->set_flashdata('message', 'Video cannot be downloaded. Please contact administrator.');
                    echo json_encode(array('return_result' => false));
                }
            }
        }
        else
        {
            $this->session->set_flashdata('message', 'There is no such a project.');
            echo json_encode(array('return_result' => false));
        }
    }

    public function generate_download_video_link()
    {
        if(!check_permissions(get_session_roleid(), 'admin/projects/generate_download_video_link'))
            redirect("pages/permission_exception");
        $project_id = strip_tags($this->input->get("project_id", true));
        $video_id = strip_tags($this->input->get("video_id", true));
        $this->load->helper('download');
        $ds = DIRECTORY_SEPARATOR;
        $final_location = dirname(__FILE__).$ds.'..'.$ds.'..'.$ds.'..'.$ds.'final_videos'.$ds;
        $video_filename = $project_id."_".$video_id.".mp4";
        $data = file_get_contents($final_location.$video_filename);
        $name = 'final_video_'.generateRandomString(5).'.mp4';
        force_download($name, $data);
    }

    public function delete_task()
    {
        if(!check_permissions(get_session_roleid(), 'admin/projects/generate_video'))
            redirect("pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $task_id = $temp[0];
        else
            redirect("admin/projects/list_projects");
        $translations = $this->translations_model->get_translations_by_task_ids(array($task_id), null, null, null);
        if($translations)
            foreach($translations as $translation)
                $this->votes_model->delete_votes(null, $translation->id, null, null, "text", null, null, null);
        $this->drafts_model->delete_draft_by_id(null, null, $task_id);
        $this->translations_model->delete_translations_by_task_id($task_id);

        if($this->tasks_model->delete_tasks($task_id, null, null))
        {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', 'Task is deleted successfully.');
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'The task could not be deleted.');
        }
        redirect("admin/projects/list_projects");
    }

}

?>