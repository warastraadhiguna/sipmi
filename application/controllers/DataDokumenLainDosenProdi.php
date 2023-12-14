<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataDokumenLainDosenProdi extends ProdiController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataDokumenLainDosenProdi_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idJenisDokumenLain = $this->input->get('idJenisDokumenLain');
        $idDosen = $this->input->get('idDosen');
        $idProdi = $this->session->userdata['idProdi'];

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $dataTambahan = array(
            'idJenisDokumenLain' =>$idJenisDokumenLain ? $idJenisDokumenLain : 1,
            'idDosen' =>set_value('idDosen', $idDosen),
            'idProdi' =>set_value('idProdi', $idProdi),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('DataDokumenLainDosenProdi/datadokumenlaindosen_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->DataDokumenLainDosenProdi_model->json();
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->DataDokumenLainDosenProdi_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen . "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }
}