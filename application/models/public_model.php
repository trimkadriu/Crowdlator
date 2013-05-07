<?php

class Public_model extends CI_Model {

    function __construct()
    {
        $this->load->library("recaptcha");
        //$this->load->model("audios_model");
    }
	
    function do_translate()
    {
        if($_POST)
        {
            // POST request
            $task_id = strip_tags($this->input->post("task_id", TRUE));
            if(strlen($task_id) == 0)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This task does not exist.');
                redirect(base_url());
            }
            //reCaptcha Validation
            $this->recaptcha->recaptcha_check_answer(
                $_SERVER['REMOTE_ADDR'],
                $this->input->post('recaptcha_challenge_field'),
                $this->input->post('recaptcha_response_field')
            );
            if(!$this->recaptcha->is_valid)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Captcha code is incorrect. Please try again');
                redirect("public/translate/task/".$task_id);
            }
            $translated_text = strip_tags($this->input->post("translated", TRUE));
            $text = $this->tasks_model->get_task_by_id($task_id)[0]->text;
            $text_size = strlen($text);
            $translated_text_size = strlen($translated_text);
            // Validate translation text
            if($translated_text_size/$text_size <= 1/3 || $translated_text == null || $translated_text == "")
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Translation cannot be done. Translated text is too short. '.
                    '<input type="button" class="btn btn-mini btn-danger" value="Back" onclick="history.back()" />');
                redirect(base_url());
            }
            $user_id = null;
            $result = $this->translations_model->create_translation($task_id, $user_id, $translated_text);
            if($result)
            {
                // Translation done
                $this->session->set_flashdata('message_type', 'success');
                $this->session->set_flashdata('message', 'You have translated a task successfully.');
                redirect(base_url());
            }
            else
            {
                // Translation creation ERROR
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Error translating task. Please try again later');
                redirect(base_url());
            }
        }
    }

    function do_audition()
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
            redirect(base_url());
        }
        $user_id = null;
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
        $data['return_url'] = base_url();
        echo json_encode($data);
    }
	
}

?>