<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		// $data['page'] = 'Dashboard';
		// $data['content'] = 'pages/Dashboard';
		// $data['item'] = $this->cart->contents();
		// $this->load->view('template/main', $data);
		$table = 'periode';
		$datas = $this->_set_datatables($table);

		$column_order = $datas['column_order'];

		// var_dump($column_order);
	}

	public function _set_datatables($table)
	{

		$this->kontol($table);
		if ($table == 'periode') {
			$data['column_order'] = array('firstname','lastname','gender','address','dob',null);
			$data['column_search'] = array('firstname','lastname','address'); 
			$data['order'] = array('id' => 'desc');
		}

		return $data;
	}

	public function kontol($table)
	{
		var_dump($table);
	}
}
