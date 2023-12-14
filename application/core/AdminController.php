<?php
class AdminController extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$level    = $this->session->userdata('level');
		
		if($level != 'admin'){
			redirect(base_url());
			return;
		}
	}
}
?>