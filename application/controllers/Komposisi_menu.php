<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komposisi_menu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('komposisi_menu_model','komposisi_menu');
	}

	public function index($id)
	{
		$this->load->helper('url');
		$data['nama_menu'] = $this->komposisi_menu->get_menu($id)->nama_menu;
		$data['page'] = 'Komposisi Menu '.$data['nama_menu'];
		$data['content'] = 'pages/komposisi_menu_view';
		$data['id_menu'] = $id;
		$data['bahans'] = $this->komposisi_menu->get_bahan();
		$this->load->view('template/main', $data);
	}

	public function ajax_list($id)
	{
		$this->load->helper('url');

		$list = $this->komposisi_menu->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $komposisi_menu) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$komposisi_menu->id_composition.'">';
			$row[] = $komposisi_menu->nama_bahan;
			$row[] = $komposisi_menu->jumlah;
			$row[] = $komposisi_menu->unitid;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_komposisi_menu('."'".$komposisi_menu->id_composition."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_komposisi_menu('."'".$komposisi_menu->id_composition."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->komposisi_menu->count_all($id),
						"recordsFiltered" => $this->komposisi_menu->count_filtered($id),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_composition)
	{	
		$data = $this->komposisi_menu->get_by_id($id_composition);
		
		
		echo json_encode($data);

	}

	public function get_ug()
	{
		// var_dump($this->input->post('id'));die();
		$arr = [];
		$id = $this->input->post('id');
		$unit_group = $this->komposisi_menu->get_unit($id);
		// var_dump($unit_group);die();

		echo json_encode($unit_group);
	}


	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'id_menu' => $this->input->post('id_menu'),
				'id_bahan' => $this->input->post('id_bahan'),
				'jumlah' => $this->input->post('jumlah'),
				'unitid' => $this->input->post('unitid')
			);

		$insert = $this->komposisi_menu->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id_menu' => $this->input->post('id_menu'),
				'id_bahan' => $this->input->post('id_bahan'),
				'jumlah' => $this->input->post('jumlah'),
				'unitid' => $this->input->post('unitid')
			);
		$this->komposisi_menu->update(array('id_composition' => $this->input->post('id_composition')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_composition)
	{
		$this->komposisi_menu->delete_by_id($id_composition);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_composition');
		foreach ($list_id as $id_composition) {
			$this->komposisi_menu->delete_by_id($id_composition);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_menu') == '')
		{
			$data['inputerror'][] = 'id_menu';
			$data['error_string'][] = 'harap isi dulu';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_bahan') == '')
		{
			$data['inputerror'][] = 'id_bahan';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('jumlah') == '')
		{
			$data['inputerror'][] = 'jumlah';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('unitid') == '')
		{
			$data['inputerror'][] = 'unitid';
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
