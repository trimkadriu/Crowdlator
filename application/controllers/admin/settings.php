<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Settings extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
    }

    function index()
    {
        redirect("admin/user/dashboard");
    }

    function account_settings()
    {
        $user_id = get_session_user_id();
        if($_POST)
        {
            //Post request
            //Validate form fields
            $this->form_validation->set_rules('full_name', 'Full name', 'trim|required|min_length[5]|max_length[50]|xss_clean|strip_tags');
            $this->form_validation->set_rules('address', 'Address', 'trim|min_length[2]|max_length[50]|xss_clean|strip_tags');
            $this->form_validation->set_rules('city', 'City', 'trim|min_length[2]|max_length[30]|xss_clean|strip_tags');
            $this->form_validation->set_rules('country', 'Country', 'trim|required|min_length[2]|max_length[30]|xss_clean|strip_tags');
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]|max_length[32]|matches[confirm_password]|md5');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim');
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
                $password = $this->input->post("password");
                $email = $this->input->post("email", TRUE);

                //Check user email if already exists
                $does_email_exists = $this->users_model->check_user_by_email($email);
                if($does_email_exists)
                {
                    $this->session->set_flashdata('message_type', 'error');
                    $this->session->set_flashdata('message', 'This email is being used by another user');
                    redirect('admin/settings/account_settings');
                }
                //Update users info
                $results = $this->users_model->update_user_info($user_id, $full_name, $address, $city, $country, $password, $email);
                if($results)
                {
                    $message_type = 'success';
                    $message = 'User info are successfully updated';
                }
                else
                {
                    $message_type = 'error';
                    $message = 'User info are not updated.  <br/>Detail: '.$results;
                }
                //Username and/or email alredy exists
                $this->session->set_flashdata('message_type', $message_type);
                $this->session->set_flashdata('message', $message);
                redirect('admin/user/dashboard');
            }
        }
        //Get request
        $temp = $this->users_model->get_user_by_id($user_id);
        $data["user"] = $temp[0];
        $this->load->view("admin/settings/account_settings", $data);
    }

}
?>