<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bahan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		$this->load->model('bahan_model','bahan');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master bahan';
		$data['content'] = 'pages/bahan_view';
		$data['ambil_unit'] = $this->bahan->ambil_unitgroup();
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->bahan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $bahan) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$bahan->id_bahan.'">';
			$row[] = $bahan->nama_bahan;
			$row[] = $bahan->description;
			$row[] = $bahan->created_at;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_bahan('."'".$bahan->id_bahan."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_bahan('."'".$bahan->id_bahan."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->bahan->count_all(),
						"recordsFiltered" => $this->bahan->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_bahan)
	{	
		$data = $this->bahan->get_by_id($id_bahan);
		$data->created_at =  ($data->created_at == '0000-00-00') ? '' : $data->created_at;
		
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();

		$tanggal = date('Y-m-d');
		
		$data = array(
				'nama_bahan' => $this->input->post('nama_bahan'),
				'unit_groupid' => $this->input->post('unit_groupid'),
				'created_at' => $tanggal
			);

		$insert = $this->bahan->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'nama_bahan' => $this->input->post('nama_bahan'),
				'unit_groupid' => $this->input->post('unit_groupid'),
				'created_at' => $this->input->post('created_at')
			);
		$this->bahan->update(array('id_bahan' => $this->input->post('id_bahan')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_bahan)
	{
		$this->bahan->delete_by_id($id_bahan);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_bahan');
		foreach ($list_id as $id_bahan) {
			$this->bahan->delete_by_id($id_bahan);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nama_bahan') == '')
		{
			$data['inputerror'][] = 'nama_bahan';
			$data['error_string'][] = 'Masukkan Nama Bahan';
			$data['status'] = FALSE;
		}

		if($this->input->post('unit_groupid') == '')
		{
			$data['inputerror'][] = 'unit_groupid';
			$data['error_string'][] = 'Pilih Unit Group';
			$data['status'] = FALSE;
		}
		// if($this->input->post('created_at') == '')
		// {
		// 	$data['inputerror'][] = 'created_at';
		// 	$data['error_string'][] = 'Last name is required';
		// 	$data['status'] = FALSE;
		// }


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
