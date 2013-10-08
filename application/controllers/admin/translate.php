<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Translate extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('tasks_model');
        $this->load->model('projects_model');
        $this->load->model('translations_model');
        $this->load->model('drafts_model');
        $this->load->model('votes_model');
        $this->load->model("audios_model");
        $this->load->model("soundcloud_model");
        $this->load->model("youtube_model");
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
            $temp = $this->tasks_model->get_task_by_id($task_id);
            $task = $temp[0];
            $project_id = $task->project_id;
            $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
            $project = $temp1[0];
            if($project->status != "In Translation")
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This task belongs to a project which is not in translation stage.');
                redirect("admin/projects/list_projects");
            }
            //reCaptcha Validation
            /*$this->load->library("recaptcha");
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
            }*/
            if($this->input->post('captcha_code') != $this->session->userdata('captcha_word'))
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
        $project_id = $task->project_id;
        $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
        $project = $temp1[0];
        if($project->status != "In Translation")
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This task belongs to a project which is not in translation stage.');
            redirect("admin/projects/list_projects");
        }
        if($task)
        {
            $data['project_name'] = $project->project_name;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $task->text;
            $data['task_id'] = $task_id;
            //$data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $data['captcha'] = get_captcha_image();
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
                        $data['project_status'][$i] = $projects[$i]->status;
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
                        $temp1 = $this->tasks_model->get_task_by_id($task_id);
                        $task = $temp1[0];
                        $project_id = $task->project_id;
                        $temp = $this->projects_model->select_project_by_id($project_id)->result();
                        $projects[$i] = $temp[0];
                        //$data['task_id'][$i] = $data['tasks'][$i]->id;
                        $data['project_name'][$i] = $projects[$i]->project_name;
                        $data['project_status'][$i] = $projects[$i]->status;
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
                        $temp1 = $this->tasks_model->get_task_by_id($task_id);
                        $task = $temp1[0];
                        $project_id = $task->project_id;
                        $temp = $this->projects_model->select_project_by_id($project_id)->result();
                        $projects[$i] = $temp[0];
                        //$data['task_id'][$i] = $data['tasks'][$i]->id;
                        $data['project_name'][$i] = $projects[$i]->project_name;
                        $data['project_status'][$i] = $projects[$i]->status;
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

    function vote_translations()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/vote_translations'))
            redirect("/pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $type = $temp[0];
        if(isset($type) && $type == 1)
        {
            $data['make_active'] = 1;
            $audios = $this->audios_model->get_audios();
            $data['audios_nr'] = false;
            $data['translation_type'] = "audio";
            if($audios)
            {
                $data['audios_nr'] = sizeof($audios);
                for($i = 0; $i < $data['audios_nr']; $i++)
                {
                    $temp = $this->projects_model->select_project_by_id($audios[$i]->project_id)->result();
                    $project = $temp[0];
                    $data['project_status'][$i] = $project->status;
                    $data['id'][$i] = $audios[$i]->id;
                    $data['project_name'][$i] = $project->project_name;
                    $data['date_created'][$i] = $audios[$i]->date_created;
                    $data['translate_from'][$i] = $project->translate_from_language;
                    $data['translate_to'][$i] = $project->translate_to_language;
                    $data['translated'][$i] = $project->translated_text;
                    $data['audio_id'][$i] = $audios[$i]->audio_id;
                    $data['project_video_id'][$i] = $project->video_id;
                    $data['project_description'][$i] = $project->project_description;
                    $data['project_id'][$i] = $project->id;
                    $data['twitter_hash_tag'] = $project->hash_tags;
                    $data['good_votes'][$i] = $this->votes_model->get_votes_count(null, null, $audios[$i]->id, null, "audio", 1, null);
                    $data['bad_votes'][$i] = $this->votes_model->get_votes_count(null, null, $audios[$i]->id, null, "audio", null, 1);
                    $data['voted'][$i] = $this->votes_model->get_if_user_voted(null, $audios[$i]->id, get_session_user_id(), "audio");
                }
            }
            $this->load->view("admin/audios/vote_audios", $data);
        }
        else
        {
            $data['make_active'] = 0;
            $translations = $this->translations_model->get_all_translations();
            $data['translation_nr'] = false;
            $data['translation_type'] = "text";
            if($translations)
            {
                $data['translation_nr'] = sizeof($translations);
                for($i = 0; $i < $data['translation_nr']; $i++)
                {
                    $temp1 = $this->tasks_model->get_task_by_id($translations[$i]->task_id);
                    $task = $temp1[0];
                    $temp = $this->projects_model->select_project_by_id($task->project_id)->result();
                    $project = $temp[0];
                    $data['project_status'][$i] = $project->status;
                    $data['translation_id'][$i] = $translations[$i]->id;
                    $data['project_name'][$i] = $project->project_name;
                    $data['date_translated'][$i] = $translations[$i]->date_created;
                    $data['translate_from'][$i] = $project->translate_from_language;
                    $data['translate_to'][$i] = $project->translate_to_language;
                    $data['text'][$i] = $task->text;
                    $data['translated'][$i] = $translations[$i]->translated_text;
                    $data['project_video_id'][$i] = $project->video_id;
                    $data['project_description'][$i] = $project->project_description;
                    $data['hash'][$i] = $project->hash_tags;
                    $data['good_votes'][$i] = $this->votes_model->get_votes_count(null, $translations[$i]->id, null, null, "text", "1", null);
                    $data['bad_votes'][$i] = $this->votes_model->get_votes_count(null, $translations[$i]->id, null, null, "text", null, "1");
                    $data['voted'][$i] = $this->votes_model->get_if_user_voted($translations[$i]->id, null, get_session_user_id(), "text");
                }
            }
            $this->load->view("admin/translations/vote_translations", $data);
        }
    }

    function vote()
    {
        if($_POST)
        {
            $translation_type = $this->input->post(strip_tags("translation_type"), true);
            $translation_id = $this->input->post(strip_tags("id"), true);
            $vote_type = $this->input->post(strip_tags("vote_type"), true);
            $already_voted = true;
            $result = false;
            if($translation_type == "text")
            {
                $temp1 = $this->translations_model->get_translation_by_id($translation_id);
                $translation = $temp1[0];
                $temp = $this->tasks_model->get_task_by_id($translation->task_id);
                $task = $temp[0];
                $project_id = $task->project_id;
                $return = 0; $return_url = "translation";
            }
            elseif($translation_type == "audio")
            {
                $temp2 = $this->audios_model->get_audios(null, null, $translation_id, null, null, null);
                $audio = $temp2[0];
                $project_id = $audio->project_id;
                $return = 1; $return_url = "audio";
            }
            //Validate if project is in translation stage
            $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
            $project = $temp1[0];
            if($translation_type == "text")
                if($project->status != "In Translation")
                    redirect();
            elseif($translation_type == "audio")
                if($project->status != "In Audition")
                    redirect();
                //Some validations
            if($vote_type == "bad" || $vote_type == "good")
            {
                if($translation_type == "text" || $translation_type == "audio")
                {
                    if(get_session_user_id())
                    {
                        if($translation_type == "text")
                            $already_voted = $this->votes_model->get_if_user_voted($translation_id, null, get_session_user_id(), "text");
                        else if($translation_type == "audio")
                            $already_voted = $this->votes_model->get_if_user_voted(null, $translation_id, get_session_user_id(), "audio");
                    }
                    else
                    {
                        //Public Vote
                        //reCaptcha Validation
                        /*$this->load->library("recaptcha");
                        $this->recaptcha->recaptcha_check_answer(
                            $_SERVER['REMOTE_ADDR'],
                            $this->input->post('recaptcha_challenge_field'),
                            $this->input->post('recaptcha_response_field')
                        );
                        if(!$this->recaptcha->is_valid)
                        {
                            $this->session->set_flashdata('message_type', 'error');
                            $this->session->set_flashdata('message', 'Captcha code is incorrect. Please try again');
                            if($translation_type == "audio")
                            {
                                $temp3 = $this->audios_model->get_audios($translation_id, null, null, null, null);
                                $translation_id = $temp3[0];
                                $translation_id = $translation_id->project_id;
                            }
                            redirect("public/vote/".$return_url."/".$translation_id);
                        }*/
                        if($this->input->post('captcha_code') != $this->session->userdata('captcha_word'))
                        {
                            $this->session->set_flashdata('message_type', 'error');
                            $this->session->set_flashdata('message', 'Captcha code is incorrect. Please try again');
                            if($translation_type == "audio")
                            {
                                $temp3 = $this->audios_model->get_audios($translation_id, null, null, null, null);
                                $translation_id = $temp3[0];
                                $translation_id = $translation_id->project_id;
                            }
                            redirect("public/vote/".$return_url."/".$translation_id);
                        }
                        get_if_voted_by_id_type($translation_id, $translation_type);
                        if($translation_type == "text")
                        {
                            //For text translations
                            if($vote_type == "bad")
                                $result = $this->votes_model->create_new($translation_id, null, null, "text", null, 1);
                            else if($vote_type == "good")
                                $result = $this->votes_model->create_new($translation_id, null, null, "text", 1, null);
                        }
                        else if($translation_type == "audio")
                        {
                            //For audio translations
                            if($vote_type == "bad")
                                $result = $this->votes_model->create_new(null, $translation_id, null, "audio", null, 1);
                            else if($vote_type == "good")
                                $result = $this->votes_model->create_new(null, $translation_id, null, "audio", 1, null);
                        }
                        //Drop some Cookies to validate that this user have voted
                        set_voted_by_id_and_type($translation_id, $translation_type);
                        if($result)
                        {
                            $this->session->set_flashdata('message_type', 'success');
                            $this->session->set_flashdata('message', 'You have successfully voted.');
                        }
                        redirect();
                    }
                    if(!$already_voted)
                    {
                        if($translation_type == "text")
                        {
                            //For text translations
                            if($vote_type == "bad")
                                $result = $this->votes_model->create_new($translation_id, null, get_session_user_id(), "text", null, 1);
                            else if($vote_type == "good")
                                $result = $this->votes_model->create_new($translation_id, null, get_session_user_id(), "text", 1, null);
                        }
                        else if($translation_type == "audio")
                        {
                            //For audio translations
                            if($vote_type == "bad")
                                $result = $this->votes_model->create_new(null, $translation_id, get_session_user_id(), "audio", null, 1);
                            else if($vote_type == "good")
                                $result = $this->votes_model->create_new(null, $translation_id, get_session_user_id(), "audio", 1, null);
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('message_type', 'error');
                        $this->session->set_flashdata('message', 'You have alredy voted this translation.');
                        redirect("admin/translate/vote_translations/".$return);
                    }
                }
                else
                {
                    $this->session->set_flashdata('message_type', 'error');
                    $this->session->set_flashdata('message', 'Translation type invalid.');
                    redirect("admin/translate/vote_translations/".$return);
                }
            }
            else
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Vote type invalid.');
                redirect("admin/translate/vote_translations/".$return);
            }
        }
        if($result)
        {
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', 'You have successfully voted.');
        }
        redirect("admin/translate/vote_translations/".$return);
    }

    function draft_list()
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
                $temp1 = $this->tasks_model->get_task_by_id($drafts[$i]->task_id);
                $task = $temp1[0];
                $temp = $this->projects_model->select_project_by_id($task->project_id)->result();
                $project = $temp[0];
                $data['project_name'][$i] = $project->project_name;
                $data['date_saved'][$i] = $drafts[$i]->date_created;
                $data['translated_from'][$i] = $project->translate_from_language;
                $data['translated_to'][$i] = $project->translate_to_language;
                $data['draft_id'][$i] = $drafts[$i]->id;
            }
        }
        $this->load->view("admin/translations/draft_list", $data);
    }

    function drafts()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/drafts'))
            redirect("/pages/permission_exception");
        //For saving to drafts
        if($_POST)
        {
            $draft_id = strip_tags($this->input->post("draft_id", true));
            $task_id = strip_tags($this->input->post("task_id", true));
            $temp = $this->tasks_model->get_task_by_id($task_id);
            $task = $temp[0];
            $project_id = $task->project_id;
            $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
            $project = $temp1[0];
            if($project->status != "In Translation")
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This task belongs to a project which is not in translation stage.');
                redirect("admin/translate/draft_list");
            }
            /*//reCaptcha Validation
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
            }*/
            if($this->input->post('captcha_code') != $this->session->userdata('captcha_word'))
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Captcha code is incorrect. Please try again');
                redirect("admin/translate/draft_list");
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
        $temp2 = $this->drafts_model->get_draft_by_id($draft_id, get_session_user_id());
        $draft = $temp2[0];
        $task_id = $draft->task_id;
        $temp = $this->tasks_model->get_task_by_id($task_id);
        $task = $temp[0];
        $project_id = $task->project_id;
        $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
        $project = $temp1[0];
        if($project->status != "In Translation")
        {
            $this->session->set_flashdata('message_type', 'error');
            $this->session->set_flashdata('message', 'This task belongs to a project which is not in translation stage.');
            redirect("admin/translate/draft_list");
        }
        if($task)
        {
            $data['project_name'] = $project->project_name;
            $data['translate_from'] = $project->translate_from_language;
            $data['translate_to'] = $project->translate_to_language;
            $data['text'] = $task->text;
            $data['task_id'] = $task_id;
            //$data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
            $data['captcha'] = get_captcha_image();
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

    function choose_translations()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/choose_translations'))
            redirect("/pages/permission_exception");
        $temp = func_get_args(0);
        if($temp)
            $type = $temp[0];
        if(isset($type) && $type == 1)
        {
            $data['make_active'] = 1;
            $audios = $this->audios_model->get_all_audios_where_choosen_not(1);
            $data['audios_nr'] = false;
            if($audios)
            {
                $data['audios_nr'] = sizeof($audios);
                for($i = 0; $i < $data['audios_nr']; $i++)
                {
                    //$task = $this->tasks_model->get_task_by_id($translations[$i]->task_id)[0];
                    $temp = $this->projects_model->select_project_by_id($audios[$i]->project_id)->result();
                    $project = $temp[0];
                    $data['id'][$i] = $audios[$i]->id;
                    $data['audio_id'][$i] = $audios[$i]->audio_id;
                    $data['project_name'][$i] = $project->project_name;
                    $data['date_recorded'][$i] = $audios[$i]->date_created;
                    $data['translate_from'][$i] = $project->translate_from_language;
                    $data['translate_to'][$i] = $project->translate_to_language;
                    $data['translated'][$i] = $project->translated_text;
                    $data['project_video_id'][$i] = $project->video_id;
                    $data['project_description'][$i] = $project->project_description;
                    $data['good_votes'][$i] = $this->votes_model->get_votes_count(null, null, $audios[$i]->id, null, "audio", 1, null);
                    $data['bad_votes'][$i] = $this->votes_model->get_votes_count(null, null, $audios[$i]->id, null, "audio", null, 1);
                    $data['choosen'][$i] = $audios[$i]->choosen;
                }
            }
            $this->load->view("admin/audios/choose_audios", $data);
        }
        else
        {
            $data['make_active'] = 0;
            $translations = $this->translations_model->get_all_translations_where_choosen_not(1);
            $data['translation_nr'] = false;
            if($translations)
            {
                $data['translation_nr'] = sizeof($translations);
                for($i = 0; $i < $data['translation_nr']; $i++)
                {
                    $temp1 = $this->tasks_model->get_task_by_id($translations[$i]->task_id);
                    $task = $temp1[0];
                    $temp = $this->projects_model->select_project_by_id($task->project_id)->result();
                    $project = $temp[0];
                    $data['translation_id'][$i] = $translations[$i]->id;
                    $data['project_name'][$i] = $project->project_name;
                    $data['date_translated'][$i] = $translations[$i]->date_created;
                    $data['translate_from'][$i] = $project->translate_from_language;
                    $data['translate_to'][$i] = $project->translate_to_language;
                    $data['text'][$i] = $task->text;
                    $data['translated'][$i] = $translations[$i]->translated_text;
                    $data['project_video_id'][$i] = $project->video_id;
                    $data['project_description'][$i] = $project->project_description;
                    $data['good_votes'][$i] = $this->votes_model->get_votes_count(null, $translations[$i]->id, null, null, "text", "1", null);
                    $data['bad_votes'][$i] = $this->votes_model->get_votes_count(null, $translations[$i]->id, null, null, "text", null, "1");
                    $data['choosen'][$i] = $translations[$i]->choosen;
                }
            }
            $this->load->view("admin/translations/choose_translations", $data);
        }
    }

    function chose_translation()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/chose_translation'))
            redirect("/pages/permission_exception");
        $translations = func_get_args(0);
        if($translations)
            $translation_id = $translations[0];
        if(!$translation_id)
            redirect("admin/user/dashboard");
        $temp = $this->translations_model->get_translation_by_id($translation_id);
        $translation = $temp[0];
        $this->session->set_flashdata('message_type', 'error');
        if($translation->approved == 0)
        {
            $this->session->set_flashdata('message', 'Translation is not approved.');
            redirect("admin/translate/choose_translations/0");
        }
        if($translation->choosen == 0)
        {
            if(!$this->translations_model->check_translation_if_choosen($translation->task_id))
            {
                $this->translations_model->set_choosen($translation_id, 1);
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'That translation was choosen the best.');
                //Check project status
                $temp = $this->tasks_model->get_task_by_id($translation->task_id);
                $task = $temp[0];
                $project_id = $task->project_id;
                $status = check_project_status($project_id);
                if($status)
                {
                    //If all tasks of this project are translated
                    $text = '';
                    $pr_tasks = $this->tasks_model->get_tasks_by_project_id($project_id)->result();
                    foreach($pr_tasks as $pr_task)
                    {
                        $this->drafts_model->delete_draft_by_id(null, null, $pr_task->id);
                        $pr_translations = $this->translations_model->get_translation_by_params(null, $pr_task->id, null, null, null, null, 0);
                        $this->translations_model->delete_not_choosen_translations_by_task_id($pr_task->id);
                        foreach($pr_translations as $pr_translation)
                        {
                            $this->votes_model->delete_votes(null, $pr_translation->id, null, null, "text", null, null, null);
                        }
                        //Save final text
                        $temp = $this->translations_model->get_translation_by_params(null, $pr_task->id, null, null, null, null, "1");
                        $trans = $temp[0];
                        if($trans)
                            $text = $text." ".$trans->translated_text;
                    }
                    $this->projects_model->update_project_translated_text($project_id, trim($text));
                    $this->projects_model->update_project_status($project_id, "In Audition");
                }
            }
        }
        else
            $this->session->set_flashdata('message', 'Translation was already choosen.');
        redirect("admin/translate/choose_translations/0");
    }

    function choose_audio()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/choose_audio'))
            redirect("/pages/permission_exception");
        $audios = func_get_args(0);
        if($audios)
            $audio_id = $audios[0];
        if(!$audio_id)
            redirect("admin/user/dashboard");
        $temp = $this->audios_model->get_audios($audio_id, null, null, null, null, 0);
        $audio = $temp[0];
        $this->session->set_flashdata('message_type', 'error');
        if($audio)
        {
            //Delete other audios that are recorded for this project from soundcloud and database, also votes
            $project_id = $audio->project_id;
            $pr_audios = $this->audios_model->get_audios(null, $project_id, null, null, null, 0);
            foreach($pr_audios as $pr_audio)
            {
                if($pr_audio->audio_id != $audio->audio_id)
                {
                    if($this->soundcloud_model->delete_track($pr_audio->audio_id))
                    {
                        $this->audios_model->delete_audio(null, null, $pr_audio->audio_id, null, null);
                        $this->votes_model->delete_votes(null, null, $pr_audio->id, null, "audio", null, null, null);
                    }
                    else
                    {
                        $this->session->set_flashdata('message_type', 'error');
                        $this->session->set_flashdata('message', 'That audio was not choosen. Please try again later');
                        redirect("admin/translate/choose_translations/1");
                    }
                }
            }
            //Change project status
            $this->audios_model->set_choosen($audio_id, 1);
            $this->projects_model->update_project_status($project_id, "Finished");
            $this->session->set_flashdata('message_type', 'success');
            $this->session->set_flashdata('message', 'That audio was choosen the best.');
        }
        else
            $this->session->set_flashdata('message', 'Audio was already choosen.');
        redirect("admin/translate/choose_translations/1");
    }

    function audio_audition()
    {
        if($this->input->post("public"))
        {
            $this->load->model('public_model');
            $this->public_model->do_audition();
            exit;
        }
        if(!check_permissions(get_session_roleid(), 'admin/translate/audio_audition'))
            redirect("/pages/permission_exception");
        if($_POST)
        {
            // POST request
            $project_id = strip_tags($this->input->post("project_id", TRUE));
            $audio_id = strip_tags($this->input->post("audio_id", TRUE));
            $permalink_url = strip_tags($this->input->post("permalink_url", TRUE));
            $download_url = strip_tags($this->input->post("download_url", TRUE));
            if(strlen($project_id) == 0)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This project does not exist.');
                redirect(base_url("admin/projects/list_projects"));
            }
            $user_id = get_session_user_id();
            $result = $this->audios_model->create_audio($project_id, $user_id, $audio_id, $permalink_url, $download_url);
            if($result)
            {
                // Translation done
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'You have recorded audio successfully.');
                $data['return_result'] = true;
            }
            else
            {
                // Translation creation ERROR
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Error saving audio.');
                $data['return_result'] = false;
            }
            $data['return_url'] = base_url("admin/projects/list_projects");
            echo json_encode($data); exit;
        }
        $temp = func_get_args(0);
        if($temp)
            $project_id = $temp[0];
        if(!$project_id)
            redirect("admin/user/dashboard");
        $temp1 = $this->projects_model->select_project_by_id($project_id)->result();
        $project = $temp1[0];$token = $this->soundcloud_model->get_access_token();
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
        $data['twitter_hash_tag'] = $project->hash_tags;
        //$data['access_token'] = $token;
        $data['client_id'] = $this->config->item("soundcloud_client_id");
        $data['redirect_url'] = $this->config->item("soundcloud_redirect_url");
        //$data['recaptcha_public_key'] = $this->config->item("recaptcha_public_key");
        $data['captcha'] = get_captcha_image();
        $this->load->view("admin/audios/audio_audition", $data);
    }

    function my_audios()
    {
        if(!check_permissions(get_session_roleid(), 'admin/translate/my_audios'))
            redirect("/pages/permission_exception");
        $audios = $this->audios_model->get_audios(null, null, null, get_session_user_id(), null);
        $data['audios'] = sizeof($audios);
        if($audios)
        {
            for($i = 0; $i < $data['audios']; $i++)
            {
                $temp1 = $this->projects_model->select_project_by_id($audios[$i]->project_id)->result();
                $project[$i] = $temp1[0];
                $data['id'][$i] = $audios[$i]->id;
                $data['project_name'][$i] = $project[$i]->project_name;
                $data['date_created'][$i] = $audios[$i]->date_created;
                $data['audio_id'][$i] = $audios[$i]->audio_id;
                $data['translated_from'][$i] = $project[$i]->translate_from_language;
                $data['translated_to'][$i] = $project[$i]->translate_to_language;
                $data['translated_text'][$i] = $project[$i]->translated_text;
            }
        }
        else
            $data['audios'] = 0;
        $this->load->view("admin/audios/my_audios", $data);
    }

}
?>