<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_model','menu');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master Menu';
		$data['content'] = 'pages/menu_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->menu->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $menu) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$menu->id_menu.'">';
			$row[] = $menu->nama_menu;
			$row[] = $menu->category;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_menu('."'".$menu->id_menu."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_menu('."'".$menu->id_menu."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Detail" onclick="detail('."'".$menu->id_menu."'".')"><i class="glyphicon glyphicon-trash"></i> Detail</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->menu->count_all(),
						"recordsFiltered" => $this->menu->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_menu)
	{	
		$data = $this->menu->get_by_id($id_menu);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'nama_menu' => $this->input->post('nama_menu'),
				'category' => $this->input->post('category')
			);

		$insert = $this->menu->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'nama_menu' => $this->input->post('nama_menu'),
				'category' => $this->input->post('category')
			);
		$this->menu->update(array('id_menu' => $this->input->post('id_menu')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_menu)
	{
		$this->menu->delete_by_id($id_menu);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_menu');
		foreach ($list_id as $id_menu) {
			$this->menu->delete_by_id($id_menu);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nama_menu') == '')
		{
			$data['inputerror'][] = 'nama_menu';
			$data['error_string'][] = 'First name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('category') == '')
		{
			$data['inputerror'][] = 'category';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
