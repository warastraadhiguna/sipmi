<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class History extends UserController
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->userdata['ID'])) {
            redirect(site_url("Login"));
        }

        $this->load->model('History_model');
        $this->load->library('datatables');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->History_model->json();
    }

    public function index()
    {
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );
        
        $tahun = $this->History_model->getDefaultTahunPelaksanaan()->tahun;
        $dataHistory = array(
            'tahun'       => $tahun,
            'tanggalAwal' => $this->History_model->getTanggalAwal(),
            'tanggalAkhir' => $this->History_model->getTanggalAkhir() ,

        );

        $this->load->view('header_list', $data);
        $this->load->view('History/history', $dataHistory);
        $this->load->view('footer_list');
    }
}