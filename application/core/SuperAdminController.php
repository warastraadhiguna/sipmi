<?php
class SuperAdminController extends MY_Controller {
	public function __construct(){
		parent::__construct();
		$level    = $this->session->userdata('level');
		
		if($level != 'superadmin'){
			redirect(base_url());
			return;
		}
	}
}
?>