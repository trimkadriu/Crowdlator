<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @Author: Trim Kadriu <trim.kadriu@hotmail.com>
 *
 */
class Editors extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('editors_model');
    }

    function index()
    {
        redirect("admin/user/dashboard");
    }

    function get_editors_filter()
    {
        if(!check_permissions(get_session_roleid(), 'admin/editors/get_editors_filter'))
            redirect("/pages/permission_exception");
        $filter = strip_tags($_GET["q"]);
        $editors = $this->editors_model->get_editors_by_filter($filter);
        $index = 0;
        foreach($editors as $editor)
        {
            $editors_list[$index] = array("id" => $editor->id, "name" => $editor->username);
            $index++;
        }
        $json_response = json_encode($editors_list);
        echo $json_response;
    }
}
?>