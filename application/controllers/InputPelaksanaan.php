<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class InputPelaksanaan extends ProdiController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('InputPelaksanaan_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->subDirectory = $this->InputPelaksanaan_model->getDefaultTahunPelaksanaan()->tahun . '/pelaksanaan/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->InputPelaksanaan_model->getIdTahunPelaksanaan();
        $result = $this->InputPelaksanaan_model->generateData($idTahunPelaksanaan);
        if (!$result) {
            flashMessage('success', 'Data berhasil direload.');
        } else {
            flashMessage('error', $result);
        }

        $prodi = $this->InputPelaksanaan_model->get_by_id_table($this->session->userdata['idProdi'], "tprodi_unit")->nama;
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $dataTambahan = array(
            'prodi' => set_value('prodi', $prodi),
            'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('InputPelaksanaan/inputpelaksanaan_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json($idTahunPelaksanaan)
    {
        header('Content-Type: application/json');
        echo $this->InputPelaksanaan_model->json($idTahunPelaksanaan);
    }
    
    public function upload()
    {
        $idDetailPelaksanaan = $this->input->post('idDetailPelaksanaan', true);
        $rowDetailPelaksanaan = $this->InputPelaksanaan_model->get_by_id($idDetailPelaksanaan);
        $rowPelaksanaan = $this->InputPelaksanaan_model->get_by_id_table($rowDetailPelaksanaan->idPelaksanaan, "tpelaksanaan");
        $rowPelaksanaan = $this->InputPelaksanaan_model->get_by_id_table($rowPelaksanaan->idKebijakan, "tkebijakan");

        $idTahunPelaksanaan = $rowPelaksanaan->idTahunPelaksanaan;
        ;
        $this->subDirectory = $this->InputPelaksanaan_model->get_by_id_table($idTahunPelaksanaan, "ttahunpelaksanaan")->tahun . '/pelaksanaan/';

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $fileName = str_replace(" ", "_", $rowPelaksanaan->nama) . '_' . $idDetailPelaksanaan;
        $longFileName = $this->subDirectory. $fileName . '.' .  $ext;
        $longFilePath = $this->uploadingDirectory . $longFileName;
        $longFilePathWithoutFile = $this->uploadingDirectory . $this->subDirectory;
        
        if (file_exists($longFilePath)) {
            unlink($longFilePath);
        }

        $config['upload_path'] = $longFilePathWithoutFile ;
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['file_name']     =   $fileName ;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('dokumen')) {
            flashMessage('error', 'Dokumen tidak dapat disimpan!');
            redirect(site_url('InputPelaksanaan') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->InputPelaksanaan_model->update($idDetailPelaksanaan, $data);
            $this->InputPelaksanaan_model->addEvent(35, $this->idUser, $this->serialize_data($data));
            
            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('InputPelaksanaan'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->InputPelaksanaan_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->InputPelaksanaan_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->InputPelaksanaan_model->update($ID, $data)) {
                $namaPathSeharusnya = $this->InputPelaksanaan_model->getDefaultTahunPelaksanaan()->tahun. '/pelaksanaan/';

                if (strpos($namaDokumen, $namaPathSeharusnya) !== false) {
                    $longFilePath = $this->uploadingDirectory . $namaDokumen;
                    if (file_exists($longFilePath)) {
                        unlink($longFilePath);
                    }
                }

                $this->InputPelaksanaan_model->addEvent(36, $this->idUser, $namaDokumen);
                
                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }
}