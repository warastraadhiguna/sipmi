<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends UserController
{
	function __construct()
    {
        parent::__construct();
		if (!isset($this->session->userdata['ID'])) {
			redirect(site_url("Login"));
		}        

		$this->load->model('Dashboard_model');        
		$this->load->library('datatables'); 		
    }
	
    public function json() 
    {
        header('Content-Type: application/json');
        echo $this->Dashboard_model->json();
    }

    public function index()
    {
		$data = array(	
			'wa'       => $this->session->userdata['nama'],
			'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
			'username' => $this->session->userdata['username'],
			'level'    => $this->session->userdata['level'],
		);
		
		$tahun = $this->Dashboard_model->getDefaultTahunPelaksanaan()->tahun;
		$dataDashboard = array(	
			'tahun'       => $tahun,
		);

		$this->load->view('header_list',$data); 
		$this->load->view('Dashboard/dashboard', $dataDashboard );  		
		$this->load->view('footer_list');     
    }
}
?>