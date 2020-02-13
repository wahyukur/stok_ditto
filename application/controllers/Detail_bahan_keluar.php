<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_bahan_keluar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('detail_bahan_keluar_model','detail_bahan_keluar');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master detail_bahan_keluar';
		$data['content'] = 'pages/detail_bahan_keluar_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->detail_bahan_keluar->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $detail_bahan_keluar) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$detail_bahan_keluar->id_detail_keluar.'">';
			$row[] = $detail_bahan_keluar->id_laporan_keluar;
			$row[] = $detail_bahan_keluar->id_menu;
			$row[] = $detail_bahan_keluar->id_periode;
			$row[] = $detail_bahan_keluar->id_bahan;
			$row[] = $detail_bahan_keluar->jumlah_bahan_keluar;
			$row[] = $detail_bahan_keluar->unitid;
			$row[] = $detail_bahan_keluar->tanggal_keluar;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_detail_bahan_keluar('."'".$detail_bahan_keluar->id_detail_bahan_keluar."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_detail_bahan_keluar('."'".$detail_bahan_keluar->id_detail_bahan_keluar."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->detail_bahan_keluar->count_all(),
						"recordsFiltered" => $this->detail_bahan_keluar->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_detail_bahan_keluar)
	{	
		$data = $this->detail_bahan_keluar->get_by_id($id_detail_keluar);
		$data->created_at =  ($data->created_at == '0000-00-00') ? '' : $data->created_at;
		
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'id_laporan_keluar' => $this->input->post('id_laporan_keluar'),
				'id_menu' => $this->input->post('id_menu'),
				'id_periode' => $this->input->post('id_periode'),
				'id_periode' => $this->input->post('id_bahan'),
				'jumlah_bahan_keluar' => $this->input->post('jumlah_bahan_keluar'),
				'unitid' => $this->input->post('unitid'),
				'tanggal_keluar' => $this->input->post('tanggal_keluar')
			);

		$insert = $this->detail_bahan_keluar->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'id_laporan_keluar' => $this->input->post('id_laporan_keluar'),
				'id_menu' => $this->input->post('id_menu'),
				'id_periode' => $this->input->post('id_periode'),
				'id_periode' => $this->input->post('id_bahan'),
				'jumlah_bahan_keluar' => $this->input->post('jumlah_bahan_keluar'),
				'unitid' => $this->input->post('unitid'),
				'tanggal_keluar' => $this->input->post('tanggal_keluar')
			);
		$this->detail_bahan_keluar->update(array('id_detail_keluar' => $this->input->post('id_detail_keluar')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_detail_keluar)
	{
		$this->detail_bahan_keluar->delete_by_id($id_detail_keluar);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_detail_keluar');
		foreach ($list_id as $id_detail_keluar) {
			$this->detail_bahan_keluar->delete_by_id($id_detail_keluar);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_laporan_keluar') == '')
		{
			$data['inputerror'][] = 'id_laporan_keluar';
			$data['error_string'][] = 'harap isi dulu';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_menu') == '')
		{
			$data['inputerror'][] = 'id_menu';
			$data['error_string'][] = 'Isi id_menu';
			$data['status'] = FALSE;
		}
		if($this->input->post('id_periode') == '')
		{
			$data['inputerror'][] = 'id_periode';
			$data['error_string'][] = 'isi id_periode';
			$data['status'] = FALSE;
		}
		if($this->input->post('id_bahan') == '')
		{
			$data['inputerror'][] = 'id_bahan';
			$data['error_string'][] = 'Isi id_bahan';
			$data['status'] = FALSE;
		}
		if($this->input->post('jumlah_bahan_keluar') == '')
		{
			$data['inputerror'][] = 'jumlah_bahan_keluar';
			$data['error_string'][] = 'Isi jumlah_bahan_keluar';
			$data['status'] = FALSE;
		}
		if($this->input->post('unitid') == '')
		{
			$data['inputerror'][] = 'unitid';
			$data['error_string'][] = 'Isi unitid';
			$data['status'] = FALSE;
		}
		if($this->input->post('tanggal_keluar') == '')
		{
			$data['inputerror'][] = 'tanggal_keluar';
			$data['error_string'][] = 'isi tanggal_keluar';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
