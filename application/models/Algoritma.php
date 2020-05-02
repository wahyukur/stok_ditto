<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Algoritma extends CI_Model {

	
	public function get_qty_period($yearmonth_a, $yearmonth_b, $id_bahan, $id_unit)
	{
		$query = $this->db->query("
			SELECT b.tanggal_trans, c.nama_bahan, d.unitid, b.end_qty  
			FROM (SELECT extract(year_month FROM tanggal_trans) month_id, 
				  max(tanggal_trans) max_tgl 
				  FROM stok_movement 
				  GROUP BY 1) a 
			INNER JOIN stok_movement b ON a.max_tgl = b.tanggal_trans 
			LEFT JOIN bahan c ON c.id_bahan = b.id_bahan 
            LEFT JOIN unit d ON d.id_unit = b.id_unit 
			WHERE b.id_bahan = $id_bahan AND b.id_unit = $id_unit AND a.month_id BETWEEN '$yearmonth_a' AND '$yearmonth_b' 
		");

		return $query->result();
	}

	public function get_bahan()
	{
		$query = $this->db->query("
			SELECT a.id_bahan, b.nama_bahan   
			FROM stok_movement a 
			LEFT JOIN bahan b ON a.id_bahan = b.id_bahan 
			GROUP BY a.id_bahan 
		");

		return $query->result();
	}

	public function get_unit($id_bahan)
	{
		$query = $this->db->query("
			SELECT a.id_unit, b.unitid 
			FROM stok_movement a 
			LEFT JOIN unit b ON a.id_unit = b.id_unit 
			WHERE a.id_bahan = $id_bahan 
			GROUP BY a.id_unit 
		");

		return $query->result();
	}

	public function get_dataset_a()
	{
		$query = $this->db->query("
			SELECT b.tanggal_trans, c.nama_bahan, d.unitid, b.end_qty 
			FROM (SELECT extract(year_month FROM tanggal_trans) month_id, 
                  max(tanggal_trans) max_tgl 
                  FROM stok_movement 
                  GROUP BY 1) a 
			INNER JOIN stok_movement b ON a.max_tgl = b.tanggal_trans 
            LEFT JOIN bahan c ON c.id_bahan = b.id_bahan 
            LEFT JOIN unit d ON d.id_unit = b.id_unit 
		");

		return $query->result();
	}

}