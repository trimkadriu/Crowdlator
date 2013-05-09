<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('translations_model');
    }

    function index()
    {
        $trans = $this->translations_model->get_translation_by_params(null, 55, null, null, null, null, "1")[0];
        print_r($trans);
    }

}
?>