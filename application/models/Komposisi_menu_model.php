<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Komposisi_menu_model extends CI_Model {

	var $table = 'komposisi_menu';
	var $column_order = array(null,'id_menu','id_bahan','jumlah','unitid',null); //set column field database for datatable orderable
	var $column_search = array('id_menu','id_bahan','jumlah','unitid'); //set column field database for datatable searchable just nama_komposisi_menu , category , address are searchable
	var $order = array('id_composition' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($id)
	{
		$this->db->select('komposisi_menu.*, bahan.*, menu.*, unit.unitid');
		$this->db->from('komposisi_menu');
		$this->db->join('bahan', 'komposisi_menu.id_bahan = bahan.id_bahan', 'left');
		$this->db->join('menu', 'komposisi_menu.id_menu = menu.id_menu', 'left');
		$this->db->join('unit', 'komposisi_menu.unitid = unit.id_unit', 'left');
		$this->db->where('komposisi_menu.id_menu', $id);

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
		// $this->db->from($this->table);
		$this->db->select('komposisi_menu.*, bahan.*, menu.*');
		$this->db->from('komposisi_menu');
		$this->db->join('bahan', 'komposisi_menu.id_bahan = bahan.id_bahan', 'left');
		$this->db->join('menu', 'komposisi_menu.id_menu = menu.id_menu', 'left');
		$this->db->where('komposisi_menu.id_menu', $id);
		return $this->db->count_all_results();
	}

	public function get_by_id($id_composition)
	{
		$this->db->from($this->table);
		$this->db->where('id_composition',$id_composition);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_bahan()
	{
		$this->db->from('bahan');
		$query = $this->db->get();

		return $query->result();
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

	public function delete_by_id($id_composition)
	{
		$this->db->where('id_composition', $id_composition);
		$this->db->delete($this->table);
	}

	public function get_menu($id)
	{
		$this->db->from('menu');
		$this->db->where('id_menu', $id);
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

	public function get_menuid()
	{
		$this->db->select('id_menu');
		$this->db->from('menu');
		$this->db->order_by('id_menu', 'desc');
		$this->db->limit('1');

		$query = $this->db->get();
		return $query->row()->id_menu;
	}
}