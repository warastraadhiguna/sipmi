<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class InputDokumenLain extends ProdiController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('InputDokumenLain_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->subDirectory = $this->InputDokumenLain_model->getDefaultTahunPelaksanaan()->tahun . '/dokumen_lain/';
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
        
        $this->load->view('header_list', $data);
        $this->load->view('InputDokumenLain/inputdokumenlain_list');
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->InputDokumenLain_model->json();
    }
    
    public function upload()
    {
        $idInputDokumenLain = $this->input->post('idInputDokumenLain', true);
        $row = $this->InputDokumenLain_model->get_by_id($idInputDokumenLain);
        $kode = str_replace(".", "_", $row->kode);

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $fileName =  'InputDokumenLain_' . $kode . '_' . $idInputDokumenLain;
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
            redirect(site_url('InputDokumenLain'));
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->InputDokumenLain_model->update($idInputDokumenLain, $data);
            $this->InputDokumenLain_model->addEvent(49, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('InputDokumenLain'));
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->InputDokumenLain_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->InputDokumenLain_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->InputDokumenLain_model->update($ID, $data)) {
                $longFilePath = $this->uploadingDirectory . $namaDokumen;
                if (file_exists($longFilePath)) {
                    unlink($longFilePath);
                }

                $this->InputDokumenLain_model->addEvent(50, $this->idUser, $namaDokumen);

                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }

    public function create()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $dataAdm = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $data = array(
            'button' => 'Create',
            'back'   => site_url('InputDokumenLain'),
            'action' => site_url('InputDokumenLain/create_action'),
            'ID' => set_value('ID'),
            'kode' => set_value('kode'),
            'nama' => set_value('nama')
        );

        $this->load->view('header', $dataAdm);
        $this->load->view('InputDokumenLain/inputdokumenlain_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();

        if ($this->form_validation->run() == false) {
            $this->create();
        } else {
            $data = array(
                'kode' => $this->input->post('kode', true),
                'nama' => $this->input->post('nama', true),
                'idUser' => $this->session->userdata['ID'],
                'idProdi' => $this->session->userdata['idProdi'],
                'idTahunPelaksanaan' => $this->InputDokumenLain_model->getDefaultTahunPelaksanaan()->ID,
            );

            if (!$this->InputDokumenLain_model->insert($data)) {
                flashMessage('error', 'Data gagal disimpan.');
            } else {
                $this->InputDokumenLain_model->addEvent(46, $this->idUser, $this->serialize_data($data));

                flashMessage('success', 'Data disimpan.');
            }
                        
            redirect(site_url('InputDokumenLain'));
        }
    }
    
    // Fungsi menampilkan form Update InputDokumenLain
    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'SI-IMUT',
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->InputDokumenLain_model->get_by_id($id);
    
        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('InputDokumenLain'),
                'action' => site_url('InputDokumenLain/update_action'),
                'ID' => set_value('ID', $row->ID),
                'kode' => set_value('kode', $row->kode),
                'nama' => set_value('nama', $row->nama),
                );
            
            $this->load->view('header', $dataAdm);
            $this->load->view('InputDokumenLain/inputdokumenlain_form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('InputDokumenLain'));
        }
    }
    
    public function update_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();
        
        if ($this->form_validation->run() == false) {
            $this->update($this->input->post('ID', true));
        } else {
            $data = array(
                'ID' => $this->input->post('ID', true),
                'kode' => $this->input->post('kode', true),
                'nama' => $this->input->post('nama', true),
                'idUser' => $this->session->userdata['ID'],
                'idProdi' => $this->session->userdata['idProdi'],
                'idTahunPelaksanaan' => $this->InputDokumenLain_model->getDefaultTahunPelaksanaan()->ID,
            );
            $row = $this->InputDokumenLain_model->get_by_id($this->input->post('ID', true));

            $this->InputDokumenLain_model->update($this->input->post('ID', true), $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->InputDokumenLain_model->addEvent(47, $this->idUser, $keterangan);
            flashMessage('success', 'Data diupdate.');
            redirect(site_url('InputDokumenLain'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->InputDokumenLain_model->get_by_id($id);

        if ($row) {
            if (!$row->dokumen) {
                $this->InputDokumenLain_model->delete($id);
                $this->InputDokumenLain_model->addEvent(48, $this->idUser, $this->serialize_data($row));

                flashMessage('success', 'Data dihapus.');
            } else {
                flashMessage('error', 'Hapus dokumen terlebih dahulu!!!');
            }
            redirect(site_url('InputDokumenLain'));
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('InputDokumenLain'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('ID', 'ID', 'trim');
        $this->form_validation->set_rules('kode', 'kode', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}