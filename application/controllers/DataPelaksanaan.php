<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataPelaksanaan extends UserController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataPelaksanaan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->DataPelaksanaan_model->getIdTahunPelaksanaan();
        $idFakultas = $this->DataPelaksanaan_model->getRealIdFakultas();
        $idProdi = $this->DataPelaksanaan_model->getRealIdProdi();

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $dataTambahan = array(
            'idFakultas'       => $idFakultas,
            'idProdi'       => $idProdi,
            'idTahunPelaksanaan' => $idTahunPelaksanaan
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('DataPelaksanaan/datapelaksanaan_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->DataPelaksanaan_model->json();
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->DataPelaksanaan_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }
}