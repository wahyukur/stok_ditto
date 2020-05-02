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
		$this->load->model('detail_masuk_model','detail_masuk');
		$this->load->model('komposisi_menu_model','komposisi_menu');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Laporan Pembelian';
		$data['content'] = 'pages/laporan_masuk_view';
		$this->load->view('template/main', $data);
	}

	public function dtl($id_masuk)
	{
		$this->load->helper('url');
		$data['id_masuk'] = $id_masuk;
		$data['tgl_masuk'] = $this->laporan_masuk->get_by_id($id_masuk)->tanggal_masuk;
		$data['bahans'] = $this->laporan_masuk->get_bahan();
		$data['page'] = 'Detail Pembelian '.$id_masuk;
		$data['content'] = 'pages/masuk_detail_view';
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
			// $row[] = '<input type="checkbox" class="data-check" value="'.$laporan_masuk->id_masuk.'">';
			$row[] = $laporan_masuk->id_masuk;
			$row[] = $laporan_masuk->tanggal_masuk;

			// <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_laporan_masuk('."'".$laporan_masuk->id_masuk."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
			//add html for action
			$row[] = '<button class="button" href="javascript:void(0)" title="Detail Transaksi" onclick="detail('."'".$laporan_masuk->id_masuk."'".')"><span>Detail </span></button>';
		
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

	public function ajax_add()
	{
		$this->_validate();

		$tipe = 'IN'; // IN
		$date = date('my', strtotime($this->input->post('tanggal_masuk'))); // bulan tahun cth : 0220 -> bulan 02, tahun 20
		$bulan = date('m', strtotime($this->input->post('tanggal_masuk')));
		$tahun = date('Y', strtotime($this->input->post('tanggal_masuk')));
		$kode = $this->laporan_masuk->get_for_check($bulan, $tahun);
		$urutan = $kode + 1;
		$id_masuk = $tipe . $date . sprintf("%03s", $urutan);

		$data = array(
			'id_masuk' => $id_masuk,
			'tanggal_masuk' => $this->input->post('tanggal_masuk')
		);

		$insert = $this->laporan_masuk->save($data);

		echo json_encode(array("status" => TRUE, "id_masuk" => $id_masuk));
		
	}

	//---------------------------------//     D E T A I L   //-------------------------------------------

	public function ajax_list_detail($id_masuk)
	{
		$this->load->helper('url');

		$list = $this->detail_masuk->get_datatables($id_masuk);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $detail) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$detail->id.'">';
			$row[] = $detail->nama_bahan;
			$row[] = $detail->qty;
			$row[] = $detail->unitid;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_detail('."'".$detail->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_detail('."'".$detail->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->detail_masuk->count_all($id_masuk),
				"recordsFiltered" => $this->detail_masuk->count_filtered($id_masuk),
				"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_add_detail()
	{
		$this->_validate_detail();

		$data = array(
			'id_masuk' => $this->input->post('id_masuk'),
			'id_bahan' => $this->input->post('id_bahan'),
			'qty' => $this->input->post('jumlah_bahan'),
			'id_unit' => $this->input->post('unitid'),
		);

		$insert = $this->laporan_masuk->save_dtl($data);

		echo json_encode(array("status" => TRUE));
		
	}

	public function ajax_edit($id)
	{	
		$data = $this->detail_masuk->get_by_id($id);
		// $data->tanggal_masuk =  ($data->tanggal_masuk == '0000-00-00') ? '' : $data->tanggal_masuk;
		
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

	public function ajax_update()
	{
		$this->_validate_detail();
		$data = array(
				'id_masuk' => $this->input->post('id_masuk'),
				'id_bahan' => $this->input->post('id_bahan'),
				'qty' => $this->input->post('jumlah_bahan'),
				'id_unit' => $this->input->post('unitid')
			);
		$this->detail_masuk->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->detail_masuk->delete_by_id($id);
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

		if($this->input->post('tanggal_masuk') == '')
		{
			$data['inputerror'][] = 'tanggal_masuk';
			$data['error_string'][] = 'Masukkan Tanggal Buat Header';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	private function _validate_detail()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_bahan') == '')
		{
			$data['inputerror'][] = 'id_bahan';
			$data['error_string'][] = 'Pilih Bahanya';
			$data['status'] = FALSE;
		}

		if($this->input->post('jumlah_bahan') == '')
		{
			$data['inputerror'][] = 'jumlah_bahan';
			$data['error_string'][] = 'Masukkan jumlah bahan';
			$data['status'] = FALSE;
		}

		if($this->input->post('unitid') == '')
		{
			$data['inputerror'][] = 'unitid';
			$data['error_string'][] = 'Pilih unit satuan';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
