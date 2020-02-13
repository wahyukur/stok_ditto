<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_keluar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('laporan_keluar_model','laporan_keluar');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master laporan_keluar';
		$data['content'] = 'pages/laporan_keluar_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->laporan_keluar->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $laporan_keluar) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$laporan_keluar->id_laporan_keluar.'">';
			$row[] = $laporan_keluar->id_periode;
			$row[] = $laporan_keluar->id_menu;
			$row[] = $laporan_keluar->jumlah_menu;
			$row[] = $laporan_keluar->tanggal_keluar;
			$row[] = $laporan_keluar->approved_;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_laporan_keluar('."'".$laporan_keluar->id_laporan_keluar."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_laporan_keluar('."'".$laporan_keluar->id_laporan_keluar."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->laporan_keluar->count_all(),
						"recordsFiltered" => $this->laporan_keluar->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_laporan_keluar)
	{	
		$data = $this->laporan_keluar->get_by_id($id_laporan_keluar);
		$data->tanggal_keluar =  ($data->tanggal_keluar == '0000-00-00') ? '' : $data->tanggal_keluar;
		
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'id_periode' => $this->input->post('id_periode'),
				'id_menu' => $this->input->post('id_menu'),
				'jumlah_menu' => $this->input->post('jumlah_menu'),
				'tanggal_keluar' => $this->input->post('tanggal_keluar'),
				'approved_' => $this->input->post('approved_')
			);

		$insert = $this->laporan_keluar->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id_periode' => $this->input->post('id_periode'),
				'id_menu' => $this->input->post('id_menu'),
				'jumlah_menu' => $this->input->post('jumlah_menu'),
				'tanggal_keluar' => $this->input->post('tanggal_keluar'),
				'approved_' => $this->input->post('approved_')
			);
		$this->laporan_keluar->update(array('id_laporan_keluar' => $this->input->post('id_laporan_keluar')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_laporan_keluar)
	{
		$this->laporan_keluar->delete_by_id($id_laporan_keluar);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_laporan_keluar');
		foreach ($list_id as $id_laporan_keluar) {
			$this->laporan_keluar->delete_by_id($id_laporan_keluar);
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

		if($this->input->post('id_menu') == '')
		{
			$data['inputerror'][] = 'id_menu';
			$data['error_string'][] = 'Isi Dulu';
			$data['status'] = FALSE;
		}
		if($this->input->post('jumlah_menu') == '')
		{
			$data['inputerror'][] = 'jumlah_menu';
			$data['error_string'][] = 'Isi jumlah';
			$data['status'] = FALSE;
		}
		if($this->input->post('tanggal_keluar') == '')
		{
			$data['inputerror'][] = 'tanggal_keluar';
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
