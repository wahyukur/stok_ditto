<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Triple_exp_smoothing extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (is_logged_in() == false) {
			redirect(base_url('index.php/'));
		}
		
		$this->load->model('algoritma','triple');
		
		// $this->load->library('session');
	}

	public function index($parameter=NULL)
	{	
		// $this->load->helper('url');

		if (empty($parameter)) {
			$dataset = $this->triple->get_dataset_a();
			$hasil = array();
		} else {
			$datefrom = $this->input->post('datefrom');
	        $dateto     = $this->input->post('dateto');           
	        $id_bahan = $this->input->post('bahan');
	        $id_unit  = $this->input->post('unit');
	        $alpha = $this->input->post('alpha');

	        $date_a_ex = explode("-", $datefrom);
			$date_b_ex = explode("-", $dateto);

			$tahun_a = $date_a_ex[0];
			$tahun_b = $date_b_ex[0];
			
			$bulan_a = $date_a_ex[1];
			$bulan_b = $date_b_ex[1];

			$yearmonth_a = $tahun_a.$bulan_a;
			$yearmonth_b = $tahun_b.$bulan_b;

			$get_data = $this->algoritma($id_bahan,$id_unit,$datefrom,$dateto,$alpha);
			$dataset = $this->triple->get_qty_period($yearmonth_a,$yearmonth_b,$id_bahan,$id_unit);
			$hasil = $get_data['hasil'];
			// var_dump($parameter);
			// die();
		}

		$data['bahan'] = $this->triple->get_bahan();
		$data['dataset'] = $dataset;
		$data['hasil'] = $hasil;
		$data['page'] = 'Triple Expontial Smoothing';
		$data['content'] = 'pages/process_view';
		$this->load->view('template/main', $data);
	}


	public function algoritma($bahan,$unit,$datefrom,$dateto,$alpha)
	{	
		// echo "<pre>";
		$date_a_ex = explode("-", $datefrom);
		$date_b_ex = explode("-", $dateto);

		$tahun_a = $date_a_ex[0];
		$tahun_b = $date_b_ex[0];
		
		$bulan_a = $date_a_ex[1];
		$bulan_b = $date_b_ex[1];

		$yearmonth_a = $tahun_a.$bulan_a;
		$yearmonth_b = $tahun_b.$bulan_b;		

		$id_bahan = $bahan;
		$id_unit = $unit;
		// $alpha = $alpha;

		$get_data = $this->triple->get_qty_period($yearmonth_a,$yearmonth_b,$id_bahan,$id_unit);

		$dataset = array();
		foreach ($get_data as $k) {
			$dataset[] = [
				'tanggal_trans' => $k->tanggal_trans, 
				'end_qty' => $k->end_qty
			];
		}

		$s = array();
		$ss = array();
		$sss = array();
		$at = array();
		$bt = array();
		$ct = array();
		$ft = array();
		$pe = array();
		$data = array();

		for ($t=0; $t < count($dataset); $t++) { 
			if ($t == 0) {
				$s[$t] = $dataset[0]['end_qty'];
				$ss[$t] = $dataset[0]['end_qty'];
				$sss[$t] = $dataset[0]['end_qty'];
			} else {
				$s[$t] = ($alpha * $dataset[$t]['end_qty']) + ((1 - $alpha) * $s[$t - 1]);
				$ss[$t] = ($alpha * $s[$t]) + ((1 - $alpha) * $ss[$t - 1]);
				$sss[$t] = ($alpha * $ss[$t]) + ((1 - $alpha) * $sss[$t - 1]);
			}

			$at[$t] = 3*$s[$t] - 3*$ss[$t] + $sss[$t];
			$bt[$t] = $alpha / (2 * pow((1-$alpha), 2)) * ((6 - 5) * $alpha) * $s[$t] - ((10 - 8) * $alpha) * $ss[$t] + ((4 - 3) * $alpha) * $sss[$t];
			$ct[$t] = ((pow($alpha, 2)) / (pow((1 - $alpha), 2)) * $s[$t]) - (2 * $ss[$t]) + $sss[$t];
			$ft[$t] = $at[$t] + ($bt[$t] * 1) + (0.5 * ($ct[$t] * 1));
			$pe[$t] = abs($dataset[$t]['end_qty'] - $ft[$t]) / count($dataset);
			
			
			// Data untuk di tampilkan ke view
			$data[$t]['s'] = $s[$t];
			$data[$t]['ss'] = $ss[$t];
			$data[$t]['sss'] = $sss[$t];
			// $data[$t]['at'] = $at[$t];
			// $data[$t]['bt'] = $bt[$t];
			// $data[$t]['ct'] = $ct[$t];
			$data[$t]['ft'] = $ft[$t];
			$data[$t]['actual'] = $dataset[$t]['end_qty'];
			$data[$t]['pe'] = $pe[$t];
		}
		
		$datax = [
			'yearmonth_a' => $yearmonth_a,
			'yearmonth_b' => $yearmonth_b,
			'id_bahan' => $id_bahan, 
			'id_unit', $id_unit
		];

		$get_dataset['dataset'] = $datax;
		$get_dataset['hasil'] = $data;

		return $get_dataset;

		// echo 'Dataset` = <br>'; 
		// var_dump($dataset);
		// echo 'Hasil = <br>';
		// var_dump($data);
	}

	public function get_unit()
	{
		$arr = [];
		$id = $this->input->post('id');
		$id_unit = $this->triple->get_unit($id);

		echo json_encode($id_unit);
	}


}
