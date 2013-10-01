<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Register extends CI_Controller {
	
	public function user()
	{
		//Validate form fields
		$this->form_validation->set_rules('full_name', 'Full name', 'trim|required|min_length[5]|max_length[50]|xss_clean|strip_tags');
		$this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[50]|xss_clean|strip_tags');
		$this->form_validation->set_rules('city', 'City', 'trim|min_length[2]|max_length[30]|xss_clean|strip_tags');
		$this->form_validation->set_rules('country', 'Country', 'trim|required|min_length[2]|max_length[30]|xss_clean|strip_tags');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]|xss_clean|strip_tags');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[32]|matches[confirm_password]|md5');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|strip_tags');
		
		//If validation fails
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('message_type', 'error');
			$this->session->set_flashdata('message', 'Please fill all required form fields in correct format.');
			redirect('pages/register');
		}
		else
		{
			//Get POST form data
			$full_name = $this->input->post("full_name", TRUE);
			$address = $this->input->post("address", TRUE);
			$city = $this->input->post("city", TRUE);
			$country = $this->input->post("country", TRUE);
			$username = $this->input->post("username", TRUE);
            $password = $this->input->post("confirm_password");
            //$confirm_password = $this->input->post("confirm_password");
			$email = $this->input->post("email", TRUE);
			
			$this->load->model('users_model');
			//Register user
			if($this->users_model->register($full_name, $address, $city, $country, $username, $password, $email))
			{
				$this->session->set_flashdata('message_type', 'success');
				$this->session->set_flashdata('message', 'The user is registred successfully.'.
												'Now you can login through the form below.');
				redirect('pages/home'); 
				//Success
			}
			else
			{
				//Username and/or email alredy exists
				$this->session->set_flashdata('message_type', 'error');
				$this->session->set_flashdata('message', 'This username or email is already registered.');
				redirect('pages/register');
			}			
		}
	}
	
}

?>