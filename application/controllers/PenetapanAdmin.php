<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PenetapanAdmin extends AdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PenetapanAdmin_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->subDirectory = $this->PenetapanAdmin_model->getDefaultTahunPelaksanaan()->tahun . '/penetapan/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->PenetapanAdmin_model->getIdTahunPelaksanaan();
        $asesmen_kecukupan = $this->PenetapanAdmin_model->get_laporan_asesmen_kecukupan($idTahunPelaksanaan);

        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
            'asesmen_kecukupan' => $asesmen_kecukupan,
            'button' => 'Simpan',
            'action' => site_url('PenetapanAdmin/simpan_laporan_asesmen_kecukupan/'. $asesmen_kecukupan->ID . "/" .$idTahunPelaksanaan) ,
        );
        
        $dataTambahan = array(
            'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );

        $this->load->view('header_list', $data);
        $this->load->view('PenetapanAdmin/penetapanadmin_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json($idTahunPelaksanaan)
    {
        header('Content-Type: application/json');
        echo $this->PenetapanAdmin_model->json($idTahunPelaksanaan);
    }

    public function simpan_laporan_asesmen_kecukupan($ID, $idTahunPelaksanaan)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $data = array(
                'nilai' => trim($this->input->post('nilai', true)),
                'peringkat' => trim($this->input->post('peringkat', true)),
                'syarat_perlu_terakreditasi' => trim($this->input->post('syarat_perlu_terakreditasi', true)),
                'syarat_perlu_peringkat_unggul' => trim($this->input->post('syarat_perlu_peringkat_unggul', true)),
                'syarat_perlu_peringkat_baik_sekali' => trim($this->input->post('syarat_perlu_peringkat_baik_sekali', true)),
            );

        $this->PenetapanAdmin_model->update_asesmen($ID, $data);
        $this->PenetapanAdmin_model->addEvent(21, $this->idUser, $this->serialize_data($data));

        flashMessage('success', 'Data disimpan.');
        redirect(site_url('PenetapanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
    }
    
    public function upload()
    {
        $IdKebijakan = $this->input->post('IdKebijakan', true);
        $row = $this->PenetapanAdmin_model->get_by_id($IdKebijakan);
        $kode = str_replace(".", "_", $row->kode);
        $idTahunPelaksanaan = $row->idTahunPelaksanaan;

        $ext =pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION);
        $fileName =    $kode . '_' .  str_replace(" ", "_", $row->nama) . '_' . $IdKebijakan;
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
            redirect(site_url('PenetapanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        } else {
            $data = array(
                'dokumen' => $longFileName,
            );
            
            $this->PenetapanAdmin_model->update($IdKebijakan, $data);
            $this->PenetapanAdmin_model->addEvent(22, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Dokumen disimpan.');
            redirect(site_url('PenetapanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function getUrlDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PenetapanAdmin_model->get_by_id($ID);

        if ($row->dokumen) {
            $path = base_url() . $this->uploadingDirectory . $row->dokumen. "?" . Date('YmdHis');
            echo json_encode($path);
        }
    }

    public function deleteDokumen()
    {
        $ID = $this->input->post('ID', true);
        $row = $this->PenetapanAdmin_model->get_by_id($ID);
        $namaDokumen = $row->dokumen;
        
        $data = array(
            'dokumen' => '',
        );
        
        if (!$namaDokumen) {
            echo json_encode("Dokumen tidak ada!!");
        } else {
            if ($this->PenetapanAdmin_model->update($ID, $data)) {
                $longFilePath = $this->uploadingDirectory . $namaDokumen;
                if (file_exists($longFilePath)) {
                    unlink($longFilePath);
                }

                $this->PenetapanAdmin_model->addEvent(23, $this->idUser, $namaDokumen);

                flashMessage('success', 'Dokumen berhasil dihapus.');
            } else {
                echo json_encode("Dokumen gagal dihapus!!");
            }
        }
    }

    public function create($idTahunPelaksanaan)
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
            'back'   => site_url('PenetapanAdmin'),
            'action' => site_url('PenetapanAdmin/create_action'),
            'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
            'ID' => set_value('ID'),
            'kode' => set_value('kode'),
            'nama' => set_value('nama'),
            'excelAuditor' => set_value('excelAuditor'),
            'excelRekomendasiAuditor' => set_value('excelRekomendasiAuditor'),
            'excelEvaluasiDiriAuditor' => set_value('excelEvaluasiDiriAuditor'),
            'excelTemuanAuditor' => set_value('excelTemuanAuditor'),
            'excelIdentifikasiRisikoAuditor' => set_value('excelIdentifikasiRisikoAuditor'),
        );

        $this->load->view('header', $dataAdm);
        $this->load->view('PenetapanAdmin/penetapanadmin_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan', true);

        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();

        if ($this->form_validation->run() == false) {
            $this->create($idTahunPelaksanaan);
        } else {
            $data = array(
                'ID' => $this->input->post('ID', true),
                'kode' => $this->input->post('kode', true),
                'nama' => $this->input->post('nama', true),
                'excelAuditor' => trim($this->input->post('excelAuditor', true)),
                'excelRekomendasiAuditor' => trim($this->input->post('excelRekomendasiAuditor', true)),
                'excelEvaluasiDiriAuditor' => trim($this->input->post('excelEvaluasiDiriAuditor', true)),
                'excelTemuanAuditor' => trim($this->input->post('excelTemuanAuditor', true)),
                'excelIdentifikasiRisikoAuditor' => trim($this->input->post('excelIdentifikasiRisikoAuditor', true)),
                'idTahunPelaksanaan' => $idTahunPelaksanaan,
            );

            $this->PenetapanAdmin_model->insert($data);
            $this->PenetapanAdmin_model->addEvent(24, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Data disimpan.');
            redirect(site_url('PenetapanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

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

        $row = $this->PenetapanAdmin_model->get_by_id($id);
        if ($row) {
            $idTahunPelaksanaan = $row->idTahunPelaksanaan;
            $data = array(
                'button' => 'Update',
                'back'   => site_url('PenetapanAdmin'),
                'action' => site_url('PenetapanAdmin/update_action'),
                'ID' => set_value('ID', $row->ID),
                'kode' => set_value('kode', $row->kode),
                'nama' => set_value('nama', $row->nama),
                'excelAuditor' => set_value('excel', $row->excelAuditor),
                'excelRekomendasiAuditor' => set_value('excel', $row->excelRekomendasiAuditor),
                'excelEvaluasiDiriAuditor' => set_value('excel', $row->excelEvaluasiDiriAuditor),
                'excelTemuanAuditor' => set_value('excelTemuanAuditor', $row->excelTemuanAuditor),
                'excelIdentifikasiRisikoAuditor' => set_value('excelIdentifikasiRisikoAuditor', $row->excelIdentifikasiRisikoAuditor),
                'idTahunPelaksanaan' => set_value('excel', $idTahunPelaksanaan),
                );

            $this->load->view('header', $dataAdm);
            $this->load->view('PenetapanAdmin/penetapanadmin_form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('PenetapanAdmin'));
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
            $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan', true);
            $data = array(
                'ID' => $this->input->post('ID', true),
                'kode' => $this->input->post('kode', true),
                'nama' => $this->input->post('nama', true),
                'excelAuditor' => trim($this->input->post('excelAuditor', true)),
                'excelRekomendasiAuditor' => trim($this->input->post('excelRekomendasiAuditor', true)),
                'excelEvaluasiDiriAuditor' => trim($this->input->post('excelEvaluasiDiriAuditor', true)),
                'excelTemuanAuditor' => trim($this->input->post('excelTemuanAuditor', true)),
                'excelIdentifikasiRisikoAuditor' => trim($this->input->post('excelIdentifikasiRisikoAuditor', true)),
            );
            $id = $this->input->post('ID', true);
            $row = $this->PenetapanAdmin_model->get_by_id($id);

            $this->PenetapanAdmin_model->update($this->input->post('ID', true), $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->PenetapanAdmin_model->addEvent(25, $this->idUser, $keterangan);

            flashMessage('success', 'Data diupdate.');
            redirect(site_url('PenetapanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->PenetapanAdmin_model->get_by_id($id);
        if ($row) {
            if (!$row->dokumen) {
                $this->PenetapanAdmin_model->delete($id);
                flashMessage('success', 'Data dihapus.');
                $this->PenetapanAdmin_model->addEvent(26, $this->idUser, $this->serialize_data($row));
            } else {
                flashMessage('error', 'Hapus dokutmen dahulu!!!');
            }
            redirect(site_url('PenetapanAdmin'). "?idTahunPelaksanaan=" . $row->idTahunPelaksanaan);
        } else {
            flashMessage('error', 'Dokumen tidak ada!');
            redirect(site_url('PenetapanAdmin'));
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