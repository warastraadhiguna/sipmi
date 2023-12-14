<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Panduan extends UserController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Panduan_model');
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

        $idTahunPelaksanaan = $this->Panduan_model->getIdTahunPelaksanaan();
        $row = $this->Panduan_model->get_system_info($idTahunPelaksanaan);
        $info = $row->panduanPengisian;

        //  $info = str_replace("\n", '</p><p>', $info);
        $dataTambahan = array(
            'button' => 'Update',
            'back'   => site_url('Panduan'),
            'action' => site_url('Panduan/update_text_action'),
            'panduanPengisian' => set_value('panduanPengisian', $info),
            //'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan)
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('Panduan/panduan_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        $idTahunPelaksanaan = $this->Panduan_model->getIdTahunPelaksanaan();
        header('Content-Type: application/json');
        echo $this->Panduan_model->json($idTahunPelaksanaan);
    }
  
    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->Panduan_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }
}