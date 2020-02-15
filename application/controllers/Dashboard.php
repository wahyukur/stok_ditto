<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		$this->load->model('menu_model','menu');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master Menu';
		$data['content'] = 'pages/dashboard_view';
		$this->load->view('template/main', $data);
	}
}
