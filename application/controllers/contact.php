<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Contact extends CI_Controller {
	
	function email()
	{
		//Validate form fields
		$this->form_validation->set_rules('full_name', 'Full name', 'trim|required|min_length[3]|max_length[50]|xss_clean|strip_tags');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|min_length[3]|max_length[50]|xss_clean|strip_tags');
        $this->form_validation->set_rules('message', 'Message', 'trim|required|min_length[10]|max_length[250]|xss_clean|strip_tags');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|strip_tags');
		
		//If validation fails
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('message_type', 'error');
			$this->session->set_flashdata('message', 'Please fill all required form fields in correct format.');
			redirect('pages/contact');
		}
		else
		{
			//Get POST form data
			$full_name = $this->input->post("full_name", TRUE);
			$subject = $this->input->post("subject", TRUE);
            $message = $this->input->post("message", TRUE);
			$email = $this->input->post("email", TRUE);
			
			$this->load->model('users_model');
			//Register user
            $result = $this->users_model->contact_email($full_name, $subject, $message, $email);
			if($result)
			{
				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', 'The email has been successfully sent.');
				redirect('pages/home');
			}
			else
			{
				$this->session->set_flashdata('message_type', 'error');
				$this->session->set_flashdata('message', 'The email could not be sent. Please try again later.<br/>'.$result);
				redirect('pages/contact');
            }
		}
	}
	
}

?>