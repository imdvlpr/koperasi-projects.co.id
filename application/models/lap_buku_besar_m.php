<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_buku_besar_m extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	//panggil data jenis kas untuk laporan
	function get_nama_kas() {
		$this->db->select('*');
		$this->db->from('nama_kas_tbl');
		$this->db->where('aktif','Y');
		$query = $this->db->get();
		if($query->num_rows()>0){
			$out = $query->result();
			return $out;
		} else {
			return array();
		}
	}

	//panggil data jenis kas untuk laporan
	function get_transaksi_kas($kas_id) {
		$this->db->select('*');
		$this->db->from('v_transaksi');
		
		if(isset($_REQUEST['tgl_dari']) && isset($_REQUEST['tgl_samp'])) {
			$tgl_dari = $_REQUEST['tgl_dari'];
			$tgl_samp = $_REQUEST['tgl_samp'];
		} else {
			$tgl_dari = date('Y') . '-01-01';
			$tgl_samp = date('Y') . '-12-31';
		}

		$where = "(DATE(tgl) >= '".$tgl_dari."' AND  DATE(tgl) = '".$tgl_samp."') AND (dari_kas = '".$kas_id."' OR  untuk_kas = '".$kas_id."')";
		$this->db->where($where);
		$this->db->order_by('tgl', 'ASC');
		$query = $this->db->get();

		if($query->num_rows()>0) {
			$out = $query->result();
			return $out;
		} else {
			return array();
		}
	}


	function get_nama_akun_id($id) {
		$this->db->select('*');
		$this->db->from('jns_akun');
		$this->db->where('id', $id);
		$query = $this->db->get();

		if($query->num_rows() > 0) {
			$out = $query->row();
			return $out;
		} else {
			$out = (object) array('nama' => '');
			return $out;
		}
	}	
}