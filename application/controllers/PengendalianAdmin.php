<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PengendalianAdmin extends AdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PengendalianAdmin_model');
        $this->load->library('datatables');
        $this->load->helper('download');

        $this->subDirectory = $this->PengendalianAdmin_model->getDefaultTahunPelaksanaan()->tahun . '/pengendalian/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->PengendalianAdmin_model->getIdTahunPelaksanaan();
        $idProdi = $this->PengendalianAdmin_model->getRealIdProdi();
        $idFakultas = $this->PengendalianAdmin_model->getRealIdFakultas();
        $asesmen_kecukupan = !$idProdi ? null :  $this->PengendalianAdmin_model->get_asesmen_kecukupan($idTahunPelaksanaan, $idProdi);

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
            'asesmen_kecukupan' => $asesmen_kecukupan
        );

        $dataTambahan = array(
            'idFakultas' =>set_value('idFakultas', $idFakultas),
            'idProdi' =>set_value('idProdi', $idProdi),
            'idTahunPelaksanaan' =>set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );
    
        $this->load->view('header_list', $data);
        $this->load->view('PengendalianAdmin/pengendalianadmin_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->PengendalianAdmin_model->json();
    }

    public function upload()
    {
        $idDetailEvaluasi = $this->input->post('idDetailEvaluasi', true);
        $rowDetailEvaluasi = $this->PengendalianAdmin_model->get_by_id($idDetailEvaluasi);
        $rowEvaluasi = $this->PengendalianAdmin_model->get_by_id_table($rowDetailEvaluasi->idEvaluasi, "tevaluasi");
        $rowTahunPelaksanaan = $this->PengendalianAdmin_model->get_by_id_table($rowEvaluasi->idTahunPelaksanaan, "ttahunpelaksanaan");
        $this->subDirectory = $rowTahunPelaksanaan->tahun . '/pengendalian/';
        $rowProdi = $this->PengendalianAdmin_model->get_by_id_table($rowEvaluasi->idProdi, "tprodi_unit");

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
            redirect(site_url('PengendalianAdmin') . '?idFakultas='. $rowProdi->idFakultas  .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='. $rowTahunPelaksanaan->ID);
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->PengendalianAdmin_model->update($idDetailEvaluasi, $data);
            $this->PengendalianAdmin_model->addEvent(55, $this->idUser, $this->serialize_data($data));
            
            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('PengendalianAdmin') . '?idFakultas='. $rowProdi->idFakultas  .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='. $rowTahunPelaksanaan->ID);
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PengendalianAdmin_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PengendalianAdmin_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->PengendalianAdmin_model->update($ID, $data)) {
                $namaPathSeharusnya = $this->PengendalianAdmin_model->getDefaultTahunPelaksanaan()->tahun. '/pengendalian/';

                if (strpos($namaDokumen, $namaPathSeharusnya) !== false) {
                    $longFilePath = $this->uploadingDirectory . $namaDokumen;
                    if (file_exists($longFilePath)) {
                        unlink($longFilePath);
                    }
                }

                $this->PengendalianAdmin_model->addEvent(56, $this->idUser, $namaDokumen);
                
                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }
}