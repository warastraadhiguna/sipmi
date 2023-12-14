<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EvaluasiAuditor extends AuditorController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;
        
    public function __construct()
    {
        parent::__construct();
        $this->load->model('EvaluasiAuditor_model');
        $this->load->library('datatables');
        $this->load->helper('download');

        $this->subDirectory = $this->EvaluasiAuditor_model->getDefaultTahunPelaksanaan()->tahun . '/evaluasi/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->EvaluasiAuditor_model->getIdTahunPelaksanaan();
        $idProdi = $this->EvaluasiAuditor_model->getRealIdProdi();
        $idFakultas = $this->EvaluasiAuditor_model->getRealIdFakultas();

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $idEvaluasi = '';
        $fileEvaluasi = '';
        $row = $this->EvaluasiAuditor_model->getSingleEvaluasiData($idProdi, $idTahunPelaksanaan);
        if ($row) {
            $idEvaluasi = $row->ID;
            $fileEvaluasi = $row->dokumenAuditor;
        }

        $dataTambahan = array(
            'fileEvaluasi' => set_value('fileEvaluasi', $fileEvaluasi),
            'idEvaluasi' => set_value('idEvaluasi', $idEvaluasi),
            'idProdi' => 	set_value('idProdi', $idProdi),
            'idTahunPelaksanaan' => 	set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
            'idFakultas'       => $idFakultas,
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('EvaluasiAuditor/evaluasiauditor_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->EvaluasiAuditor_model->json();
    }
    
    public function downloadEmptyFile()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->EvaluasiAuditor_model->get_by_id($ID);

        if ($row->dokumen) {
            $this->EvaluasiAuditor_model->addEvent(51, $this->idUser, $this->serialize_data($row));

            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function upload()
    {
        $idEvaluasi = $this->input->post('idEvaluasi', true);
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaanUpload', true);
        $idFakultas = $this->input->post('idFakultasUpload', true);
        $idProdi = $this->input->post('idProdiUpload', true);

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $fileName =  'EvaluasiProdi_' . $idEvaluasi;
        $longFileName = $this->subDirectory. $fileName . '.' .  $ext;
        $longFilePath = $this->uploadingDirectory . $longFileName;
        $longFilePathWithoutFile = $this->uploadingDirectory . $this->subDirectory;
        
        if (file_exists($longFilePath)) {
            unlink($longFilePath);
        }

        $config['upload_path'] = $longFilePathWithoutFile ;
        $config['allowed_types'] = 'xlsx|xls';
        $config['file_name']     =   $fileName ;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('dokumen')) {
            flashMessage('error', 'Dokumen tidak dapat disimpan!');
            redirect(site_url('EvaluasiAuditor'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        } else {
            $data = array(
                'dokumenAuditor' => $longFileName,
            );
            
            $this->EvaluasiAuditor_model->update($idEvaluasi, $data);
            $this->EvaluasiAuditor_model->addEvent(52, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('EvaluasiAuditor'). '?idFakultas=' . $idFakultas . '&idProdi=' . $idProdi . '&idTahunPelaksanaan=' . $idTahunPelaksanaan);
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->EvaluasiAuditor_model->get_by_id($ID);

        if ($row->dokumenAuditor) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumenAuditor. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->EvaluasiAuditor_model->get_by_id($ID);
        $namaDokumen = $row->dokumenAuditor;
        
        $data = array(
            'dokumenAuditor' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->EvaluasiAuditor_model->update($ID, $data)) {
                flashMessage('success', 'Dokumen berhasil dihapus.');
                $this->EvaluasiAuditor_model->addEvent(53, $this->idUser, $namaDokumen);
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }

    public function prosesDokumen()
    {
        $ID = $this->input->post('ID', true);

        $idTahunPelaksanaan = $this->EvaluasiAuditor_model->getIdTahunPelaksanaan();
        $row = $this->EvaluasiAuditor_model->get_by_id($ID);
        $namaDokumen = $row->dokumenAuditor;
            
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            $this->EvaluasiAuditor_model->addEvent(54, $this->idUser, $this->serialize_data($row));

            $path = $this->uploadingDirectory . $namaDokumen;
            $result = $this->EvaluasiAuditor_model->evaluasi($path, $ID, $idTahunPelaksanaan);
            if ($result) {
                echo json_encode($result);
            }
        }
    }
}