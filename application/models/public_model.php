<?php

class Public_model extends CI_Model {
	
    function do_translate()
    {
        if($_POST)
        {
            // POST request
            $task_id = $this->input->post("task_id", TRUE);
            if(strlen($task_id) == 0)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'This task does not exist.');
                redirect(base_url());
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
                $this->session->set_flashdata('message', 'Error translating task. Error: '.$result);
                redirect(base_url());
            }
        }
    }
	
}

?>