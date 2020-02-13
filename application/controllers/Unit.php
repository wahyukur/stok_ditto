<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Unit extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('unit_model','unit');
	}

	public function index($unit_groupid)
	{
		// var_dump($unit_groupid);
		$this->load->helper('url');
		$data['page'] = 'Master Unit Convertion';
		$data['content'] = 'pages/unit_view';
		$data['foreign_key'] = $unit_groupid;
		$data['ambil_unit'] = $this->unit->ambil_unitgroup();
		$this->load->view('template/main', $data);
	}


	public function ajax_list($id)
	{
		// die(var_dump());
		$list = $this->unit->get_datatables($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$unit->id_unit.'">';
			$row[] = $unit->unitid;
			$row[] = $unit->unit_groupid;
			$row[] = $unit->convertion;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Ed1t" onclick="edit_unit('."'".$unit->id_unit."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_unit('."'".$unit->id_unit."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		// var_dump($unit->id_unit);

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->unit->count_all($id),
						"recordsFiltered" => $this->unit->count_filtered($id),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_unit)
	{	
		$data = $this->unit->get_by_id($id_unit);
		//$data->created_at =  ($data->created_at == '0000-00-00') ? '' : $data->created_at;
		
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();
		
		$units = $this->input->post('unitid');
		$group = $this->input->post('unit_groupid');
		$ambil_units = $this->unit->selectUnit();
		$cek = FALSE;

		foreach ($ambil_units as $key) {
			if ($units == $key->unitid && $group == $key->unit_groupid) {
				$cek = TRUE;
			}
		}

		
		if ($cek == false) {
			$data = array(
				'unitid' => $this->input->post('unitid'),
				'unit_groupid' => $this->input->post('unit_groupid'),
				'convertion' => $this->input->post('convertion')
			);

			$insert = $this->unit->save($data);
			echo json_encode(array("status" => TRUE));
		}
		
	}


	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'unitid' => $this->input->post('unitid'),
				'unit_groupid' => $this->input->post('unit_groupid'),
				'convertion' => $this->input->post('convertion')
			);
		$this->unit->update(array('id_unit' => $this->input->post('id_unit')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_unit)
	{
		$this->unit->delete_by_id($id_unit);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_unit');
		foreach ($list_id as $id_unit) {
			$this->unit->delete_by_id($id_unit);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('unitid') == '')
		{
			$data['inputerror'][] = 'unitid';
			$data['error_string'][] = 'isi unit';
			$data['status'] = FALSE;
		}

		if($this->input->post('unit_groupid') == '')
		{
			$data['inputerror'][] = 'unit_groupid';
			$data['error_string'][] = 'isi unit';
			$data['status'] = FALSE;
		}
		if($this->input->post('convertion') == '')
		{
			$data['inputerror'][] = 'convertion';
			$data['error_string'][] = 'isi convertion';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
