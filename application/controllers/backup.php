<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup extends MY_Controller {

	// backup files in directory
	function files()
	{
		$opt = array(
			'src' => '../www', // dir name to backup
			'dst' => 'backup/files' // dir name backup output destination
		);
     
		// // Codeigniter v3x
		// $this->load->library('recurseZip_lib', $opt);
		// $download = $this->recursezip_lib->compress();
     
		// Codeigniter v2x
		$zip    = $this->load->library('recurseZip_lib', $opt);     
		$download = $zip->compress();
		
     
		redirect(base_url($download));
	}
   
	// backup database.sql
	public function db()
	{
		$this->load->model('Backup_m','backup', TRUE);	
		$this->backup->truncate_session();
		$this->load->dbutil();
   
		$prefs = array(
			'format' => 'zip',
			'filename' => 'backup.sql'
		);
   
		$backup =& $this->dbutil->backup($prefs);
   
		$db_name = 'backup_' . date("Y-m-d") . '.zip'; // file name
		$save  = 'backup/db/' . $db_name; // dir name backup output destination
   
		$this->load->helper('file');
		write_file($save, $backup);
   
		$this->load->helper('download');
		force_download($db_name, $backup);
	}

}
