<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bayar_m extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	function get_data_transaksi_ajax($offset, $limit, $q='', $sort, $order) {
		$sql = "SELECT v_hitung_pinjaman.* , tbl_anggota.category
				FROM v_hitung_pinjaman
				JOIN tbl_anggota ON tbl_anggota.id = v_hitung_pinjaman.anggota_id";
		$where = " WHERE lunas='Belum'  ";
		if(is_array($q)) {
			if($q['kode_transaksi'] != '') {
				$q['kode_transaksi'] = str_replace('TPJ', '', $q['kode_transaksi']);
				$q['kode_transaksi'] = $q['kode_transaksi'] * 1;
				$where .=" AND (v_hitung_pinjaman.id LIKE '".$q['kode_transaksi']."' OR v_hitung_pinjaman.anggota_id LIKE '".$q['kode_transaksi']."') ";
			} else {
				if($q['cari_nama'] != '') {
					$where .=" AND tbl_anggota.nama LIKE '%".$q['cari_nama']."%' ";
					//$sql .= " LEFT JOIN tbl_anggota ON (v_hitung_pinjaman.anggota_id = tbl_anggota.id) ";
				}
				if($q['cari_anggota'] != '') {
					$where .=" AND tbl_anggota.category = '".$q['cari_anggota']."' ";
				}
				if($q['tgl_dari'] != '' && $q['tgl_sampai'] != '') {
					$where .=" AND DATE(tgl_pinjam) >= '".$q['tgl_dari']."' ";
					$where .=" AND DATE(tgl_pinjam) <= '".$q['tgl_sampai']."' ";
				}
			}
		}
		$sql .= $where;
		$result['count'] = $this->db->query($sql)->num_rows();
		$sql .=" ORDER BY {$sort} {$order} ";
		$sql .=" LIMIT {$offset},{$limit} ";
		$result['data'] = $this->db->query($sql)->result();
		return $result;
	}
	
	// Added by Gani
	public function import_db($data) {
		if(is_array($data)) {

			$pair_arr = array();
			foreach ($data as $rows) {
				//if(trim($rows['A']) == '') { continue; }
				// per baris
				$pair = array();
				foreach ($rows as $key => $val) {
					if($key == 'A') { $pair['tgl_bayar'] = $val; }
					if($key == 'B') { $pair['pinjam_id'] = (int)str_replace("PJ","",$val);}
					if($key == 'C') { $pair['angsuran_ke'] = $val; }
					if($key == 'D') { $pair['jumlah_bayar'] = $val; }
					if($key == 'E') { $pair['denda_rp'] = $val; }
					if($key == 'F') { $pair['terlambat'] = $val; }
					if($key == 'G') { $pair['keterangan'] = $val; }
				}
				$pair['ket_bayar'] = 'Angsuran';
				$pair['dk'] = 'D';
				$pair['kas_id'] = 1;
				$pair['jns_trans'] = 48;
				$pair['user_name'] = $this->data['u_name'];
				$pair_arr[] = $pair;
			}
			//var_dump($pair_arr);
			//return 1;
			return $this->db->insert_batch('tbl_pinjaman_d', $pair_arr);
		} else {
			return FALSE;
		}
	}
	
	function get_data_excel() {
		$sql = "SELECT  a.*, b.nama, b.identitas FROM v_hitung_pinjaman a
				JOIN tbl_anggota b ON b.id = a.anggota_id
				WHERE lunas='Belum' ";
		$result['data'] = $this->db->query($sql)->result();
		return $result;
	}
}

