<?php
class ProdiController extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$level    = $this->session->userdata('level');
		
		if($level != 'prodi'){
			redirect(base_url());
			return;
		}		
	}
}
?>