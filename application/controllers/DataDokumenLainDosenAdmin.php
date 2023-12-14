<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class DataDokumenLainDosenAdmin extends AdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DataDokumenLainDosenAdmin_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->subDirectory = $this->DataDokumenLainDosenAdmin_model->getDefaultTahunPelaksanaan()->tahun . '/dokumen_lain/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
        // print_r($this->DataDokumenLainDosenAdmin_model->json());
        // return;
        $idTahunPelaksanaan = $this->DataDokumenLainDosenAdmin_model->getIdTahunPelaksanaan();
        $idProdi = $this->DataDokumenLainDosenAdmin_model->getRealIdProdi();
        $idFakultas = $this->DataDokumenLainDosenAdmin_model->getRealIdFakultas();
        $idJenisDokumenLain = $this->input->get('idJenisDokumenLain');
        $idDosen = $this->input->get('idDosen');
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
            'idJenisDokumenLain' =>$idJenisDokumenLain ? $idJenisDokumenLain : 1,
            'idDosen' =>set_value('idDosen', $idDosen),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('DataDokumenLainDosenAdmin/datadokumenlaindosen_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->DataDokumenLainDosenAdmin_model->json();
    }

    public function upload()
    {
        $idInputDokumenLainDosen = $this->input->post('idInputDokumenLainDosen', true);
        $idJenisDokumenLain = $this->input->post('idJenisDokumenLain', true);
        
        $row = $this->DataDokumenLainDosenAdmin_model->get_by_id($idInputDokumenLainDosen);
        $rowTahunPelaksanaan = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idTahunPelaksanaan, "ttahunpelaksanaan");
        $rowUser = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idUserDosen, "tuser");
        $rowProdi= $this->DataDokumenLainDosenAdmin_model->get_by_id_table($rowUser->idProdi, "tprodi_unit");

        $this->subDirectory = $rowTahunPelaksanaan->tahun . '/dokumen_lain/';

        $kode = str_replace(".", "_", $row->kode);

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $fileName =  'DokumenLainDosen_' . $kode. '_' . $idJenisDokumenLain  . '_' . $idInputDokumenLainDosen;
        
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
            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$rowTahunPelaksanaan->ID.'&idDosen='. $row->idUserDosen .'&idJenisDokumenLain=' . $row->idJenisDokumenLain));
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->DataDokumenLainDosenAdmin_model->update($idInputDokumenLainDosen, $data);
            $this->DataDokumenLainDosenAdmin_model->addEvent(64, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$rowTahunPelaksanaan->ID.'&idDosen='. $row->idUserDosen .'&idJenisDokumenLain=' . $row->idJenisDokumenLain));
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->DataDokumenLainDosenAdmin_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen . "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->DataDokumenLainDosenAdmin_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;

        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->DataDokumenLainDosenAdmin_model->update($ID, $data)) {
                $longFilePath = $this->uploadingDirectory . $namaDokumen;
                if (file_exists($longFilePath)) {
                    unlink($longFilePath);
                }

                $this->DataDokumenLainDosenAdmin_model->addEvent(65, $this->idUser, $namaDokumen);

                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }

    public function create($idJenisDokumenLain, $idTahunPelaksanaan, $idUserDosen)
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
            'back'   => site_url('DataDokumenLainDosenAdmin'),
            'action' => site_url('DataDokumenLainDosenAdmin/create_action'),
            'ID' => set_value('ID'),
            'kode' => set_value('kode'),
            'nama' => set_value('nama'),
            'idJenisDokumenLain' => $idJenisDokumenLain,
            'idTahunPelaksanaan' => $idTahunPelaksanaan,
            'idUserDosen' => $idUserDosen,
        );

        $this->load->view('header', $dataAdm);
        $this->load->view('DataDokumenLainDosenAdmin/inputdokumenlaindosen_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();
        $idJenisDokumenLain = $this->input->post('idJenisDokumenLain', true) ;
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan', true) ;
        $idUserDosen = $this->input->post('idUserDosen', true);

        if ($this->form_validation->run() == false) {
            $this->create($idJenisDokumenLain, $idTahunPelaksanaan, $idUserDosen);
        } else {
            $data = array(
                'kode' => $this->input->post('kode', true),
                'nama' => $this->input->post('nama', true),
                'idUser' => $this->session->userdata['ID'],
                'idUserDosen' => $idUserDosen,
                'idJenisDokumenLain' => $idJenisDokumenLain ,
                'idTahunPelaksanaan' => $idTahunPelaksanaan,
            );

            if (!$this->DataDokumenLainDosenAdmin_model->insert($data)) {
                flashMessage('error', 'Data gagal disimpan.');
            } else {
                $this->DataDokumenLainDosenAdmin_model->addEvent(61, $this->idUser, $this->serialize_data($data));

                flashMessage('success', 'Data disimpan.');
            }
            
            $rowUser = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($idUserDosen, "tuser");
            $rowProdi= $this->DataDokumenLainDosenAdmin_model->get_by_id_table($rowUser->idProdi, "tprodi_unit");

            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$idTahunPelaksanaan.'&idDosen='.  $idUserDosen  .'&idJenisDokumenLain=' . $idJenisDokumenLain));
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

        $row = $this->DataDokumenLainDosenAdmin_model->get_by_id($id);
    
        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('DataDokumenLainDosenAdmin'),
                'action' => site_url('DataDokumenLainDosenAdmin/update_action'),
                'ID' => set_value('ID', $row->ID),
                'kode' => set_value('kode', $row->kode),
                'nama' => set_value('nama', $row->nama),
                'idJenisDokumenLain' => "",
                );
            
            $this->load->view('header', $dataAdm);
            $this->load->view('DataDokumenLainDosenAdmin/inputdokumenlaindosen_form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            $rowTahunPelaksanaan = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idTahunPelaksanaan, "ttahunpelaksanaan");
            $rowUser = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idUserDosen, "tuser");
            $rowProdi= $this->DataDokumenLainDosenAdmin_model->get_by_id_table($rowUser->idProdi, "tprodi_unit");
            
            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$rowTahunPelaksanaan->ID.'&idDosen='. $row->idUserDosen .'&idJenisDokumenLain=' . $row->idJenisDokumenLain));
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
            );
            $row = $this->DataDokumenLainDosenAdmin_model->get_by_id($this->input->post('ID', true));

            $this->DataDokumenLainDosenAdmin_model->update($this->input->post('ID', true), $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->DataDokumenLainDosenAdmin_model->addEvent(62, $this->idUser, $keterangan);
            flashMessage('success', 'Data diupdate.');
            $rowTahunPelaksanaan = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idTahunPelaksanaan, "ttahunpelaksanaan");
            $rowUser = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idUserDosen, "tuser");
            $rowProdi= $this->DataDokumenLainDosenAdmin_model->get_by_id_table($rowUser->idProdi, "tprodi_unit");
            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$rowTahunPelaksanaan->ID.'&idDosen='. $row->idUserDosen .'&idJenisDokumenLain=' . $row->idJenisDokumenLain));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->DataDokumenLainDosenAdmin_model->get_by_id($id);
        $rowTahunPelaksanaan = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idTahunPelaksanaan, "ttahunpelaksanaan");
        $rowUser = $this->DataDokumenLainDosenAdmin_model->get_by_id_table($row->idUserDosen, "tuser");
        $rowProdi= $this->DataDokumenLainDosenAdmin_model->get_by_id_table($rowUser->idProdi, "tprodi_unit");

        if ($row) {
            if (!$row->dokumen) {
                $this->DataDokumenLainDosenAdmin_model->delete($id);
                $this->DataDokumenLainDosenAdmin_model->addEvent(63, $this->idUser, $this->serialize_data($row));

                flashMessage('success', 'Data dihapus.');
            } else {
                flashMessage('error', 'Hapus dokumen terlebih dahulu!!!');
            }
            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$rowTahunPelaksanaan->ID.'&idDosen='. $row->idUserDosen .'&idJenisDokumenLain=' . $row->idJenisDokumenLain));
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('DataDokumenLainDosenAdmin?idFakultas='. $rowProdi->idFakultas .'&idProdi='. $rowProdi->ID .'&idTahunPelaksanaan='.$rowTahunPelaksanaan->ID.'&idDosen='. $row->idUserDosen .'&idJenisDokumenLain=' . $row->idJenisDokumenLain));
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