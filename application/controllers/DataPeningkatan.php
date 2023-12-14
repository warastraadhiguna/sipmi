<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataPeningkatan extends UserController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataPeningkatan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->uploadingDirectory =$this->uploadingDirectory;
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->DataPeningkatan_model->getIdTahunPelaksanaan();
        $idProdi = $this->DataPeningkatan_model->getRealIdProdi();
        $idFakultas = $this->DataPeningkatan_model->getRealIdFakultas();
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $dataTambahan = array(
            'idFakultas' =>set_value('idFakultas', $idFakultas),
            'idProdi' =>set_value('idProdi', $idProdi),
            'idTahunPelaksanaan' =>set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('DataPeningkatan/datapeningkatan_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->DataPeningkatan_model->json();
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->DataPeningkatan_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }
}