<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class PelaksanaanAdmin extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PelaksanaanAdmin_model');
        $this->load->library('datatables');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $idTahunPelaksanaan = $this->PelaksanaanAdmin_model->getIdTahunPelaksanaan();
        
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );
        
        $dataTambahan = array(
            'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );

        $this->load->view('header_list', $data);
        $this->load->view('PelaksanaanAdmin/pelaksanaanadmin_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function json($idTahunPelaksanaan)
    {
        header('Content-Type: application/json');
        echo $this->PelaksanaanAdmin_model->json($idTahunPelaksanaan);
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
            'back'   => site_url('PelaksanaanAdmin'),
            'action' => site_url('PelaksanaanAdmin/create_action'),
            'ID' => set_value('ID'),
            'idKebijakan' => set_value('idKebijakan'),
            'nama' => set_value('nama'),
            'idTahunPelaksanaan' => set_value('idTahunPelaksanaan', $idTahunPelaksanaan),
        );

        $this->load->view('header', $dataAdm);
        $this->load->view('PelaksanaanAdmin/pelaksanaanadmin_form', $data);
        $this->load->view('footer');
    }

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url("Login"));
        }
        $id = $this->input->post('ID', true);
        $this->_rules();

        if ($this->form_validation->run() == false) {
            $this->create($id);
        } else {
            $idTahunPelaksanaan = $this->input->post('idTahunPelaksanaan', true);
            $data = array(
            'ID' => $id,
            'idKebijakan' => $this->input->post('idKebijakan', true),
            'nama' => $this->input->post('nama', true),
        );
        
            $this->PelaksanaanAdmin_model->insert($data);
            flashMessage('success', 'Data disimpan.');
            $this->PelaksanaanAdmin_model->addEvent(27, $this->idUser, $this->serialize_data($data));

            redirect(site_url('PelaksanaanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }
    
    // Fungsi menampilkan form Update PelaksanaanAdmin
    public function update($id)
    {
        // Jika session data username tidak ada maka akan dialihkan kehalaman Login
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'SIPMI',
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->PelaksanaanAdmin_model->get_by_id($id);
    
        if ($row) {
            $idKebijakan = $row->idKebijakan;
            $idTahunPelaksanaan = $this->PelaksanaanAdmin_model->get_by_id_table($idKebijakan, 'tkebijakan')->idTahunPelaksanaan;
            $data = array(
                'button' => 'Update',
                'back'   => site_url('PelaksanaanAdmin'),
                'action' => site_url('PelaksanaanAdmin/update_action'),
                'ID' => set_value('ID', $row->ID),
                'idKebijakan' => set_value('idKebijakan', $idKebijakan),
                'nama' => set_value('nama', $row->nama),
                'idTahunPelaksanaan' => set_value('excel', $idTahunPelaksanaan),
                );
            
            $this->load->view('header', $dataAdm);
            $this->load->view('PelaksanaanAdmin/pelaksanaanadmin_form', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('PelaksanaanAdmin'));
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
                'idKebijakan' => $this->input->post('idKebijakan', true),
                'nama' => $this->input->post('nama', true),
            );

            $id = $this->input->post('ID', true);
            $row = $this->PelaksanaanAdmin_model->get_by_id($id);

            $this->PelaksanaanAdmin_model->update($this->input->post('ID', true), $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->PelaksanaanAdmin_model->addEvent(28, $this->idUser, $keterangan);

            flashMessage('success', 'Data diupdate.');
            redirect(site_url('PelaksanaanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->PelaksanaanAdmin_model->get_by_id($id);
        if ($row) {
            $idTahunPelaksanaan = $this->PelaksanaanAdmin_model->get_by_id_table($row->idKebijakan, 'tkebijakan')->idTahunPelaksanaan;
            $this->PelaksanaanAdmin_model->delete($id);
            flashMessage('success', 'Data dihapus.');
            $this->PelaksanaanAdmin_model->addEvent(29, $this->idUser, $this->serialize_data($row));
            
            redirect(site_url('PelaksanaanAdmin'). "?idTahunPelaksanaan=" . $idTahunPelaksanaan);
        } else {
            flashMessage('error', 'Data tidak ada.');
            redirect(site_url('PelaksanaanAdmin'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('ID', 'ID', 'trim');
        $this->form_validation->set_rules('idKebijakan', 'Kode Kebijakan', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama PelaksanaanAdmin', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}