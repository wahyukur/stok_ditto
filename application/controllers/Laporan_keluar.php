<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_keluar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		$this->load->model('laporan_keluar_model','laporan_keluar');
		$this->load->model('detail_keluar_model','detail_keluar');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Laporan Penjualan';
		$data['content'] = 'pages/laporan_keluar_view';
		$this->load->view('template/main', $data);
	}

	public function dtl($id_keluar)
	{
		$this->load->helper('url');
		$data['id_keluar'] = $id_keluar;
		$data['tgl_keluar'] = $this->laporan_keluar->get_by_id($id_keluar)->tanggal_keluar;
		$data['menu'] = $this->laporan_keluar->get_menu();
		$data['page'] = 'Detail Laporan Keluar '.$id_keluar;
		$data['content'] = 'pages/keluar_detail_view';
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
			// $row[] = '<input type="checkbox" class="data-check" value="'.$laporan_keluar->id_keluar.'">';
			$row[] = $laporan_keluar->id_keluar;
			$row[] = $laporan_keluar->tanggal_keluar;

			//add html for action
			$row[] = '<button class="button" href="javascript:void(0)" title="Detail Transaksi" onclick="detail('."'".$laporan_keluar->id_keluar."'".')"><span>Detail </span></button>';
		
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

	public function ajax_add()
	{
		$this->_validate();
		
		$tipe = 'OUT'; // IN
		$date = date('my', strtotime($this->input->post('tanggal_keluar'))); // bulan tahun cth : 0220 -> bulan 02, tahun 20
		$bulan = date('m', strtotime($this->input->post('tanggal_keluar')));
		$tahun = date('Y', strtotime($this->input->post('tanggal_keluar')));
		$kode = $this->laporan_keluar->get_for_check($bulan, $tahun);
		$urutan = $kode + 1;
		$id_keluar = $tipe . $date . sprintf("%03s", $urutan);

		$data = array(
			'id_keluar' => $id_keluar,
			'tanggal_keluar' => $this->input->post('tanggal_keluar')
		);

		$insert = $this->laporan_keluar->save($data);

		echo json_encode(array("status" => TRUE, "id_keluar" => $id_keluar));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('tanggal_keluar') == '')
		{
			$data['inputerror'][] = 'tanggal_keluar';
			$data['error_string'][] = 'Isi tanggal';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	// ------------------------------------------------------------------ DETAIL ---------------------------------------------- //

	public function ajax_list_detail($id_keluar)
	{
		$this->load->helper('url');

		$list = $this->detail_keluar->get_datatables($id_keluar);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $detail) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$detail->id.'">';
			$row[] = $detail->nama_menu;
			$row[] = $detail->qty;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_detail('."'".$detail->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_detail('."'".$detail->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->detail_keluar->count_all($id_keluar),
				"recordsFiltered" => $this->detail_keluar->count_filtered($id_keluar),
				"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_add_detail()
	{
		$this->_validate_detail();

		$data = array(
			'id_keluar' => $this->input->post('id_keluar'),
			'id_menu' => $this->input->post('id_menu'),
			'qty' => $this->input->post('jumlah_menu')
		);

		$id_insert = $this->detail_keluar->save_dtl($data);

		$get_komposisi = $this->detail_keluar->get_bahan($id_insert);
		foreach ($get_komposisi as $k) {
			$this->detail_keluar->save_dtl_komposisi($k);
		}

		echo json_encode(array("status" => TRUE));
		
	}

	public function ajax_edit($id)
	{	
		$data = $this->detail_keluar->get_by_id($id);
		
		echo json_encode($data);

	}

	public function ajax_update()
	{
		$this->_validate_detail();
		$data = array(
				'id_keluar' => $this->input->post('id_keluar'),
				'id_menu' => $this->input->post('id_menu'),
				'qty' => $this->input->post('jumlah_menu')
			);
		$this->detail_keluar->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->detail_keluar->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_keluar');
		foreach ($list_id as $id_keluar) {
			$this->detail_keluar->delete_by_id($id_keluar);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate_detail()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_menu') == '')
		{
			$data['inputerror'][] = 'id_menu';
			$data['error_string'][] = 'Pilih Menu';
			$data['status'] = FALSE;
		}

		if($this->input->post('jumlah_menu') == '')
		{
			$data['inputerror'][] = 'jumlah_menu';
			$data['error_string'][] = 'Masukkan Jumlah Menu';
			$data['status'] = FALSE;
		}

		if (!empty($this->input->post('jumlah_menu')) && !empty($this->input->post('id_menu'))) {
			$get_qty = $this->detail_keluar->get_qty($this->input->post('jumlah_menu'), $this->input->post('id_menu'));
			$cek_stok = true;
			$bahan = [];

			foreach ($get_qty as $c) {
				$get_stok = $this->detail_keluar->get_stok($c->id_bahan, $c->id_unit);
				if ($get_stok == NULL ) {
					$cek_stok = false;
				} else {
					$jumlah_stok = $this->detail_keluar->get_jumlah($c->id_bahan, $c->id_unit);
					if ($c->qtySum > $jumlah_stok) {
						$cek_stok = false;
					}
				}
			}
			
			// echo '<pre>';
			// var_dump($bahan);
			// die();

			if(!$cek_stok)
			{
				$data['inputerror'][] = 'jumlah_menu';
				$data['error_string'][] = 'Jumlah stok bahan kurang / kosong';
				$data['status'] = FALSE;
			}
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
