<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Evaluasi extends UserController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Evaluasi_model');
        $this->load->library('datatables');
        $this->load->helper('download');

        $this->subDirectory = $this->Evaluasi_model->getDefaultTahunPelaksanaan()->tahun . '/evaluasi/';
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

        $idTahunPelaksanaan = $this->Evaluasi_model->getIdTahunPelaksanaan();
        $idFakultas = $this->Evaluasi_model->getRealIdFakultas();
        $idProdi = $this->Evaluasi_model->getRealIdProdi();

        $listProdi = $this->Evaluasi_model->get_all_table("tprodi_unit");
        $dataTambahan = array(
            'idFakultas'       => $idFakultas,
            'idProdi'       => $idProdi,
            'idTahunPelaksanaan' => $idTahunPelaksanaan
        );
    
        $this->load->view('header_list', $data);
        $this->load->view('Evaluasi/evaluasi_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Evaluasi_model->json();
    }
}