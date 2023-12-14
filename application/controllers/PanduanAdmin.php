<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PanduanAdmin extends AdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PanduanAdmin_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->subDirectory = $this->PanduanAdmin_model->getDefaultTahunPelaksanaan()->tahun . '/panduan/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
        
        $idTahunPelaksanaan = $this->PanduanAdmin_model->getIdTahunPelaksanaan();
        $this->PanduanAdmin_model->generateData($idTahunPelaksanaan);
        
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->PanduanAdmin_model->get_system_info($idTahunPelaksanaan);
        $dataTambahan = array(
            'button' => 'Update',
            'back'   => site_url('PanduanAdmin'),
            'action' => site_url('PanduanAdmin/update_text_action/' . $idTahunPelaksanaan),
            'idInfo'=> set_value('idInfo', $row->ID),
            'panduanPengisian' => set_value('panduanPengisian', $row->panduanPengisian),
            'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('PanduanAdmin/panduanadmin_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function update_text_action($idTahunPelaksanaan)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $data = array(
            'panduanPengisian' => $this->input->post('panduanPengisian', true),
        );

        $idInfo = $this->input->post('idInfo', true);
        if ($this->PanduanAdmin_model->update_by_table("tinfo", $idInfo, $data)) {
            flashMessage('success', 'Update Record Success.');
        } else {
            flashMessage('error', 'Update Record gagal!!!');
        }

        redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
    }

    public function json($idInfo)
    {
        header('Content-Type: application/json');
        echo $this->PanduanAdmin_model->json($idInfo);
    }
    
    public function upload()
    {
        $idpanduanadmin = $this->input->post('idpanduanadmin', true);
        $row = $this->PanduanAdmin_model->get_by_id($idpanduanadmin);
        $idTahunPelaksanaan = $this->PanduanAdmin_model->get_by_id_table($row->idInfo, "tinfo")->idTahunPelaksanaan;
        $this->subDirectory = $this->PanduanAdmin_model->get_by_id_table($idTahunPelaksanaan, "ttahunpelaksanaan")->tahun . '/panduan/';

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        //$name =pathinfo($_FILES['dokumen']['name'], PATHINFO_FILENAME);
        $fileName =  str_replace(" ", "_", $row->keterangan)  . '_' . $idpanduanadmin;
        $longFileName = $this->subDirectory. $fileName . '.' .  $ext;
        $longFilePath = $this->uploadingDirectory . $longFileName;
        $longFilePathWithoutFile = $this->uploadingDirectory . $this->subDirectory;

        if (file_exists($longFilePath)) {
            unlink($longFilePath);
        }

        $config['upload_path'] = $longFilePathWithoutFile;
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['file_name']     =   $fileName ;
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('dokumen')) {
            flashMessage('error', 'Dokumen tidak dapat disimpan!');
            redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->PanduanAdmin_model->update($idpanduanadmin, $data);
            $this->PanduanAdmin_model->addEvent(19, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PanduanAdmin_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PanduanAdmin_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->PanduanAdmin_model->update($ID, $data)) {
                $idTahunPelaksanaan =  $this->PanduanAdmin_model->get_by_id_table($row->idInfo, "tinfo")->idTahunPelaksanaan ;

                $namaPathSeharusnya = $this->PanduanAdmin_model->get_by_id_table($idTahunPelaksanaan, "ttahunpelaksanaan")->tahun. '/panduan/';

                if (strpos($namaDokumen, $namaPathSeharusnya) !== false) {
                    $longFilePath = $this->uploadingDirectory . $namaDokumen;
                    if (file_exists($longFilePath)) {
                        unlink($longFilePath);
                    }
                }

                $this->PanduanAdmin_model->addEvent(20, $this->idUser, $namaDokumen);

                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }

    public function create($idInfo)
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
            'back'   => site_url('PanduanAdmin'),
            'action' => site_url('PanduanAdmin/create_action'),
            'idInfo' => set_value('idInfo', $idInfo),
            'ID' => set_value('ID'),
            'keterangan' => set_value('keterangan'),
        );

        $this->load->view('header', $dataAdm);
        $this->load->view('PanduanAdmin/panduanadmin_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
        $id = $this->input->post('ID', true);
        $this->_rules();
        if ($this->form_validation->run() == false) {
            $this->create($id);
        } else {
            $idInfo = $this->input->post('idInfo', true);
            $idTahunPelaksanaan = $this->PanduanAdmin_model->get_by_id_table($idInfo, "tinfo")->idTahunPelaksanaan;
            $data = array(
            'ID' => $id,
            'keterangan' => $this->input->post('keterangan', true),
            'idInfo' => $idInfo ,
            );

            $this->PanduanAdmin_model->insert($data);
            $this->PanduanAdmin_model->addEvent(16, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Data disimpan.');
            redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }
    
    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'LPMAI',
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->PanduanAdmin_model->get_by_id($id);
    
        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('PanduanAdmin'),
                'action' => site_url('PanduanAdmin/update_action'),
                'ID' => set_value('ID', $row->ID),
                'keterangan' => set_value('keterangan', $row->keterangan),
                'idInfo' => set_value('keterangan', $row->idInfo),
                );
            
            $this->load->view('header', $dataAdm);
            $this->load->view('PanduanAdmin/panduanadmin_form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('PanduanAdmin'));
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
            $idInfo = $this->input->post('idInfo', true);
            $idTahunPelaksanaan = $this->PanduanAdmin_model->get_by_id_table($idInfo, "tinfo")->idTahunPelaksanaan;

            $data = array(
                'ID' => $this->input->post('ID', true),
                'keterangan' => $this->input->post('keterangan', true),
            );
            $id = $this->input->post('ID', true);
            $row = $this->PanduanAdmin_model->get_by_id($id);

            $this->PanduanAdmin_model->update($this->input->post('ID', true), $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->PanduanAdmin_model->addEvent(17, $this->idUser, $keterangan);

            flashMessage('success', 'Data diupdate.');
            redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->PanduanAdmin_model->get_by_id($id);
        $idTahunPelaksanaan = $this->PanduanAdmin_model->get_by_id_table($row->idInfo, "tinfo")->idTahunPelaksanaan;

        if ($row) {
            if ($row->dokumen) {
                flashMessage('error', 'Hapus dokumen terlebih dahulu!!');
            } else {
                $this->PanduanAdmin_model->delete($id);
                $this->PanduanAdmin_model->addEvent(18, $this->idUser, $this->serialize_data($row));

                flashMessage('success', 'Data dihapus.');
            }
            redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('PanduanAdmin') . "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('ID', 'ID', 'trim');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}