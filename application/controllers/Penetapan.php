<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Penetapan extends UserController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Penetapan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('Penetapan/penetapan_list');
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Penetapan_model->json();
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->Penetapan_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }
}