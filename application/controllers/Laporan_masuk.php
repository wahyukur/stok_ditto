<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_masuk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		$this->load->model('laporan_masuk_model','laporan_masuk');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Laporan Masuk';
		$data['content'] = 'pages/laporan_masuk_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->laporan_masuk->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $laporan_masuk) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$laporan_masuk->id_laporan_masuk.'">';
			$row[] = $laporan_masuk->id_periode;
			$row[] = $laporan_masuk->id_bahan;
			$row[] = $laporan_masuk->jumlah_bahan;
			$row[] = $laporan_masuk->unitid;
			$row[] = $laporan_masuk->tanggal_masuk;
			$row[] = $laporan_masuk->approved_;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_laporan_masuk('."'".$laporan_masuk->id_laporan_masuk."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_laporan_masuk('."'".$laporan_masuk->id_laporan_masuk."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->laporan_masuk->count_all(),
						"recordsFiltered" => $this->laporan_masuk->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_laporan_masuk)
	{	
		$data = $this->laporan_masuk->get_by_id($id_laporan_masuk);
		$data->tanggal_masuk =  ($data->tanggal_masuk == '0000-00-00') ? '' : $data->tanggal_masuk;
		
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'id_periode' => $this->input->post('id_periode'),
				'id_bahan' => $this->input->post('id_bahan'),
				'jumlah_bahan' => $this->input->post('jumlah_bahan'),
				'unitid' => $this->input->post('unitid'),
				'tanggal_masuk' => $this->input->post('tanggal_masuk'),
				'approved_' => $this->input->post('approved_')
			);

		$insert = $this->laporan_masuk->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id_periode' => $this->input->post('id_periode'),
				'id_bahan' => $this->input->post('id_bahan'),
				'jumlah_bahan' => $this->input->post('jumlah_bahan'),
				'unitid' => $this->input->post('unitid'),
				'tanggal_masuk' => $this->input->post('tanggal_masuk'),
				'approved_' => $this->input->post('approved_')
			);
		$this->laporan_masuk->update(array('id_laporan_masuk' => $this->input->post('id_laporan_masuk')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_laporan_masuk)
	{
		$this->laporan_masuk->delete_by_id($id_laporan_masuk);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_laporan_masuk');
		foreach ($list_id as $id_laporan_masuk) {
			$this->laporan_masuk->delete_by_id($id_laporan_masuk);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_periode') == '')
		{
			$data['inputerror'][] = 'id_periode';
			$data['error_string'][] = 'harap isi dulu';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_bahan') == '')
		{
			$data['inputerror'][] = 'id_bahan';
			$data['error_string'][] = 'Isi Dulu';
			$data['status'] = FALSE;
		}
		if($this->input->post('jumlah_bahan') == '')
		{
			$data['inputerror'][] = 'jumlah_bahan';
			$data['error_string'][] = 'Isi jumlah';
			$data['status'] = FALSE;
		}
		if($this->input->post('unitid') == '')
		{
			$data['inputerror'][] = 'unitid';
			$data['error_string'][] = 'Isi dulu';
			$data['status'] = FALSE;
		}
		if($this->input->post('tanggal_masuk') == '')
		{
			$data['inputerror'][] = 'tanggal_masuk';
			$data['error_string'][] = 'Isi tanggal';
			$data['status'] = FALSE;
		}
		if($this->input->post('approved_') == '')
		{
			$data['inputerror'][] = 'approved_';
			$data['error_string'][] = 'approved_';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
