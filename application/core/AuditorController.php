<?php
class AuditorController extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$level    = $this->session->userdata('level');
		
		if($level != 'auditor'){
			redirect(base_url());
			return;
		}		
	}
}
?>