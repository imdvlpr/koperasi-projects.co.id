<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_akun_m extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	//Added
	public function import_db($data) {
		if(is_array($data)) {

			$pair_arr = array();
			foreach ($data as $rows) {
				//if(trim($rows['A']) == '') { continue; }
				// per baris
				$pair = array();
				foreach ($rows as $key => $val) {
					if($key == 'A') { $pair['kd_aktiva'] = $val; }
					if($key == 'B') { $pair['jns_trans'] = $val; }
					if($key == 'C') { $pair['akun'] = $val; }
					if($key == 'D') { $pair['pemasukan'] = $val; }
					if($key == 'E') { $pair['pengeluaran'] = $val; }
					if($key == 'F') { $pair['aktif'] = $val; }
					if($key == 'G') { $pair['laba_rugi'] = $val; }
				}
				$pair_arr[] = $pair;
			}
			//var_dump($pair_arr);
			//return 1;
			return $this->db->insert_batch('jns_akun', $pair_arr);
		} else {
			return FALSE;
		}
	}
}