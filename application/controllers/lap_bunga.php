<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_bunga extends AdminController {

	public function __construct() {
		parent::__construct();
    $this->load->helper('fungsi');
    $this->load->model('general_m');
    $this->load->model('lap_kas_anggota_m');
	}

	public function index() {
    //Menampilkan semua data anggota

		$this->data['judul_browser'] = 'Setting';
		$this->data['judul_utama'] = 'Setting';
		// $this->data['judul_sub'] = 'Jenis Simpanan <a href="'.site_url('jenis_simpanan/import').'" class="btn btn-sm btn-success">Import Data</a>';

		$this->output->set_template('gc');

		$this->load->library('grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table('jns_simpan');
		$crud->set_subject('Jenis Simpanan');


		$crud->display_as('jns_simpan','Jenis Simpanan');
		$crud->display_as('auto_simpan','Simpanan Otomatis');
		$crud->fields('jns_simpan','jumlah','tenor','bunga','fixed','tampil','auto_simpan');

		$crud->required_fields('jns_simpan');


		$crud->unset_read();
		//$crud->unset_add();
		$crud->unset_delete();
		$output = $crud->render();

		$out['output'] = $this->data['judul_browser'];
		$this->load->section('judul_browser', 'default_v', $out);
		$out['output'] = $this->data['judul_utama'];
		$this->load->section('judul_utama', 'default_v', $out);
		$out['output'] = $this->data['judul_sub'];
		$this->load->section('judul_sub', 'default_v', $out);
		$out['output'] = $this->data['u_name'];
		$this->load->section('u_name', 'default_v', $out);

		$this->load->view('default_v', $output);

//Jenis Pinjaman
$this->data['judul_browser'] = 'Setting';
$this->data['judul_utama'] = 'Setting';
// $this->data['judul_sub'] = 'Jenis Pinjaman <a href="'.site_url('jenis_pinjaman/import').'" class="btn btn-sm btn-success">Import Data</a>';

$this->output->set_template('gc');

$this->load->library('grocery_CRUD');
$crud = new grocery_CRUD();
$crud->set_table('jns_pinjaman');
$crud->set_subject('Jenis Pinjaman');


$crud->display_as('jns_pinjaman','Jenis Pinjaman');
$crud->display_as('provinsi','Provisi');
$crud->fields('jns_pinjaman','jumlah','max','biaya_adm','bunga','provinsi','tenor','fixed','tampil','transaksi_toko');

$crud->required_fields('jns_pinjaman');


$crud->unset_read();
//$crud->unset_add();
$crud->unset_delete();
$output = $crud->render();

$out['output'] = $this->data['judul_browser'];
$this->load->section('judul_browser', 'default_v', $out);
$out['output'] = $this->data['judul_utama'];
$this->load->section('judul_utama', 'default_v', $out);
$out['output'] = $this->data['judul_sub'];
$this->load->section('judul_sub', 'default_v', $out);
$out['output'] = $this->data['u_name'];
$this->load->section('u_name', 'default_v', $out);
$this->load->view('default_v', $output);

	}
}
