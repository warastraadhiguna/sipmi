<?php
class AuditorPimpinanController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $level    = $this->session->userdata('level');
        // localhost/si_imut/index.php/DataDokumenLainDosen
    
        if ($level != 'auditor' && $level != 'pimpinan') {
            redirect(base_url());
            return;
        }
    }
}