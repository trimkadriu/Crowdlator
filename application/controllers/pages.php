<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function index()
	{
		$this->load->view('pages/home');
	}
	
	public function admin()
	{
		$this->load->view('admin/home');
	}
	
	public function home()
	{
		$this->load->view('pages/home');
	}
	
	public function faq()
	{
		$this->load->view('pages/faq');
	}
	
	public function contact()
	{
		$this->load->view('pages/contact');
	}
	
	public function register()
	{
		$this->load->view('pages/register');
	}
	
	public function translations()
	{
		$this->load->view('pages/translations');
	}

    public function permission_exception()
    {
        $this->load->view("exceptions/permission");
    }
}

?>