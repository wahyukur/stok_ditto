<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_group extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('unit_group_model','unit_group');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master Unit';
		$data['content'] = 'pages/unit_group_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->unit_group->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $unit_group) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$unit_group->unit_groupid.'">';
			$row[] = $unit_group->unit_groupid;
			$row[] = $unit_group->description;
			$row[] = $unit_group->created_at;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Ed1t" onclick="edit_unit_group('."'".$unit_group->unit_groupid."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_unit_group('."'".$unit_group->unit_groupid."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Detail" onclick="detail('."'".$unit_group->unit_groupid."'".')"><i class="glyphicon glyphicon-plus"></i> Koneversi</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->unit_group->count_all(),
						"recordsFiltered" => $this->unit_group->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($unit_groupid)
	{	
		$data = $this->unit_group->get_by_id($unit_groupid);
		$data->created_at =  ($data->created_at == '0000-00-00') ? '' : $data->created_at;
		
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();
		
		$tanggal = date('Y-m-d');

		$data = array(
				
				'unit_groupid' => $this->input->post('unit_groupid'),
				'description' => $this->input->post('description'),
				'created_at' => $tanggal
				
			);


		$insert = $this->unit_group->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'unit_groupid' => $this->input->post('unit_groupid'),
				'description' => $this->input->post('description'),
				'created_at' => $this->input->post('created_at')
			);
		$this->unit_group->update(array('unit_groupid' => $this->input->post('unit_groupid')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($unit_groupid)
	{
		$this->unit_group->delete_by_id($unit_groupid);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('unit_groupid');
		foreach ($list_id as $unit_groupid) {
			$this->unit_group->delete_by_id($unit_groupid);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;


		if($this->input->post('unit_groupid') == '')
		{
			$data['inputerror'][] = 'unit_groupid';
			$data['error_string'][] = 'isi unit';
			$data['status'] = FALSE;
		}
		if($this->input->post('description') == '')
		{
			$data['inputerror'][] = 'description';
			$data['error_string'][] = 'isi description';
			$data['status'] = FALSE;
		}
		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
