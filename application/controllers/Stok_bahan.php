<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_bahan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		$this->load->model('stok_bahan_model','stok_bahan');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Stok Bahan';
		$data['content'] = 'pages/stok_bahan_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->stok_bahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $stok_bahan) {
			$no++;
			$row = array();
			// $row[] = '<input type="checkbox" class="data-check" value="'.$stok_bahan->id_stok.'">';
			$row[] = $stok_bahan->nama_bahan;
			$row[] = $stok_bahan->jumlah_bahan;
			$row[] = $stok_bahan->unit_stok;
			$row[] = $stok_bahan->changed_date;

			//add html for action
			// $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_stok_bahan('."'".$stok_bahan->id_stok."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			// 	  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_stok_bahan('."'".$stok_bahan->id_stok."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->stok_bahan->count_all(),
						"recordsFiltered" => $this->stok_bahan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	// public function ajax_edit($id_stok)
	// {	
	// 	$data = $this->stok_bahan->get_by_id($id_stok);
	// 	$data->changed_date =  ($data->changed_date == '0000-00-00') ? '' : $data->changed_date;
		
	// 	echo json_encode($data);

	// }

	// public function ajax_add()
	// {
	// 	$this->_validate();
		
	// 	$data = array(
	// 			'id_bahan' => $this->input->post('id_bahan'),
	// 			'jumlah_bahan' => $this->input->post('jumlah_bahan'),
	// 			'unit_stok' => $this->input->post('unit_stok'),
	// 			'changed_date' => $this->input->post('changed_date')
	// 		);

	// 	$insert = $this->stok_bahan->save($data);

	// 	echo json_encode(array("status" => TRUE));
	// }

	// public function ajax_update()
	// {
	// 	$this->_validate();
	// 	$data = array(
	// 			'id_bahan' => $this->input->post('id_bahan'),
	// 			'jumlah_bahan' => $this->input->post('jumlah_bahan'),
	// 			'unit_stok' => $this->input->post('unit_stok'),
	// 			'changed_date' => $this->input->post('changed_date')
	// 		);
	// 	$this->stok_bahan->update(array('id_stok' => $this->input->post('id_stok')), $data);
	// 	echo json_encode(array("status" => TRUE));
	// }

	// public function ajax_delete($id_stok)
	// {
	// 	$this->stok_bahan->delete_by_id($id_stok);
	// 	echo json_encode(array("status" => TRUE));
	// }

	// public function ajax_bulk_delete()
	// {
	// 	$list_id = $this->input->post('id_stok');
	// 	foreach ($list_id as $id_stok) {
	// 		$this->stok_bahan->delete_by_id($id_stok);
	// 	}
	// 	echo json_encode(array("status" => TRUE));
	// }

	// private function _validate()
	// {
	// 	$data = array();
	// 	$data['error_string'] = array();
	// 	$data['inputerror'] = array();
	// 	$data['status'] = TRUE;

	// 	if($this->input->post('id_bahan') == '')
	// 	{
	// 		$data['inputerror'][] = 'id_bahan';
	// 		$data['error_string'][] = 'harap isi dulu';
	// 		$data['status'] = FALSE;
	// 	}

	// 	if($this->input->post('jumlah_bahan') == '')
	// 	{
	// 		$data['inputerror'][] = 'jumlah_bahan';
	// 		$data['error_string'][] = 'Isi Jumlah';
	// 		$data['status'] = FALSE;
	// 	}
	// 	if($this->input->post('unit_stok') == '')
	// 	{
	// 		$data['inputerror'][] = 'unit_stok';
	// 		$data['error_string'][] = 'Isi Unit';
	// 		$data['status'] = FALSE;
	// 	}
	// 	if($this->input->post('changed_date') == '')
	// 	{
	// 		$data['inputerror'][] = 'changed_date';
	// 		$data['error_string'][] = 'Isi tanggal';
	// 		$data['status'] = FALSE;
	// 	}


	// 	if($data['status'] === FALSE)
	// 	{
	// 		echo json_encode($data);
	// 		exit();
	// 	}
	// }

}
