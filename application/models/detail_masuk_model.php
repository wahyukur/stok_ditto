<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_masuk_model extends CI_Model {

	var $table = 'masuk_detail';
	var $column_order = array(null,'id_masuk','id_bahan','qty','id_unit',null); //set column field database for datatable orderable
	var $column_search = array('id_masuk','id_bahan','qty','id_unit'); //set column field database for datatable searchable just nama_masuk_detail , category , address are searchable
	var $order = array('id' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($id_masuk)
	{
		
        $this->db->select('masuk_detail.*, bahan.*, unit.unitid');
		$this->db->from('masuk_detail');
		$this->db->join('bahan', 'masuk_detail.id_bahan = bahan.id_bahan', 'left');
		$this->db->join('unit', 'masuk_detail.id_unit = unit.id_unit', 'left');
		$this->db->where('masuk_detail.id_masuk', $id_masuk);

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

	function get_datatables($id_masuk)
	{
		$this->_get_datatables_query($id_masuk);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($id_masuk)
	{
		$this->_get_datatables_query($id_masuk);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($id_masuk)
	{
        $this->db->from($this->table);
        $this->db->where('id_masuk',$id_masuk);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
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

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}


}