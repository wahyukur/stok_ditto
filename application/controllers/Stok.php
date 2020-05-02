<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		$this->load->model('Stok_model','stok');
		$this->load->model('Stok_dtl_model','detail');
		$this->load->model('laporan_keluar_model','laporan_keluar');
		$this->load->model('detail_keluar_model','detail_keluar');
		$this->load->model('laporan_masuk_model','laporan_masuk');
		$this->load->model('detail_masuk_model','detail_masuk');
		$this->load->model('komposisi_menu_model','komposisi_menu');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Informasi Stok';
		$data['content'] = 'pages/stok_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->stok->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $stok) {
			$no++;
			$row = array();
			$row[] = $stok->nama_bahan;
			$row[] = $stok->jumlah_bahan.' '.$stok->unitid;
			$row[] = $stok->changed_date;

			$arr = [$stok->id_bahan, $stok->id_unit];
			$parameter = implode("-", $arr);	


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Exchange" onclick="exchange('."'".$stok->id_stok."'".')"><i class="fas fa-exchange-alt"></i> Exchange</a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Detail" onclick="detail('."'".$parameter."'".')"><i class="fas fa-align-justify"></i> Detail</a>';
		
			$data[] = $row;
		}

		// onclick="delete_menu('."'".$menu->id_menu."'".')"
		// onclick="edit_menu('."'".$menu->id_menu."'".')"

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->stok->count_all(),
						"recordsFiltered" => $this->stok->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_exchange($id_stok)
	{	
		$data['get'] = $this->stok->get_by_id($id_stok);
		$id = $this->stok->get_by_id($id_stok)->id_bahan;
		$data['unit'] = $this->stok->get_unit($id);
		echo json_encode($data);
	}

	public function exchange()
	{
		$this->_validate();
		$konversi_a = $this->stok->convertion($this->input->post('id_unit'));
		$konversi_b = $this->stok->convertion($this->input->post('unitid_exc'));
		$qty_hasil = 0;

		if ($konversi_a < $konversi_b) {
			$qty_hasil = $this->input->post('qty_exc') * $konversi_b;

		} elseif ($konversi_a > $konversi_b) {
			$qty_hasil = $this->input->post('qty_exc') / $konversi_a;

		} elseif ($konversi_a == $konversi_b) {
			$qty_hasil = $this->input->post('qty_exc') * 1;
		}
		
		
		$tipe = 'XOUT'; // IN
		$date = date('my', strtotime(date('Y-m-d'))); // bulan tahun cth : 0220 -> bulan 02, tahun 20
		$bulan = date('m', strtotime(date('Y-m-d')));
		$tahun = date('Y', strtotime(date('Y-m-d')));
		$kode = $this->laporan_keluar->get_for_check($bulan, $tahun);
		$urutan = $kode + 1;
		$id_keluar = $tipe . $date . sprintf("%03s", $urutan);

		$data = array(
			'id_keluar' => $id_keluar,
			'tanggal_keluar' => date('Y-m-d')
		);

		$insert = $this->laporan_keluar->save($data);

		$data = array(
			'id_keluar' => $id_keluar,
			'id_menu' => 0,
			'qty' => 0
		);

		$id_insert = $this->detail_keluar->save_dtl($data);

		$data_komposisi = [
			'id' => $id_insert,
			'id_keluar' => $id_keluar, 
			'id_menu' => 0,
			'id_bahan' => $this->input->post('id_bahan'),
			'qty_total' => $this->input->post('qty_exc'),
			'id_unit' =>  $this->input->post('id_unit')
		];
		
		$this->detail_keluar->save_dtl_komposisi($data_komposisi);

		// ------------------------ EX IN ----------------------------- //

		$tipe = 'EXIN'; // IN
		$date = date('my', strtotime(date('Y-m-d'))); // bulan tahun cth : 0220 -> bulan 02, tahun 20
		$bulan = date('m', strtotime(date('Y-m-d')));
		$tahun = date('Y', strtotime(date('Y-m-d')));
		$kode = $this->laporan_masuk->get_for_check($bulan, $tahun);
		$urutan = $kode + 1;
		$id_masuk = $tipe . $date . sprintf("%03s", $urutan);

		$data = array(
			'id_masuk' => $id_masuk,
			'tanggal_masuk' => date('Y-m-d')
		);

		$insert = $this->laporan_masuk->save($data);

		$data = array(
			'id_masuk' => $id_masuk,
			'id_bahan' => $this->input->post('id_bahan'),
			'qty' => $qty_hasil,
			'id_unit' => $this->input->post('unitid_exc'),
		);

		$insert = $this->laporan_masuk->save_dtl($data);
		
		// $data = array(
		// 			'nama_menu' => $this->input->post('nama_menu'),
		// 			'category' => $this->input->post('category')
		// 		);
		// $this->menu->update(array('id_menu' => $this->input->post('id_menu')), $data);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('qty_exc') == '')
		{
			$data['inputerror'][] = 'qty_exc';
			$data['error_string'][] = 'Quantity Kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('unitid_exc') == '')
		{
			$data['inputerror'][] = 'unitid_exc';
			$data['error_string'][] = 'Pilih Unit';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	// ------------------------------------------ DETAIL MOVEMENT ----------------------------------------------- //

	public function dtl($parameter)
	{
		$this->load->helper('url');
		$id = explode("-", $parameter);
		$id_bahan = $id[0];
		$id_unit = $id[1];
		$bahan = $this->detail->get_bahan($id_bahan);
		$unit = $this->detail->get_unit($id_unit);
		$data['parameter'] = $parameter;
		$data['page'] = 'Stok Movement '.$bahan.' ('.$unit.')';
		$data['content'] = 'pages/stok_dtl_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list_dtl($parameter)
	{
		$this->load->helper('url');
		$id = explode("-", $parameter);
		$id_bahan = $id[0];
		$id_unit = $id[1];

		$list = $this->detail->get_datatables($id_bahan, $id_unit);
		// var_dump($list);
		// die();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $k) {
			$no++;
			$row = array();
			$row[] = $k->tanggal_trans;
			$row[] = $k->nomor_trans;
			$row[] = $k->nama_bahan;
			$row[] = $k->begin_qty.' '.$k->unitid;
			$row[] = $k->masuk.' '.$k->unitid;
			$row[] = $k->keluar.' '.$k->unitid;
			$row[] = $k->end_qty.' '.$k->unitid;

			
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->detail->count_all($id_bahan, $id_unit),
						"recordsFiltered" => $this->detail->count_filtered($id_bahan, $id_unit),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

}
