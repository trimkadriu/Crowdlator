<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('tasks_model');
        $this->load->model('projects_model');
        $this->load->model('translations_model');
        $this->load->model('drafts_model');
    }

    function index()
    {
        redirect("admin/user/dashboard");
    }

    function task_id()
    {
        if($this->input->post("public"))
        {
            $this->load->model('public_model');
            $this->public_model->do_translate();
            exit;
        }
        if(!check_permissions(get_session_roleid(), 'admin/translate/task_id'))
            redirect("/pages/permission_exception");
        if($_POST)
        {
            // POST request
            $task_id = $this->input->post("task_id", TRUE);
            if(strlen($task_id) == 0)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This task does not exist.');
                redirect("admin/user/dashboard");
            }
            //reCaptcha Validation
            $this->load->library("recaptcha");
            $this->recaptcha->recaptcha_check_answer(
                $_SERVER['REMOTE_ADDR'],
                $this->input->post('recaptcha_challenge_field'),
                $this->input->post('recaptcha_response_field')
            );
            if(!$this->recaptcha->is_valid)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Captcha code is incorrect. Please try again');
                redirect("admin/translate/task_id/".$task_id);
            }
            $translated_text = strip_tags($this->input->post("translated", TRUE));
            $temp = $this->tasks_model->get_task_by_id($task_id);
            $text = $temp[0]->text;
            $text_size = strlen($text);
            $translated_text_size = strlen($translated_text);
            // Validate translation text
            if($translated_text_size/$text_size <= 1/3 || $translated_text == null || $translated_text == "")
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Translation cannot be done. Translated text is too short. '.
                                                '<input type="button" class="btn btn-mini btn-danger" value="Back" onclick="history.back()" />');
                redirect("admin/projects/list_projects");
            }
            $user_id = get_session_user_id();
            $result = $this->translations_model->create_translation($task_id, $user_id, $translated_text);
            if($result)
            {
                // Translation done
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'You have translated a task successfully.');
                redirect("admin/projects/list_projects");
            }
            else
            {
                // Translation creation ERROR
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Error translating task. Error: '.$result);
                redirect("admin/projects/list_projects");
            }
        }
        // GET request
        $temp = func_get_args(0);
        if($temp)
            $task_id = $temp[0];
        if(!$task_id)
            redirect("admin/user/dashboard");
        $temp = $this->tasks_model->get_task_by_id($task_id);
        $task = $temp[0];
        if($task)
        {
            $project_id = $task->project_id;
            $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
            $project = $temp1[0];
            $data['project_name'] = $project->project_name;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $task->text;
            $data['task_id'] = $task_id;
            $data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $this->load->view("admin/projects/translate", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This task does not exist.');
            redirect("admin/user/dashboard");
        }
    }

    function my_translations()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/my_translations'))
            redirect("/pages/permission_exception");
        $translated_tasks = $this->translations_model->get_translations_by_user_id(get_session_user_id());
        $data['translations'] = sizeof($translated_tasks);
        $data['translations_tasks'] = $translated_tasks;
        if($translated_tasks)
        {
            for($i = 0; $i <= $data['translations'] - 1; $i++)
            {
                $taskid = $translated_tasks[$i]->task_id;
                $temp = $this->tasks_model->get_task_by_id($taskid);
                $task[$i] = $temp[0];
                $temp1 = $this->projects_model->select_project_by_id($task[$i]->project_id)->result();
                $projects[$i] = $temp1[0];
                $data['task_id'][$i] = $task[$i]->id;
                $data['project_name'][$i] = $projects[$i]->project_name;
                $data['translate_from'][$i] = $projects[$i]->translate_from_language;
                $data['translate_to'][$i] = $projects[$i]->translate_to_language;
                $data['translate_text'][$i] = $task[$i]->text;
                $data['translated_text'][$i] = $translated_tasks[$i]->translated_text;
            }
        }
        else
            $data['translations'] = 0;
        $this->load->view("admin/translations/my_translations", $data);
    }

    function translations()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/translations'))
            redirect("/pages/permission_exception");
        $data['tasks'] = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        $data['translations_nr'] = false;
        $data['make_active'] = 1;
        if($data['tasks'])
        {
            $ids = array();
            for($i = 0; $i < sizeof($data['tasks']); $i++)
            {
                $ids[$i] = $data['tasks'][$i]->id;
            }
            if(sizeof($ids) > 0)
            {
                $translations = $this->translations_model->get_translations_by_task_ids($ids, false);
                $data['translations_nr'] = sizeof($translations);
                if($translations)
                {
                    for($i = 0; $i < $data['translations_nr']; $i++)
                    {
                        $task_id = $translations[$i]->task_id;
                        $temp = $this->tasks_model->get_task_by_id($task_id);
                        $task = $temp[0];
                        $project_id = $task->project_id;
                        $temp = $this->projects_model->select_project_by_id($project_id)->result();
                        $projects[$i] = $temp[0];
                        //$data['task_id'][$i] = $data['tasks'][$i]->id;
                        $data['project_name'][$i] = $projects[$i]->project_name;
                        $data['translate_from'][$i] = $projects[$i]->translate_from_language;
                        $data['translate_to'][$i] = $projects[$i]->translate_to_language;
                        $data['translate_text'][$i] = $task->text; //$data['tasks'][$i]->text;
                        $data['translated_text'][$i] = $translations[$i]->translated_text;
                        $data['date_translated'][$i] = $translations[$i]->date_created;
                        $data['translation_id'][$i] = $translations[$i]->id;
                        $data['reviewed'][$i] = $translations[$i]->reviewed;
                        $data['approved'][$i] = $translations[$i]->approved;
                    }
                }
                else
                    $data['translations_nr'] = 0;
            }

        }
        $this->load->view("admin/translations/translations", $data);
    }

    function translations_reviewed()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/translations'))
            redirect("/pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $reviewed = $temp[0];
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'Please specify if reviewed.');
            redirect('admin/translate/translations');
        }
        if($reviewed == 1)
            $data['make_active'] = 2;
        if($reviewed == 0)
            $data['make_active'] = 3;
        $data['tasks'] = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        $data['translations_nr'] = false;
        if($data['tasks'])
        {
            $ids = array();
            for($i = 0; $i < sizeof($data['tasks']); $i++)
            {
                $ids[$i] = $data['tasks'][$i]->id;
            }
            if(sizeof($ids) > 0)
            {
                $translations = $this->translations_model->get_translations_by_task_ids($ids, false, $reviewed);
                $data['translations_nr'] = sizeof($translations);
                if($translations)
                {
                    for($i = 0; $i < $data['translations_nr']; $i++)
                    {
                        $task_id = $translations[$i]->task_id;
                        $task = $this->tasks_model->get_task_by_id($task_id)[0];
                        $project_id = $task->project_id;
                        $temp = $this->projects_model->select_project_by_id($project_id)->result();
                        $projects[$i] = $temp[0];
                        //$data['task_id'][$i] = $data['tasks'][$i]->id;
                        $data['project_name'][$i] = $projects[$i]->project_name;
                        $data['translate_from'][$i] = $projects[$i]->translate_from_language;
                        $data['translate_to'][$i] = $projects[$i]->translate_to_language;
                        $data['translate_text'][$i] = $task->text;
                        $data['translated_text'][$i] = $translations[$i]->translated_text;
                        $data['date_translated'][$i] = $translations[$i]->date_created;
                        $data['translation_id'][$i] = $translations[$i]->id;
                        $data['reviewed'][$i] = $translations[$i]->reviewed;
                        $data['approved'][$i] = $translations[$i]->approved;
                    }
                }
                else
                    $data['translations_nr'] = 0;
            }

        }
        $this->load->view("admin/translations/translations", $data);
    }

    function translations_approved()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/translations_approved'))
            redirect("/pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $approved = $temp[0];
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'Please specify if approved.');
            redirect('admin/translate/translations');
        }
        if($approved == 1)
            $data['make_active'] = 4;
        if($approved == 0)
            $data['make_active'] = 5;
        $data['tasks'] = $this->tasks_model->get_tasks_by_editor(get_session_user_id());
        $data['translations_nr'] = false;
        if($data['tasks'])
        {
            $ids = array();
            for($i = 0; $i < sizeof($data['tasks']); $i++)
            {
                $ids[$i] = $data['tasks'][$i]->id;
            }
            if(sizeof($ids) > 0)
            {
                $translations = $this->translations_model->get_translations_by_task_ids($ids, false, null, $approved);
                $data['translations_nr'] = sizeof($translations);
                if($translations)
                {
                    for($i = 0; $i < $data['translations_nr']; $i++)
                    {
                        $task_id = $translations[$i]->task_id;
                        $task = $this->tasks_model->get_task_by_id($task_id)[0];
                        $project_id = $task->project_id;
                        $temp = $this->projects_model->select_project_by_id($project_id)->result();
                        $projects[$i] = $temp[0];
                        //$data['task_id'][$i] = $data['tasks'][$i]->id;
                        $data['project_name'][$i] = $projects[$i]->project_name;
                        $data['translate_from'][$i] = $projects[$i]->translate_from_language;
                        $data['translate_to'][$i] = $projects[$i]->translate_to_language;
                        $data['translate_text'][$i] = $task->text;
                        $data['translated_text'][$i] = $translations[$i]->translated_text;
                        $data['date_translated'][$i] = $translations[$i]->date_created;
                        $data['translation_id'][$i] = $translations[$i]->id;
                        $data['reviewed'][$i] = $translations[$i]->reviewed;
                        $data['approved'][$i] = $translations[$i]->approved;
                    }
                }
                else
                    $data['translations_nr'] = 0;
            }

        }
        $this->load->view("admin/translations/translations", $data);
    }

    function editor_edit_translation()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/editor_edit_translation'))
            redirect("/pages/permission_exception");
        if($_POST)
        {
            // POST request
            $translation_id = strip_tags($this->input->post("translation_id", TRUE));
            if(strlen($translation_id) == 0)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This task does not exist.');
                redirect("admin/user/dashboard");
            }
            $translated_text = strip_tags($this->input->post("translated", TRUE));
            $result = $this->translations_model->edit_translation_by_editor($translation_id, $translated_text);
            if($result)
            {
                // Translation done
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'You have edited and reviewed a task successfully.');
                redirect("admin/translate/translations");
            }
            else
            {
                // Translation creation ERROR
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Error editing task. Error: '.$result);
                redirect("admin/translate/translations");
            }
        }
    }

    function set_reviewed()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/set_reviewed'))
            redirect("/pages/permission_exception");
        $get_r = $this->input->get('r', true);
        $get_id = $this->input->get('id', true);
        if(isset($get_r) && isset($get_id))
        {
            $reviewed = strip_tags($get_r);
            $id = strip_tags($get_id);
            $this->translations_model->set_reviewed($id, $reviewed);
        }
    }

    function set_approved()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/set_approved'))
            redirect("/pages/permission_exception");
        $get_a = $this->input->get('a', true);
        $get_id = $this->input->get('id', true);
        if(isset($get_a) && isset($get_id))
        {
            $approved = strip_tags($get_a);
            $id = strip_tags($get_id);
            $this->translations_model->set_approved($id, $approved);
        }
    }

    function remove_translation()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/remove_translation'))
            redirect("/pages/permission_exception");
        $translation_id = strip_tags($this->input->post("translation_id", true));
        if($this->translations_model->get_translation_by_id($translation_id))
        {
            if($this->translations_model->delete_translation_by_id($translation_id))
            {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'Translation was successfully removed.');
                redirect("admin/translate/translations");
            }
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This translation does not exist.');
            redirect("admin/translate/translations");
        }
    }

    function vote()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/vote'))
            redirect("/pages/permission_exception");
        // GET request
        $temp = func_get_args(0);
        if($temp)
            $translation_id = $temp[0];
        if(!$translation_id)
            redirect("admin/user/dashboard");
        $translation = $this->translations_model->get_translation_by_id($translation_id)[0];
        $task = $this->tasks_model->get_task_by_id($translation->task_id)[0];
        $project = $this->projects_model->select_project_by_id($task->project_id)->result()[0];
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
//            $data['next_tasks'] = $result;print_r($data['next_tasks']);exit;
            //$data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $this->load->view("admin/translations/vote", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This translation does not exist.');
            redirect(base_url());
        }
    }

    public function vote_translations()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/vote_translations'))
            redirect("/pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $type = $temp[0];
        if(isset($type) && $type == 1)
            $data['make_active'] = 1;
        else
            $data['make_active'] = 0;
        $translations = $this->translations_model->get_all_translations();
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
                $data['date_translated'][$i] = $translations[$i]->date_created;
                $data['translate_from'][$i] = $project->translate_from_language;
                $data['translate_to'][$i] = $project->translate_to_language;
            }
        }
        $this->load->view("admin/translations/vote_translations", $data);
    }

    public function draft_list()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/draft_list'))
            redirect("/pages/permission_exception");
        $drafts = $this->drafts_model->get_drafts_by_user_id(get_session_user_id());
        $data['drafts_nr'] = false;
        if($drafts)
        {
            $data['drafts_nr'] = sizeof($drafts);
            for($i = 0; $i < $data['drafts_nr']; $i++)
            {
                $task = $this->tasks_model->get_task_by_id($drafts[$i]->task_id)[0];
                $project = $this->projects_model->select_project_by_id($task->project_id)->result()[0];
                $data['project_name'][$i] = $project->project_name;
                $data['date_saved'][$i] = $drafts[$i]->date_created;
                $data['translated_from'][$i] = $project->translate_from_language;
                $data['translated_to'][$i] = $project->translate_to_language;
                $data['draft_id'][$i] = $drafts[$i]->id;
            }
        }
        $this->load->view("admin/translations/draft_list", $data);
    }

    public function drafts()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/drafts'))
            redirect("/pages/permission_exception");
        //For saving to drafts
        if($_POST)
        {
            $draft_id = strip_tags($this->input->post("draft_id", true));
            $task_id = strip_tags($this->input->post("task_id", true));
            //reCaptcha Validation
            $this->load->library("recaptcha");
            $this->recaptcha->recaptcha_check_answer(
                $_SERVER['REMOTE_ADDR'],
                $this->input->post('recaptcha_challenge_field'),
                $this->input->post('recaptcha_response_field')
            );
            if(!$this->recaptcha->is_valid)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Captcha code is incorrect. Please try again');
                redirect("admin/translate/task_id/".$task_id);
            }
            $draft_text = strip_tags($this->input->post("translated", true));
            if($draft_id != null)
                $result = $this->drafts_model->update_draft_by_id($draft_id, $draft_text, get_session_user_id());
            else
                $result = $this->drafts_model->create_draft(get_session_user_id(), $task_id, $draft_text);
            if($result)
            {
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'This translation is saved in drafts. Later you can continue translating it.');
                redirect(base_url("admin/translate/draft_list"));
            }
            else
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'There was a problem saving your translation in draft. Please try again later.');
                redirect(base_url("admin/user/dashboard"));
            }
        }
        //Get current draft
        $temp = func_get_args(0);
        if($temp)
            $draft_id = $temp[0];
        if(!$draft_id)
            redirect("admin/user/dashboard");
        $draft = $this->drafts_model->get_draft_by_id($draft_id, get_session_user_id())[0];
        $task_id = $draft->task_id;
        $temp = $this->tasks_model->get_task_by_id($task_id);
        $task = $temp[0];
        if($task)
        {
            $project_id = $task->project_id;
            $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
            $project = $temp1[0];
            $data['project_name'] = $project->project_name;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $task->text;
            $data['task_id'] = $task_id;
            $data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $data['draft_text'] = $draft->draft_text;
            $data['draft_id'] = $draft_id;
            $this->load->view("admin/projects/translate", $data);
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This draft does not exist.');
            redirect("admin/user/dashboard");
        }
    }

    function delete_draft()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/delete_draft'))
            redirect("/pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $draft_id = $temp[0];
        if(!$draft_id)
            redirect("admin/user/dashboard");
        if($this->drafts_model->delete_draft_by_id($draft_id, get_session_user_id()))
        {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', 'Draft is deleted successfully.');
        }
        else
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'There was a problem deleting this draft. Please try again later.');
        }
        redirect("admin/translate/draft_list");

    }
}
?>