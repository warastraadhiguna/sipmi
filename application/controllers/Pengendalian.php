<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pengendalian extends UserController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pengendalian_model');
        $this->load->library('datatables');
        $this->load->helper('download');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->Pengendalian_model->getIdTahunPelaksanaan();
        $idProdi = $this->Pengendalian_model->getRealIdProdi();
        $idFakultas = $this->Pengendalian_model->getRealIdFakultas();
        $asesmen_kecukupan = !$idProdi ? null :  $this->Pengendalian_model->get_asesmen_kecukupan($idTahunPelaksanaan, $idProdi);

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
            'asesmen_kecukupan' =>$asesmen_kecukupan
        );

        $dataTambahan = array(
            'idFakultas' =>set_value('idFakultas', $idFakultas),
            'idProdi' =>set_value('idProdi', $idProdi),
            'idTahunPelaksanaan' =>set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );
    
        $this->load->view('header_list', $data);
        $this->load->view('Pengendalian/pengendalian_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Pengendalian_model->json();
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->Pengendalian_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }
}