<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PengendalianProdi extends ProdiController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PengendalianProdi_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper('download');

        $this->subDirectory = $this->PengendalianProdi_model->getDefaultTahunPelaksanaan()->tahun . '/pengendalian/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idProdi = $this->session->userdata['idProdi'];
        $asesmen_kecukupan = !$idProdi ? null :  $this->PengendalianProdi_model->get_asesmen_kecukupan($idProdi);

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
            'asesmen_kecukupan' =>$asesmen_kecukupan
        );

        $prodi = $this->PengendalianProdi_model->get_by_id_table($idProdi, "tprodi_unit")->nama;
        $dataTambahan = array(
            'idFakultas' =>set_value('idFakultas', 6),
            'prodi' =>set_value('prodi', $prodi),
        );
    
        $this->load->view('header_list', $data);
        $this->load->view('PengendalianProdi/pengendalianprodi_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->PengendalianProdi_model->json();
    }

    public function upload()
    {
        $idDetailEvaluasi = $this->input->post('idDetailEvaluasi', true);
        // $rowDetailEvaluasi = $this->PengendalianProdi_model->get_by_id($idDetailEvaluasi);

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $fileName = $idDetailEvaluasi;
        $longFileName = $this->subDirectory. $fileName . '.' .  $ext;
        $longFilePath = $this->uploadingDirectory . $longFileName;
        $longFilePathWithoutFile = $this->uploadingDirectory . $this->subDirectory;
        
        if (file_exists($longFilePath)) {
            unlink($longFilePath);
        }

        $config['upload_path'] = $longFilePathWithoutFile ;
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['file_name']     =   $fileName ;
        //$config['encrypt_name']  = true;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('dokumen')) {
            flashMessage('error', 'Dokumen tidak dapat disimpan! ' .  $longFilePathWithoutFile);
            redirect(site_url('PengendalianProdi'));
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->PengendalianProdi_model->update($idDetailEvaluasi, $data);
            $this->PengendalianProdi_model->addEvent(55, $this->idUser, $this->serialize_data($data));
            
            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('PengendalianProdi'));
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PengendalianProdi_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PengendalianProdi_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->PengendalianProdi_model->update($ID, $data)) {
                $namaPathSeharusnya = $this->PengendalianProdi_model->getDefaultTahunPelaksanaan()->tahun. '/pengendalian/';

                if (strpos($namaDokumen, $namaPathSeharusnya) !== false) {
                    $longFilePath = $this->uploadingDirectory . $namaDokumen;
                    if (file_exists($longFilePath)) {
                        unlink($longFilePath);
                    }
                }

                $this->PengendalianProdi_model->addEvent(56, $this->idUser, $namaDokumen);
                
                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }
}