<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class EvaluasiProdi extends ProdiController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('EvaluasiProdi_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
        $this->load->helper('download');

        $this->subDirectory = $this->EvaluasiProdi_model->getDefaultTahunPelaksanaan()->tahun . '/evaluasi/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->EvaluasiProdi_model->getIdTahunPelaksanaan();
        $result = $this->EvaluasiProdi_model->generateEvaluasi($idTahunPelaksanaan);
        if (!$result) {
            flashMessage('success', 'Data berhasil direload.');
        } else {
            flashMessage('error', $result);
        }

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->EvaluasiProdi_model->getSingleEvaluasiData($idTahunPelaksanaan);
        $dataTambahan = array(
            'fileEvaluasi' => set_value('fileEvaluasi', $row->dokumen),
            'idEvaluasi' => set_value('idEvaluasi', $row->ID),
            'isSubmitted'=> set_value('isSubmitted', $row->isSubmitted),
            'idTahunPelaksanaan' =>set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('EvaluasiProdi/evaluasiprodi_list', $dataTambahan);
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->EvaluasiProdi_model->json();
    }
    
    public function downloadEmptyFile()
    {
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan', true);
        $jenisMaster = $this->input->post('jenisMaster', true);

        $row = $this->EvaluasiProdi_model->get_system_info($idTahunPelaksanaan);
        $jenisMaster = str_replace(".html", "", $jenisMaster);
        $dokumen = $jenisMaster == "S1" ? $row->dokumenEvaluasi
        : ($jenisMaster == "S2"? $row->dokumenEvaluasiS2 :
        (
            $jenisMaster == "S3"? $row->dokumenEvaluasiS3 :
            (
                $jenisMaster == "D3"?  $row->dokumenEvaluasiD3 :
                $row->dokumenEvaluasiD4
            )
        ));
        
        if ($dokumen) {
            $path = base_url() . $this->uploadingDirectory . $dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function unggahdatalama($id)
    {
        $row = $this->EvaluasiProdi_model->GetDataLama($id);

        if ($row) {
            $data = array(
                'dokumen' => $row->dokumen,
            );
            
            $this->EvaluasiProdi_model->update($id, $data);
            $this->EvaluasiProdi_model->addEvent(38, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
        } else {
            flashMessage('error', 'Dokumen tidak ditemukan.');
        }

        redirect(site_url('EvaluasiProdi'));
    }

    public function upload()
    {
        $idEvaluasi = $this->input->post('idEvaluasi', true);
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
            redirect(site_url('EvaluasiProdi'));
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->EvaluasiProdi_model->update($idEvaluasi, $data);
            $this->EvaluasiProdi_model->addEvent(37, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('EvaluasiProdi'));
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->EvaluasiProdi_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->EvaluasiProdi_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->EvaluasiProdi_model->update($ID, $data)) {
                $longFilePath = $this->uploadingDirectory . $namaDokumen;
                if (file_exists($longFilePath)) {
                    unlink($longFilePath);
                }

                $this->EvaluasiProdi_model->addEvent(39, $this->idUser, $namaDokumen);

                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }

    public function submitData()
    {
        $ID = $this->input->post('ID', true);
        $data = array(
            'isSubmitted' => 1,
        );
        
        if ($this->EvaluasiProdi_model->update($ID, $data)) {
            $this->EvaluasiProdi_model->addEvent(40, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen berhasil disubmit.');
        } else {
            echo json_encode("Dokumen gagal disubmint!!");
        }
    }

    /* public function prosesDokumen()
    {
    $ID = $this->input->post('ID', true);
    $row = $this->EvaluasiProdi_model->get_by_id($ID);
    $namaDokumen = $row->dokumen;

    if (!$namaDokumen) {
        echo json_encode("Dokumen tidak ada!!");
    } else {
        $path = $this->uploadingDirectory . $namaDokumen;
        $idTahunPelaksanaan = $this->EvaluasiProdi_model->getIdTahunPelaksanaan();
        $result = $this->EvaluasiProdi_model->evaluasi($path, $ID, $idTahunPelaksanaan);
        if ($result) {
            echo json_encode($result);
        }
    }
    }*/
}