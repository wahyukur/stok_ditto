<?php

class Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function set($table)
	{
		if ($table == 'menu') {
			$data['column_order']  = $column_order = array(null,'nama_menu','category',null);
			$data['column_search'] = $column_search = array('nama_menu','category');
			$data['order']         = $order = array('id_menu' => 'desc');
		} elseif ($table == 'bahan') {
			$data['column_order']  = $column_order = array(null,'nama_menu','category',null);
			$data['column_search'] = $column_search = array('nama_menu','category');
			$data['order']         = $order = array('id_menu' => 'desc');
		}

		return $data;
	}

	private function _get_datatables_query($table)
	{
		$this->db->from($table);
		$datas = $this->set($table);

		$column_search = $datas['column_search'];
		$column_order  = $datas['column_order'];
		$order         = $datas['order'];

		$i = 0;
	
		foreach ($column_search as $item) 
		{
			if($_POST['search']['value'])
			{
				if($i===0) 
				{
					$this->db->group_start(); 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($column_search) - 1 == $i) 
					$this->db->group_end();
			}
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($table)
	{
		$this->_get_datatables_query($table);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($table)
	{
		$this->_get_datatables_query($table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($table)
	{
		$this->db->from($table);
		return $this->db->count_all_results();
	}

	public function get_by_id($table,$id)
	{
		$this->db->from($table);
		$this->db->where('id_menu',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($table,$data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{		
		$this->db->update('menu', $data, $where);
		return $this->db->affected_rows();
		
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_menu', $id);
		$this->db->delete('menu');
	}
}