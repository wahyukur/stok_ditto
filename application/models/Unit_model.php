<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_model extends CI_Model {

	var $table = 'unit';
	var $column_order = array(null,'unitid','unit_groupid','convertion',null); //set column field database for datatable orderable
	var $column_search = array('unitid','unit_groupid','convertion'); //set column field database for datatable searchable just nama_bahan , category , address are searchable
	var $order = array('unitid' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($id)
	{
		// var_dump($id);die();
		$this->db->from($this->table);
		$this->db->where('unit_groupid', $id);
		

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($id)
	{
		
		$this->_get_datatables_query($id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id)
	{
		$this->_get_datatables_query($id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id)
	{
		$this->db->from($this->table);
		$this->db->where('unit_groupid', $id);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_unit)
	{
		$this->db->from($this->table);
		$this->db->where('id_unit',$id_unit);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id_unit)
	{
		$this->db->where('id_unit', $id_unit);
		$this->db->delete($this->table);
	}

	public function ambil_unitgroup()
	{
		// $this->db->select('unit_groupid');
		$this->db->from('unit_group');
		$query = $this->db->get();

		return $query->result();
	}

	public function selectUnit()
	{
		$this->db->select('*');
		$this->db->from('unit');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_unitid()
	{
		$this->db->select('unit_groupid');
		$this->db->from('unit_group');
		$this->db->order_by('unit_groupid', 'desc');
		$this->db->limit('1');

		$query = $this->db->get();
		return $query->row()->unit_groupid;
	}
}