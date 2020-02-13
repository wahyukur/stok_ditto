<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('periode_model','periode');
	}

	public function index()
	{
		$this->load->helper('url');
		$data['page'] = 'Master periode';
		$data['content'] = 'pages/periode_view';
		$this->load->view('template/main', $data);
	}

	public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->periode->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $periode) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" class="data-check" value="'.$periode->id_periode.'">';
			$row[] = $periode->description;
			$row[] = $periode->start_date;
			$row[] = $periode->end_date;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_periode('."'".$periode->id_periode."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_periode('."'".$periode->id_periode."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}


		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->periode->count_all(),
						"recordsFiltered" => $this->periode->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id_periode)
	{	
		$data = $this->periode->get_by_id($id_periode);
		$data->start_date =  ($data->start_date == '0000-00-00') ? '' : $data->start_date;
		$data->end_date =  ($data->end_date == '0000-00-00') ? '' : $data->end_date;
		echo json_encode($data);

	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
				'description' => $this->input->post('description'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date')
			);

		$insert = $this->periode->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'description' => $this->input->post('description'),
				'start_date' => $this->input->post('start_date'),
				'end_date' => $this->input->post('end_date')
			);
		$this->periode->update(array('id_periode' => $this->input->post('id_periode')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id_periode)
	{
		$this->periode->delete_by_id($id_periode);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id_periode');
		foreach ($list_id as $id_periode) {
			$this->periode->delete_by_id($id_periode);
		}
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('description') == '')
		{
			$data['inputerror'][] = 'description';
			$data['error_string'][] = 'harap isi dulu';
			$data['status'] = FALSE;
		}

		if($this->input->post('start_date') == '')
		{
			$data['inputerror'][] = 'start_date';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('end_date') == '')
		{
			$data['inputerror'][] = 'end_date';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
