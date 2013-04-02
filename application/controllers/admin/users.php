<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
    }

    function index()
    {
        redirect("admin/user/dashboard");
    }

    function list_users()
    {
        if(!check_permissions(get_session_roleid(), 'admin/users/list_users'))
            redirect("pages/permission_exception");
        $data["users"] = $this->users_model->get_users_with_role_name(0);
        $data["roles"] = $this->users_model->get_roles();
        $this->load->view("admin/users/list_users", $data);
    }

    function change_user_role()
    {
        if(!check_permissions(get_session_roleid(), 'admin/users/list_users'))
            redirect("pages/permission_exception");
        if($_POST)
        {
            //Validate form fields
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|strip_tags');
            $this->form_validation->set_rules('role', 'Role ID', 'trim|required|min_length[1]|max_length[2]|xss_clean|strip_tags');
            //If validation fails
            if ($this->form_validation->run() == FALSE)
            {
                $this->session->set_flashdata('message_type', 'error');
                $this->session->set_flashdata('message', 'Please fill all required form fields in correct format.');
                redirect("admin/users/list_users");
            }
            else
            {
                //Get POST form data
                $email = $this->input->post("email", TRUE);
                $role = $this->input->post("role", TRUE);
                $result = $this->users_model->change_user_role($email, $role);
                if($result)
                {
                    $message_type = 'success';
                    $message = 'User role is updated successfully';
                }
                else
                {
                    $message_type = 'error';
                    $message = $result;
                }
                $this->session->set_flashdata('message_type', $message_type);
                $this->session->set_flashdata('message', $message);
                redirect("admin/users/list_users");
            }
        }
        else
            redirect("admin/user/dashboard");
    }

}
?>