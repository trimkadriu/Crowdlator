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
        // Questions and Answers. If you want to add more just follow the array structure.
        $data['question'][0] = "What is Crowdlator";
        $data['answer'][0] = "Crowdlator is an online platform for multimedia translation. It is a project by Al Jazeera ".
                            "intended to help journalists to translate videos by crowd. It is implemented by UNICEF Innovations Lab.";
        $data['question'][1] = "How Crowdlator works";
        $data['answer'][1] = "It is very simple and user friendly platform. Administrators can create a project by uploading the ".
                            "video and transcript, then by dividing the transcript to micro tasks and assigning to editors. ".
                            "The editors will review the tasks (which are also voted by the crowd) and approve most appropriate ones. ".
                            "Then the super editor will make the final decision on choosing the best translation for that transcript. ".
                            "When the translation of the transcript is finished, starts the second stage, making audio version for the ".
                            "translated text. This is going through the same procedure like the text translation. After when the ".
                            "super editor choose the best audio audition, the project is finished and the final video will be produced, ".
                            "with translated audio over the original video. A download option will be available for the Administrator.";
        $data['question'][2] = "What are the limits";
        $data['answer'][2] = "Limits are mostly to videos and audios length. Since this platform is still a prototype version.";
        $data['question'][3] = "Is this service free of charge";
        $data['answer'][3] = "This is service is offered free of charge. This platform is build over OpenSource software.";
		$this->load->view('pages/faq', $data);
	}
	
	public function contact()
	{
        $data['zoom'] = $this->config->item("google_maps_zoom");
        $data['latitude'] = $this->config->item("google_maps_latitude");
        $data['longitude'] = $this->config->item("google_maps_longitude");
		$this->load->view('pages/contact', $data);
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