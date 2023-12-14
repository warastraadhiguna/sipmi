<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class TahunPelaksanaan extends AdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    // Konstruktor
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TahunPelaksanaan_model');
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
        
        $this->load->view('header_list', $data);
        $this->load->view('TahunPelaksanaan/tahunpelaksanaan_list');
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->TahunPelaksanaan_model->json();
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
            'back'   => site_url('TahunPelaksanaan'),
            'action' => site_url('TahunPelaksanaan/create_action'),
            'ID' => set_value('ID'),
            'keterangan' => set_value('keterangan'),
            'tahun' => set_value('tahun'),
            'isActive' => set_value('isActive', 'Aktif'),
        );

        $this->load->view('header', $dataAdm);
        $this->load->view('TahunPelaksanaan/tahunpelaksanaan_form', $data);
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
            $tahun = $this->input->post('tahun', true);
            $data = array(
                'ID' => $this->input->post('ID', true),
                'tahun' => $tahun,
                'keterangan' => $this->input->post('keterangan', true),
                'isActive' => $this->input->post('isActive', true),
            );

            if ($this->TahunPelaksanaan_model->insert($data)) {
                $path = $this->uploadingDirectory . $tahun;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    mkdir($path . "/panduan", 0777, true);
                    mkdir($path . "/evaluasi", 0777, true);
                    mkdir($path . "/peningkatan", 0777, true);
                    mkdir($path . "/pelaksanaan", 0777, true);
                    mkdir($path . "/penetapan", 0777, true);
                    mkdir($path . "/dokumen_lain", 0777, true);
                    mkdir($path . "/pengendalian", 0777, true);
                }

                $this->TahunPelaksanaan_model->addEvent(13, $this->idUser, $this->serialize_data($data));

                flashMessage('success', 'Data disimpan.');
            } else {
                flashMessage('error', 'Data gagal disimpan.');
            }
        
            redirect(site_url('TahunPelaksanaan'));
        }
    }

    //   public function update($id)
    //   {
    // if (!isset($this->session->userdata['username']))
    // {
    // 	redirect(base_url("Login"));
    // }

    // $dataAdm = array(
    // 	'wa'       => 'Web administrator',
    // 	'univ'     => 'SIPMI',
    // 	'username' => $this->session->userdata['username'],
    // 	'level'    => $this->session->userdata['level'],
    // );

    //       $row = $this->TahunPelaksanaan_model->get_by_id($id);
    
    //       if ($row) {
    //           $data = array(
    //               'button' => 'Update',
    // 		'back'   => site_url('tahunPelaksanaan'),
    //               'action' => site_url('tahunPelaksanaan/update_action'),
    // 		'ID' => set_value('ID', $row->ID),
    // 		'tahun' => set_value('tahun', $row->tahun),
    // 		'keterangan' => set_value('keterangan', $row->keterangan),
    // 		);
            
    // 	$this->load->view('header',$dataAdm);
    //           $this->load->view('tahunPelaksanaan/tahunPelaksanaan_form', $data);
    // 	$this->load->view('footer');
    //       }
    // else {
    //           $this->session->set_flashdata('message', 'Record Not Found');
    //           redirect(site_url('tahunPelaksanaan'));
    //       }
    //   }
    
    //   public function update_action(){
    // if (!isset($this->session->userdata['username'])) {
    // 	redirect(base_url("Login"));
    // }
    
    //       $this->_rules();
        
    //       if ($this->form_validation->run() == FALSE) {
    //           $this->update($this->input->post('ID', TRUE));
    //       }
    // else {
    //           $data = array(
    // 		'ID' => $this->input->post('ID',TRUE),
    // 		'tahun' => $this->input->post('tahun',TRUE),
    // 		'keterangan' => $this->input->post('keterangan',TRUE),
    // 	);

    //           $this->TahunPelaksanaan_model->update($this->input->post('ID', TRUE), $data);
    //           flashMessage('success', 'Data diupdate.');
    //           redirect(site_url('tahunPelaksanaan'));
    //       }
    //   }

    public function setDefault($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->TahunPelaksanaan_model->get_by_id($id);
        if ($row) {
            $result = $this->TahunPelaksanaan_model->setDefault($id);
            if (!$result) {
                $this->TahunPelaksanaan_model->addEvent(14, $this->idUser, $this->serialize_data($row) . " <<Set Default>> ");

                flashMessage('success', 'Data sudah diubah.');
            } else {
                flashMessage('error', $result);
            }
        } else {
            flashMessage('error', 'Data tidak ada.');
        }

        redirect(site_url('TahunPelaksanaan'));
    }

    public function setStatus($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->TahunPelaksanaan_model->get_by_id($id);
        if ($row) {
            $result = $this->TahunPelaksanaan_model->setStatus($id);
            if (!$result) {
                $this->TahunPelaksanaan_model->addEvent(14, $this->idUser, $this->serialize_data($row) . " <<Set Status>> ");

                flashMessage('success', 'Data sudah diubah.');
            } else {
                flashMessage('error', $result);
            }
        } else {
            flashMessage('error', 'Data tidak ada.');
        }

        redirect(site_url('TahunPelaksanaan'));
    }

    public function copyData($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->TahunPelaksanaan_model->get_by_id($id);
        if ($row) {
            if ($row->isDefault == 1) {
                flashMessage('error', "Data tidak dapat dikopikan ke tahun default!!");
            } else {
                $result = $this->TahunPelaksanaan_model->copyData($id);
                if (!$result) {
                    $this->TahunPelaksanaan_model->addEvent(14, $this->idUser, $this->serialize_data($row) . " <<Copy data>> ");

                    flashMessage('success', 'Data sudah dicopy.');
                } else {
                    flashMessage('error', $result);
                }
            }
        } else {
            flashMessage('error', 'Data tidak ada.');
        }

        redirect(site_url('TahunPelaksanaan'));
    }
    
    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->TahunPelaksanaan_model->get_by_id($id);

        if ($row) {
            if ($this->TahunPelaksanaan_model->get_by_id_table_detail($id, 'idTahunPelaksanaan', 'tkebijakan')) {
                flashMessage('error', 'Data gagal dihapus!! Data pada tahun tersebut, sudah teriisi!');
                redirect(site_url('TahunPelaksanaan'));
                return;
            }

            if ($this->TahunPelaksanaan_model->delete($id)) {
                flashMessage('success', 'Data dihapus.');
                $path = $this->uploadingDirectory . $row->tahun;

                $files = glob('path/to/temp/*'); // get all file names
                foreach ($files as $file) { // iterate files
                    if (is_file($file)) {
                        unlink($file);
                    } // delete file
                }

                $this->deleteFile($path . "/panduan");
                $this->deleteFile($path . "/evaluasi");
                $this->deleteFile($path. "/peningkatan");
                $this->deleteFile($path. "/pelaksanaan");
                $this->deleteFile($path. "/penetapan");
                $this->deleteFile($path. "/dokumen_lain");
                $this->deleteFile($path. "/pengendalian");
                                
                rmdir($path);
                $this->TahunPelaksanaan_model->addEvent(15, $this->idUser, $this->serialize_data($row));
            } else {
                flashMessage('error', 'Data gagal dihapus.');
            }
            
            redirect(site_url('TahunPelaksanaan'));
        } else {
            flashMessage('error', 'Data tidak ada.');
            redirect(site_url('TahunPelaksanaan'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('ID', 'ID', 'trim');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('tahun', 'Tahun Pelaksanaan', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    private function deleteFile($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        rmdir($path);
    }
}