<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends CI_Model {

	var $table = 'stok_bahan';
	var $column_order = array('b.nama_bahan','a.jumlah_bahan','a.unit_stok','a.changed_date'); //set column field database for datatable orderable
	var $column_search = array('b.nama_bahan','a.jumlah_bahan','a.unit_stok','a.changed_date'); //set column field database for datatable searchable just nama_stok_bahan , category , address are searchable
	var $order = array('id_stok' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		
		// $this->db->from($this->table);
		$this->db->select('a.*, b.nama_bahan, c.unitid');
		$this->db->from('stok_bahan as a');
		$this->db->join('bahan as b', 'a.id_bahan = b.id_bahan');
		$this->db->join('unit as c', 'c.id_unit = a.id_unit');


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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_stok)
	{
		$this->db->select('a.*, b.nama_bahan, c.unitid');
		$this->db->from('stok_bahan as a');
		$this->db->join('bahan as b', 'a.id_bahan = b.id_bahan', 'left');
		$this->db->join('unit as c', 'a.id_unit = c.id_unit', 'left');
		$this->db->where('a.id_stok',$id_stok);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_unit($id)
	{
		// var_dump($id);
		// die();
		$query = $this->db->query("
			SELECT * 
			FROM unit 
			WHERE unit_groupid = (select unit_groupid from bahan where id_bahan = '$id')
		");

		return $query->result();
	}


	public function convertion($id_unit)
	{
		$this->db->from('unit');
		$this->db->where('id_unit', $id_unit);
		$query = $this->db->get();
		return $query->row()->convertion;
	}
}