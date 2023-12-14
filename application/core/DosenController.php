<?php
class DosenController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $level    = $this->session->userdata('level');
        
        if ($level != 'dosen') {
            redirect(base_url());
            return;
        }
    }
}